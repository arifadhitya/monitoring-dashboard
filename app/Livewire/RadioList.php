<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RadioList extends Component
{
    public function render()
    {
        $querySampel = DB::table('permintaan_radiologi')
            ->join('reg_periksa', 'permintaan_radiologi.no_rawat', '=', 'reg_periksa.no_rawat')
            ->join('pasien', 'reg_periksa.no_rkm_medis', '=', 'pasien.no_rkm_medis')
            ->whereDate('permintaan_radiologi.tgl_permintaan', now()->format('Y-m-d'))
            ->where('permintaan_radiologi.jam_sampel', '!=', '00:00:00')
            ->select([
                DB::raw("'KELUAR SAMPEL' as kategori"),
                DB::raw('COUNT(DISTINCT permintaan_radiologi.noorder) as total'),
                DB::raw("SUM(CASE WHEN pasien.jk = 'L' THEN 1 ELSE 0 END) as pria"),
                DB::raw("SUM(CASE WHEN pasien.jk = 'P' THEN 1 ELSE 0 END) as wanita"),
                DB::raw("SUM(CASE WHEN reg_periksa.kd_pj = 'BPJ' THEN 1 ELSE 0 END) as bpjs"),
                DB::raw("SUM(CASE WHEN reg_periksa.kd_pj = 'A09' THEN 1 ELSE 0 END) as umum"),
                DB::raw("SUM(CASE WHEN reg_periksa.kd_pj NOT IN ('BPJ', 'A09') THEN 1 ELSE 0 END) as lainnya"),
                DB::raw("SUM(CASE WHEN reg_periksa.status_lanjut = 'Ralan' THEN 1 ELSE 0 END) as ralan"),
                DB::raw("SUM(CASE WHEN reg_periksa.status_lanjut = 'Ranap' THEN 1 ELSE 0 END) as ranap"),
            ]);

        $queryHasil = DB::table('permintaan_radiologi')
            ->join('reg_periksa', 'permintaan_radiologi.no_rawat', '=', 'reg_periksa.no_rawat')
            ->join('pasien', 'reg_periksa.no_rkm_medis', '=', 'pasien.no_rkm_medis')
            ->whereDate('permintaan_radiologi.tgl_permintaan', now()->format('Y-m-d'))
            ->where('permintaan_radiologi.jam_hasil', '!=', '00:00:00')
            ->select([
                DB::raw("'KELUAR HASIL' as kategori"),
                DB::raw('COUNT(DISTINCT permintaan_radiologi.noorder) as total'),
                DB::raw("SUM(CASE WHEN pasien.jk = 'L' THEN 1 ELSE 0 END) as pria"),
                DB::raw("SUM(CASE WHEN pasien.jk = 'P' THEN 1 ELSE 0 END) as wanita"),
                DB::raw("SUM(CASE WHEN reg_periksa.kd_pj = 'BPJ' THEN 1 ELSE 0 END) as bpjs"),
                DB::raw("SUM(CASE WHEN reg_periksa.kd_pj = 'A09' THEN 1 ELSE 0 END) as umum"),
                DB::raw("SUM(CASE WHEN reg_periksa.kd_pj NOT IN ('BPJ', 'A09') THEN 1 ELSE 0 END) as lainnya"),
                DB::raw("SUM(CASE WHEN reg_periksa.status_lanjut = 'Ralan' THEN 1 ELSE 0 END) as ralan"),
                DB::raw("SUM(CASE WHEN reg_periksa.status_lanjut = 'Ranap' THEN 1 ELSE 0 END) as ranap"),
            ]);

        $results = $querySampel->unionAll($queryHasil)->get();

        return view('livewire.radio-list', compact('results'));
    }

}
