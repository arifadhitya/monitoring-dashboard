<div class="bg-white p-4 rounded-xl" style="height: 400px;" wire:init="initChart">
    <canvas id="lineRadioChart" wire:ignore style="height: 100%; width: 100%;"></canvas>   
</div>
  
  @push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
(function () {
    // Hindari deklarasi ulang saat navigasi Livewire
    if (window.radioChartInitialized) return;
    window.radioChartInitialized = true;

    let radioChartInstance = null;

    function renderRadioChart({labels, values, chartType}) {
        const ctx = document.getElementById('lineRadioChart');
        if (!ctx) return;

        if (radioChartInstance) radioChartInstance.destroy();

        radioChartInstance = new Chart(ctx, {
            type: chartType,
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Permintaan Radiologi',
                        data: values.RadioCount,
                        borderColor: '#00a6f4',
                        backgroundColor: 'rgba(0, 166, 244, 1)',
                        tension: 0.4,
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

    function bindRadioChartListener() {
        Livewire.on('updateRadioChart', (data) => {
            setTimeout(() => {
                renderRadioChart(data);
            }, 100);
        });
    }

    document.addEventListener('livewire:init', bindRadioChartListener);

    document.addEventListener('livewire:navigated', () => {
        radioChartInstance = null;
        bindRadioChartListener();
    });
})();
</script>
@endpush
