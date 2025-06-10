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
    <h2>{{$chatId}} 番の部屋</h2>
    <p>{{$users[0]}}と{{$users[1]}}のチャット部屋</p>
    <div>
       <p>メッセージを送信</p>
       <form action="/chat/{{$chatId}}" method="post">
          @csrf
          <textarea id="message-content" type="text" name="message"></textarea>
          <button type="submit">送信</button>
       </form>
    </div>
    @foreach($messages as $message)
        {{$message->content}} by {{$message->getDisplayName()}}  posted on {{$message->created_at}}
        <br>
    @endforeach
</body>
</html>