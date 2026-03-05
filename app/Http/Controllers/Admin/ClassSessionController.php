<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Center;
use App\Models\CenterClass;
use App\Models\ClassSession;
use App\Models\SessionAttendance;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ClassSessionController extends Controller
{
    public function index(Request $request, Center $center, CenterClass $center_class): View
    {
        if ($center_class->center_id !== $center->id) {
            abort(404);
        }

        $year = (int) $request->get('year', now()->year);
        $month = (int) $request->get('month', now()->month);
        $month = max(1, min(12, $month));
        $year = max(2000, min(2100, $year));

        $start = Carbon::createFromDate($year, $month, 1)->startOfDay();
        $end = $start->copy()->endOfMonth();
        $startStr = $start->toDateString();
        $endStr = $end->toDateString();

        $sessionsCollection = $center_class->classSessions()
            ->selectRaw('class_sessions.*, DATE(session_date) as session_date_str')
            ->whereBetween('session_date', [$startStr, $endStr])
            ->orderBy('session_date')
            ->get();

        $sessionsByDate = [];
        foreach ($sessionsCollection as $s) {
            $key = isset($s->session_date_str) ? (string) $s->session_date_str : $this->toDateKey($s->session_date);
            if (! isset($sessionsByDate[$key])) {
                $sessionsByDate[$key] = [];
            }
            $sessionsByDate[$key][] = $s;
        }

        $prev = $start->copy()->subMonth();
        $next = $start->copy()->addMonth();

        $weeks = $this->buildCalendarWeeks($start, $sessionsByDate);

        $totalSessionsInMonth = $sessionsCollection->count();
        $canTakeAttendance = $this->canManageAttendance($center_class);

        return view('admin.class-sessions.index', compact(
            'center',
            'center_class',
            'year',
            'month',
            'start',
            'prev',
            'next',
            'weeks',
            'totalSessionsInMonth',
            'canTakeAttendance'
        ));
    }

    /** Chỉ admin hoặc giáo viên được gán vào lớp mới được điểm danh. */
    private function canManageAttendance(CenterClass $center_class): bool
    {
        $user = auth()->user();
        if ($user->isAdmin()) {
            return true;
        }
        return $center_class->teachers()->where('user_id', $user->id)->exists();
    }

    public function store(Request $request, Center $center, CenterClass $center_class): RedirectResponse
    {
        if ($center_class->center_id !== $center->id) {
            abort(404);
        }

        $validated = $request->validate([
            'session_date' => ['required', 'date'],
            'time_slot' => ['nullable', 'string', 'max:50'],
            'note' => ['nullable', 'string', 'max:255'],
        ]);

        $dateStr = Carbon::parse($validated['session_date'])->format('Y-m-d');
        $center_class->classSessions()->create([
            'session_date' => $dateStr,
            'time_slot' => $validated['time_slot'] ?? null,
            'note' => $validated['note'] ?? null,
        ]);

        $d = Carbon::parse($validated['session_date']);
        return redirect()->route('admin.centers.classes.sessions.index', [
            $center,
            $center_class,
            'year' => $d->year,
            'month' => $d->month,
        ])->with('success', 'Đã đánh dấu buổi học.');
    }

    public function destroy(Center $center, CenterClass $center_class, ClassSession $session): RedirectResponse
    {
        if ($center_class->center_id !== $center->id || $session->center_class_id !== $center_class->id) {
            abort(404);
        }

        $d = $session->session_date;
        // Xóa điểm danh của buổi học để trang/Excel điểm danh tính đúng số buổi học
        SessionAttendance::where('class_session_id', $session->id)->delete();
        $session->delete();

        return redirect()->route('admin.centers.classes.sessions.index', [
            $center,
            $center_class,
            'year' => $d->year,
            'month' => $d->month,
        ])->with('success', 'Đã xóa buổi học.');
    }

    /** Xóa tất cả buổi học trong một ngày. */
    public function destroyByDate(Request $request, Center $center, CenterClass $center_class): RedirectResponse
    {
        if ($center_class->center_id !== $center->id) {
            abort(404);
        }

        $validated = $request->validate([
            'session_date' => ['required', 'date'],
        ]);

        $dateStr = Carbon::parse($validated['session_date'])->format('Y-m-d');
        $sessionsToDelete = $center_class->classSessions()
            ->whereDate('session_date', $dateStr)
            ->get(['id']);
        $sessionIds = $sessionsToDelete->pluck('id')->toArray();
        // Xóa điểm danh của các buổi học để trang/Excel điểm danh tính đúng số buổi học
        if (! empty($sessionIds)) {
            SessionAttendance::whereIn('class_session_id', $sessionIds)->delete();
        }
        $deleted = $center_class->classSessions()
            ->whereDate('session_date', $dateStr)
            ->delete();

        $d = Carbon::parse($dateStr);
        $message = $deleted > 0 ? 'Đã xóa ' . $deleted . ' buổi học trong ngày này.' : 'Không có buổi học nào trong ngày này.';

        return redirect()->route('admin.centers.classes.sessions.index', [
            $center,
            $center_class,
            'year' => $d->year,
            'month' => $d->month,
        ])->with('success', $message);
    }

    /** Xóa toàn bộ buổi học đã đánh dấu của lớp. */
    public function destroyAll(Center $center, CenterClass $center_class): RedirectResponse
    {
        if ($center_class->center_id !== $center->id) {
            abort(404);
        }

        $sessionIds = $center_class->classSessions()->pluck('id')->toArray();
        if (! empty($sessionIds)) {
            SessionAttendance::whereIn('class_session_id', $sessionIds)->delete();
        }
        $deleted = $center_class->classSessions()->delete();

        return redirect()->route('admin.centers.classes.sessions.index', [$center, $center_class])
            ->with('success', $deleted > 0 ? 'Đã xóa toàn bộ ' . $deleted . ' buổi học.' : 'Không có buổi học nào để xóa.');
    }

    /** Trả về JSON danh sách buổi học trong một ngày (để modal luôn có dữ liệu đúng). */
    public function sessionsByDate(Request $request, Center $center, CenterClass $center_class): JsonResponse
    {
        if ($center_class->center_id !== $center->id) {
            abort(404);
        }
        $dateStr = $request->get('date');
        if (! $dateStr) {
            return response()->json(['sessions' => []]);
        }
        $date = Carbon::parse($dateStr)->format('Y-m-d');
        $sessions = $center_class->classSessions()
            ->whereDate('session_date', $date)
            ->orderBy('id')
            ->get(['id', 'session_date', 'time_slot', 'note'])
            ->map(fn ($s) => ['id' => $s->id, 'time_slot' => $s->time_slot ?? '', 'note' => $s->note ?? '']);
        return response()->json(['sessions' => $sessions]);
    }

    /** Trang điểm danh: bảng học viên × buổi học (mở tab mới). */
    public function attendancePage(Center $center, CenterClass $center_class): View
    {
        if ($center_class->center_id !== $center->id) {
            abort(404);
        }
        if (! $this->canManageAttendance($center_class)) {
            abort(403, 'Bạn không có quyền điểm danh lớp học này.');
        }
        $sessions = $center_class->classSessions()->orderBy('session_date')->get(['id', 'session_date', 'note']);
        $students = $center_class->students()->ordered()->get(['id', 'name']);
        $attendance = [];
        foreach (SessionAttendance::whereIn('class_session_id', $sessions->pluck('id'))->get() as $a) {
            if (! isset($attendance[$a->class_session_id])) {
                $attendance[$a->class_session_id] = [];
            }
            $attendance[$a->class_session_id][$a->student_id] = $a->attended;
        }
        return view('admin.class-sessions.attendance', compact('center', 'center_class', 'sessions', 'students', 'attendance'));
    }

    /** Trả về JSON ma trận điểm danh (giữ cho tương thích nếu cần). */
    public function attendanceMatrix(Center $center, CenterClass $center_class): JsonResponse
    {
        if ($center_class->center_id !== $center->id) {
            abort(404);
        }
        $sessions = $center_class->classSessions()->orderBy('session_date')->get(['id', 'session_date', 'note']);
        $students = $center_class->students()->ordered()->get(['id', 'name']);
        $attendance = [];
        foreach (SessionAttendance::whereIn('class_session_id', $sessions->pluck('id'))->get() as $a) {
            if (! isset($attendance[$a->class_session_id])) {
                $attendance[$a->class_session_id] = [];
            }
            $attendance[$a->class_session_id][$a->student_id] = $a->attended;
        }
        return response()->json([
            'sessions' => $sessions->map(fn ($s) => [
                'id' => $s->id,
                'session_date' => $s->session_date->format('Y-m-d'),
                'label' => $s->note ?: ('Buổi ' . $s->session_date->format('d/m/Y')),
            ]),
            'students' => $students->map(fn ($s) => ['id' => $s->id, 'name' => $s->name]),
            'attendance' => $attendance,
        ]);
    }

    /** Lưu toàn bộ điểm danh (ma trận học viên × buổi học). */
    public function saveAttendanceBulk(Request $request, Center $center, CenterClass $center_class): RedirectResponse|JsonResponse
    {
        if ($center_class->center_id !== $center->id) {
            abort(404);
        }
        if (! $this->canManageAttendance($center_class)) {
            abort(403, 'Bạn không có quyền điểm danh lớp học này.');
        }
        $input = $request->input('attendance', []);
        if (! is_array($input)) {
            $input = [];
        }
        $sessionIds = $center_class->classSessions()->pluck('id')->toArray();
        $studentIds = $center_class->students()->pluck('id')->toArray();
        foreach ($sessionIds as $sessionId) {
            $byStudent = $input[$sessionId] ?? [];
            if (! is_array($byStudent)) {
                $byStudent = [];
            }
            foreach ($studentIds as $studentId) {
                $attended = isset($byStudent[$studentId]) && (bool) $byStudent[$studentId];
                SessionAttendance::updateOrCreate(
                    [
                        'class_session_id' => $sessionId,
                        'student_id' => $studentId,
                    ],
                    ['attended' => $attended]
                );
            }
        }
        if ($request->wantsJson()) {
            return response()->json(['success' => true]);
        }
        return redirect()->back()->with('success', 'Đã lưu điểm danh.');
    }

    /** Trả về JSON danh sách học viên + trạng thái điểm danh của buổi học (cho modal điểm danh). */
    public function attendance(Center $center, CenterClass $center_class, ClassSession $session): JsonResponse
    {
        if ($center_class->center_id !== $center->id || $session->center_class_id !== $center_class->id) {
            abort(404);
        }

        $students = $center_class->students()->ordered()->get(['id', 'name']);
        $attendanceMap = $session->attendances()->pluck('attended', 'student_id')->toArray();

        return response()->json([
            'session' => [
                'id' => $session->id,
                'session_date' => $session->session_date->format('Y-m-d'),
                'note' => $session->note,
            ],
            'students' => $students->map(fn ($s) => ['id' => $s->id, 'name' => $s->name]),
            'attendance' => $attendanceMap,
        ]);
    }

    /** Lưu điểm danh buổi học. */
    public function saveAttendance(Request $request, Center $center, CenterClass $center_class, ClassSession $session): RedirectResponse|JsonResponse
    {
        if ($center_class->center_id !== $center->id || $session->center_class_id !== $center_class->id) {
            abort(404);
        }

        $input = $request->input('attendance', []);
        if (! is_array($input)) {
            $input = [];
        }

        $studentIds = $center_class->students()->pluck('id')->toArray();
        foreach ($studentIds as $studentId) {
            $attended = isset($input[$studentId]) && (bool) $input[$studentId];
            SessionAttendance::updateOrCreate(
                [
                    'class_session_id' => $session->id,
                    'student_id' => $studentId,
                ],
                ['attended' => $attended]
            );
        }

        if ($request->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()->back()->with('success', 'Đã lưu điểm danh.');
    }

    /** Xuất Excel điểm danh: danh sách học viên × các buổi học (Có mặt / Vắng). */
    public function exportAttendance(Center $center, CenterClass $center_class): StreamedResponse
    {
        if ($center_class->center_id !== $center->id) {
            abort(404);
        }
        if (! $this->canManageAttendance($center_class)) {
            abort(403, 'Bạn không có quyền điểm danh lớp học này.');
        }

        $sessions = $center_class->classSessions()->orderBy('session_date')->get();
        $students = $center_class->students()->ordered()->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Điểm danh');

        $col = 1;
        $sheet->setCellValue(Coordinate::stringFromColumnIndex($col++) . '1', 'STT');
        $sheet->setCellValue(Coordinate::stringFromColumnIndex($col++) . '1', 'Họ tên');
        foreach ($sessions as $idx => $s) {
            $label = 'Buổi ' . ($idx + 1) . ' (' . $s->session_date->format('d/m/Y') . ')';
            $sheet->setCellValue(Coordinate::stringFromColumnIndex($col++) . '1', $label);
        }
        $lastCol = $col - 1;
        $sessionIds = $sessions->pluck('id')->toArray();
        $attendanceBySession = [];
        foreach ($sessionIds as $sid) {
            $attendanceBySession[$sid] = SessionAttendance::where('class_session_id', $sid)
                ->get()
                ->keyBy('student_id');
        }

        $row = 2;
        foreach ($students as $i => $student) {
            $c = 1;
            $sheet->setCellValue(Coordinate::stringFromColumnIndex($c++) . $row, $i + 1);
            $sheet->setCellValue(Coordinate::stringFromColumnIndex($c++) . $row, $student->name);
            foreach ($sessions as $s) {
                $rec = $attendanceBySession[$s->id][$student->id] ?? null;
                $value = ($rec && $rec->attended) ? 'Có mặt' : 'Vắng';
                $sheet->setCellValue(Coordinate::stringFromColumnIndex($c++) . $row, $value);
            }
            $row++;
        }

        for ($c = 1; $c <= $lastCol; $c++) {
            $sheet->getColumnDimension(Coordinate::stringFromColumnIndex($c))->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);
        $fileName = 'diem_danh_' . \Str::slug($center_class->name) . '_' . date('Y-m-d') . '.xlsx';

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $fileName, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

    private function buildCalendarWeeks(Carbon $monthStart, array $sessionsByDate): array
    {
        $firstDay = $monthStart->copy()->startOfMonth();
        $lastDay = $monthStart->copy()->endOfMonth();
        $startPad = (int) $firstDay->format('N') - 1; // 0 = Monday, 6 = Sunday
        $startPad = max(0, $startPad);
        $daysInMonth = $lastDay->day;
        $weeks = [];
        $day = 1;
        $currentWeek = [];

        for ($i = 0; $i < $startPad; $i++) {
            $prevDate = $firstDay->copy()->subDays($startPad - $i);
            $dateKey = $this->toDateKey($prevDate);
            $currentWeek[] = [
                'date' => $prevDate,
                'is_current_month' => false,
                'sessions' => $sessionsByDate[$dateKey] ?? [],
            ];
        }

        while ($day <= $daysInMonth) {
            $date = $firstDay->copy()->addDays($day - 1);
            $dateKey = $this->toDateKey($date);
            $currentWeek[] = [
                'date' => $date,
                'is_current_month' => true,
                'sessions' => $sessionsByDate[$dateKey] ?? [],
            ];
            $day++;
            if (count($currentWeek) === 7) {
                $weeks[] = $currentWeek;
                $currentWeek = [];
            }
        }

        if (count($currentWeek) > 0) {
            $next = $lastDay->copy()->addDay();
            while (count($currentWeek) < 7) {
                $currentWeek[] = [
                    'date' => $next->copy(),
                    'is_current_month' => false,
                    'sessions' => [],
                ];
                $next->addDay();
            }
            $weeks[] = $currentWeek;
        }

        return $weeks;
    }

    private function toDateKey(mixed $date): string
    {
        if ($date instanceof Carbon) {
            return $date->format('Y-m-d');
        }
        return Carbon::parse($date)->format('Y-m-d');
    }
}
