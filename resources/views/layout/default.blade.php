<!doctype html>
<html lang="en" class="h-100">

<head>
    @if(auth()->check())
    <script src="{{asset('assets\fallback.js')}}"></script>
    @endif
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ env('APP_NAME' )}}</title>
    <!-- <link href="{{asset('assets\bootstrap-5.3.6-dist\css\bootstrap.min.css')}}" rel="stylesheet"> -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @yield("style")
</head>

<body class="d-flex flex-column h-100">
    @include("include.header")
    @yield("content")
    @include("include.footer")
    <!-- <script src="{{asset('assets\bootstrap-5.3.6-dist\js\bootstrap.bundle.min.js')}}"></script> -->
</body>

<script type="module">
    @if(session()->has('toast'))
    var toastrData = @json(session()->get('toast'));
    toastr[toastrData.type](toastrData.message, toastrData.title);
    @endif
</script>

</html>