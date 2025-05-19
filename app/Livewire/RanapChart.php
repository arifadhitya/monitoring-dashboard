<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RanapChart extends Component
{
    public $labels = [];
    public $values = [];
    public $chartType = "bar";

    public function mount()
    {
        $this->fetchData();
        $this->initChart();
    }

    public function initChart()
    {
        $this->dispatch('updateRanapChart', labels: $this->labels, values: $this->values, chartType: $this->chartType);
    }

    private function fetchData()
    {
        $end = Carbon::now()->startOfMonth();
        $start = (clone $end)->subMonths(11);

        $period = collect();
        $current = $start->copy();

        while ($current <= $end) {
            $period->push($current->copy());
            $current->addMonth();
        }

        $this->labels = $period->map(fn ($date) => $date->format('M Y'))->toArray();

        $ranap12Bulan = [];

        foreach ($period as $month) {
            $startOfMonth = $month->copy()->startOfMonth()->toDateString();
            $endOfMonth = $month->copy()->endOfMonth()->toDateString();

            $patientQuery = DB::table('reg_periksa')
                ->where('reg_periksa.status_lanjut', 'Ranap')
                ->whereBetween('reg_periksa.tgl_registrasi', [$startOfMonth, $endOfMonth]);

            $ranap12Bulan[] = (clone $patientQuery)
                ->count();
        }

        $this->values = [
            'Ranap' => $ranap12Bulan,
        ];
    }

    public function render(){
        return view('livewire.ranap-chart');
    }
}
