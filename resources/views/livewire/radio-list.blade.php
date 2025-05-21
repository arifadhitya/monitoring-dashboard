<div class="p-4 rounded-2xl">
    <table class="w-full border-separate border-spacing-y-4">
        <thead class="bg-gray-100 border-b border-gray-300 dark:border-neutral-600">
            <tr>
                <th class="text-start p-2">Kategori</th>
                <th class="text-start p-2">Jumlah Pasien</th>
                <th class="text-start p-2">Asuransi</th>
                <th class="text-start p-2">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($results as $row)
            <tr class="">
                <td class="text-xl font-bold p-2">
                    {{ $row->kategori }}
                </td>
                <td class="p-2">
                    <div class="text-xl font-bold">{{ $row->total }} Pasien</div>
                    <div class="flex gap-3 text-xs">
                        <div>{{ $row->pria }} Pria</div>
                        <div>{{ $row->wanita }} Wanita</div>
                    </div>
                </td>
                <td class="p-2">
                    <div class="flex gap-6">
                        <div class="flex flex-col items-center">
                            <div class="text-xl font-bold">{{ $row->bpjs }}</div>
                            <div class="text-xs">BPJS</div>
                        </div>
                        <div class="flex flex-col items-center">
                            <div class="text-xl font-bold">{{ $row->umum }}</div>
                            <div class="text-xs">Umum</div>
                        </div>
                        <div class="flex flex-col items-center">
                            <div class="text-xl font-bold">{{ $row->lainnya }}</div>
                            <div class="text-xs">Lainnya</div>
                        </div>
                    </div>
                </td>
                <td class="p-2">
                    <div class="flex gap-6">
                        <div class="flex flex-col items-center">
                            <div class="text-xl font-bold">{{ $row->ranap }}</div>
                            <div class="text-xs">Ranap</div>
                        </div>
                        <div class="flex flex-col items-center">
                            <div class="text-xl font-bold">{{ $row->ralan }}</div>
                            <div class="text-xs">Ralan</div>
                        </div>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
