<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;

class RanapList extends Component
{
    public function render()
    {
        $data = DB::table('kamar')
            ->join('bangsal', 'kamar.kd_bangsal', '=', 'bangsal.kd_bangsal')
            ->leftJoin('kamar_inap', function ($join) {
                $join->on('kamar.kd_kamar', '=', 'kamar_inap.kd_kamar')
                     ->where('kamar_inap.stts_pulang', '=', '-');
            })
            ->leftJoin('reg_periksa', 'kamar_inap.no_rawat', '=', 'reg_periksa.no_rawat')
            ->leftJoin('pasien', 'reg_periksa.no_rkm_medis', '=', 'pasien.no_rkm_medis')
            ->where('kamar.statusdata', '1')
            ->select(
                'bangsal.nm_bangsal',
                DB::raw('COUNT(DISTINCT kamar.kd_kamar) AS total'),
                DB::raw("COUNT(CASE WHEN kamar.status = 'KOSONG' THEN 1 END) AS kosong"),
                DB::raw("COUNT(CASE WHEN kamar.status = 'ISI' THEN 1 END) AS terisi"),
                DB::raw("COUNT(CASE WHEN kamar.status = 'DIBOOKING' THEN 1 END) AS dibooking"),
                DB::raw("COUNT(CASE WHEN kamar.status = 'DIBERSIHKAN' THEN 1 END) AS dibersihkan"),
                DB::raw("COUNT(DISTINCT kamar_inap.no_rawat) AS total_pasien"),
                DB::raw("COUNT(CASE WHEN pasien.jk = 'L' THEN 1 END) AS jumlah_pria"),
                DB::raw("COUNT(CASE WHEN pasien.jk = 'P' THEN 1 END) AS jumlah_wanita")
            )
            ->groupBy('bangsal.nm_bangsal')
            ->orderBy('bangsal.nm_bangsal')
            ->get();

        return view('livewire.ranap-list', compact('data'));
    }
}
