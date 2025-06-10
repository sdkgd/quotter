<!doctype html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Quotter</title>
</head>
<body>
    <h1>Quotter</h1>
    <h2>{{$displayName}} さんのフォロワー</h2>  
    @foreach($users as $user)
        <p><a href="/user/{{$user->user_name}}">{{$user->display_name}}</a></p>
    @endforeach
</body>
</html>