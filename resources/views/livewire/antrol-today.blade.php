<div>
    <div class="flex flex-col gap-8">
        <div class="flex items-center gap-2">
                <div class="bg-sky-500 rounded-xl p-2">
                    <flux:icon.users class="size-8 text-neutral-50"/>
                </div>
                <strong class="font-bold text-2xl">MJKN hari ini</strong>
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
                <div class="flex flex-wrap">
                    <!-- Baris 1, Kolom 1 -->
                    <div class="w-1/2">
                        <div class="flex flex-col items-start">
                            <strong class="text-xl font-bold">Checkin</strong>
                            <span class="text-xl">{{ $jumlahCheckin }}</span>
                        </div>
                    </div>

                    <!-- Baris 1, Kolom 2 -->
                    <div class="w-1/2">
                        <div class="flex flex-col items-start">
                            <strong class="text-xl font-bold">Batal</strong>
                            <span class="text-xl">{{ $jumlahBatal }}</span>
                        </div>
                    </div>

                    <!-- Baris 2, Kolom 1 -->
                    <div class="w-1/2 mt-4">
                        <div class="flex flex-col items-start">
                            <strong class="text-xl font-bold">Belum</strong>
                            <span class="text-xl">{{ $jumlahBelum }}</span>
                        </div>
                    </div>

                    <!-- Baris 2, Kolom 2 -->
                    <div class="w-1/2 mt-4">
                        <div class="flex flex-col items-start">
                            <strong class="text-xl font-bold">Gagal</strong>
                            <span class="text-xl">{{ $jumlahGagal }}</span>
                        </div>
                    </div>
                </div>


    </div>
</div>
