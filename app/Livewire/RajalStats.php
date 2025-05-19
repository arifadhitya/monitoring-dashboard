<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


class RajalStats extends Component
{
    public $startDate, $endDate;
    protected $listeners = ['updateDate' => 'updateDate'];
    public $jumlahPoli = 0, $jumlahIgd = 0, $jumlahHemo = 0;

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
            ->whereBetween('reg_periksa.tgl_registrasi', [$this->startDate, $this->endDate]);
        
        $poliCount=(clone $patientQuery)
            ->whereNotIn('reg_periksa.kd_poli', ['IGDK', 'U0011', 'U0012', 'U0013', 'U0014', 'U0037', 'U0040', 'U0041', 'U0042'])
            ->count();

        $igdCount=(clone $patientQuery)
            ->whereNotIn('reg_periksa.kd_poli', ['IGDK', 'U0040'])
            ->count();

        $hemoCount=(clone $patientQuery)
            ->where('reg_periksa.kd_poli', 'U0037')
            ->count();
        
        $this->jumlahPoli = $poliCount;
        $this->jumlahIgd = $igdCount;
        $this->jumlahHemo = $hemoCount;

    }

    public function render()
    {
        return view('livewire.rajal-stats');
    }
}
