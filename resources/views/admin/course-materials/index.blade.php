@extends('admin.layouts.app')

@section('title', 'Tài liệu khóa học – ' . $course->name)

@push('styles')
<style>
.drop-zone {
    border: 2px dashed #ccc;
    border-radius: 8px;
    padding: 2rem;
    text-align: center;
    background: #fafafa;
    transition: border-color .2s, background .2s;
}
.drop-zone.dragover { border-color: #007bff; background: #e7f1ff; }
.drop-zone .drop-text { color: #666; margin: 0; }
.drop-zone input[type="file"] { display: none; }
.material-tree { list-style: none; padding-left: 0; }
.material-tree .material-tree-item { padding-left: 0; }
.material-tree .tree-row { border-radius: 4px; padding-left: 0.5rem; }
.material-tree .tree-row:hover { background: #f8f9fa; }
.material-tree .tree-toggle { cursor: pointer; width: 14px; text-align: center; transition: transform .15s; flex-shrink: 0; }
.material-tree .tree-toggle.expanded { transform: rotate(90deg); }
.material-tree .tree-children { display: none; list-style: none; padding-left: 0; margin-left: 1.5rem; border-left: 1px solid #dee2e6; padding-bottom: 0.25rem; }
.material-tree .tree-children.show { display: block; }
.material-tree .tree-actions .btn-link { font-size: 0.875rem; }
.material-tree .tree-label { min-width: 0; }
</style>
@endpush

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Tài liệu: {{ $course->name }}</h3>
        <div class="card-tools">
            @if(auth()->user()->isAdmin())
            <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#createFolderModal"><i class="fas fa-folder-plus"></i> Tạo thư mục</button>
            @endif
            <a href="{{ route('admin.courses.index') }}" class="btn btn-sm btn-default"><i class="fas fa-arrow-left"></i> Danh sách khóa học</a>
        </div>
    </div>
    <div class="card-body">
        {{-- Vùng kéo thả upload (chọn thư mục hoặc mặc định gốc) - chỉ admin --}}
        @if(auth()->user()->isAdmin())
        <div class="drop-zone mb-4" id="dropZone" data-upload-url="{{ route('admin.courses.materials.upload', $course) }}" data-parent-id="" data-token="{{ csrf_token() }}">
            <p class="drop-text"><i class="fas fa-cloud-upload-alt fa-2x text-muted mb-2"></i><br>Kéo thả file vào đây hoặc <label for="fileInput" class="btn btn-sm btn-outline-primary mb-0">chọn file</label></p>
            <p class="mb-0" id="dropZoneTarget"><small class="text-muted">File sẽ được thêm vào: <strong>Gốc</strong></small></p>
            <p class="mb-0 mt-1" id="dropZoneResetWrap" style="display: none;"><button type="button" id="dropZoneReset" class="btn btn-sm btn-outline-secondary">Đặt về gốc</button></p>
            <input type="file" id="fileInput" name="files[]" multiple accept=".pdf,.doc,.docx,.ppt,.pptx,.xls,.xlsx,.txt,.jpg,.jpeg,.png">
        </div>
        @endif

        {{-- Cây thư mục / tài liệu --}}
        <div class="material-tree-wrap">
            @if(count($tree) > 0)
                <ul class="material-tree list-unstyled mb-0">
                    @include('admin.course-materials.partials.tree-node', ['nodes' => $tree, 'course' => $course, 'depth' => 0])
                </ul>
            @else
                <p class="text-center text-muted py-4">Chưa có tài liệu. Tạo thư mục hoặc kéo thả file vào vùng trên.</p>
            @endif
        </div>
    </div>
</div>

{{-- Modal tạo thư mục --}}
<div class="modal fade" id="createFolderModal" tabindex="-1" aria-labelledby="createFolderModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createFolderModalLabel"><i class="fas fa-folder-plus"></i> Tạo thư mục mới</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Đóng"><span aria-hidden="true">&times;</span></button>
            </div>
            <form id="createFolderForm" action="{{ route('admin.courses.materials.store', $course) }}" method="POST">
                @csrf
                <input type="hidden" name="type" value="folder">
                <input type="hidden" name="parent_id" id="createFolderParentId" value="">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="folderName">Tên thư mục <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="folderName" name="name" value="{{ old('name') }}" placeholder="Nhập tên thư mục" required autofocus>
                        @error('name')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="folderSortOrder">Thứ tự</label>
                        <input type="number" class="form-control" id="folderSortOrder" name="sort_order" value="{{ old('sort_order', 0) }}" min="0">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary" id="createFolderSubmit">Tạo thư mục</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
(function() {
    var tree = document.querySelector('.material-tree');
    if (tree) {
        tree.addEventListener('click', function(e) {
            var toggle = e.target.closest('.tree-toggle');
            if (toggle && toggle.closest('.is-folder')) {
                e.preventDefault();
                var item = toggle.closest('.material-tree-item');
                var children = item.querySelector(':scope > .tree-children');
                if (children) {
                    toggle.classList.toggle('expanded');
                    children.classList.toggle('show');
                }
            }
        });
        tree.addEventListener('click', function(e) {
            var btn = e.target.closest('[data-action="create-folder"]');
            if (btn) {
                e.preventDefault();
                var parentId = btn.getAttribute('data-parent-id') || '';
                document.getElementById('createFolderParentId').value = parentId;
                $('#createFolderModal').modal('show');
            }
            var uploadBtn = e.target.closest('[data-action="upload-into-folder"]');
            if (uploadBtn) {
                e.preventDefault();
                var parentId = uploadBtn.getAttribute('data-parent-id') || '';
                var folderName = uploadBtn.getAttribute('data-folder-name') || 'Thư mục';
                var dropZone = document.getElementById('dropZone');
                var targetEl = document.getElementById('dropZoneTarget');
                var resetWrap = document.getElementById('dropZoneResetWrap');
                if (dropZone && targetEl) {
                    dropZone.setAttribute('data-parent-id', parentId);
                    targetEl.innerHTML = '<small class="text-muted">File sẽ được thêm vào: <strong class="text-primary">' + escapeHtml(folderName) + '</strong></small>';
                    if (resetWrap) resetWrap.style.display = 'block';
                }
            }
        });
        function escapeHtml(s) {
            var div = document.createElement('div');
            div.textContent = s;
            return div.innerHTML;
        }
        var rootItems = tree.querySelectorAll(':scope > .material-tree-item');
        rootItems.forEach(function(li) {
            var children = li.querySelector(':scope > .tree-children');
            var toggle = li.querySelector('.tree-toggle');
            if (children && toggle) {
                toggle.classList.add('expanded');
                children.classList.add('show');
            }
        });
    }
    var createFolderBtn = document.querySelector('[data-toggle="modal"][data-target="#createFolderModal"]');
    if (createFolderBtn) createFolderBtn.addEventListener('click', function() {
        document.getElementById('createFolderParentId').value = '';
    });

    var createFolderForm = document.getElementById('createFolderForm');
    if (createFolderForm) {
        createFolderForm.addEventListener('submit', function(e) {
            e.preventDefault();
            var btn = document.getElementById('createFolderSubmit');
            var fd = new FormData(createFolderForm);
            btn.disabled = true;
            fetch(createFolderForm.action, {
                method: 'POST',
                body: fd,
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
            }).then(function(r) { return r.json().then(function(d) { return { ok: r.ok, data: d }; }); })
              .then(function(res) {
                btn.disabled = false;
                if (res.ok && res.data.redirect) {
                    $('#createFolderModal').modal('hide');
                    createFolderForm.reset();
                    document.getElementById('folderSortOrder').value = 0;
                    window.location.href = res.data.redirect;
                } else {
                    alert(res.data.message || (res.data.errors && res.data.errors.name ? res.data.errors.name[0] : 'Có lỗi xảy ra.'));
                }
              })
              .catch(function() { btn.disabled = false; alert('Có lỗi kết nối.'); });
        });
    }
})();
(function() {
    var dropZone = document.getElementById('dropZone');
    var fileInput = document.getElementById('fileInput');
    if (!dropZone || !fileInput) return;
    var uploadUrl = dropZone.getAttribute('data-upload-url');
    var token = dropZone.getAttribute('data-token');

    document.getElementById('dropZoneReset') && document.getElementById('dropZoneReset').addEventListener('click', function() {
        dropZone.setAttribute('data-parent-id', '');
        var targetEl = document.getElementById('dropZoneTarget');
        var resetWrap = document.getElementById('dropZoneResetWrap');
        if (targetEl) targetEl.innerHTML = '<small class="text-muted">File sẽ được thêm vào: <strong>Gốc</strong></small>';
        if (resetWrap) resetWrap.style.display = 'none';
    });

    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(function(ev) {
        dropZone.addEventListener(ev, function(e) {
            e.preventDefault();
            e.stopPropagation();
        });
    });
    ['dragenter', 'dragover'].forEach(function(ev) {
        dropZone.addEventListener(ev, function() { dropZone.classList.add('dragover'); });
    });
    ['dragleave', 'drop'].forEach(function(ev) {
        dropZone.addEventListener(ev, function() { dropZone.classList.remove('dragover'); });
    });

    dropZone.addEventListener('drop', function(e) {
        var files = e.dataTransfer.files;
        if (!files.length) return;
        uploadFiles(files);
    });

    fileInput.addEventListener('change', function() {
        if (this.files.length) uploadFiles(this.files);
        this.value = '';
    });

    dropZone.querySelector('label').addEventListener('click', function(e) { e.preventDefault(); fileInput.click(); });

    function uploadFiles(files) {
        var parentId = dropZone.getAttribute('data-parent-id') || '';
        var fd = new FormData();
        fd.append('_token', token);
        if (parentId) fd.append('parent_id', parentId);
        for (var i = 0; i < files.length; i++) fd.append('files[]', files[i]);
        var xhr = new XMLHttpRequest();
        xhr.open('POST', uploadUrl);
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        xhr.setRequestHeader('Accept', 'application/json');
        xhr.onload = function() {
            if (xhr.status >= 200 && xhr.status < 300) {
                var res = JSON.parse(xhr.responseText);
                if (res.redirect) window.location.href = res.redirect;
            } else {
                var res = JSON.parse(xhr.responseText);
                alert(res.message || 'Có lỗi khi tải lên.');
            }
        };
        xhr.onerror = function() { alert('Lỗi kết nối.'); };
        xhr.send(fd);
    }
})();
</script>
@endpush
@endsection
