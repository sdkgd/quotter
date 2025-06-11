@props([
    'userName'=>'',
    'displayName'=>'',
    'profile'=>'',
])

<form action="{{route('user.edit.put',['userName'=>$userName])}}" method="post" enctype="multipart/form-data">
    @method('PUT')
    @csrf
    <div class="mb-4">
        <label for="input1" class="text-m text-gray-700 block mb-1 font-bold">
            表示名
        </label>
        <input type="text" name="input1" id="input1" class="bg-gray-100 border border-gray-200 rounded py-1 px-3 block focus:ring-blue-500 focus:border-blue-500 text-gray-700" placeholder="Enter your name" value="{{$displayName}}">
    </div>

    <div class="mb-4">
    <label for="input2" class="text-m text-gray-700 block mb-1 font-bold">
        自己紹介
    </label>
    <textarea
        rows="4"
        cols="50"
        name="input2"
        id="input2"
        value="testing"
        class="bg-gray-100 rounded border border-gray-200 py-1 px-3 block focus:ring-blue-500 focus:border-blue-500 text-gray-700"
        placeholder="Enter your profile"
    >{{$profile}}</textarea>
    </div>

    <div class="mb-4">
        <label for="input3" class="text-m text-gray-700 block mb-1 font-bold">
            プロフィール画像
        </label>
        <input 
            type="file" 
            name="input3" 
            id="input3" 
            class="block w-full text-sm text-slate-500
                file:mr-4 file:py-2 file:px-4
                file:rounded-full file:border-0
                file:text-sm file:font-semibold
                file:bg-gray-50 file:text-gray-700
                hover:file:bg-gray-100" 
        >
    </div>

    @error('input1')
        <p style="color:red;">{{$message}}</p>
    @enderror
    @error('input2')
        <p style="color:red;">{{$message}}</p>
    @enderror
    @error('input3')
        <p style="color:red;">{{$message}}</p>
    @enderror

    <div class="flex justify-end">
        <x-element.button-post>変更を保存</x-element.button-post>
    </div>
</form>