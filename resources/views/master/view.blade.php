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
                <thead id="tableHead">
                    <tr>
                        <th>No.</th>
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
                <div id="dynamicFields"></div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Save</button>
            </div>
        </form>
    </div>
</div>

<script type="module">
    let selectedTable = "";
    let selectedText = "";
    let currentPage = 1;
    let currentColumns = [];

    function fetchData(page = 1) {
        if (!selectedTable) return;
        $.post("{{ route('masters.data') }}", {
            table: selectedTable,
            search: $('#searchInput').val(),
            page: page,
            _token: '{{ csrf_token() }}'
        }, function(res) {
            currentColumns = res.columns;
            renderTableHead();
            $('#tableBody').html('');
            if (res.data.length === 0) {
                $('#tableBody').append('<tr><td colspan="' + (currentColumns.length + 2) + '" class="text-center">No data found</td></tr>');
            } else {
                res.data.forEach((row, index) => {
                    let cells = `<td>${index + 1}</td>`;
                    currentColumns.forEach(col => {
                        cells += `<td>${row[col] ?? ''}</td>`;
                    });
                    cells += `<td>
                        <button class="btn btn-sm btn-warning editBtn" data-id="${row.id}">Edit</button>
                        <button class="btn btn-sm btn-danger deleteBtn" data-id="${row.id}">Delete</button>
                    </td>`;
                    $('#tableBody').append(`<tr>${cells}</tr>`);
                });
            }
            renderPagination(res);
        }).fail(() => toastr.error("Failed to fetch data."));
    }

    function renderTableHead() {
        let headRow = '<tr><th>No.</th>';
        currentColumns.forEach(col => {
            headRow += `<th>${col.replace(/_/g, ' ').replace(/\b\w/g, char => char.toUpperCase())}</th>`;
        });
        headRow += '<th>Actions</th></tr>';
        $('#tableHead').html(headRow);
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
        selectedText = $(this).find('option:selected').text();
        $('#inputTable').val(selectedTable);
        fetchData();
    });

    $('#searchInput').on('input', function() {
        fetchData();
    });

    $('#addNewBtn').on('click', function() {
        $('#modalTitle').text('Add ' + selectedText);
        $('#inputId').val('');
        $('#inputTable').val(selectedTable);
        $('#dynamicFields').html('');
        currentColumns.forEach(col => {
            $('#dynamicFields').append(`
                <div class="mb-3">
                    <label class="form-label">${col.replace(/_/g, ' ').replace(/\b\w/g, char => char.toUpperCase())}</label>
                    <input type="text" class="form-control" name="${col}" required>
                </div>`);
        });
        bootstrap.Modal.getOrCreateInstance(document.getElementById('masterModal')).show();
    });

    $(document).on('click', '.editBtn', function() {
        const id = $(this).data('id');
        $('#modalTitle').text('Edit ' + selectedText);
        $('#inputId').val(id);
        $('#inputTable').val(selectedTable);

        $.post("{{ route('masters.edit.fetch') }}", {
            _token: '{{ csrf_token() }}',
            table: selectedTable,
            id: id
        }, function(row) {
            console.log()
            let modalBody = '';
            currentColumns.forEach(col => {
                if (['id', 'created_at', 'updated_at', 'deleted_at'].includes(col)) return;
                const label = col.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
                const value = row[col] ?? '';
                modalBody += `
                <div class="mb-3">
                    <label for="input_${col}" class="form-label">${label}</label>
                    <input type="text" class="form-control" name="${col}" id="input_${col}" value="${value}" required>
                </div>`;
            });
            $('#dynamicFields').html(modalBody);
            bootstrap.Modal.getOrCreateInstance(document.getElementById('masterModal')).show();
        }).fail(() => toastr.error("Failed to load data."));
    });

    $('#masterForm').on('submit', function(e) {
        e.preventDefault();
        const id = $('#inputId').val();
        const url = id ? "{{ url('/masters') }}/" + id : "{{ route('masters.store') }}";
        const method = id ? 'POST' : 'POST';
        const formData = $(this).serialize() + (id ? '&_method=PUT' : '');
        $.ajax({
            url: url,
            method: method,
            data: formData,
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
            url: "{{ url('/masters') }}/" + $(this).data('id'),
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