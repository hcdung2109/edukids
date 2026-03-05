<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseMaterial;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\View\View;

class CourseMaterialController extends Controller
{
    use AuthorizesAdminTrait;

    public function index(Course $course, Request $request): View
    {
        $user = auth()->user();
        if (! $user->isAdmin()) {
            // Giáo viên chỉ được xem tài liệu khóa học của lớp mình được gán (lớp có liên kết khóa học này)
            $canView = \App\Models\CenterClass::where('course_id', $course->id)
                ->whereHas('teachers', fn ($q) => $q->where('users.id', $user->id))
                ->exists();
            if (! $canView) {
                abort(403, 'Bạn chỉ được xem tài liệu khóa học của lớp mình được gán.');
            }
        }

        $all = CourseMaterial::where('course_id', $course->id)->ordered()->get();
        $tree = $this->buildMaterialTree($all, null);

        return view('admin.course-materials.index', compact('course', 'tree'));
    }

    /** Xây cây thư mục/tài liệu từ danh sách phẳng. */
    private function buildMaterialTree($items, ?int $parentId): array
    {
        $branch = [];
        foreach ($items as $item) {
            if ($item->parent_id === $parentId) {
                $branch[] = [
                    'item' => $item,
                    'children' => $this->buildMaterialTree($items, (int) $item->id),
                ];
            }
        }

        return $branch;
    }

    public function create(Course $course, Request $request): View
    {
        $this->authorizeAdmin();
        $parentId = $request->query('parent');
        $parent = $parentId ? CourseMaterial::where('course_id', $course->id)->where('type', CourseMaterial::TYPE_FOLDER)->findOrFail($parentId) : null;

        return view('admin.course-materials.create', compact('course', 'parent'));
    }

