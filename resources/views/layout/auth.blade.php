<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield("title", "To Do App")</title>
    <!-- <link href="{{asset("assets\bootstrap-5.3.6-dist\css\bootstrap.min.css")}}" rel="stylesheet"> -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @yield("style")
</head>

<body class="d-flex align-items-center py-4 bg-body-tertiary">

    @yield("content")

    <!-- <script src="{{asset("assets\bootstrap-5.3.6-dist\js\bootstrap.bundle.min.js")}}"></script> -->
</body>

@if (session()->has('toast'))
    <script type="module">
        var toastrData = @json(session()->get('toast'));
        toastr[toastrData.type](toastrData.message, toastrData.title);
    </script>
@endif

<script type="module">
    $('document').ready(function(){
        console.log("jquery jalan");
    });
</script>

</html>