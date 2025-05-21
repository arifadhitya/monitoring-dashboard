<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AntrolChart extends Component
{
    public $labels = [];
    public $values = [];
    public $chartType = "bar";

    public function mount(){
        $this->fetchData();
        $this->initChart();
    }

    public function initChart(){
        $this->dispatch('updateAntrolChart', labels: $this->labels, values: $this->values, chartType: $this->chartType);
    }

    public function fetchData(){
        $dateNow = Carbon::now()->format('Y-m-d');
        $antrolCheckinCount = [];
        $antrolBatalCount = [];
        $antrolBelumCount = [];

        $checkinCount=DB::table('referensi_mobilejkn_bpjs')
                        ->where('referensi_mobilejkn_bpjs.status','Checkin')
                        ->whereDate('referensi_mobilejkn_bpjs.tanggalperiksa', $dateNow)
                        ->count();

        $batalCount=DB::table('referensi_mobilejkn_bpjs')
                        ->where('referensi_mobilejkn_bpjs.status','Batal')
                        ->whereDate('referensi_mobilejkn_bpjs.tanggalperiksa', $dateNow)
                        ->count();

        $belumCount=DB::table('referensi_mobilejkn_bpjs')
                        ->where('referensi_mobilejkn_bpjs.status','Belum')
                        ->whereDate('referensi_mobilejkn_bpjs.tanggalperiksa', $dateNow)
                        ->count();

        $antrolCheckinCount[] = $checkinCount;
        $antrolBatalCount[] = $batalCount;
        $antrolBelumCount[] = $belumCount;

        $this->values = [
            'Checkin' => $antrolCheckinCount,
            'Batal' => $antrolBatalCount,
            'Belum' => $antrolBelumCount,
        ];

        $this->labels = ['MJKN Hari Ini'];
    }

    public function render(){
        return view('livewire.antrol-chart');
    }
}
