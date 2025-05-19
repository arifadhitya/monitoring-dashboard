<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RanapToday extends Component
{
    public $dateNow;
    protected $listeners = ['updateDate' => 'updateDate'];

    public $jumlahPasien = 0, $jumlahPria = 0, $jumlahWanita = 0;
    public $jumlahBPJS = 0, $jumlahUMUM = 0, $jumlahAskesLain = 0;
    public $jumlahIGD = 0, $jumlahAntrianRanap = 0;

    public function mount()
    {
        $this->dateNow = Carbon::now()->format('Y-m-d');
        $this->fetchData();
    }

    public function fetchData()
    {
        // Pasien Ranap Hari Ini
        $patientQuery = DB::table('reg_periksa')
            ->join('pasien', 'reg_periksa.no_rkm_medis', '=', 'pasien.no_rkm_medis')
            ->where('reg_periksa.status_lanjut', 'Ranap')
            ->where('reg_periksa.tgl_registrasi', $this->dateNow);

        $genderCount = (clone $patientQuery)
            ->selectRaw("
                SUM(CASE WHEN pasien.jk = 'L' THEN 1 ELSE 0 END) as male_count,
                SUM(CASE WHEN pasien.jk = 'P' THEN 1 ELSE 0 END) as female_count
            ")
            ->first();

        $assuranceStat = (clone $patientQuery)
            ->selectRaw("
                SUM(CASE WHEN reg_periksa.kd_pj = 'BPJ' THEN 1 ELSE 0 END) as bpjs_count,
                SUM(CASE WHEN reg_periksa.kd_pj = 'A09' THEN 1 ELSE 0 END) as umum_count,
                SUM(CASE WHEN reg_periksa.kd_pj NOT IN ('BPJ', 'A09') THEN 1 ELSE 0 END) as other_count
            ")
            ->first();

        $jumlahIGD = (clone $patientQuery)
            ->where('kd_poli', 'IGDK')
            ->count();

        $antrianRanap = DB::table('permintaan_ranap')
            ->leftJoin('kamar_inap', 'permintaan_ranap.no_rawat', '=', 'kamar_inap.no_rawat')
            ->whereNull('kamar_inap.no_rawat')
            ->count();

        // Assign ke variabel publik
        $this->jumlahPasien = (clone $patientQuery)->count();
        $this->jumlahPria = $genderCount->male_count ?? 0;
        $this->jumlahWanita = $genderCount->female_count ?? 0;
        $this->jumlahBPJS = $assuranceStat->bpjs_count ?? 0;
        $this->jumlahUMUM = $assuranceStat->umum_count ?? 0;
        $this->jumlahAskesLain = $assuranceStat->other_count ?? 0;
        $this->jumlahAntrianRanap = $antrianRanap;
        $this->jumlahIGD = $jumlahIGD;
    }

    
    public function render()
    {
        return view('livewire.ranap-today');
    }
}
