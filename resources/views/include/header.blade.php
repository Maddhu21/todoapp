<!-- <header class="m-3">
    <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
        <div class="container-fluid"> <a class="navbar-brand" href="#">ToDo App</a> <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation"> <span class="navbar-toggler-icon"></span> </button>
            <div class="collapse navbar-collapse" id="navbarCollapse">
                <ul class="navbar-nav me-auto mb-2 mb-md-0">
                    <li class="nav-item"> <a class="nav-link active" aria-current="page" href="{{route('home')}}">Home</a> </li>
                    @if(session('role') == "Admin")
                    <li class="nav-item"> <a class="nav-link" href="{{route('masters.index')}}">Master</a> </li>
                    @endif
                </ul>
                <a href="{{route('logout')}}" class="btn btn-outline-secondary" role="button">Logout</a>
            </div>
        </div>
    </nav>
</header> -->
<div class="d-flex flex-column flex-shrink-0 p-3 bg-body-tertiary" style="width: 280px;">
    <a href="/" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto link-body-emphasis text-decoration-none">
        <span class="fs-4">{{ ENV('APP_NAME') }}</span>
    </a>
    <hr>
    <ul class="nav nav-pills flex-column mb-auto">
        <li class="nav-item">
            <a href="{{route('home')}}" class="nav-link {{ request()->routeIs('home') ? 'active' : 'link-body-emphasis' }}" aria-current="page">
                Home
            </a>
        </li>
        @if(session('role') == "Admin")
        <hr>
        <li>
            <a href="{{route('masters.index')}}" class="nav-link {{ request()->routeIs('masters.index') ? 'active' : 'link-body-emphasis' }}">
                Master Settings
            </a>
        </li>
        @endif
    </ul>
    <hr>
    <div class="dropdown">
        <a href="#" class="d-flex align-items-center link-body-emphasis text-decoration-none dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
            <img src="{{ asset('assets\pictures\userprofile\default.svg') }}" alt="" width="32" height="32" class="rounded-circle me-2">
            <strong>{{ Auth()->user()->name }}</strong>
        </a>
        <ul class="dropdown-menu text-small shadow">
            <li><a class="dropdown-item profileBtn" href="#" data-bs-toggle="modal" data-bs-target="#profileModal">Profile</a></li>
            <li>
                <hr class="dropdown-divider">
            </li>
            <li><a class="dropdown-item" href="{{route('logout')}}">Sign out</a></li>
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
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-pencil">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M4 20h4l10.5 -10.5a2.828 2.828 0 1 0 -4 -4l-10.5 10.5v4" />
                        <path d="M13.5 6.5l4 4" />
                    </svg>
                </a>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="d-flex justify-content-center">
                <img src="http://127.0.0.1:8000/assets\pictures\userprofile\default.svg" alt="" width="300px" height="300px" class="rounded-circle me-2">
            </div>
            <form action="" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3 d-flex align-items-center">
                        <label for="userName" class="form-control w-75 border-0 text-end">Name</label>
                        <input name="title" type="text" style="background-color: transparent;" class="form-control border-0 shadow-none bg-none" id="userName" value="{{ old('name') ?? Auth()->User()->name}}" disabled>
                    </div>

                    <div class="mb-3 d-flex align-items-center">
                        <label for="userEmail" class="form-control w-75 border-0 text-end">Email</label>
                        <input name="email" type="text"  style="background-color: transparent;" class="form-control border-0 shadow-none" id="userEmail" value="{{ old('email') ?? Auth()->User()->email}}" disabled>
                    </div>
                </div>

                <div class="modal-footer editProfileFooter d-none">
                    <button type="button" class="btn btn-outline-danger rounded-pill cancelEditProfileBtn">Cancel</button>
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
        name = $('#userName').val();
        email = $('#userEmail').val();
        $('.enableEditProfileBtn').addClass('disabled');
        $('.editProfileFooter').removeClass('d-none');
        $('#userName').removeAttr('disabled');
        $('#userEmail').removeAttr('disabled');
    });

    $(document).on('click', '.cancelEditProfileBtn', function() {
        console.log('jalan');
        $('.enableEditProfileBtn').removeClass('disabled');
        $('.editProfileFooter').addClass('d-none');
        $('#userName').val(name);
        $('#userEmail').val(email);
        $('#userName').prop('disabled', true);
        $('#userEmail').prop('disabled', true);
    });

    $(document).on('click', '.profileBtn', function() {
        bootstrap.Modal.getOrCreateInstance(document.getElementById('profileModal')).show();

        // $.post("{{ route('masters.edit.fetch') }}", {
        //     _token: '{{ csrf_token() }}',
        //     table: selectedTable,
        //     id: id
        // }, function (row) {
        //     console.log()
        //     let modalBody = '';
        //     currentColumns.forEach(col => {
        //         if (['id', 'created_at', 'updated_at', 'deleted_at'].includes(col)) return;
        //         const label = col.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
        //         const value = row[col] ?? '';
        //         modalBody += `
        //         <div class="mb-3">
        //             <label for="input_${col}" class="form-label">${label}</label>
        //             <input type="text" class="form-control" name="${col}" id="input_${col}" value="${value}" required>
        //         </div>`;
        //     });
        //     $('#dynamicFields').html(modalBody);

        // }).fail(() => toastr.error("Failed to load data."));
    });
</script>