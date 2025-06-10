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
    @if(\Illuminate\Support\Facades\Auth::id())
        <button onClick="location.href='/quoot/create'">Quoot作成画面へ</button>
        <button onClick="location.href='/user/{{$userName}}'">マイページへ</button>
    @endif
    <br>
    @foreach($quoots as $quoot)
        {{$quoot->content}}  by <a href="{{route('user.index',['userName'=>$quoot->getUserName()])}}">{{$quoot->getDisplayName()}}</a>  posted on {{$quoot->created_at}}
        @if(\Illuminate\Support\Facades\Auth::id()===(int)$quoot->user_id)
            <a href="{{route('quoot.update',['quootId'=>$quoot->id])}}">更新</a>
            <form style="display:inline" action="{{ route('quoot.delete',['quootId'=>$quoot->id])}}" method="post">
                @method('DELETE')
                @csrf
                <button type="submit">削除</button>
            </form>
        @endif
        <br>
    @endforeach
</body>
</html>