<div class="bg-white p-4 rounded-xl" style="height: 400px;" wire:init="initChart">
    <canvas id="lineLabChart" wire:ignore style="height: 100%; width: 100%;"></canvas>
</div>
  
  {{-- <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> --}}
  @push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
(function () {
    if (window.labChartInitialized) return;
    window.labChartInitialized = true;

    let labChartInstance = null;

    function renderLabChart({labels, values, chartType}) {
        const ctx = document.getElementById('lineLabChart');
        if (!ctx) return;

        if (labChartInstance) labChartInstance.destroy();

        labChartInstance = new Chart(ctx, {
            type: chartType,
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Pengunjung Lab',
                        data: values.LabData,
                        borderColor: '#00a6f4',
                        backgroundColor: 'rgba(0, 166, 244, 1)',
                        tension: 0.4
                    },
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: { y: { beginAtZero: true } }
            }
        });
    }

    function bindLabChartListener() {
        Livewire.on('updateLabChart', (data) => {
            setTimeout(() => {
                renderLabChart(data);
            }, 100);
        });
    }

    document.addEventListener('livewire:init', bindLabChartListener);

    document.addEventListener('livewire:navigated', () => {
        Livewire.dispatch('initChart');
        labChartInstance = null;
        bindLabChartListener();
    });
})();
</script>
@endpush