<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RajalChart extends Component
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
        $this->dispatch('updateRajalChart', labels: $this->labels, values: $this->values, chartType: $this->chartType);
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

        $poli = [];

        foreach ($period as $month) {
            $startOfMonth = $month->copy()->startOfMonth()->toDateString();
            $endOfMonth = $month->copy()->endOfMonth()->toDateString();

            $patientQuery = DB::table('reg_periksa')
                ->where('reg_periksa.status_lanjut', 'Ralan')
                ->whereBetween('reg_periksa.tgl_registrasi', [$startOfMonth, $endOfMonth]);

            $poli[] = (clone $patientQuery)
                ->whereNotIn('reg_periksa.kd_poli', [
                    'IGDK', 'U0011', 'U0012', 'U0013', 'U0014', 'U0037', 'U0040', 'U0041', 'U0042'
                ])
                ->count();
        }

        $this->values = [
            'Poli' => $poli,
        ];
    }

    public function render()
    {
        return view('livewire.rajal-chart');
    }
}
