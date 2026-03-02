<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Center;
use App\Models\CenterClass;
use App\Models\Student;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\View\View;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;

class CenterStudentController extends Controller
{
    public function index(Center $center, CenterClass $class): View
    {
        $center_class = $class;
        if ($center_class->center_id !== $center->id) {
            abort(404);
        }
        $students = $center_class->students()->ordered()->paginate(20);
        return view('admin.center-students.index', compact('center', 'center_class', 'students'));
    }

    public function create(Center $center, CenterClass $class): View
    {
        $center_class = $class;
        if ($center_class->center_id !== $center->id) {
            abort(404);
        }
        return view('admin.center-students.create', compact('center', 'center_class'));
    }

    public function store(Request $request, Center $center, CenterClass $class): RedirectResponse
    {
        $center_class = $class;
        if ($center_class->center_id !== $center->id) {
            abort(404);
        }
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'date_of_birth' => ['nullable', 'date'],
            'parent_name' => ['nullable', 'string', 'max:255'],
            'parent_phone' => ['nullable', 'string', 'max:50'],
            'note' => ['nullable', 'string'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ], ['name.required' => 'Họ tên học viên không được để trống.']);
        $validated['center_class_id'] = $center_class->id;
        $validated['sort_order'] = (int) ($validated['sort_order'] ?? 0);

        $center_class->students()->create($validated);

