<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AntrolToday extends Component
{
    public $dateNow;
    protected $listeners = ['updateDate' => 'updateDate'];
    public $jumlahPasien = 0, $jumlahPria = 0, $jumlahWanita = 0;
    public $jumlahCheckin = 0, $jumlahGagal = 0, $jumlahBatal = 0;
    public $jumlahBelum = 0;

    public function mount(){
        $this->dateNow=Carbon::now()->format('Y-m-d');
        $this->fetchData();
    }

    public function fetchData()
    {
        $patientQuery=DB::table('referensi_mobilejkn_bpjs')
            ->join('pasien', 'referensi_mobilejkn_bpjs.norm', '=', 'pasien.no_rkm_medis')
            ->where('referensi_mobilejkn_bpjs.tanggalperiksa', $this->dateNow);
        
        $genderCount=(clone $patientQuery)
            ->selectRaw("SUM(CASE WHEN pasien.jk = 'L' THEN 1 ELSE 0 END) as male_count,
            SUM(CASE WHEN pasien.jk = 'P' THEN 1 ELSE 0 END) as female_count")
            ->first();
        
        $validasiAntrol=(clone $patientQuery)
            ->selectRaw("SUM(CASE WHEN referensi_mobilejkn_bpjs.status = 'Checkin' THEN 1 ELSE 0 END) as checkin_count,
            SUM(CASE WHEN referensi_mobilejkn_bpjs.status = 'Gagal' THEN 1 ELSE 0 END) as gagal_count,
            SUM(CASE WHEN referensi_mobilejkn_bpjs.status = 'Batal' THEN 1 ELSE 0 END) as batal_count,
            SUM(CASE WHEN referensi_mobilejkn_bpjs.status = 'Belum' THEN 1 ELSE 0 END) as belum_count")
            ->first();
        
        $this->jumlahPasien = (clone $patientQuery)->count();
        $this->jumlahPria = $genderCount->male_count ?? 0;
        $this->jumlahWanita = $genderCount->female_count ?? 0;
        $this->jumlahCheckin = $validasiAntrol->checkin_count ?? 0;
        $this->jumlahGagal = $validasiAntrol->gagal_count ?? 0;
        $this->jumlahBatal = $validasiAntrol->batal_count ?? 0;
        $this->jumlahBelum = $validasiAntrol->belum_count ?? 0;

    }

    public function render()
    {
        return view('livewire.antrol-today');
    }
}
