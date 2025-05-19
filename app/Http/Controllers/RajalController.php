<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RajalController extends Controller
{
    public function index(Request $request){
        // Get input dates or set default (this day)
        $startDate = $request->input('start_date', Carbon::now()->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->format('Y-m-d'));

        // Query patient data once and store it in a variable
        $pasien = DB::table('reg_periksa')
            ->join('pasien', 'pasien.no_rkm_medis', '=', 'pasien.no_rkm_medis') // Join only when needed
            ->select( // Select only required fields
                'reg_periksa.no_rawat',
                'reg_periksa.png_jawab',
                'reg_periksa.tgl_registrasi',
                'pasien.jk',
                'reg_periksa.kd_poli'
            )
            // Exclude ( IGD & IGD Ponek, Poli Anestesi, Lab Anatomi, Lab Klinik, Radio, Hemo, Endoskopi)
            ->whereNotIn('reg_periksa.kd_poli', ['IGDK', 'U0011', 'U0012', 'U0013', 'U0014', 'U0037', 'U0040', 'U0041', 'U0042'])
            ->whereBetween('rajal.tgl_registrasi', [$startDate, $endDate]);

        // Count total patients
        $patientCount = $pasien->count();

        // Count Male & Female
        $genderCounts = $pasien->selectRaw("
            SUM(CASE WHEN gender = 'L' THEN 1 ELSE 0 END) as male_count,
            SUM(CASE WHEN gender = 'P' THEN 1 ELSE 0 END) as female_count
        ")->first();

        // Count patients by insurance type
        $insuranceStats = $pasien
            ->select('insurance_type', DB::raw('COUNT(*) as total'))
            ->groupBy('insurance_type')
            ->get();

        // Get actual patient data
        // $rajalData = $pasien->get();

        return view('rajal', [
            // 'rajalData' => $rajalData,
            'jumlahPasien' => $patientCount,
            'jumlahPria' => $genderCounts->male_count ?? 0,
            'jumlahWanita' => $genderCounts->female_count ?? 0,
            'statAsuransi' => $insuranceStats
        ]);
    }
}
