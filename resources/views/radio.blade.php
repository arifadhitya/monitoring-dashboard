<x-layouts.app title="Radiologi">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        
        <div class="grid rounded-xl p-4 gap-4 border border-neutral-200 dark:border-neutral-700">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="rounded-xl border p-4 border-neutral-200 dark:border-neutral-700 md:col-span-2 h-full">
                    @livewire('radio-chart')
                </div>
                <div class="rounded-xl border p-4 border-neutral-200 dark:border-neutral-700 h-full">
                    @livewire('radio-today')
                </div>
            </div>
            <div class="grid grid-cols-1 border rounded-xl">
                @livewire('radio-list')
            </div>
        </div>
        
    </div>     
</x-layouts.app>