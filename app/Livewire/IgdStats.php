<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class IgdStats extends Component
{
    public $startDate, $endDate;
    protected $listeners = ['updateDate' => 'updateDate'];
    public $jumlahPasien_IGD = 0, $jumlahPria_IGD = 0, $jumlahWanita_IGD = 0;
    public $jumlahBPJS_IGD = 0, $jumlahUMUM_IGD = 0, $jumlahAskesLain_IGD = 0;

    public function mount(){
        $this->startDate=Carbon::now()->format('Y-m-d');
        $this->endDate=Carbon::now()->format('Y-m-d');
        $this->fetchData();
    }

    public function updateDate($start, $end)
    {
        $this->startDate = $start;
        $this->endDate = $end;
        $this->fetchData();
        // $this->render(); 
    }

    public function fetchData()
    {
        $patientQuery=DB::table('reg_periksa')
            ->join('pasien', 'reg_periksa.no_rkm_medis', '=', 'pasien.no_rkm_medis')
            ->where('reg_periksa.status_lanjut', 'Ralan')
            ->where('reg_periksa.kd_poli', ['IGDK', 'U0040'])
            ->whereBetween('reg_periksa.tgl_registrasi', [$this->startDate, $this->endDate]);
        
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
        
        $this->jumlahPasien_IGD = (clone $patientQuery)->count();
        $this->jumlahPria_IGD = $genderCount->male_count ?? 0;
        $this->jumlahWanita_IGD = $genderCount->female_count ?? 0;
        $this->jumlahBPJS_IGD = $assuranceStat->bpjs_count ?? 0;
        $this->jumlahUMUM_IGD = $assuranceStat->umum_count ?? 0;
        $this->jumlahAskesLain_IGD = $assuranceStat->other_count ?? 0;

    }

    public function render()
    {
        return view('livewire.igd-stats');
    }
}
