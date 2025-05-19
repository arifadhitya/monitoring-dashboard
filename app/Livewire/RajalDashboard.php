<?php

namespace App\Livewire;

use Livewire\Component;
use Carbon\Carbon;

class RajalDashboard extends Component
{
    public $startDate, $endDate;

    public function mount(){
        $this->startDate = Carbon::now()->format('Y-m-d');
        $this->endDate = Carbon::now()->format('Y-m-d');
    }

    public function applyDateFilter()
    {
        $this->dispatch('updateDateRange', $this->startDate, $this->endDate);
    }

    public function render()
    {
        return view('livewire.rajal-dashboard');
    }
}
