@props([
    'messages'=>[],
])

<div id="scrollTarget" class="bg-white rounded-md shadow-lg mt-5 mb-5 p-4 overflow-auto max-h-80">
    <ul>
        @foreach($messages as $message)
            @if(\Illuminate\Support\Facades\Auth::id()===$message->mentioned_user_id)
            <div class="mb-2">
                <div class="flex justify-end">
                    <div class="p-4 bg-blue-300 rounded-md max-w-md">
                        <li class="">
                            {!!nl2br(e($message->content))!!}
                        </li>
                    </div>
                </div>
                <p class="text-xs text-right text-slate-400">{{$message->created_at}}</p>
            </div>
            @else
            <div class="mb-2">
                <div class="flex justify-start">
                    <div class="p-4 bg-gray-300 rounded-md max-w-md">
                        <li class="">
                            {!!nl2br(e($message->content))!!}
                        </li>
                    </div>
                </div>
                <p class="text-xs text-left text-slate-400">{{$message->created_at}}</p>
            </div>
            @endif
        @endforeach
    </ul>
</div>