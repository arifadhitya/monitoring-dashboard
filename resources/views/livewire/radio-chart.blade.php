<div class="bg-white p-4 rounded-xl shadow" style="height: 400px;">
    <h2 class="text-lg font-semibold mb-2">Permintaan Radiologi</h2>
    <canvas id="lineRadioChart" wire:ignore style="height: 100%; width: 100%;"></canvas>   
    <pre class="text-xs text-gray-500">Labels: @json($labels)</pre>
    <pre class="text-xs text-gray-500">Values: @json($values)</pre>
</div>
  
  {{-- <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> --}}
  @push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
(function () {
    // Hindari deklarasi ulang saat navigasi Livewire
    if (window.radioChartInitialized) return;
    window.radioChartInitialized = true;

    window.radioChartInstance = null;
    window.radioChartListenerBound = false;

    function renderRadioChart({labels, values, chartType}) {
        const ctx = document.getElementById('lineRadioChart');
        if (!ctx) return;

        if (window.radioChartInstance) window.radioChartInstance.destroy();

        window.radioChartInstance = new Chart(ctx, {
            type: chartType,
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Permintaan Radiologi',
                        data: values.RadioCount,
                        borderColor: '#F3C178',
                        backgroundColor: 'rgba(243, 193, 120, 1)',
                        tension: 0.4,
                        barPercentage: 0.2 
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
        if (window.radioChartListenerBound) return;

        Livewire.on('updateRadioChart', (data) => {
            setTimeout(() => {
                renderRadioChart(data);
            }, 100);
        });

        window.radioChartListenerBound = true;
    }

    document.addEventListener('livewire:init', bindRadioChartListener);

    document.addEventListener('livewire:navigated', () => {
        Livewire.dispatch('initChart');
        window.radioChartListenerBound = false;
        bindRadioChartListener();
    });
})();
</script>
@endpush
