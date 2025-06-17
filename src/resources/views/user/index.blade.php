<x-layout>

    <x-header></x-header>
    <x-main>
        <div class="h-8"></div>
        <div class="flex justify-between">
            <div class="flex">
                @if($imagePath)
                    @env('production')
                        <img src="{{ $imagePath }}" alt="profile image" width="120" height="120" class="object-contain">
                    @endenv
                    @env('local')
                        <img src="{{ asset('storage/app/public/'.$imagePath) }}" alt="profile image" width="120" height="120" class="object-contain">
                    @endenv
                @else
                    @env('production')
                        <img src="https://cognitobirm-quotter-static-file.s3.ap-northeast-1.amazonaws.com/default_profile_icon.png" alt="profile image" width="120" height="120" class="object-contain">
                    @endenv
                    @env('local')
                        <img src="{{ asset('storage/app/public/default_profile_icon.png') }}" alt="profile image" width="120" height="120" class="object-contain">
                    @endenv
                @endif
                <div class="ml-8">
                    <h2 class="text-3xl font-bold mb-4">{{$displayName}}</h2>
                    <p>{!!nl2br(e($profile))!!}</p>
                </div>
            </div>

            <div>
                <ul class="flex space-x-4">
                    <li><a href="{{route('user.follows',['userName'=>$userName])}}" class="text-center text-gray-500 hover:text-black">Follows</a></li>
                    <li><a href="{{route('user.followers',['userName'=>$userName])}}" class="text-center text-gray-500 hover:text-black">Followers</a></li>
                </ul>
            </div>
        </div>

        @auth
            <div class="flex flex-wrap justify-center">
                @if(\Illuminate\Support\Facades\Auth::id()!==$id)
                    @if(!$isFollowing)
                        <div>
                            <form action="/user/{{$userName}}/follow" method="post">
                                @csrf
                                <x-element.button-post>フォローする</x-element.button-post>
                            </form>
                        </div>
                    @else
                        <div>
                            <form action="/user/{{$userName}}/unfollow" method="post">
                                @method('DELETE')
                                @csrf
                                <x-element.button-post>フォロー解除</x-element.button-post>
                            </form>
                        </div>
                    @endif
                    <div>
                        <form action="/user/{{$userName}}/chat" method="post">
                            @csrf
                            <x-element.button-post>チャットを開始</x-element.button-post>
                        </form>
                    </div>
                @else
                    <x-element.link-get :href="route('user.edit',['userName'=>$userName])">プロフィールを編集</x-element.link-get>
                @endif
            </div>
        @endauth

        <x-quoot.list :quoots="$quoots"></x-quoot.list>

    </x-main>    
    
</x-layout>