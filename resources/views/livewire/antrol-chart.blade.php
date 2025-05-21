<div class="bg-white p-4 rounded-xl" style="height: 400px;" wire:init="initChart">
    <canvas id="lineAntrolChart" wire:ignore style="height: 80%; width: 80%;"></canvas>   
</div>
  
  {{-- <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> --}}
  @push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
(function () {
    if (window.antrolChartInitialized) return;
    window.antrolChartInitialized = true;

    let antrolChartInstance = null;

    function renderChart({ labels, values, chartType }) {
        const ctx = document.getElementById('lineAntrolChart');
        if (!ctx) return;

        if (antrolChartInstance) antrolChartInstance.destroy();

        antrolChartInstance = new Chart(ctx, {
            type: chartType,
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Checkin',
                        data: values.Checkin,
                        borderColor: '#59C9A5',
                        backgroundColor: 'rgba(89, 201, 165, 1)',
                        tension: 0.4,
                        categoryPercentage: 0.6,
                        barPercentage: 0.4
                    },
                    {
                        label: 'Batal',
                        data: values.Batal,
                        borderColor: '#EF6F6C',
                        backgroundColor: 'rgba(239, 111, 108, 1)',
                        tension: 0.4,
                        categoryPercentage: 0.6,
                        barPercentage: 0.4 
                    },
                    {
                        label: 'Belum',
                        data: values.Belum,
                        borderColor: '#99B2DD',
                        backgroundColor: 'rgba(153, 178, 221, 1)',
                        tension: 0.4,
                        categoryPercentage: 0.6,
                        barPercentage: 0.4
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

    // Setup listener untuk update chart pada navigasi Livewire
    function setupAntrolChartListeners() {
        Livewire.on('updateAntrolChart', (data) => {
            setTimeout(() => {
                renderChart(data);
            }, 100); // Delay kecil untuk pastikan canvas sudah ada
        });
    }

    // Inisialisasi listener chart pada event livewire:init
    document.addEventListener('livewire:init', setupAntrolChartListeners);

    // Reset chart instance pada navigasi Livewire (SPA mode)
    document.addEventListener('livewire:navigated', () => {
        Livewire.dispatch('initChart');
        antrolChartInstance = null; // Reset instance
        setupAntrolChartListeners();
    });
})();
</script>
@endpush