    public function store(Request $request, Course $course): RedirectResponse|JsonResponse
    {
        $this->authorizeAdmin();
        $type = $request->input('type', CourseMaterial::TYPE_FOLDER);
        $parentId = $request->input('parent_id') ?: null;

        if ($type === CourseMaterial::TYPE_FOLDER) {
            $validated = $request->validate(['name' => ['required', 'string', 'max:255']], ['name.required' => 'Tên thư mục không được để trống.']);
            CourseMaterial::create([
                'course_id' => $course->id,
                'parent_id' => $parentId,
                'name' => $validated['name'],
                'type' => CourseMaterial::TYPE_FOLDER,
                'sort_order' => (int) $request->input('sort_order', 0),
            ]);
            $redirect = route('admin.courses.materials.index', [$course]);
            if ($parentId) {
                $redirect .= '?parent=' . $parentId;
            }
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['success' => true, 'redirect' => $redirect, 'message' => 'Đã tạo thư mục.']);
            }
            return redirect($redirect)->with('success', 'Đã tạo thư mục.');
        } else {
            $request->validate([
                'file' => ['required', 'file', 'max:51200', 'mimes:pdf,doc,docx,ppt,pptx,xls,xlsx,txt,jpg,jpeg,png'],
            ], ['file.required' => 'Vui lòng chọn file.', 'file.mimes' => 'Chỉ chấp nhận file PDF, Word, PowerPoint, Excel, ảnh, txt.']);
            $file = $request->file('file');
            $path = $file->store('courses/' . $course->id . '/materials', 'public');
            $name = $request->input('display_name') ?: $file->getClientOriginalName();
            CourseMaterial::create([
                'course_id' => $course->id,
                'parent_id' => $parentId,
                'name' => $name,
                'type' => CourseMaterial::TYPE_FILE,
                'file_path' => $path,
                'mime_type' => $file->getMimeType(),
                'file_size' => $file->getSize(),
                'sort_order' => (int) $request->input('sort_order', 0),
            ]);
        }

        $redirect = route('admin.courses.materials.index', [$course]);
        if ($parentId) {
            $redirect .= '?parent=' . $parentId;
        }
        return redirect($redirect)->with('success', $type === CourseMaterial::TYPE_FOLDER ? 'Đã tạo thư mục.' : 'Đã thêm tài liệu.');
    }

    /** Upload nhiều file (kéo thả). */
    public function storeFiles(Request $request, Course $course): RedirectResponse|JsonResponse
    {
        $this->authorizeAdmin();
        $parentId = $request->input('parent_id') ?: null;
        $request->validate([
            'files' => ['required', 'array'],
            'files.*' => ['file', 'max:51200', 'mimes:pdf,doc,docx,ppt,pptx,xls,xlsx,txt,jpg,jpeg,png'],
        ], ['files.required' => 'Vui lòng chọn ít nhất một file.']);
        $count = 0;
        foreach ($request->file('files') as $file) {
            if (! $file->isValid()) {
                continue;
            }
            $path = $file->store('courses/' . $course->id . '/materials', 'public');
            CourseMaterial::create([
                'course_id' => $course->id,
                'parent_id' => $parentId,
                'name' => $file->getClientOriginalName(),
                'type' => CourseMaterial::TYPE_FILE,
                'file_path' => $path,
                'mime_type' => $file->getMimeType(),
                'file_size' => $file->getSize(),
                'sort_order' => $count,
            ]);
            $count++;
        }
        $redirect = route('admin.courses.materials.index', [$course]);
        if ($parentId) {
            $redirect .= '?parent=' . $parentId;
        }
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['success' => true, 'redirect' => $redirect, 'message' => "Đã thêm {$count} tài liệu."]);
        }
        return redirect($redirect)->with('success', "Đã thêm {$count} tài liệu.");
    }

    public function edit(Course $course, CourseMaterial $material): View
    {
        $this->authorizeAdmin();
        if ($material->course_id !== $course->id) {
            abort(404);
        }
        $parent = $material->parent_id ? CourseMaterial::where('course_id', $course->id)->find($material->parent_id) : null;

        return view('admin.course-materials.edit', compact('course', 'material', 'parent'));
    }

    public function update(Request $request, Course $course, CourseMaterial $material): RedirectResponse
    {
        $this->authorizeAdmin();
        if ($material->course_id !== $course->id) {
            abort(404);
        }
        $validated = $request->validate(['name' => ['required', 'string', 'max:255']]);
        $material->update(['name' => $validated['name'], 'sort_order' => (int) $request->input('sort_order', 0)]);

        $redirect = route('admin.courses.materials.index', [$course]);
        if ($material->parent_id) {
            $redirect .= '?parent=' . $material->parent_id;
        }
        return redirect($redirect)->with('success', 'Đã cập nhật.');
    }

    public function destroy(Course $course, CourseMaterial $material): RedirectResponse
    {
        $this->authorizeAdmin();
        if ($material->course_id !== $course->id) {
            abort(404);
        }
        $parentId = $material->parent_id;
        $material->delete();
        $redirect = route('admin.courses.materials.index', [$course]);
        if ($parentId) {
            $redirect .= '?parent=' . $parentId;
        }
        return redirect($redirect)->with('success', 'Đã xóa.');
    }

    /**
     * Tải file tài liệu xuống.
     */
    public function view(Course $course, CourseMaterial $material): StreamedResponse
    {
        if ($material->course_id !== $course->id || ! $material->isFile() || ! $material->file_path) {
            abort(404);
        }
        $user = auth()->user();
        if (! $user->isAdmin()) {
            $canView = \App\Models\CenterClass::where('course_id', $course->id)
                ->whereHas('teachers', fn ($q) => $q->where('users.id', $user->id))
                ->exists();
            if (! $canView) {
                abort(403, 'Bạn chỉ được xem tài liệu khóa học của lớp mình được gán.');
            }
        }
        $path = Storage::disk('public')->path($material->file_path);
        if (! is_file($path)) {
            abort(404);
        }
        $mime = $material->mime_type ?: 'application/octet-stream';
        $filename = addslashes($material->name);

        $headers = [
            'Content-Type' => $mime,
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'X-Content-Type-Options' => 'nosniff',
        ];

        return response()->stream(function () use ($path) {
            $stream = fopen($path, 'rb');
            if ($stream) {
                fpassthru($stream);
                fclose($stream);
            }
        }, 200, $headers);
    }

    private function breadcrumb(CourseMaterial $item): array
    {
        $items = [];
        $current = $item;
        while ($current) {
            array_unshift($items, $current);
            $current = $current->parent;
        }

        return $items;
    }
}
