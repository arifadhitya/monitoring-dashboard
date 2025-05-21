<div>
    <div class="flex flex-col gap-8">
        <div class="flex items-center gap-2">
                <div class="bg-sky-500 rounded-xl p-2">
                    <flux:icon.users class="size-8 text-neutral-50"/>
                </div>
                <strong class="font-bold text-2xl">Radiologi hari ini</strong>
            </div>
            <div class="flex flex-col gap-4">
                <div class="flex items-center gap-4">
                    <div class="flex items-center text-8xl">
                        {{-- <flux:icon.users-round class="size-16"/> --}}
                        {{ $jumlahPasien }}
                    </div>
                    <div class="flex flex-col justify-center h-full gap-2">
                        <div class="flex items-center gap-2">
                            <flux:icon.mars class="size-8"/>
                            <div class="flex flex-col">
                                <strong class="text-m font-bold">Pria</strong>
                                <span class="text-m">{{ $jumlahPria }}</span>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <flux:icon.venus class="size-8"/>
                            <div class="flex flex-col">
                                <strong class="text-m font-bold">Wanita</strong>
                                <span class="text-m">{{ $jumlahWanita }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex flex-row gap-5">
                    <div class="flex flex-col">
                        <strong class="text-xl font-bold">Pasien Ranap</strong>
                        <span class="text-xl">{{ $jumlahRanap }} Permintaan</span>
                    </div>
                    <div class="flex flex-col">
                        <strong class="text-xl font-bold">Pasien Ralan</strong>
                        <span class="text-xl">{{ $jumlahRalan }} Permintaan</span>
                    </div>
                </div>
            </div>
            <div class="grid grid-cols-3 border rounded-2xl p-4">
                <div class="flex flex-col ">
                    <span class="text-3xl">{{ $jumlahBPJS }}</span> 
                    <span>BPJS</span>
                </div>
                <div class="flex flex-col  ">
                    <span class="text-3xl">{{ $jumlahUMUM }}</span> 
                    <span>Umum</span>
                </div>
                <div class="flex flex-col  ">
                    <span class="text-3xl">{{ $jumlahAskesLain }}</span> 
                    <span>Lainnya</span>
                </div>
            </div>
    </div>
</div>
