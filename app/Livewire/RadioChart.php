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

    public function mount()
    {
        $this->fetchData();
        $this->initChart();
    }

    public function initChart(){
        $this->dispatch('updateRadioChart', labels: $this->labels, values: $this->values, chartType: $this->chartType);
    }

    public function fetchData(){
        $end = Carbon::now()->startOfMonth();
        $start = (clone $end)->subMonths(11);

        $period = collect();
        $current = $start->copy();

        while ($current <= $end) {
            $period->push($current->copy());
            $current->addMonth();
        }

        $this->labels = $period->map(fn ($date) => $date->format('M Y'))->toArray();

        $radioCount = [];

        foreach($period as $month){
            $startOfMonth = $month->copy()->startOfMonth()->toDateString();
            $endOfMonth = $month->copy()->endOfMonth()->toDateString();
            
            $count=DB::table('permintaan_radiologi')
                        ->whereBetween('permintaan_radiologi.tgl_permintaan', [$startOfMonth, $endOfMonth])
                        ->count();
            $radioCount[] = $count;
        }

        $this->values = [
            'RadioCount' => $radioCount,
        ];
    }

    public function render(){
        return view('livewire.radio-chart');
    }
}
