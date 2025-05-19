<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RadioChart extends Component
{
    public $labels = [];
    public $values = [];
    public $chartType = "bar";

    public $startDate, $endDate;
    protected $listeners = ['updateDate' => 'updateDate', 'initChart' => 'initChart'];
    public $chartInitialized = false;

    public function mount(){
        $this->startDate = Carbon::now()->format('Y-m-d');
        $this->endDate = Carbon::now()->format('Y-m-d');
        $this->fetchData();
    }

    public function initChart(){
        $this->dispatch('updateRadioChart', labels: $this->labels, values: $this->values, chartType: $this->chartType);
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

        foreach($dates as $date){
            $count=DB::table('permintaan_radiologi')
                        ->whereDate('permintaan_radiologi.tgl_permintaan', $date)
                        ->count();
            $radioCount[] = $count;
        }

        $this->values = [
            'RadioCount' => $radioCount,
        ];

        if ($this->startDate === $this->endDate) {
            $this->chartType = 'bar';
        } else {
            $this->chartType = 'line';
        }
    }

    public function render(){
        if (!$this->chartInitialized) {
            $this->initChart();
            $this->chartInitialized = true;
        }

        return view('livewire.radio-chart');
    }
}
