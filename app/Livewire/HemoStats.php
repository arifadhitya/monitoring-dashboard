<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class HemoStats extends Component
{
    public $startDate, $endDate;
    protected $listeners = ['updateDate' => 'updateDate'];
    public $jumlahPasien_HEMO = 0, $jumlahPria_HEMO = 0, $jumlahWanita_HEMO = 0;
    public $jumlahBPJS_HEMO = 0, $jumlahUMUM_HEMO = 0, $jumlahAskesLain_HEMO = 0;

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
            ->where('reg_periksa.kd_poli', 'U0037')
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
        
        $this->jumlahPasien_HEMO = (clone $patientQuery)->count();
        $this->jumlahPria_HEMO = $genderCount->male_count ?? 0;
        $this->jumlahWanita_HEMO = $genderCount->female_count ?? 0;
        $this->jumlahBPJS_HEMO = $assuranceStat->bpjs_count ?? 0;
        $this->jumlahUMUM_HEMO = $assuranceStat->umum_count ?? 0;
        $this->jumlahAskesLain_HEMO = $assuranceStat->other_count ?? 0;

    }

    public function render()
    {
        return view('livewire.hemo-stats');
    }
}
