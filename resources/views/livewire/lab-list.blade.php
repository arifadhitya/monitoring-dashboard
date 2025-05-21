<div class="p-4 rounded-2xl">
    <table class="w-full border-separate border-spacing-y-4">
        <thead class="bg-gray-100 border-b border-gray-300 dark:border-neutral-600">
            <tr>
                <th class="text-start p-2">Bulan</th>
                <th class="text-start p-2">Jumlah Pasien</th>
                <th class="text-start p-2">Laboratorium</th>
                <th class="text-start p-2">Asuransi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $row)
            <tr class="">
                <td class="text-xl font-bold p-2">
                    {{ $row['bulan'] }}
                </td>
                <td class="p-2">
                    <div class="text-xl font-bold">{{ $row['total_pasien'] }} Pasien</div>
                    <div class="flex gap-3 text-xs">
                        <div>{{ $row['p'] }} Pria</div>
                        <div>{{ $row['l'] }} Wanita</div>
                    </div>
                </td>
                <td class="p-2">
                    <div class="flex gap-6">
                        <div class="flex flex-col items-center">
                            <div class="text-xl font-bold">{{ $row['lab_pk'] }}</div>
                            <div class="text-xs">Lab PK</div>
                        </div>
                        <div class="flex flex-col items-center">
                            <div class="text-xl font-bold">{{ $row['lab_pa'] }}</div>
                            <div class="text-xs">Lab PA</div>
                        </div>
                        <div class="flex flex-col items-center">
                            <div class="text-xl font-bold">{{ $row['lab_mb'] }}</div>
                            <div class="text-xs">Lab MB</div>
                        </div>
                    </div>
                </td>
                <td class="p-2">
                    <div class="flex gap-6">
                        <div class="flex flex-col items-center">
                            <div class="text-xl font-bold">{{ $row['bpjs'] }}</div>
                            <div class="text-xs">BPJS</div>
                        </div>
                        <div class="flex flex-col items-center">
                            <div class="text-xl font-bold">{{ $row['umum'] }}</div>
                            <div class="text-xs">Umum</div>
                        </div>
                        <div class="flex flex-col items-center">
                            <div class="text-xl font-bold">{{ $row['lainnya'] }}</div>
                            <div class="text-xs">Lainnya</div>
                        </div>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
