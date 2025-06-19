@extends("layout.auth")

@section("style")
<style>
    html,
    body {
        height: 100%;
    }

    .form-signin {
        max-width: 330px;
        padding: 1rem;
    }

    .form-signin .form-floating{
        margin-bottom: 10px;
    }

    .form-signin .form-floating:focus-within {
        z-index: 2;
    }

    .form-signin input[type="email"] {
        margin-bottom: -1px;
        border-bottom-right-radius: 0;
        border-bottom-left-radius: 0;
    }

    .form-signin input[type="password"] {
        border-top-left-radius: 0;
        border-top-right-radius: 0;
    }
</style>
@endsection

@section("content")
<main class="form-signin w-100 m-auto">
    <form method="post" action="{{route("register.post")}}">
        @csrf
        <h1 class="h3 mb-3 fw-normal">Register Account</h1>
        <div class="form-floating">
            <input type="text" name="fullname" class="form-control" id="floatingInputName" placeholder="John Doe" value="{{old('fullname')}}">
            <label for="floatingInputName">Full Name</label>
            @error("fullname")
            <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        <div class="form-floating">
            <input type="email" name="email" class="form-control" id="floatingInputEmail" placeholder="name@example.com" value="{{old('email')}}">
            <label for="floatingInputEmail">Email address</label>
            @error("email")
            <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        <div class="form-floating">
            <input type="password" name="password" class="form-control" id="floatingPassword" placeholder="Password">
            <label for="floatingPassword">Password</label>
            @error("password")
            <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        <div class="form-floating">
            <input type="password" name="password2" class="form-control" id="floatingCPassword" placeholder="Password">
            <label for="floatingCPassword">Confirm Password</label>
            @error("password2")
            <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        <div class="row">
            <div class="col-md-6">
                <button class="btn btn-outline-primary py-2" type="submit">Register</button>
            </div>
            <div class="col-md-6 d-flex flex-row-reverse">
                <a href="{{route("login")}}" class="btn btn-outline-danger">Cancel</a>
            </div>
        </div>
    </form>
</main>
@endsection