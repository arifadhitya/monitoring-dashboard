<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RadioToday extends Component
{
    public $dateNow;
    protected $listeners = ['updateDate' => 'updateDate'];
    public $jumlahPasien = 0, $jumlahPria = 0, $jumlahWanita = 0;
    public $jumlahBPJS = 0, $jumlahUMUM = 0, $jumlahAskesLain = 0;
    public $jumlahRalan = 0, $jumlahRanap = 0;

    public function mount(){
        $this->dateNow=Carbon::now()->format('Y-m-d');
        $this->fetchData();
    }

    public function fetchData(){
        $data = DB::table('permintaan_radiologi')
        ->join('reg_periksa', 'permintaan_radiologi.no_rawat', '=', 'reg_periksa.no_rawat')
        ->join('pasien', 'reg_periksa.no_rkm_medis', '=', 'pasien.no_rkm_medis')
        ->whereDate('permintaan_radiologi.tgl_permintaan', $this->dateNow)
        ->select(
            DB::raw('COUNT(DISTINCT permintaan_radiologi.noorder) as total_pasien'),
            DB::raw("SUM(CASE WHEN pasien.jk = 'P' THEN 1 ELSE 0 END) as jumlah_perempuan"),
            DB::raw("SUM(CASE WHEN pasien.jk = 'L' THEN 1 ELSE 0 END) as jumlah_laki"),
            DB::raw("SUM(CASE WHEN reg_periksa.kd_pj = 'BPJ' THEN 1 ELSE 0 END) as jumlah_bpjs"),
            DB::raw("SUM(CASE WHEN reg_periksa.kd_pj = 'A09' THEN 1 ELSE 0 END) as jumlah_umum"),
            DB::raw("SUM(CASE WHEN reg_periksa.kd_pj NOT IN ('BPJ', 'A09') THEN 1 ELSE 0 END) as jumlah_lainnya"),
            DB::raw("SUM(CASE WHEN permintaan_radiologi.status = 'Ralan' THEN 1 ELSE 0 END) as jumlah_ralan"),
            DB::raw("SUM(CASE WHEN permintaan_radiologi.status = 'Ranap' THEN 1 ELSE 0 END) as jumlah_ranap")
        )
        ->first();

        $this->jumlahPasien = $data->total_pasien;
        $this->jumlahPria = $data->jumlah_laki;
        $this->jumlahWanita = $data->jumlah_perempuan;
        $this->jumlahBPJS = $data->jumlah_bpjs;
        $this->jumlahUMUM = $data->jumlah_umum;
        $this->jumlahAskesLain = $data->jumlah_lainnya;
        $this->jumlahRalan = $data->jumlah_ralan;
        $this->jumlahRanap = $data->jumlah_ranap;
    }
    public function render()
    {
        return view('livewire.radio-today');
    }
}
