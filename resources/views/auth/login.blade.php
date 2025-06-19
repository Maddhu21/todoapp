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
    <form method="post" action="{{route("login.post")}}">
        @csrf
        <h1 class="h3 mb-3 fw-normal">Please sign in</h1>
        @if(session()->has("success"))
        <div class="alert alert-success">
            {{session()->get("success")}}
        </div>
        @endif
        @if(session()->has("error"))
        <div class="alert alert-danger">
            {{session()->get("error")}}
        </div>
        @endif
        <div class="form-floating">
            <input type="email" name="email" class="form-control" id="floatingInput" placeholder="name@example.com" value="{{old('email')}}">
            <label for="floatingInput">Email address</label>
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
        <div class="form-check text-start my-3">
            <input class="form-check-input" type="checkbox" value="remember-me" id="checkDefault">
            <label class="form-check-label" for="checkDefault">Remember me</label>
        </div>
        <div class="row">
            <div class="col-md-6">
                <button class="btn btn-outline-primary py-2" type="submit">Sign in</button>
            </div>
            <div class="col-md-6 d-flex flex-row-reverse">
                <a href="{{route("register")}}" class="btn btn-outline-info" role="button"> Register</a>
            </div>
        </div>
    </form>
</main>
@endsection