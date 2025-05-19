<div class="p-4 rounded-2xl">
    <table class="w-full border-separate border-spacing-y-4">
        <thead class="bg-gray-100 border-b border-gray-300 dark:border-neutral-600">
            <tr>
                <th class="text-start p-2">Nama Kamar</th>
                <th class="text-start p-2">Jumlah Pasien</th>
                <th class="text-start p-2">Ketersediaan Tempat Tidur</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $row)
            <tr class="">
                <td class="text-xl font-bold p-2">
                    {{ $row->nm_bangsal }}
                </td>
                <td class="p-2">
                    <div class="text-xl font-bold">{{ $row->total_pasien }} Pasien</div>
                    <div class="flex gap-3 text-xs">
                        <div>{{ $row->jumlah_pria }} Pria</div>
                        <div>{{ $row->jumlah_wanita }} Wanita</div>
                    </div>
                </td>
                <td class="p-2">
                    <div class="flex gap-6">
                        <div class="flex flex-col items-center">
                            <div class="text-xl font-bold">{{ $row->kosong }}</div>
                            <div class="text-xs">Kosong</div>
                        </div>
                        <div class="flex flex-col items-center">
                            <div class="text-xl font-bold">{{ $row->terisi }}</div>
                            <div class="text-xs">Terisi</div>
                        </div>
                        <div class="flex flex-col items-center">
                            <div class="text-xl font-bold">{{ $row->dibooking }}</div>
                            <div class="text-xs">Terjadwal</div>
                        </div>
                        <div class="flex flex-col items-center">
                            <div class="text-xl font-bold">{{ $row->dibersihkan }}</div>
                            <div class="text-xs">Pemeliharaan</div>
                        </div>
                        <div class="flex flex-col items-center">
                            <div class="text-xl font-bold">{{ $row->total }}</div>
                            <div class="text-xs">Total bed</div>
                        </div>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
