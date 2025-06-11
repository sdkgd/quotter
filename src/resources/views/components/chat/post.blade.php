<form action="/chat/{{$chatId}}" method="post">
    @csrf
    <textarea 
        id="message-content" 
        rows="2"
        type="text" 
        name="message"
        class="focus:ring-blue-400 focus:border-blue-400 mt-1 block w-full text:text-sm border border-gray-300 rounded-md p-2"
        placeholder="メッセージを入力"></textarea>
    @error('message')
        <p style="color:red;">{{$message}}</p>
    @enderror
    <div class="flex flex-wrap justify-end">
        <x-element.button-post>送信</x-element.button-post>
    </div>
</form>