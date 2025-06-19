@extends("layout.default")
@section("content")
<main class="flex-shrink-0 mt-5">
    <div class="container" style="max-width: 600px;">
        <div class="my-3 p-3 bg-body rounded shadow-sm">
            <div class="border-bottom d-flex align-items-center justify-content-between pb-3">
                <div>
                    <h6>All Task</h6>
                </div>
                <div>
                    <!-- <a href="{{ route('task.add') }}" class="btn btn-success">New Task</a> -->
                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addTaskModal">
                        New Task
                    </button>
                </div>
            </div>

            @foreach ($tasks as $item)
            <div class="card mb-3 shadow-sm">
                <div class="card-body d-flex justify-content-between align-items-start">
                    <div class="d-flex flex-row align-items-center gap-3">
                        @if ($isComplete = $item->status_master_id == $status->firstWhere('name', 'Complete')?->id)
                        <div class="text-success">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-checks">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M7 12l5 5l10 -10" />
                                <path d="M2 12l5 5m5 -5l5 -5" />
                            </svg>
                        </div>
                        @elseif ($item->status_master_id == $status->firstWhere('name', 'Overdue')?->id)
                        <div class="text-danger">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor" class="icon icon-tabler icons-tabler-filled icon-tabler-alert-square-rounded">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M12 2l.642 .005l.616 .017l.299 .013l.579 .034l.553 .046c4.687 .455 6.65 2.333 7.166 6.906l.03 .29l.046 .553l.041 .727l.006 .15l.017 .617l.005 .642l-.005 .642l-.017 .616l-.013 .299l-.034 .579l-.046 .553c-.455 4.687 -2.333 6.65 -6.906 7.166l-.29 .03l-.553 .046l-.727 .041l-.15 .006l-.617 .017l-.642 .005l-.642 -.005l-.616 -.017l-.299 -.013l-.579 -.034l-.553 -.046c-4.687 -.455 -6.65 -2.333 -7.166 -6.906l-.03 -.29l-.046 -.553l-.041 -.727l-.006 -.15l-.017 -.617l-.004 -.318v-.648l.004 -.318l.017 -.616l.013 -.299l.034 -.579l.046 -.553c.455 -4.687 2.333 -6.65 6.906 -7.166l.29 -.03l.553 -.046l.727 -.041l.15 -.006l.617 -.017c.21 -.003 .424 -.005 .642 -.005zm.01 13l-.127 .007a1 1 0 0 0 0 1.986l.117 .007l.127 -.007a1 1 0 0 0 0 -1.986l-.117 -.007zm-.01 -8a1 1 0 0 0 -.993 .883l-.007 .117v4l.007 .117a1 1 0 0 0 1.986 0l.007 -.117v-4l-.007 -.117a1 1 0 0 0 -.993 -.883z" />
                            </svg>
                        </div>
                        @else
                        <div class="text-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-hourglass">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M6.5 7h11" />
                                <path d="M6.5 17h11" />
                                <path d="M6 20v-2a6 6 0 1 1 12 0v2a1 1 0 0 1 -1 1h-10a1 1 0 0 1 -1 -1z" />
                                <path d="M6 4v2a6 6 0 1 0 12 0v-2a1 1 0 0 0 -1 -1h-10a1 1 0 0 0 -1 1z" />
                            </svg>
                        </div>
                        @endif
                        <div class="flex-grow-1">
                            <h5 class="card-title mb-1 {{ $isComplete ? 'text-decoration-line-through' : '' }}">
                                {{ $item->title }}
                                @if($item->status_master_id == $status->firstWhere('name', 'Overdue')?->id)
                                <span class="badge bg-danger flash">Overdue</span>
                                @endif
                            </h5>
                            <p class="card-subtitle mb-2 text-muted small">Deadline: {{ $item->deadline }}</p>
                            <p class="card-text">{{ $item->description }}</p>
                        </div>

                    </div>

                    <div class="d-flex align-items-start gap-2">
                        <div class="dropdown">
                            <!-- SVG -->
                            <button class="btn btn-primary" type="button" id="dropdownMenuButton{{ $item->id }}" data-bs-toggle="dropdown" aria-expanded="false">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="currentColor" class="icon icon-tabler icon-tabler-caret-down">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M6 9l6 6l6 -6" />
                                </svg>
                            </button>
                            <!-- Dropdown items -->
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton{{ $item->id }}">
                                @php
                                $statusFlag = in_array($item->status_master_id, [
                                $status->firstWhere('name', 'Incomplete')?->id,
                                $status->firstWhere('name', 'Overdue')?->id
                                ]);
                                @endphp

                                @if ($statusFlag)
                                <li>
                                    <a class="dropdown-item" href="{{ route('task.status.update', [$item->id, $status->firstWhere('name', 'Complete')?->id]) }}">Mark as Done</a>
                                </li>
                                @else
                                <li>
                                    <a class="dropdown-item" href="{{ route('task.status.update', [$item->id, $status->firstWhere('name', 'Incomplete')?->id]) }}">Mark as Incomplete</a>
                                </li>
                                @endif

                                <li><a href="#" class="dropdown-item btn-edit-task" data-id="{{ $item->id }}" data-bs-toggle="modal" data-bs-target="#editTaskModal">Edit</a></li>
                            </ul>
                        </div>

                        <form action="{{ route('task.delete', $item->id) }}" method="post">
                            @csrf
                            @method('DELETE')
                            <!-- SVG -->
                            <button type="submit" class="btn btn-danger">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M4 7h16" />
                                    <path d="M10 11v6" />
                                    <path d="M14 11v6" />
                                    <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2l1-12" />
                                    <path d="M9 7V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v3" />
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    </div>
</main>

