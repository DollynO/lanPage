<div

    {{ $attributes->merge(['class' => 'w-full h-full block bg-white shadow-md relative  overflow-hidden rounded-lg  border  dark:border-gray-700 dark:bg-zinc-900 dark:text-white  custom-background']) }}>

    <div class="p-2 sm:p-8 w-full h-full custom-card">


        @if($title ?? false)
            <h1
                {{$title->attributes ?? ''}} class="mb-4 text-xl leading-none tracking-tight md:text-xl lg:text-2xl text-secondary-700 dark:text-secondary-400  font-bold border-b border-b-gray-500 p-4 font-russoone">

                {{$title ?? ''}}
            </h1>

        @endif

        {{$slot}}
        @if($buttons ?? false)
            <div class="px-4 py-3 m-6 mt-3 bg-gray-50 text-right sm:px-6 rounded-lg dark:bg-gray-900">
                {{$buttons}}
            </div>
        @endif

    </div>

</div>

{{--<div class="relative aspect-video overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">--}}
{{--    <svg class="absolute inset-0 size-full stroke-gray-900/20 dark:stroke-neutral-100/20" fill="none">--}}
{{--        <defs>--}}
{{--            <pattern id="pattern-68ab88fc098c8" x="0" y="0" width="8" height="8" patternUnits="userSpaceOnUse">--}}
{{--                <path d="M-1 5L5 -1M3 9L8.5 3.5" stroke-width="0.5"></path>--}}
{{--            </pattern>--}}
{{--        </defs>--}}
{{--        <rect stroke="none" fill="url(#pattern-68ab88fc098c8)" width="100%" height="100%"></rect>--}}
{{--    </svg>--}}
{{--</div>--}}
