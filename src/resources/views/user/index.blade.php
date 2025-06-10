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
    <h2>ユーザ {{$displayName}} のページ</h2>
    <p>{{$profile}}</p>
    <button onClick="location.href='/user/{{$userName}}/follows'">フォローリストへ</button>
    <button onClick="location.href='/user/{{$userName}}/followers'">フォロワーリストへ</button>
    @if(\Illuminate\Support\Facades\Auth::id()!==$id)
        @if(!$isFollowing)
            <div>
                <form action="/user/{{$userName}}/follow" method="post">
                    @csrf
                    <button type="submit">フォローする</button>
                </form>
            </div>
        @else
            <div>
                <form action="/user/{{$userName}}/unfollow" method="post">
                    @method('DELETE')
                    @csrf
                    <button type="submit">フォロー解除</button>
                </form>
            </div>
        @endif
        <div>
            <form action="/user/{{$userName}}/chat" method="post">
                @csrf
                <button type="submit">チャットを開始</button>
            </form>
        </div>
    @else
        <div>
            <button onClick="location.href='/user/{{$userName}}/edit'">プロフィールを編集</button>
        </div>
    @endif
    @foreach($quoots as $quoot)
        <p>{{$quoot->content}}  by {{$quoot->getDisplayName()}}  posted on {{$quoot->created_at}}</p>
    @endforeach
</body>
</html>