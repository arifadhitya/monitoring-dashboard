<div class="flex items-center gap-4">
    <div class="flex items-center gap-2">
        <label for="start_date" class="text-sm font-medium whitespace-nowrap">Tanggal</label>
        <input type="date" id="start_date" wire:model="startDate" class="p-2 border rounded w-40 dark:bg-neutral-700 dark:border-neutral-600">
    </div>
    <div class="flex items-center gap-2">
        <label for="end_date" class="text-sm font-medium whitespace-nowrap">s.d.</label>
        <input type="date" id="end_date" wire:model="endDate" class="p-2 border rounded w-40 dark:bg-neutral-700 dark:border-neutral-600">
    </div>
    <button wire:click="applyDateFilter" class="p-2 bg-blue-500 text-white rounded">Terapkan</button>
</div>