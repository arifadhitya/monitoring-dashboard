<div>
    <div class="flex flex-col gap-4">
        <div class="flex gap-2 items-center">
            <div class="flex items-center gap-2 text-5xl font-bold rounded-xl p-4 bg-sky-500 ">
                <flux:icon.users-round class="size-16"/> {{ $jumlahPasien }}
            </div>
            <div class="flex flex-col justify-center h-full ">
                <div class="flex gap-1 items-center">
                    <span class="text-2xl">{{ $jumlahPria }}</span>
                    <span class="flex gap-1">Laki-laki <flux:icon.mars /></span>
                </div>
                <div class="flex gap-1 items-center">
                    <span class="text-2xl">{{ $jumlahWanita }}</span>
                    <span class="flex gap-1">Perempuan <flux:icon.venus/></span>
                </div>
            </div>
        </div>
        <div class="grid grid-cols-3">
            <div class="flex flex-col ">
                <span>BPJS</span>
                <span class="text-3xl">{{ $jumlahBPJS }}</span> 
            </div>
            <div class="flex flex-col  ">
                <span>Umum</span>
                <span class="text-3xl">{{ $jumlahUMUM }}</span> 
            </div>
            <div class="flex flex-col  ">
                <span>Lainnya</span>
                <span class="text-3xl">{{ $jumlahAskesLain }}</span> 
            </div>
        </div>
    </div>
</div>
