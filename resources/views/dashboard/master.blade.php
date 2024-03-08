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
<body class="bg-gray-100">

<div id="app">
    <navigation-component></navigation-component>
    <router-view></router-view>
</div>

@vite('resources/js/app.js')
</body>
</html>
