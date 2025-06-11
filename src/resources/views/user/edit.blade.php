<x-layout>
    <x-header></x-header>
    <x-main>
        <h2 class="text-xl font-bold mb-4">プロフィールを編集</h2>
        <x-user.edit-put 
            :userName="$userName"
            :displayName="$displayName"
            :profile="$profile"
        ></x-user.edit-put>
    </x-main>
</x-layout>