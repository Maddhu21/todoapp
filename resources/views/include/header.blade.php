<style>
    .profile-overlay {
        opacity: 0 !important;
        transition: opacity 0.3s ease !important;
        background-color: rgba(0, 0, 0, 0.5) !important;
    }

    .profile-wrapper:hover .profile-overlay {
        opacity: 1 !important;
    }

    .profile-wrapper {
        transition: all 0.3s ease !important;
        width: 150px;
        height: 150px;
    }
</style>

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
            <img src="{{ asset('storage/'.Auth()->user()->profile_image) ?? asset('assets\pictures\userprofile\default.svg') }}"
                alt="" width="32" height="32" class="rounded-circle me-2 object-fit-cover" id="sidebarProfilePicture">
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
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content shadow">
            <div class="row">
                <div class="col-md-4 py-3 bg-light">
                    <form id="changePfpForm" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-5 position-relative d-inline-block profile-wrapper">
                            <img src="{{ asset('storage/'.Auth()->user()->profile_image ) ?? asset('assets\pictures\userprofile\default.svg') }}"
                                alt="" class="rounded-5 me-2 object-fit-cover profile-image w-100 h-100"
                                id="profileImagePreview">

                            <!-- Camera Icon Overlay -->
                            <label for="profileImageInput" class="profile-overlay position-absolute top-0 start-0 w-100 h-100 d-flex flex-column justify-content-center align-items-center text-white rounded-5"
                                id="cameraIconOverlay" style="cursor: pointer;">
                                <i>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="icon icon-tabler icons-tabler-outline icon-tabler-camera">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path
                                            d="M5 7h1a2 2 0 0 0 2 -2a1 1 0 0 1 1 -1h6a1 1 0 0 1 1 1a2 2 0 0 0 2 2h1a2 2 0 0 1 2 2v9a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-9a2 2 0 0 1 2 -2" />
                                        <path d="M9 13a3 3 0 1 0 6 0a3 3 0 0 0 -6 0" />
                                    </svg>
                                    <small>Click to change</small>
                                </i>
                            </label>
                            <input type="file" id="profileImageInput" name="profile_image" accept="image/*"
                                class="d-none">
                        </div>
                    </form>

                    <ul class="nav flex-column mb-auto">
                        <li class="nav-item">
                            <a href="#" class="nav-link active" aria-current="page">
                                Account Details
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link link-body-emphasis">
                                Change Password
                            </a>
                        </li>
                    </ul>
                </div>
                <!-- Right Side -->
                <div class="col-md-8 py-3">
                    <form id="editProfileForm" action="" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3 d-flex align-items-center">
                            <label for="userName" class="form-control border-0 text-end">Name</label>
                            <input name="name" type="text" style="background-color: transparent;"
                                class="form-control border-0 border-2 shadow-none bg-none" id="userName" disabled>
                        </div>

                        <div class="mb-3 d-flex align-items-center">
                            <label for="userEmail" class="form-control w-75 border-0 text-end">Email</label>
                            <input name="email" type="text" style="background-color: transparent;"
                                class="form-control border-0 border-2 shadow-none" id="userEmail" disabled>
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
    </div>
</div>

@push('scripts')
<script type="module">
    //Script for opening profile modal
    let name = '';
    let email = '';
    let originalProfile = document.getElementById('profileImagePreview').src;
    $(document).on('click', '.enableEditProfileBtn', function() {
        openEdit();
    });

    $(document).on('click', '.cancelEditProfileBtn', function() {
        closeEdit(true);
    });

    $('#profileModal').on('shown.bs.modal', function() {
        $('#userName').val("{{ Auth()->user()->name }}");
        $('#userEmail').val("{{ Auth()->user()->email }}");
    })

    document.getElementById('profileImageInput').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();

            reader.onload = function(e) {
                document.getElementById('profileImagePreview').src = e.target.result;

            };
            reader.readAsDataURL(file);

            //Prepare FormData
            const form = document.getElementById("changePfpForm");
            const formData = new FormData(form);
            // Add the _method and _token if not automatically included
            formData.append('_method', 'PATCH');

            $.ajax({
                url: "{{ route('profiles.update', Auth()->id()) }}",
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    document.getElementById('sidebarProfilePicture').src = document.getElementById('profileImagePreview').src;
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
                        console.log(xhr.responseJSON);
                    }
                }
            });

        }
    });

    $('#editProfileForm').on('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this); // This automatically captures all form fields including files

        // For checkboxes, you might need to handle them separately
        $('#editProfileForm').find('input[type="checkbox"]').each(function() {
            const name = $(this).attr('name');
            if (!name) return;

            if (this.checked) {
                formData.append(name, $(this).val());
            }
        });
        // Add the _method and _token if not automatically included
        formData.append('_method', 'PATCH');

        $.ajax({
            url: "{{ route('profiles.update', Auth()->id()) }}",
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                closeEdit(false);
                $('#userName').val(formData.get('name'));
                $('#userEmail').val(formData.get('email'));
                $('#navbarUserName strong').text(formData.get('name'));
                document.getElementById('sidebarProfilePicture').src = document.getElementById('profileImagePreview').src;
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
                    console.log(xhr.responseJSON);
                }
            }
        });
    });

    function closeEdit(targetPicture) {
        $('.enableEditProfileBtn').removeClass('disabled');
        $('.editProfileFooter').addClass('d-none');
        $('#cameraIconOverlay').addClass('d-none');
        $('#userName').addClass('border-0');
        $('#userEmail').addClass('border-0');
        $('#userName').prop('disabled', true);
        $('#userEmail').prop('disabled', true);
        if (targetPicture) {
            document.getElementById('profileImagePreview').src = originalProfile;
        }
    }

    function openEdit() {
        $('.enableEditProfileBtn').addClass('disabled');
        $('.editProfileFooter').removeClass('d-none');
        $('#cameraIconOverlay').removeClass('d-none');
        $('#userName').removeClass('border-0');
        $('#userEmail').removeClass('border-0');
        $('#userName').removeAttr('disabled');
        $('#userEmail').removeAttr('disabled');
    }
</script>
@endpush