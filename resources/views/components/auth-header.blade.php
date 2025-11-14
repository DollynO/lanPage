@props([
    'title',
    'description',
])

<div class="flex w-full flex-col text-center">
    <flux:heading class="font-bangers text-8xl" size="xl">{{ $title }}</flux:heading>
    <flux:subheading class="text-6xl">{{ $description }}</flux:subheading>
</div>
