@props([
    'href'=>route('quoot.index'),
])

<a 
    href={{$href}}
    class="py-1.5 px-4 bg-gray-50 hover:bg-gray-100 active:bg-gray-200 rounded-lg">
        {{$slot}}
</a>