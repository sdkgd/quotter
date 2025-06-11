@props([
    'users'=>[],
])

<div class="bg-white rounded-md shadow-lg mt-5 mb-5 ">
    <ul>
        @foreach($users as $user)
            <li class="border-b last:border-0 border-gray-200 p-4">
                <p class="text-xl"><a href="/user/{{$user->user_name}}">{{$user->display_name}}</a></p>
                <p>{{$user->profile}}</p>
            </li>
        @endforeach
    </ul>
</div>