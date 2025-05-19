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

    public $startDate, $endDate;
    protected $listeners = ['updateDate' => 'updateDate'];

    public function mount(){
        $this->startDate = Carbon::now()->format('Y-m-d');
        $this->endDate = Carbon::now()->format('Y-m-d');
        $this->fetchData();
        $this->initChart();
    }

    public function initChart(){
        $this->dispatch('updateAntrolChart', labels: $this->labels, values: $this->values, chartType: $this->chartType);
    }

    public function updateDate($start, $end){
        $this->startDate = $start;
        $this->endDate = $end;
        $this->fetchData();
        $this->initChart(); 
    }


    public function fetchData(){
        // Ambil semua tanggal dalam range
        $dates = collect();
        $period = Carbon::parse($this->startDate)->daysUntil($this->endDate);
        foreach ($period as $date) {
            $dates->push($date->format('Y-m-d'));
        }
        $this->labels = $dates->toArray();
        $antrolCheckinCount = [];
        $antrolBatalCount = [];

        foreach($dates as $date){
            $checkinCount=DB::table('referensi_mobilejkn_bpjs')
                        ->where('referensi_mobilejkn_bpjs.status','Checkin')
                        ->whereDate('referensi_mobilejkn_bpjs.tanggalperiksa', $date)
                        ->count();

            $batalCount=DB::table('referensi_mobilejkn_bpjs')
                        ->where('referensi_mobilejkn_bpjs.status','Batal')
                        ->whereDate('referensi_mobilejkn_bpjs.tanggalperiksa', $date)
                        ->count();

            $belumCount=DB::table('referensi_mobilejkn_bpjs')
                        ->where('referensi_mobilejkn_bpjs.status','Belum')
                        ->whereDate('referensi_mobilejkn_bpjs.tanggalperiksa', $date)
                        ->count();

            $antrolCheckinCount[] = $checkinCount;
            $antrolBatalCount[] = $batalCount;
            $antrolBelumCount[] = $belumCount;
        }

        $this->values = [
            'Checkin' => $antrolCheckinCount,
            'Batal' => $antrolBatalCount,
            'Belum' => $antrolBelumCount,
        ];

        $this->chartType = $this->startDate === $this->endDate ? 'bar' : 'line';
    }

    public function render(){
        return view('livewire.antrol-chart');
    }
}
