@foreach($nodes as $node)
@php $item = $node['item']; $children = $node['children']; @endphp
<li class="material-tree-item {{ $item->isFolder() ? 'is-folder' : 'is-file' }}" data-id="{{ $item->id }}">
    <div class="d-flex align-items-center py-1 px-2 tree-row">
        @if($item->isFolder())
            <span class="tree-toggle mr-1" role="button" tabindex="0" aria-label="Mở rộng/thu gọn">
                <i class="fas fa-chevron-right text-muted"></i>
            </span>
        @else
            <span class="tree-toggle mr-1" style="width: 14px; display: inline-block;"></span>
        @endif
        @if($item->isFolder())
            <i class="fas fa-folder text-warning mr-2"></i>
        @else
            <i class="fas fa-file text-secondary mr-2"></i>
        @endif
        <span class="tree-label flex-grow-1 text-truncate">
            @if($item->isFolder())
                <span class="font-weight-medium">{{ $item->name }}</span>
            @else
                <a href="{{ route('admin.courses.materials.view', [$course, $item]) }}">{{ $item->name }}</a>
            @endif
        </span>
        @if($item->isFile() && $item->file_size)
            <span class="text-muted small mr-2">{{ number_format($item->file_size / 1024, 1) }} KB</span>
        @endif
        <span class="tree-actions">
            @if($item->isFolder())
                @if(auth()->user()->isAdmin())
                <button type="button" class="btn btn-xs btn-link p-0 text-info mr-1" title="Thêm file vào thư mục này" data-action="upload-into-folder" data-parent-id="{{ $item->id }}" data-folder-name="{{ $item->name }}">
                    <i class="fas fa-file-upload"></i>
                </button>
                <button type="button" class="btn btn-xs btn-link p-0 text-primary mr-1" title="Tạo thư mục con" data-action="create-folder" data-parent-id="{{ $item->id }}">
                    <i class="fas fa-folder-plus"></i>
                </button>
                @endif
            @else
                <a href="{{ route('admin.courses.materials.view', [$course, $item]) }}" class="btn btn-xs btn-link p-0 text-success mr-1" title="Tải xuống"><i class="fas fa-download"></i></a>
            @endif
            @if(auth()->user()->isAdmin())
            <a href="{{ route('admin.courses.materials.edit', [$course, $item]) }}" class="btn btn-xs btn-link p-0 mr-1" title="Sửa"><i class="fas fa-edit"></i></a>
            <form action="{{ route('admin.courses.materials.destroy', [$course, $item]) }}" method="POST" class="d-inline" onsubmit="return confirm('Xóa?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-xs btn-link p-0 text-danger" title="Xóa"><i class="fas fa-trash"></i></button>
            </form>
            @endif
        </span>
    </div>
    @if($item->isFolder() && count($children) > 0)
        <ul class="tree-children list-unstyled mb-0">
            @include('admin.course-materials.partials.tree-node', ['nodes' => $children, 'course' => $course, 'depth' => $depth + 1])
        </ul>
    @endif
</li>
@endforeach
