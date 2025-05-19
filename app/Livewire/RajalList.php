<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;

class RajalList extends Component
{
    public function render()
    {
        $data = DB::table('reg_periksa')
            ->join('pasien', 'reg_periksa.no_rkm_medis', '=', 'pasien.no_rkm_medis')
            ->leftJoin('referensi_mobilejkn_bpjs', 'reg_periksa.no_rawat', '=', 'referensi_mobilejkn_bpjs.no_rawat')
            ->join('poliklinik', 'reg_periksa.kd_poli', '=', 'poliklinik.kd_poli')
            ->select(
                'poliklinik.nm_poli',
                DB::raw('COUNT(*) AS total_pasien'),
                DB::raw("COUNT(CASE WHEN pasien.jk = 'L' THEN 1 END) AS jumlah_pria"),
                DB::raw("COUNT(CASE WHEN pasien.jk = 'P' THEN 1 END) AS jumlah_wanita"),
                DB::raw("COUNT(CASE WHEN reg_periksa.kd_pj = 'BPJ' THEN 1 END) AS jumlah_bpjs"),
                DB::raw("COUNT(CASE WHEN reg_periksa.kd_pj = 'A09' THEN 1 END) AS jumlah_umum"),
                DB::raw("COUNT(CASE WHEN reg_periksa.kd_pj NOT IN ('BPJ', 'A09') THEN 1 END) AS jumlah_lainnya"),
                DB::raw("COUNT(CASE WHEN referensi_mobilejkn_bpjs.status = 'Checkin' THEN 1 END) AS jumlah_checkin")
            )
            ->where('reg_periksa.tgl_registrasi', now()->toDateString())
            ->whereNotIn('reg_periksa.kd_poli', ['IGDK', 'U0011', 'U0012', 'U0013', 'U0014', 'U0037', 'U0040', 'U0041', 'U0042'])
            ->groupBy('poliklinik.nm_poli')
            ->orderBy('poliklinik.nm_poli')
            ->get();

        return view('livewire.rajal-list', compact('data'));
    }
}
