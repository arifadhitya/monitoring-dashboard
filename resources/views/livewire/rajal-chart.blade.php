<div class="bg-white p-4 rounded-xl" style="height: 400px;" wire:init="initChart">
    <canvas id="lineChart" wire:ignore style="height: 100%; width: 100%;"></canvas>   
</div>
  
  {{-- <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> --}}
  @push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
(function () {
    if (window.rajalChartInitialized) return;
    window.rajalChartInitialized = true;

    let rajalChartInstance = null;

    function renderChart({ labels, values, chartType }) {
        const ctx = document.getElementById('lineChart');
        if (!ctx) return;

        if (rajalChartInstance) rajalChartInstance.destroy();

        rajalChartInstance = new Chart(ctx, {
            type: chartType,
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Poli',
                        data: values.Poli,
                        borderColor: '#00a6f4',
                        backgroundColor: 'rgba(0, 166, 244, 1)',
                        tension: 0.4
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: { y: { beginAtZero: true } }
            }
        });
    }

    function setupRajalChartListeners() {
        Livewire.on('updateRajalChart', (data) => {
            setTimeout(() => {
                renderChart(data);
            }, 100); // Delay kecil untuk pastikan canvas sudah ada
        });
    }

    document.addEventListener('livewire:init', setupRajalChartListeners);

    document.addEventListener('livewire:navigated', () => {
        Livewire.dispatch('initChart');
        rajalChartInstance = null; // Reset instance
        setupRajalChartListeners();
    });
})();
</script>
@endpush