<!-- Add Task Modal -->
<div class="modal fade" id="addTaskModal" tabindex="-1" aria-labelledby="addTaskModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="addTaskModalLabel">Add New Task</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('task.add.post') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="inputTitle" class="form-label">Title</label>
                        <input name="title" type="text" class="form-control" id="inputTitle" value="{{ old('title') }}">
                        @error('title')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="inputDate" class="form-label">Deadline</label>
                        <input name="deadline" type="date" class="form-control" id="inputDate">
                        @error('deadline')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="descInput" class="form-label">Description</label>
                        <textarea name="description" class="form-control" id="descInput" rows="3"></textarea>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary rounded-pill" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success rounded-pill">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Task Modal -->
<div class="modal fade" id="editTaskModal" tabindex="-1" aria-labelledby="editTaskModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Edit Task</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form id="editTaskForm">
                @csrf
                @method('PUT')
                <input type="hidden" id="editTaskId">

                <div class="modal-body">
                    <div class="mb-3">
                        <label for="editTitle" class="form-label">Title</label>
                        <input type="text" id="editTitle" class="form-control" name="title">
                    </div>

                    <div class="mb-3">
                        <label for="editDeadline" class="form-label">Deadline</label>
                        <input type="date" id="editDeadline" class="form-control" name="deadline">
                    </div>

                    <div class="mb-3">
                        <label for="editDescription" class="form-label">Description</label>
                        <textarea id="editDescription" class="form-control" name="description" rows="3"></textarea>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary rounded-pill" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success rounded-pill">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script type="module">
    $(document).ready(function() {
        // Open modal and prefill values
        $('.btn-edit-task').on('click', function(e) {
            e.preventDefault();
            let taskId = $(this).data('id');

            $.get('/task/' + taskId, function(task) {
                $('#editTaskId').val(task.id);
                $('#editTitle').val(task.title);
                $('#editDeadline').val(task.deadline);
                $('#editDescription').val(task.description);
            }).fail(function() {
                toastr.error('Failed to fetch task details.', 'Error');
            });
        });

        // Handle AJAX form submission
        $('#editTaskForm').on('submit', function(e) {
            e.preventDefault();

            let taskId = $('#editTaskId').val();
            let formData = {
                _token: '{{ csrf_token() }}',
                _method: 'PUT',
                title: $('#editTitle').val(),
                deadline: $('#editDeadline').val(),
                description: $('#editDescription').val()
            };

            $.ajax({
                url: '/task/update/' + taskId,
                type: 'POST',
                data: formData,
                success: function(response) {
                    toastr.success('Task has been updated.', 'Success')
                    setTimeout(() => {
                        location.reload();
                    }, 1000); // waits 2 seconds
                },
                error: function(xhr) {
                    toastr.error('Failed to update task. Please check input.', 'Fail');
                }
            });
        });
    });
</script>
@endsection