<x-layouts.app.header :title="$title ?? null">
    <flux:main class="!p-4 lg:p-8!">
        {{ $slot }}
    </flux:main>
</x-layouts.app.header>
