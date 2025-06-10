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
    <h2>プロフィールを編集</h2>
    <form action="{{route('user.edit.put',['userName'=>$userName])}}" method="post">
        @method('PUT')
        @csrf
        <p>表示名</p>
        <input type="text" name="input1" id="input1" placeholder="Enter your name" value="{{$displayName}}">

        <p>自己紹介</p>
        <textarea
            rows="4"
            cols="50"
            name="input2"
            id="input2"
            value="testing"
            placeholder="Enter your profile"
        >{{$profile}}</textarea>

        <p>プロフィール画像</p>
        <!-- ここに画像アップロード機能を実装予定 -->

        <button type="submit">変更を保存</button>
    </form>
</body>
</html>