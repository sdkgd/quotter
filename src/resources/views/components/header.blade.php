<div class="relative">
    <header class="fixed bg-gray-200 max-w-screen-md w-full pl-8 pr-8 h-24 opacity-90">
    
        <div class="flex justify-between items-center p-6">

            <a href="/quoot">
                <h1 class="left-1 text-center text-black text-4xl font-bold">Quotter</h1>
            </a>

            @auth
                @php
                    $userName = \Illuminate\Support\Facades\Auth::user()->user_name;
                @endphp
                <nav>
                    <ul class="flex space-x-4">
                        <li><a href="{{route('quoot.create')}}" class="text-center text-gray-500 hover:text-black">Create</a></li>
                        <li><a href="{{route('user.index',['userName'=>$userName])}}" class="text-center text-gray-500 hover:text-black">My page</a></li>
                        <form action="{{route('logout')}}"  method="post">
                            @csrf
                            <button type="submit" name="logout" id="logout" class="text-center text-gray-500 hover:text-black">Logout</button>
                        </form>
                    </ul>
                </nav>
            @endauth

            @guest
                <nav>
                    <ul class="flex space-x-4">
                        <li><a href="{{route('login')}}" class="text-center text-gray-500 hover:text-black">Login</a></li>
                        <li><a href="{{route('register')}}" class="text-center text-gray-500 hover:text-black">Register</a></li>
                    </ul>
                </nav>
            @endguest

        </div>
        
    </header>
</div>