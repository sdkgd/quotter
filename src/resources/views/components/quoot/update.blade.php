@props([
    'quoot'=>[],
])

<form action="{{route('quoot.update.put',['quootId'=>$quoot->id])}}" method="post">
    @method('PUT')
    @csrf
    <textarea 
        id="quoot-content" 
        rows="3"
        type="text" 
        name="quoot"
        class="focus:ring-blue-400 focus:border-blue-400 mt-1 block w-full text:text-sm border border-gray-300 rounded-md p-2"
        placeholder="つぶやきを入力">{{$quoot->content}}</textarea>
    @error('quoot')
        <p style="color:red;">{{$message}}</p>
    @enderror
    <div class="flex flex-wrap justify-end">
        <x-element.button-post>編集</x-element.button-post>
    </div>
</form>