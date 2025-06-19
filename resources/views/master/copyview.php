@extends("layout.default")

@section("content")
<main class="flex-shrink-0 mt-5">
    <div class="container" style="max-width: 800px;">
        <div class="my-3 p-3 bg-body rounded shadow-sm">
            <h6 class="pb-2 mb-3">Masters Lists</h6>
            <form id="tableSelectForm">
                <select name="table_names" id="tableSelect" class="form-select mb-3">
                    <option value="">Please Select</option>
                    @php
                    $identifier = ['_', 'masters'];
                    $replacement = [' ', ''];
                    @endphp
                    @foreach ($masterTables as $tableItem)
                    <option value="{{ $tableItem }}">{{ ucwords(str_replace($identifier, $replacement, $tableItem)) }}</option>
                    @endforeach
                </select>
            </form>

            <div class="d-flex justify-content-between mb-2">
                <input type="text" class="form-control w-50" id="searchInput" placeholder="Search...">
                <button class="btn btn-success" id="addNewBtn">Add New</button>
            </div>

            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="tableBody"></tbody>
            </table>
            <nav>
                <ul class="pagination" id="pagination"></ul>
            </nav>
        </div>
    </div>
</main>

<!-- Modal -->
<div class="modal fade" id="masterModal" tabindex="-1">
    <div class="modal-dialog">
        <form id="masterForm" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Add</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="id" id="inputId">
                <input type="hidden" name="table" id="inputTable">
                <div class="mb-3">
                    <label for="inputName" class="form-label">Name</label>
                    <input type="text" class="form-control" name="name" id="inputName" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Save</button>
            </div>
        </form>
    </div>
</div>

<script type="module">
    let selectedTable = "";
    let currentPage = 1;

    function fetchData(page = 1) {
        if (!selectedTable) return;
        $.post("{{ route('masters.data') }}", {
            table: selectedTable,
            search: $('#searchInput').val(),
            page: page,
            _token: '{{ csrf_token() }}'
        }, function(res) {
            $('#tableBody').html('');
            if (res.data.length === 0) {
                $('#tableBody').append('<tr><td colspan="3" class="text-center">No data found</td></tr>');
            } else {
                res.data.forEach((row, index) => {
                    $('#tableBody').append(`
                <tr>
                    <td>${index + 1}</td>
                    <td>${row.name}</td>
                    <td>
                        <button class="btn btn-sm btn-warning editBtn" data-id="${row.id}" data-name="${row.name}">Edit</button>
                        <button class="btn btn-sm btn-danger deleteBtn" data-id="${row.id}">Delete</button>
                    </td>
                </tr>`);
                });
            }
            renderPagination(res);
        }).fail(() => toastr.error("Failed to fetch data."));
    }

    function renderPagination(res) {
        $('#pagination').html('');
        for (let i = 1; i <= res.last_page; i++) {
            $('#pagination').append(`<li class="page-item ${i === res.current_page ? 'active' : ''}"><a class="page-link" href="#">${i}</a></li>`);
        }
    }

    $(document).on('click', '.page-link', function(e) {
        e.preventDefault();
        currentPage = parseInt($(this).text());
        fetchData(currentPage);
    });

    $('#tableSelect').on('change', function() {
        selectedTable = $(this).val();
        $('#inputTable').val(selectedTable);
        console.log('trigger');
        fetchData();
    });

    $('#searchInput').on('input', function() {
        fetchData();
    });

    $('#addNewBtn').on('click', function() {
        $('#modalTitle').text('Add');
        $('#inputId').val('');
        $('#inputName').val('');
        const modal = bootstrap.Modal.getOrCreateInstance(document.getElementById('masterModal'));
        modal.show();
    });

    $(document).on('click', '.editBtn', function() {
        $('#modalTitle').text('Edit');
        $('#inputId').val($(this).data('id'));
        $('#inputName').val($(this).data('name'));
        const modal = bootstrap.Modal.getOrCreateInstance(document.getElementById('masterModal'));
        modal.show();
    });

    $('#masterForm').on('submit', function(e) {
        e.preventDefault();
        const formData = $(this).serialize();
        const id = $('#inputId').val();
        const url = id ? "{{ route('masters.update', ':id') }}".replace(':id', id) : "{{ route('masters.store') }}";
        const method = id ? 'POST' : 'POST';
        $.ajax({
            url: url,
            method: method,
            data: formData + (id ? '&_method=PUT' : ''),
            success: function() {
                toastr.success(id ? "Updated successfully" : "Created successfully");
                bootstrap.Modal.getOrCreateInstance(document.getElementById('masterModal')).hide();
                fetchData();
            },
            error: () => toastr.error("Failed to save data.")
        });
    });

    $(document).on('click', '.deleteBtn', function() {
        if (!confirm('Are you sure?')) return;
        $.ajax({
            url: "{{ route('masters.destroy', ':id') }}".replace(':id', $(this).data('id')),
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                _method: 'DELETE',
                table: selectedTable
            },
            success: function() {
                toastr.success("Deleted successfully");
                fetchData();
            },
            error: () => toastr.error("Delete failed.")
        });
    });
</script>
@endsection