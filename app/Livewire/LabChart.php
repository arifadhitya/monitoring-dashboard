<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LabChart extends Component
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
        $this->dispatch('updateLabChart', labels: $this->labels, values: $this->values, chartType: $this->chartType);
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

        $labData = [];
        // $labPAData = [];

        foreach ($period as $month) {
            $startOfMonth = $month->copy()->startOfMonth()->toDateString();
            $endOfMonth = $month->copy()->endOfMonth()->toDateString();

            $labPKCount = DB::table('permintaan_lab')
                ->whereBetween('tgl_permintaan', [$startOfMonth, $endOfMonth])
                ->count();

            $labPACount = DB::table('permintaan_labpa')
                ->whereBetween('tgl_permintaan', [$startOfMonth, $endOfMonth])
                ->count();

            $labData[] = $labPKCount+$labPACount;
            // $labPAData[] = $labPACount;
        }

        $this->values = [
            'LabData' => $labData,
            // 'LabPA' => $labPAData,
        ];
    }

    public function render()
    {
        return view('livewire.lab-chart');
    }
}
