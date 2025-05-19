<?php

namespace App\Livewire;
use Livewire\Livewire;
use Livewire\Component;

class DateFilter extends Component
{
    public $startDate, $endDate;

    public function mount()
    {
        $this->startDate = now()->format('Y-m-d');
        $this->endDate = now()->format('Y-m-d');
    }

    public function applyDateFilter()
    {
        // $this->emit('updateDate', $this->startDate, $this->endDate);
        // Livewire::emitTo('hemo-stats', 'updateDate', $this->startDate, $this->endDate);
        // Livewire::emitTo('igd-stats', 'updateDate', $this->startDate, $this->endDate);
        $this->dispatch('updateDate', start: $this->startDate, end: $this->endDate);
    }

    public function render()
    {
        return view('livewire.date-filter');
    }
}

