@props([
    'quoots'=>[],
])

<div class="bg-white rounded-md shadow-lg mt-5 mb-5 overflow-auto">
    <ul>
        @foreach($quoots as $quoot)
        <li class="border-b last:border-0 border-gray-200 p-4">
            <div class="flex">

                @if($quoot->getImagePath())
                    <img src="{{ asset('storage/app/public/'.$quoot->getImagePath()) }}" alt="profile image" width="60" height="60" class="object-contain">
                @else
                    <img src="{{ asset('storage/app/public/default_profile_icon.png') }}" alt="profile image" width="60" height="60" class="object-contain">
                @endif

                <div class="ml-4">
                    <span class="inline-block rounded-full px-2 py-1 text-s font-bold mb-1">
                        <a href="{{route('user.index',['userName'=>$quoot->getUserName()])}}">{{$quoot->getDisplayName()}}</a> 
                    </span>
                    
                    <p class="text-gray-600 px-2 mb-1">{!!nl2br(e($quoot->content))!!}</p>
                </div>

            </div>

            <p class="text-xs text-right">posted on {{$quoot->created_at}}</p>
            
            @if(\Illuminate\Support\Facades\Auth::id()===$quoot->user_id)
            <div class="mt-2 text-xs text-right">
                <span class="mr-1"><a href="{{route('quoot.update',['quootId'=>$quoot->id])}}">更新</a></span>
                <form style="display:inline" action="{{route('quoot.delete',['quootId'=>$quoot->id])}}" method="post">
                    @method('DELETE')
                    @csrf
                    <button type="submit">削除</button>
                </form>
            </div>
            @endif
            
        </li>
        @endforeach
    </ul>
</div>