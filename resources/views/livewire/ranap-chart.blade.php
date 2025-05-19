<div class="bg-white p-4 rounded-xl" style="height: 400px;" wire:init="initChart">
    <canvas id="lineChartRanap" wire:ignore style="height: 100%; width: 100%;"></canvas> 
</div>
  
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
(function () {
    // Memastikan hanya sekali inisialisasi chart untuk mencegah duplikasi
    if (window.ranapChartInitialized) return;
    window.ranapChartInitialized = true;

    let ranapChartInstance = null;

    // Fungsi untuk merender chart dengan data yang diberikan
    function renderRanapChart({ labels, values, chartType }) {
        const ctx = document.getElementById('lineChartRanap');
        if (!ctx) return;

        if (ranapChartInstance) ranapChartInstance.destroy();

        ranapChartInstance = new Chart(ctx, {
            type: chartType,
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Rawat Inap',
                        data: values.Ranap,
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

    // Menangani update chart dari Livewire
    function setupRanapChartListeners() {
        Livewire.on('updateRanapChart', (data) => {
            setTimeout(() => {
                renderRanapChart(data);
            }, 100); // Menunggu agar canvas sudah ada
        });
    }

    // Inisialisasi listener pada saat Livewire dimulai
    document.addEventListener('livewire:init', setupRanapChartListeners);

    // Reset chart pada navigasi Livewire (SPA mode)
    document.addEventListener('livewire:navigated', () => {
        ranapChartInstance = null; // Reset chart instance pada navigasi
        setupRanapChartListeners();
    });
})();
</script>
@endpush
