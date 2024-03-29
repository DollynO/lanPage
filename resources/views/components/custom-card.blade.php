<div
    {{$attributes}} class="w-full block p-6 bg-white border border-gray-200 rounded-lg shadow hover:bg-gray-100 dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-700">
    <div
        {{$title->attributes ?? ''}} class="mb-4 text-xl leading-none tracking-tight text-gray-900 md:text-4xl lg:text-5xl dark:text-white text-center">
        {{$title ?? ''}}
    </div>
    {{$slot}}
    @if($buttons ?? false)
        <div class="px-4 py-3 -m-6 mt-3 bg-gray-50 text-right sm:px-6 rounded-lg">
            {{$buttons}}
        </div>
    @endif
</div>
