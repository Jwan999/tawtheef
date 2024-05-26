<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @vite('resources/css/app.css')
    <title>Tawtheef</title>
</head>
<body class="bg-light">
{{--<div v-if="{{auth()->user()}}">--}}
{{--    {{ auth()->user() }}!--}}
{{--</div>--}}

<div id="app"></div>

@vite('resources/js/app.js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>
</body>
</html>
