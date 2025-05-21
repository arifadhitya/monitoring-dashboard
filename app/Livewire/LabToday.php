<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LabToday extends Component
{
    public $dateNow;
    protected $listeners = ['updateDate' => 'updateDate'];

    public $totalPasien = 0, $jumlahPria = 0, $jumlahWanita = 0;
    public $jumlahBPJS = 0, $jumlahUMUM = 0, $jumlahAskesLain = 0;
    public $totalLabPK = 0, $totalLabPA = 0, $totalLabMB = 0;

    public function mount()
    {
        $this->dateNow = Carbon::now()->format('Y-m-d');
        $this->fetchData();
    }

    public function fetchData()
    {
        // Hitung masing-masing lab
        $this->totalLabPA = DB::table('permintaan_labpa')->whereDate('tgl_permintaan', $this->dateNow)->count();
        $this->totalLabPK = DB::table('permintaan_lab')->whereDate('tgl_permintaan', $this->dateNow)->count();
        $this->totalLabMB = DB::table('permintaan_labmb')->whereDate('tgl_permintaan', $this->dateNow)->count();

        $this->totalPasien = $this->totalLabPA + $this->totalLabPK + $this->totalLabMB;

        // Gabung data pasien dari tiga tabel lab
        $labUnion = DB::table('permintaan_lab as pl')
            ->select('pl.no_rawat')
            ->whereDate('pl.tgl_permintaan', $this->dateNow)
            ->unionAll(
                DB::table('permintaan_labpa as pa')
                    ->select('pa.no_rawat')
                    ->whereDate('pa.tgl_permintaan', $this->dateNow)
            )
            ->unionAll(
                DB::table('permintaan_labmb as mb')
                    ->select('mb.no_rawat')
                    ->whereDate('mb.tgl_permintaan', $this->dateNow)
            );

        $patients = DB::table(DB::raw("({$labUnion->toSql()}) as labs"))
            ->mergeBindings($labUnion)
            ->join('reg_periksa as rp', 'labs.no_rawat', '=', 'rp.no_rawat')
            ->join('pasien as p', 'rp.no_rkm_medis', '=', 'p.no_rkm_medis')
            ->selectRaw('
                COUNT(DISTINCT labs.no_rawat) as total_pasien,
                SUM(CASE WHEN p.jk = "L" THEN 1 ELSE 0 END) as total_laki,
                SUM(CASE WHEN p.jk = "P" THEN 1 ELSE 0 END) as total_perempuan,
                SUM(CASE WHEN rp.kd_pj = "BPJ" THEN 1 ELSE 0 END) as total_bpjs,
                SUM(CASE WHEN rp.kd_pj = "A09" THEN 1 ELSE 0 END) as total_umum,
                SUM(CASE WHEN rp.kd_pj NOT IN ("BPJ", "A09") THEN 1 ELSE 0 END) as total_lainnya
            ')
            ->first();

        // Assign ke variabel publik
        $this->jumlahPria = $patients->total_laki ?? 0;
        $this->jumlahWanita = $patients->total_perempuan ?? 0;
        $this->jumlahBPJS = $patients->total_bpjs ?? 0;
        $this->jumlahUMUM = $patients->total_umum ?? 0;
        $this->jumlahAskesLain = $patients->total_lainnya ?? 0;
    }

    
    public function render()
    {
        return view('livewire.lab-today');
    }
}
