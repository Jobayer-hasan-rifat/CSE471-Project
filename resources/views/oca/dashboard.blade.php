<x-layouts.oca>
    <x-slot name="content">
        <!-- Main Content -->
        <div class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50">
            <!-- Dashboard Content -->
            <main class="p-6">
                <div id="content" class="transition-opacity duration-300">
                    @include('oca.dashboard-content', [
                        'totalEvents' => $totalEvents ?? 0,
                        'totalBudget' => $totalBudget ?? 0,
                        'totalClubs' => $totalClubs ?? 0,
                        'eventDistribution' => $eventDistribution ?? ['pending' => 0, 'approved' => 0, 'rejected' => 0],
                        'pendingEvents' => $pendingEvents ?? collect(),
                        'recentEvents' => $recentEvents ?? collect(),
                        'chartData' => $chartData ?? ['months' => [], 'eventCounts' => []]
                    ])
                </div>
            </main>
        </div>
    </x-slot>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            initializeCharts();
        });

        function initializeCharts() {
            const ctx = document.getElementById('eventChart');
            if (!ctx) return;

            try {
                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: @json($chartData['months'] ?? []),
                        datasets: [{
                            label: 'Events',
                            data: @json($chartData['eventCounts'] ?? []),
                            borderColor: 'rgb(75, 192, 192)',
                            tension: 0.1
                        }]
                    },
                    options: {
                        responsive: true,
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    stepSize: 1
                                }
                            }
                        }
                    }
                });
            } catch (error) {
                console.error('Error initializing chart:', error);
            }
        }
    </script>
    @endpush
</x-layouts.oca>
