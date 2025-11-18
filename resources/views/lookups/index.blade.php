<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lookup Tables Management</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        * { box-sizing: border-box; }
        body { font-family: Arial, sans-serif; color: #000; margin: 0; background: #fff; }
        .container-table { max-width: 100%; margin: 0; padding: 10px; }
        h3 { background: #f1f1f1; padding: 6px; margin-bottom: 10px; font-weight: bold; border: 1px solid #ddd; font-size: 19px; line-height: 1.2; }
        .top-bar { display: flex; align-items: center; flex-wrap: wrap; gap: 10px; margin-bottom: 10px; }
        .records-found { flex: 1 1 auto; font-size: 14px; color: #555; min-width: 150px; }
        .action-buttons { margin-left: auto; display: flex; gap: 10px; }
        .btn { border: none; cursor: pointer; padding: 6px 12px; font-size: 13px; border-radius: 2px; white-space: nowrap; transition: background-color 0.3s ease; text-decoration: none; display: inline-block; text-align: center; }
        .btn-add { background-color: #df7900; color: white; }
        .btn-add:hover { background-color: #b46500; }
        .btn-back { background-color: #ccc; color: #333; }
        .btn-back:hover { background-color: #aaa; }
        .table-responsive { width: 100%; overflow-x: auto; border: 1px solid #ddd; max-height: 600px; overflow-y: auto; background: #fff; }
        table { width: 100%; border-collapse: collapse; font-size: 13px; min-width: 900px; }
        thead tr { background-color: black; color: white; height: 35px; font-weight: normal; }
        thead th { padding: 6px 5px; text-align: left; border-right: 1px solid #444; white-space: nowrap; }
        thead th:last-child { border-right: none; }
        tbody tr { background-color: #fefefe; border-bottom: 1px solid #ddd; min-height: 28px; }
        tbody tr:nth-child(even) { background-color: #f8f8f8; }
        tbody td { padding: 5px 5px; border-right: 1px solid #ddd; white-space: nowrap; vertical-align: middle; font-weight: 400; font-size: 12px; }
        tbody td:last-child { border-right: none; }
        .status-active { color: green; font-weight: bold; }
        .status-inactive { color: red; font-weight: bold; }
        .btn-action { padding: 2px 6px; font-size: 11px; margin: 1px; border: 1px solid #ddd; background: white; cursor: pointer; border-radius: 2px; text-decoration: none; display: inline-block; }
        .btn-edit { color: #0d6efd; border-color: #0d6efd; }
        .btn-edit:hover { background: #0d6efd; color: white; }
        .btn-delete { color: #dc3545; border-color: #dc3545; }
        .btn-delete:hover { background: #dc3545; color: white; }
        .btn-add-value { color: #198754; border-color: #198754; }
        .btn-add-value:hover { background: #198754; color: white; }
        .category-header { background-color: #e9ecef !important; font-weight: bold; }
        .search-box { padding: 5px 10px; border: 1px solid #ddd; border-radius: 2px; font-size: 13px; width: 200px; }
        /* Modal Styles */
        .modal { display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,.5); z-index:1000; align-items:center; justify-content:center; }
        .modal.show { display:flex; }
        .modal-content { background:#fff; border-radius:6px; width:95%; max-width:400px; box-shadow:0 4px 6px rgba(0,0,0,.1); padding:0; }
        .modal-header { background-color: #f8f9fa; border-bottom: 1px solid #dee2e6; padding: 10px 15px; display:flex; align-items:center; justify-content:space-between; }
        .modal-title { font-weight: 600; font-size: 16px; }
        .modal-close { background:none; border:none; font-size:18px; cursor:pointer; color:#666; }
        .modal-body { padding:15px; }
        .modal-footer { padding:12px 15px; border-top:1px solid #ddd; display:flex; justify-content:flex-end; gap:8px; background:#f9f9f9; }
        .form-control { font-size: 13px; width:100%; padding:6px 8px; border:1px solid #ccc; border-radius:2px; }
        .form-label { font-size: 13px; font-weight: 600; margin-bottom: 5px; display:block; }
        .form-check { display:flex; align-items:center; gap:8px; margin-bottom:10px; }
        .form-check-input { width:16px; height:16px; }
        .form-check-label { font-size:13px; }
        .mb-3 { margin-bottom: 12px; }
        @media (max-width: 768px) {
            .top-bar { flex-direction: column; align-items: flex-start; }
            .action-buttons { margin-left: 0; width: 100%; }
            .table-responsive { max-height: 500px; }
        }
        .alert-success { background:#d4edda; color:#155724; border:1px solid #c3e6cb; padding:8px 12px; border-radius:3px; margin-bottom:12px; }
        .alert-close { background:none; border:none; font-size:16px; cursor:pointer; float:right; }
    </style>
</head>
<body>
@extends('layouts.app')
@section('content')

<div class="dashboard">
    <div class="container-table">
        <h3>Lookup Tables Management</h3>
        @if(session('success'))
        <div class="alert alert-success" id="successAlert">
            {{ session('success') }}
            <button type="button" class="alert-close" onclick="document.getElementById('successAlert').style.display='none'">×</button>
        </div>
        @endif
        <div class="top-bar">
            <div class="records-found">Total {{ $categories->count() }} Categories Found</div>
            <div>
                <input type="text" id="searchInput" class="search-box" placeholder="Search categories...">
            </div>
            <div class="action-buttons">
                <button type="button" class="btn btn-add" onclick="openAddCategoryModal()">
                    <i class="fas fa-plus"></i> Add Category
                </button>
                <button class="btn btn-back" onclick="window.history.back()">
                    <i class="fas fa-arrow-left"></i> Back
                </button>
            </div>
        </div>
        <div class="table-responsive">
            <table id="lookupTable">
                <thead>
                    <tr>
                        <th>Seq</th>
                        <th>Category Name</th>
                        <th>Active</th>
                        <th>Values Count</th>
                        <th>Description/Code</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($categories as $category)
                    <tr class="category-header" data-category="{{ strtolower($category->name) }}">
                        <td>{{ $loop->iteration }}</td>
                        <td><strong>{{ $category->name }}</strong></td>
                        <td>
                            <span class="{{ $category->active ? 'status-active' : 'status-inactive' }}">
                                {{ $category->active ? 'Yes' : 'No' }}
                            </span>
                        </td>
                        <td>{{ $category->values->count() }}</td>
                        <td>-</td>
                        <td>
                            <button type="button" class="btn-action btn-add-value" 
                                    onclick="openAddValueModal({{ $category->id }}, '{{ $category->name }}')"
                                    title="Add Value">
                                <i class="fas fa-plus"></i> Add Value
                            </button>
                            <button type="button" class="btn-action btn-edit" 
                                    onclick="openEditCategoryModal({{ $category->id }}, '{{ $category->name }}', {{ $category->active ? 'true' : 'false' }})"
                                    title="Edit Category">
                                <i class="fas fa-edit"></i>
                            </button>
                            <form action="{{ route('lookup-categories.destroy', $category) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-action btn-delete" title="Delete Category" 
                                        onclick="return confirm('Are you sure you want to delete this value?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @foreach($category->values as $value)
                    <tr data-category="{{ strtolower($category->name) }}">
                        <td>{{ $value->seq }}</td>
                        <td>{{ $value->name }}</td>
                        <td>
                            <span class="{{ $value->active ? 'status-active' : 'status-inactive' }}">
                                {{ $value->active ? 'Yes' : 'No' }}
                            </span>
                        </td>
                        <td>-</td>
                        <td>
                            @if($value->description)
                                {{ $value->description }}
                            @elseif($value->code)
                                Code: {{ $value->code }}
                            @else
                                -
                            @endif
                        </td>
                        <td>
                            <button type="button" class="btn-action btn-edit" 
                                    onclick="openEditValueModal({{ $value->id }}, {{ $value->seq }}, '{{ $value->name }}', '{{ $value->description }}', '{{ $value->code }}', {{ $value->active ? 'true' : 'false' }})"
                                    title="Edit Value">
                                <i class="fas fa-edit"></i>
                            </button>
                            <form action="{{ route('lookup-values.destroy', $value) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-action btn-delete" title="Delete Value" 
                                        onclick="return confirm('Kya aap sure hain ke aap ye value delete karna chahte hain?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                    @if($category->values->count() == 0)
                    <tr data-category="{{ strtolower($category->name) }}">
                        <td colspan="6" style="text-align: center; color: #6c757d;">
                            Is category mein koi values nahi hain
                        </td>
                    </tr>
                    @endif
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Add Category Modal -->
    <div class="modal" id="addCategoryModal">
        <div class="modal-content">
            <div class="modal-header">
                <span class="modal-title">Add New Category</span>
                <button type="button" class="modal-close" onclick="closeModal('addCategoryModal')">×</button>
            </div>
            <form id="addCategoryForm" action="{{ route('lookup-categories.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="categoryName" class="form-label">Category Name</label>
                        <input type="text" class="form-control" id="categoryName" name="name" required>
                    </div>
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="categoryActive" name="active" value="1" checked>
                        <label class="form-check-label" for="categoryActive">Active</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-back" onclick="closeModal('addCategoryModal')">Cancel</button>
                    <button type="submit" class="btn btn-add">Save Category</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Category Modal -->
    <div class="modal" id="editCategoryModal">
        <div class="modal-content">
            <div class="modal-header">
                <span class="modal-title">Edit Category</span>
                <button type="button" class="modal-close" onclick="closeModal('editCategoryModal')">×</button>
            </div>
            <form id="editCategoryForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="editCategoryName" class="form-label">Category Name</label>
                        <input type="text" class="form-control" id="editCategoryName" name="name" required>
                    </div>
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="editCategoryActive" name="active" value="1">
                        <label class="form-check-label" for="editCategoryActive">Active</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-back" onclick="closeModal('editCategoryModal')">Cancel</button>
                    <button type="submit" class="btn btn-add">Update Category</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Add Value Modal -->
    <div class="modal" id="addValueModal">
        <div class="modal-content">
            <div class="modal-header">
                <span class="modal-title">Add Value to <span id="addValueCategoryName"></span></span>
                <button type="button" class="modal-close" onclick="closeModal('addValueModal')">×</button>
            </div>
            <form id="addValueForm" method="POST">
                @csrf
                <div class="modal-body">
                    <input type="hidden" id="addValueCategoryId" name="lookup_category_id">
                    <div class="mb-3">
                        <label for="valueSeq" class="form-label">Sequence Number</label>
                        <input type="number" class="form-control" id="valueSeq" name="seq" required>
                    </div>
                    <div class="mb-3">
                        <label for="valueName" class="form-label">Value Name</label>
                        <input type="text" class="form-control" id="valueName" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="valueDescription" class="form-label">Description (Optional)</label>
                        <textarea class="form-control" id="valueDescription" name="description" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="valueCode" class="form-label">Code (Optional)</label>
                        <input type="text" class="form-control" id="valueCode" name="code">
                    </div>
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="valueActive" name="active" value="1" checked>
                        <label class="form-check-label" for="valueActive">Active</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-back" onclick="closeModal('addValueModal')">Cancel</button>
                    <button type="submit" class="btn btn-add">Save Value</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Value Modal -->
    <div class="modal" id="editValueModal">
        <div class="modal-content">
            <div class="modal-header">
                <span class="modal-title">Edit Value</span>
                <button type="button" class="modal-close" onclick="closeModal('editValueModal')">×</button>
            </div>
            <form id="editValueForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="editValueSeq" class="form-label">Sequence Number</label>
                        <input type="number" class="form-control" id="editValueSeq" name="seq" required>
                    </div>
                    <div class="mb-3">
                        <label for="editValueName" class="form-label">Value Name</label>
                        <input type="text" class="form-control" id="editValueName" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="editValueDescription" class="form-label">Description (Optional)</label>
                        <textarea class="form-control" id="editValueDescription" name="description" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="editValueCode" class="form-label">Code (Optional)</label>
                        <input type="text" class="form-control" id="editValueCode" name="code">
                    </div>
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="editValueActive" name="active" value="1">
                        <label class="form-check-label" for="editValueActive">Active</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-back" onclick="closeModal('editValueModal')">Cancel</button>
                    <button type="submit" class="btn btn-add">Update Value</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Search functionality
    document.getElementById('searchInput').addEventListener('keyup', function() {
        const filter = this.value.toLowerCase();
        const rows = document.getElementById('lookupTable').getElementsByTagName('tr');
        for (let i = 1; i < rows.length; i++) {
            const category = rows[i].getAttribute('data-category');
            const cells = rows[i].getElementsByTagName('td');
            let found = false;
            if (category && category.includes(filter)) {
                found = true;
            } else {
                for (let j = 0; j < cells.length; j++) {
                    if (cells[j]) {
                        const text = cells[j].textContent.toLowerCase();
                        if (text.includes(filter)) {
                            found = true;
                            break;
                        }
                    }
                }
            }
            rows[i].style.display = found ? '' : 'none';
        }
    });

    // Modal helpers
    function openAddCategoryModal() {
        document.getElementById('addCategoryForm').reset();
        document.getElementById('addCategoryModal').classList.add('show');
        document.body.style.overflow = 'hidden';
    }
    function openEditCategoryModal(categoryId, categoryName, isActive) {
        document.getElementById('editCategoryName').value = categoryName;
        document.getElementById('editCategoryActive').checked = isActive;
        document.getElementById('editCategoryForm').action = `/lookups/categories/${categoryId}`;
        document.getElementById('editCategoryModal').classList.add('show');
        document.body.style.overflow = 'hidden';
    }
    function openAddValueModal(categoryId, categoryName) {
        document.getElementById('addValueCategoryName').textContent = categoryName;
        document.getElementById('addValueCategoryId').value = categoryId;
        document.getElementById('addValueForm').action = `/lookups/categories/${categoryId}/values`;
        document.getElementById('addValueForm').reset();
        document.getElementById('addValueModal').classList.add('show');
        document.body.style.overflow = 'hidden';
    }
    function openEditValueModal(valueId, seq, name, description, code, isActive) {
        document.getElementById('editValueSeq').value = seq;
        document.getElementById('editValueName').value = name;
        document.getElementById('editValueDescription').value = description;
        document.getElementById('editValueCode').value = code;
        document.getElementById('editValueActive').checked = isActive;
        document.getElementById('editValueForm').action = `/lookups/values/${valueId}`;
        document.getElementById('editValueModal').classList.add('show');
        document.body.style.overflow = 'hidden';
    }
    function closeModal(id) {
        document.getElementById(id).classList.remove('show');
        document.body.style.overflow = '';
    }
    // Close modal on backdrop click or ESC
    document.querySelectorAll('.modal').forEach(function(modal){
        modal.addEventListener('click', function(e){
            if(e.target === modal) closeModal(modal.id);
        });
    });
    document.addEventListener('keydown', function(e){
        if(e.key === 'Escape'){
            document.querySelectorAll('.modal.show').forEach(function(modal){
                closeModal(modal.id);
            });
        }
    });
    // Form submission handlers
    document.getElementById('addCategoryForm').addEventListener('submit', function(e) { });
    document.getElementById('editCategoryForm').addEventListener('submit', function(e) { });
    document.getElementById('addValueForm').addEventListener('submit', function(e) { });
    document.getElementById('editValueForm').addEventListener('submit', function(e) { });
</script>
@endsection
</body>
</html>
