<x-layouts.app title="Rawat Inap">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div class="flex items-center justify-between">
            <div>
                <flux:breadcrumbs>
                    <flux:breadcrumbs.item href="#" icon="home" />
                    <flux:breadcrumbs.item icon="ellipsis-horizontal" />
                    <flux:breadcrumbs.item>Post</flux:breadcrumbs.item>
                </flux:breadcrumbs>
            </div>
            <div class="bg-white dark:bg-neutral-800 p-4 rounded-lg show">
                @livewire('date-filter')
            </div>
        </div>
        <div class="relative h-full flex-1 overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
            @livewire('radio-chart')
        </div>
        <div class="grid auto-rows-min gap-4 md:grid-cols-3">
            <div class="flex flex-col space-y-4 relative aspect-video overflow-hidden rounded-xl border p-3 gap-y-4 border-neutral-200 dark:border-neutral-700">
                <x-placeholder-pattern class="absolute inset-0 size-full stroke-gray-900/20 dark:stroke-neutral-100/20" />
                {{-- <span class="font-bold">Stat Pasien Poliklinik</span>
                @livewire('poli-stats') --}}
            </div>
            <div class="flex flex-col space-y-4 relative aspect-video overflow-hidden rounded-xl border p-3 gap-y-4 border-neutral-200 dark:border-neutral-700">
                <x-placeholder-pattern class="absolute inset-0 size-full stroke-gray-900/20 dark:stroke-neutral-100/20" />
                {{-- <span class="font-bold">Stat Pasien IGD</span>
                @livewire('igd-stats') --}}
                
            </div>
            <div class="flex flex-col space-y-4 relative aspect-video overflow-hidden rounded-xl border p-3 gap-y-4 border-neutral-200 dark:border-neutral-700">
                <x-placeholder-pattern class="absolute inset-0 size-full stroke-gray-900/20 dark:stroke-neutral-100/20" />
                {{-- <span class="font-bold">Stat Pasien Hemodialisa</span>
                @livewire('hemo-stats') --}}
            </div>
        </div>
    </div>     
</x-layouts.app>