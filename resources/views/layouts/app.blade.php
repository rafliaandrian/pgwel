<x-layouts::app.sidebar :title="$title ?? null">
    <flux:main>
        {{ $slot }}
    </flux:main>

    @include('components.toast')
</x-layouts::app.sidebar>