        return redirect()->route('admin.centers.classes.students.index', [$center, $center_class])
            ->with('success', 'Đã thêm học viên.');
    }

    public function edit(Center $center, CenterClass $class, Student $student): View
    {
        $center_class = $class;
        if ($center_class->center_id !== $center->id || $student->center_class_id !== $center_class->id) {
            abort(404);
        }
        return view('admin.center-students.edit', compact('center', 'center_class', 'student'));
    }

    public function update(Request $request, Center $center, CenterClass $class, Student $student): RedirectResponse
    {
        $center_class = $class;
        if ($center_class->center_id !== $center->id || $student->center_class_id !== $center_class->id) {
            abort(404);
        }
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'date_of_birth' => ['nullable', 'date'],
            'parent_name' => ['nullable', 'string', 'max:255'],
            'parent_phone' => ['nullable', 'string', 'max:50'],
            'note' => ['nullable', 'string'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);
        $validated['sort_order'] = (int) ($validated['sort_order'] ?? 0);
        $student->update($validated);

        return redirect()->route('admin.centers.classes.students.index', [$center, $center_class])
            ->with('success', 'Đã cập nhật học viên.');
    }

    public function destroy(Center $center, CenterClass $class, Student $student): RedirectResponse
    {
        $center_class = $class;
        if ($center_class->center_id !== $center->id || $student->center_class_id !== $center_class->id) {
            abort(404);
        }
        $student->delete();
        return redirect()->route('admin.centers.classes.students.index', [$center, $center_class])
            ->with('success', 'Đã xóa học viên.');
    }

    public function importForm(Center $center, CenterClass $center_class): View
    {
        if ($center_class->center_id !== $center->id) {
            abort(404);
        }
        return view('admin.center-students.import', compact('center', 'center_class'));
    }

    public function downloadTemplate(Center $center, CenterClass $center_class): StreamedResponse
    {
        if ($center_class->center_id !== $center->id) {
            abort(404);
        }
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Danh sách học viên');
        $headers = ['Họ tên', 'Email', 'Điện thoại', 'Ngày sinh', 'Phụ huynh', 'SĐT phụ huynh', 'Ghi chú'];
        $sheet->fromArray($headers, null, 'A1');
        $sheet->fromArray(['Nguyễn Văn A', 'email@example.com', '0901234567', '2016-05-15', 'Nguyễn Văn Bố', '0912345678', 'Ví dụ dòng 1'], null, 'A2');
        $sheet->fromArray(['Trần Thị B', '', '', '', '', '', ''], null, 'A3');
        foreach (range('A', 'G') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
        $writer = new Xlsx($spreadsheet);
        $fileName = 'mau_import_hoc_vien_' . date('Y-m-d') . '.xlsx';
        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $fileName, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

    public function import(Request $request, Center $center, CenterClass $center_class): RedirectResponse
    {
        if ($center_class->center_id !== $center->id) {
            abort(404);
        }
        $request->validate([
            'file' => ['required', 'file', 'mimes:xlsx,xls', 'max:5120'],
        ], [
            'file.required' => 'Vui lòng chọn file Excel.',
            'file.mimes' => 'File phải là Excel (.xlsx hoặc .xls).',
        ]);

        $file = $request->file('file');
        $imported = $this->parseExcelAndImport($file, $center_class);

        return redirect()->route('admin.centers.classes.students.index', [$center, $center_class])
            ->with('success', "Đã import {$imported} học viên từ file Excel.");
    }

    private function parseExcelAndImport(UploadedFile $file, CenterClass $center_class): int
    {
        try {
            $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReaderForFile($file->getRealPath());
            $reader->setReadDataOnly(true);
            $spreadsheet = $reader->load($file->getRealPath());
            $sheet = $spreadsheet->getActiveSheet();
            $rows = $sheet->toArray();
        } catch (\Throwable $e) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'file' => ['Không đọc được file Excel. Kiểm tra định dạng file.'],
            ]);
        }

        $count = 0;
        $header = array_map('trim', $rows[0] ?? []);
        $nameCol = $this->findColumnIndex($header, ['Họ tên', 'Họ và tên', 'Tên', 'Name']);
        $emailCol = $this->findColumnIndex($header, ['Email', 'E-mail']);
        $phoneCol = $this->findColumnIndex($header, ['Điện thoại', 'SĐT', 'Phone', 'Số điện thoại']);
        $dobCol = $this->findColumnIndex($header, ['Ngày sinh', 'Date of birth', 'DOB']);
        $parentNameCol = $this->findColumnIndex($header, ['Phụ huynh', 'Họ tên phụ huynh', 'Parent']);
        $parentPhoneCol = $this->findColumnIndex($header, ['SĐT phụ huynh', 'Điện thoại phụ huynh']);
        $noteCol = $this->findColumnIndex($header, ['Ghi chú', 'Note']);

        if ($nameCol === null) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'file' => ['File Excel cần có cột "Họ tên" (hoặc "Họ và tên", "Tên") ở dòng đầu.'],
            ]);
        }

        foreach (array_slice($rows, 1) as $row) {
            $name = trim((string) ($row[$nameCol] ?? ''));
            if ($name === '') {
                continue;
            }
            $center_class->students()->create([
                'name' => $name,
                'email' => $emailCol !== null ? $this->cleanCell($row[$emailCol] ?? null) : null,
                'phone' => $phoneCol !== null ? $this->cleanCell($row[$phoneCol] ?? null) : null,
                'date_of_birth' => $dobCol !== null ? $this->parseDate($row[$dobCol] ?? null) : null,
                'parent_name' => $parentNameCol !== null ? $this->cleanCell($row[$parentNameCol] ?? null) : null,
                'parent_phone' => $parentPhoneCol !== null ? $this->cleanCell($row[$parentPhoneCol] ?? null) : null,
                'note' => $noteCol !== null ? $this->cleanCell($row[$noteCol] ?? null) : null,
                'sort_order' => $count,
            ]);
            $count++;
        }

        return $count;
    }

    private function findColumnIndex(array $header, array $names): ?int
    {
        foreach ($names as $name) {
            $key = array_search($name, $header, true);
            if ($key !== false) {
                return (int) $key;
            }
        }
        return null;
    }

    private function cleanCell(mixed $value): ?string
    {
        if ($value === null || $value === '') {
            return null;
        }
        $s = trim((string) $value);
        return $s === '' ? null : $s;
    }

    private function parseDate(mixed $value): ?string
    {
        if ($value === null || $value === '') {
            return null;
        }
        if ($value instanceof \DateTimeInterface) {
            return $value->format('Y-m-d');
        }
        $s = trim((string) $value);
        if ($s === '') {
            return null;
        }
        $date = \Carbon\Carbon::parse($s);
        return $date->format('Y-m-d');
    }
}
