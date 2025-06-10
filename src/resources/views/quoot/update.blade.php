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
    <h1>Quoot編集画面</h1>
    <div>
        <p>編集フォーム</p>
        <form action="{{route('quoot.update.put',['quootId'=>$quoot->id])}}" method="post">
            @method('PUT')
            @csrf
            <textarea id="quoot-content" type="text" name="quoot" >{{$quoot->content}}</textarea>
            <button type="submit">編集</button>
        </form>
    </div>
</body>
</html>