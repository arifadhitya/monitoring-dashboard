<div>
    <div class="flex flex-col gap-4">
        <div class="flex gap-2 items-center">
            <div class="flex items-center gap-2 text-5xl font-bold rounded-xl p-4 bg-emerald-400 ">
                <flux:icon.users-round class="size-16"/> {{ $jumlahPasien_HEMO }}
            </div>
            <div class="flex flex-col justify-center h-full ">
                <div class="flex gap-1 items-center">
                    <span class="text-2xl">{{ $jumlahPria_HEMO }}</span>
                    <span class="flex gap-1">Laki-laki <flux:icon.mars /></span>
                </div>
                <div class="flex gap-1 items-center">
                    <span class="text-2xl">{{ $jumlahWanita_HEMO }}</span>
                    <span class="flex gap-1">Perempuan <flux:icon.venus/></span>
                </div>
            </div>
        </div>
        <div class="grid grid-cols-3">
            <div class="flex flex-col ">
                <span>BPJS</span>
                <span class="text-3xl">{{ $jumlahBPJS_HEMO }}</span> 
            </div>
            <div class="flex flex-col  ">
                <span>Umum</span>
                <span class="text-3xl">{{ $jumlahUMUM_HEMO }}</span> 
            </div>
            <div class="flex flex-col  ">
                <span>Lainnya</span>
                <span class="text-3xl">{{ $jumlahAskesLain_HEMO }}</span> 
            </div>
        </div>
    </div>
</div>
