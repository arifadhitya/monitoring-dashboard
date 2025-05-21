<?php

namespace App\Livewire;

use Livewire\Component;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\DB;

class LabList extends Component
{
    public function render()
    {
        $data = [];

        $now = Carbon::now();
        $period = CarbonPeriod::create(
            $now->copy()->subMonths(11)->startOfMonth(),
            '1 month',
            $now->copy()->startOfMonth()
        );

        // Ubah iterator period ke array, lalu balik urutan (terbaru dulu)
        $months = iterator_to_array($period);
        $months = array_reverse($months);

        foreach ($months as $month) {
            $start = $month->copy()->startOfMonth()->toDateString();
            $end = $month->copy()->endOfMonth()->toDateString();

            $labPA = DB::table('permintaan_labpa')
                ->whereBetween('tgl_permintaan', [$start, $end])
                ->count();

            $labPK = DB::table('permintaan_lab')
                ->whereBetween('tgl_permintaan', [$start, $end])
                ->count();

            $labMB = DB::table('permintaan_labmb')
                ->whereBetween('tgl_permintaan', [$start, $end])
                ->count();

            $totalPasien = $labPA + $labPK + $labMB;

            $genderpa = DB::table('permintaan_labpa')
                ->join('reg_periksa', 'permintaan_labpa.no_rawat', '=', 'reg_periksa.no_rawat')
                ->join('pasien', 'reg_periksa.no_rkm_medis', '=', 'pasien.no_rkm_medis')
                ->whereBetween('tgl_registrasi', [$start, $end])
                ->selectRaw("
                    SUM(CASE WHEN pasien.jk = 'P' THEN 1 ELSE 0 END) as perempuan,
                    SUM(CASE WHEN pasien.jk = 'L' THEN 1 ELSE 0 END) as laki_laki
                ")
                ->first();

            $genderpk = DB::table('permintaan_lab')
                ->join('reg_periksa', 'permintaan_lab.no_rawat', '=', 'reg_periksa.no_rawat')
                ->join('pasien', 'reg_periksa.no_rkm_medis', '=', 'pasien.no_rkm_medis')
                ->whereBetween('tgl_registrasi', [$start, $end])
                ->selectRaw("
                    SUM(CASE WHEN pasien.jk = 'P' THEN 1 ELSE 0 END) as perempuan,
                    SUM(CASE WHEN pasien.jk = 'L' THEN 1 ELSE 0 END) as laki_laki
                ")
                ->first();

            $gendermb = DB::table('permintaan_labmb')
                ->join('reg_periksa', 'permintaan_labmb.no_rawat', '=', 'reg_periksa.no_rawat')
                ->join('pasien', 'reg_periksa.no_rkm_medis', '=', 'pasien.no_rkm_medis')
                ->whereBetween('tgl_registrasi', [$start, $end])
                ->selectRaw("
                    SUM(CASE WHEN pasien.jk = 'P' THEN 1 ELSE 0 END) as perempuan,
                    SUM(CASE WHEN pasien.jk = 'L' THEN 1 ELSE 0 END) as laki_laki
                ")
                ->first();

            $totalPerempuan = ($genderpa->perempuan ?? 0) + ($genderpk->perempuan ?? 0) + ($gendermb->perempuan ?? 0);
            $totalLakiLaki = ($genderpa->laki_laki ?? 0) + ($genderpk->laki_laki ?? 0) + ($gendermb->laki_laki ?? 0);

            // Asuransi PA
            $asuransiPA = DB::table('permintaan_labpa')
                ->join('reg_periksa', 'permintaan_labpa.no_rawat', '=', 'reg_periksa.no_rawat')
                ->whereBetween('tgl_permintaan', [$start, $end])
                ->selectRaw("
                    SUM(CASE WHEN kd_pj = 'BPJS' THEN 1 ELSE 0 END) as total_bpjs,
                    SUM(CASE WHEN kd_pj = 'A09' THEN 1 ELSE 0 END) as total_a09,
                    SUM(CASE WHEN kd_pj NOT IN ('BPJS', 'A09') THEN 1 ELSE 0 END) as total_exclude
                ")->first();

            // Asuransi PK
            $asuransiPK = DB::table('permintaan_lab')
                ->join('reg_periksa', 'permintaan_lab.no_rawat', '=', 'reg_periksa.no_rawat')
                ->whereBetween('tgl_permintaan', [$start, $end])
                ->selectRaw("
                    SUM(CASE WHEN kd_pj = 'BPJS' THEN 1 ELSE 0 END) as total_bpjs,
                    SUM(CASE WHEN kd_pj = 'A09' THEN 1 ELSE 0 END) as total_a09,
                    SUM(CASE WHEN kd_pj NOT IN ('BPJS', 'A09') THEN 1 ELSE 0 END) as total_exclude
                ")->first();

            // Asuransi MB
            $asuransiMB = DB::table('permintaan_labmb')
                ->join('reg_periksa', 'permintaan_labmb.no_rawat', '=', 'reg_periksa.no_rawat')
                ->whereBetween('tgl_permintaan', [$start, $end])
                ->selectRaw("
                    SUM(CASE WHEN kd_pj = 'BPJS' THEN 1 ELSE 0 END) as total_bpjs,
                    SUM(CASE WHEN kd_pj = 'A09' THEN 1 ELSE 0 END) as total_a09,
                    SUM(CASE WHEN kd_pj NOT IN ('BPJS', 'A09') THEN 1 ELSE 0 END) as total_exclude
                ")->first();

            $total_bpjs = ($asuransiPA->total_bpjs ?? 0) + ($asuransiPK->total_bpjs ?? 0) + ($asuransiMB->total_bpjs ?? 0);
            $total_umum = ($asuransiPA->total_a09 ?? 0) + ($asuransiPK->total_a09 ?? 0) + ($asuransiMB->total_a09 ?? 0);
            $total_lainnya = ($asuransiPA->total_exclude ?? 0) + ($asuransiPK->total_exclude ?? 0) + ($asuransiMB->total_exclude ?? 0);

            $data[] = [
                'bulan' => $month->translatedFormat('F Y'),
                'total_pasien' => $totalPasien,
                'p' => $totalPerempuan,
                'l' => $totalLakiLaki,
                'lab_pk' => $labPK,
                'lab_pa' => $labPA,
                'lab_mb' => $labMB,
                'bpjs' => $total_bpjs,
                'umum' => $total_umum,
                'lainnya' => $total_lainnya,
            ];
        }

        return view('livewire.lab-list', compact('data'));
    }

}
