<x-layout>
    <x-header></x-header>
    <x-main>
        <p class="text-2xl font-bold mb-4">{{$users[0]}}と{{$users[1]}}のチャット部屋</p>
        
        <x-chat.list :messages="$messages"></x-chat.list>
        <script src="{{ asset('/js/scroll.js') }}"></script>
        <x-chat.post :chatId="$chatId"></x-chat.post>
    </x-main>
</x-layout>