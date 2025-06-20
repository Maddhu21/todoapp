<div class="d-flex flex-column flex-shrink-0 p-3 bg-body-secondary" style="width: 280px;">
    <a href="/" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto link-body-emphasis text-decoration-none">
        <span class="fs-4">{{ ENV('APP_NAME') }}</span>
    </a>
    <hr>
    <ul class="nav nav-pills flex-column mb-auto">
        <li class="nav-item">
            <a href="{{ route('home') }}"
                class="nav-link {{ request()->routeIs('home') ? 'active' : 'link-body-emphasis' }}" aria-current="page">
                Home
            </a>
        </li>
        @if (session('role') == 'Admin')
            <hr>
            <li>
                <a href="{{ route('masters.index') }}"
                    class="nav-link {{ request()->routeIs('masters.index') ? 'active' : 'link-body-emphasis' }}">
                    Master Settings
                </a>
            </li>
        @endif
    </ul>
    <hr>
    <div class="dropdown">
        <a href="#" class="d-flex align-items-center link-body-emphasis text-decoration-none dropdown-toggle"
            data-bs-toggle="dropdown" aria-expanded="false">
            <img src="{{ asset('assets\pictures\userprofile\default.svg') }}" alt="" width="32"
                height="32" class="rounded-circle me-2">
            <span id="navbarUserName"><strong>{{ Auth()->user()->name }}</strong></span>
        </a>
        <ul class="dropdown-menu text-small shadow">
            <li><a class="dropdown-item profileBtn" href="#" data-bs-toggle="modal"
                    data-bs-target="#profileModal">Profile</a></li>
            <li>
                <hr class="dropdown-divider">
            </li>
            <li><a class="dropdown-item" href="{{ route('logout') }}">Sign out</a></li>
        </ul>
    </div>
</div>

<!-- User Profile -->
<div class="modal fade" id="profileModal" tabindex="-1" aria-labelledby="profileModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="profileModalLabel">User Profile</h5>
                <a href="#" class="btn btn-warning mx-3 enableEditProfileBtn">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-pencil">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M4 20h4l10.5 -10.5a2.828 2.828 0 1 0 -4 -4l-10.5 10.5v4" />
                        <path d="M13.5 6.5l4 4" />
                    </svg>
                </a>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="d-flex justify-content-center">
                <img src="http://127.0.0.1:8000/assets\pictures\userprofile\default.svg" alt="" width="300px"
                    height="300px" class="rounded-circle me-2">
            </div>
            <form id="editProfileForm" action="" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3 d-flex align-items-center">
                        <label for="userName" class="form-control w-75 border-0 text-end">Name</label>
                        <input name="name" type="text" style="background-color: transparent;"
                            class="form-control border-0 border-2 shadow-none bg-none" id="userName" disabled>
                    </div>

                    <div class="mb-3 d-flex align-items-center">
                        <label for="userEmail" class="form-control w-75 border-0 text-end">Email</label>
                        <input name="email" type="text" style="background-color: transparent;"
                            class="form-control border-0 border-2 shadow-none" id="userEmail" disabled>
                    </div>
                </div>

                <div class="modal-footer editProfileFooter d-none">
                    <button type="button"
                        class="btn btn-outline-danger rounded-pill cancelEditProfileBtn">Cancel</button>
                    <button type="submit" class="btn btn-warning rounded-pill">Edit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script type="module">
    //Script for opening profile modal
    let name = '';
    let email = '';
    $(document).on('click', '.enableEditProfileBtn', function() {
        openEdit();
    });

    $(document).on('click', '.cancelEditProfileBtn', function() {
        closeEdit();
    });

    $('#profileModal').on('shown.bs.modal', function() {
        $('#userName').val("{{ Auth()->user()->name }}");
        $('#userEmail').val("{{ Auth()->user()->email }}");
    })

    $('#editProfileForm').on('submit', function(e) {
        e.preventDefault();
        const data = {};

        $('#editProfileForm').find('input, select, textarea').each(function() {
            const name = $(this).attr('name');
            if (!name) return;

            const type = $(this).attr('type');

            if (type === 'checkbox') {
                if (!data[name]) data[name] = [];
                if (this.checked) data[name].push($(this).val());
            } else {
                data[name] = $(this).val();
            }
        });

        $.ajax({
            url: '{{ route('profiles.update', Auth()->id()) }}',
            type: 'POST',
            data: {
                _method: 'PATCH',
                _token: data._token,
                name: data.name,
                email: data.email
            },
            success: function(response) {
                closeEdit();
                $('#userName').val(data.name);
                $('#userEmail').val(data.email);
                $('#navbarUserName strong').text(data.name);
                toastr.success("Profile has been updated.", "Success");
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    let errors = xhr.responseJSON.errors;
                    $.each(errors, function(field, messages) {
                        toastr.error(messages[0],
                            "Fail"); // Show first error for each field
                    });
                } else {
                    toastr.error("Profile update fail.", "Fail");
                    console.log(xhr.status);
                    console.log(xhr.responseJSON.errors);
                }
            }
        });
    });

    function closeEdit() {
        $('.enableEditProfileBtn').removeClass('disabled');
        $('.editProfileFooter').addClass('d-none');
        $('#userName').addClass('border-0');
        $('#userEmail').addClass('border-0');
        $('#userName').prop('disabled', true);
        $('#userEmail').prop('disabled', true);
    }

    function openEdit() {
        $('.enableEditProfileBtn').addClass('disabled');
        $('.editProfileFooter').removeClass('d-none');
        $('#userName').removeClass('border-0');
        $('#userEmail').removeClass('border-0');
        $('#userName').removeAttr('disabled');
        $('#userEmail').removeAttr('disabled');
    }
</script>
