<!doctype html>
<html lang="en" class="h-100">

<head>
    @if(auth()->check())
    <script src="{{asset('assets\fallback.js')}}"></script>
    @endif
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ env('APP_NAME' )}}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @yield("style")
</head>

<body class="d-flex h-100">
    @include("include.header")
    <div class="flex-grow-1 p-4">
        @yield("content")
    </div>
</body>

@if(session()->has('toast'))
<script type="module">
    var toastrData = @json(session()->get('toast'));
    toastr[toastrData.type](toastrData.message, toastrData.title);
</script>
@endif


</html>