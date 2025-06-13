@props([
    'users'=>[],
])

<div class="bg-white rounded-md shadow-lg mt-5 mb-5 ">
    <ul>
        @foreach($users as $user)
            <li class="border-b last:border-0 border-gray-200 p-4">
                <div class="flex">
                    @if($user->image)
                        <img src="{{ asset('storage/app/public/'.$user->image->path) }}" alt="profile image" width="60" height="60" class="object-contain">
                    @else
                        <img src="{{ asset('storage/app/public/default_profile_icon.png') }}" alt="profile image" width="60" height="60" class="object-contain">
                    @endif

                    <div class="ml-8">
                        <p class="text-xl"><a href="/user/{{$user->user_name}}">{{$user->display_name}}</a></p>
                        <p>{!!nl2br(e($user->profile))!!}</p>
                    </div>
                </div>
            </li>
        @endforeach
    </ul>
</div>