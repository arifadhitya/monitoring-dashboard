<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TodayStats extends Component
{
    public $dateNow;
    protected $listeners = ['updateDate' => 'updateDate'];
    public $jumlahPasien = 0, $jumlahPria = 0, $jumlahWanita = 0;
    public $jumlahBPJS = 0, $jumlahUMUM = 0, $jumlahAskesLain = 0;
    public $jumlahAntrol = 0;

    public function mount(){
        $this->dateNow=Carbon::now()->format('Y-m-d');
        $this->fetchData();
    }

    public function fetchData()
    {
        $patientQuery=DB::table('reg_periksa')
            ->join('pasien', 'reg_periksa.no_rkm_medis', '=', 'pasien.no_rkm_medis')
            ->where('reg_periksa.status_lanjut', 'Ralan')
            ->where('reg_periksa.tgl_registrasi', $this->dateNow)
            ->whereNotIn('reg_periksa.kd_poli', ['IGDK', 'U0011', 'U0012', 'U0013', 'U0014', 'U0037', 'U0040', 'U0041', 'U0042']);
        
        // $patientCount=(clone $patientQuery)->count();
        
        $genderCount=(clone $patientQuery)
        ->selectRaw("SUM(CASE WHEN pasien.jk = 'L' THEN 1 ELSE 0 END) as male_count,
        SUM(CASE WHEN pasien.jk = 'P' THEN 1 ELSE 0 END) as female_count")
        ->first();
        
        $assuranceStat=(clone $patientQuery)
        ->selectRaw("SUM(CASE WHEN reg_periksa.kd_pj = 'BPJ' THEN 1 ELSE 0 END) as bpjs_count,
        SUM(CASE WHEN reg_periksa.kd_pj = 'A09' THEN 1 ELSE 0 END) as umum_count,
        SUM(CASE WHEN reg_periksa.kd_pj NOT IN ('BPJ', 'A09') THEN 1 ELSE 0 END) as other_count")
        ->first();

        $checkinStat=DB::table('referensi_mobilejkn_bpjs')
            ->where('tanggalperiksa', $this->dateNow)
            ->where('status', 'Checkin');
        
        $this->jumlahPasien = (clone $patientQuery)->count();
        $this->jumlahPria = $genderCount->male_count ?? 0;
        $this->jumlahWanita = $genderCount->female_count ?? 0;
        $this->jumlahBPJS = $assuranceStat->bpjs_count ?? 0;
        $this->jumlahUMUM = $assuranceStat->umum_count ?? 0;
        $this->jumlahAskesLain = $assuranceStat->other_count ?? 0;
        $this->jumlahAntrol = $checkinStat->count();

    }

    public function render()
    {
        return view('livewire.today-stats');
    }
}
