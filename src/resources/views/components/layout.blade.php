<!doctype html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Quotter</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-200 text-black">
    <div class="flex justify-center">
        {{$slot}}
    </div>
</body>
</html>