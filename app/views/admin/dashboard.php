<div class="container mx-auto p-4 md:p-8 mt-24 mb-8 max-w-full min-h-screen">
    <h1 class="text-4xl font-extrabold text-gray-900 mb-12 mt-8 text-center">Admin Dashboard</h1>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white p-6 rounded-lg shadow-md flex items-center justify-between border border-gray-200">
            <div>
                <p class="text-lg font-medium text-gray-500">New Orders</p>
                <p class="text-3xl font-bold text-gray-900 mt-1"><?php echo htmlspecialchars($newOrders); ?></p>
                <p class="text-sm text-green-500 mt-1 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 10l7-7m0 0l7 7m-7-7v18" />
                    </svg>
                    <span class="font-semibold">+2.00% (30 days)</span>
                </p>
            </div>
            <div class="p-3 bg-blue-100 rounded-full text-blue-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                </svg>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-md flex items-center justify-between border border-gray-200">
            <div>
                <p class="text-lg font-medium text-gray-500">Total Income</p>
                <p class="text-3xl font-bold text-gray-900 mt-1">$<?php echo number_format($totalIncome, 2); ?></p>
                <p class="text-sm text-green-500 mt-1 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 10l7-7m0 0l7 7m-7-7v18" />
                    </svg>
                    <span class="font-semibold">+5.45% Increased</span>
                </p>
            </div>
            <div class="p-3 bg-green-100 rounded-full text-green-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-md flex items-center justify-between border border-gray-200">
            <div>
                <p class="text-lg font-medium text-gray-500">Total Expense</p>
                <p class="text-3xl font-bold text-gray-900 mt-1">$<?php echo number_format($totalExpense, 2); ?></p>
                <p class="text-sm text-red-500 mt-1 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                    </svg>
                    <span class="font-semibold">-2.00% Expense</span>
                </p>
            </div>
            <div class="p-3 bg-red-100 rounded-full text-red-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 9H7a2 2 0 00-2 2v6a2 2 0 002 2h10a2 2 0 002-2v-6a2 2 0 00-2-2zM7 9V5a2 2 0 012-2h6a2 2 0 012 2v4m-5 4h.01M12 16h.01" />
                </svg>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-md flex items-center justify-between border border-gray-200">
            <div>
                <p class="text-lg font-medium text-gray-500">New User</p>
                <p class="text-3xl font-bold text-gray-900 mt-1"><?php echo htmlspecialchars($newUser); ?></p>
                <p class="text-sm text-orange-500 mt-1 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                    </svg>
                    <span class="font-semibold">-25.00% Earning</span>
                </p>
            </div>
            <div class="p-3 bg-orange-100 rounded-full text-orange-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <div class="bg-white p-6 rounded-lg shadow-md border border-gray-200">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-bold text-gray-900">Yearly Stats</h2>
                <div class="text-gray-600">
                    <select class="ml-2 px-2 py-1 border rounded-md">
                        <option>Yearly</option>
                    </select>
                </div>
            </div>
            <p class="text-3xl font-bold text-gray-900 mb-6">$<?php echo number_format($yearlyStatsTotal, 2); ?></p>
            <div class="h-64 flex items-center justify-center">
                <canvas id="yearlyStatsChart"></canvas>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <a href="<?php echo BASE_URL; ?>/admin/products" class="flex-1 p-6 bg-white border border-gray-200 rounded-lg shadow-md hover:bg-gray-100 hover:shadow-lg transition-all duration-200 flex flex-col items-center justify-center text-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-[--color-dark-blue] mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                </svg>
                <h2 class="text-2xl font-bold text-gray-800">Manage Products</h2>
                <p class="text-gray-600 mt-2">Add, edit, or delete products.</p>
            </a>
            
            <a href="<?php echo BASE_URL; ?>/admin/users" class="flex-1 p-6 bg-white border border-gray-200 rounded-lg shadow-md hover:bg-gray-100 hover:shadow-lg transition-all duration-200 flex flex-col items-center justify-center text-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-gray-600 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                <h2 class="text-2xl font-bold text-gray-800">Manage Users</h2>
                <p class="text-gray-600 mt-2">View and manage user accounts.</p>
            </a>

            <div class="flex-1 p-6 bg-white border border-gray-200 rounded-lg shadow-md flex flex-col items-center justify-center text-center text-gray-500">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mb-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m0 0H6" />
                </svg>
                <h2 class="text-xl font-bold text-gray-800">New Feature</h2>
                <p class="text-gray-600 mt-2">Features coming soon..</p>
            </div>

            <div class="flex-1 p-6 bg-white border border-gray-200 rounded-lg shadow-md flex flex-col items-center justify-center text-center text-gray-500">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mb-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m0 0H6" />
                </svg>
                <h2 class="text-xl font-bold text-gray-800">Another Feature</h2>
                <p class="text-gray-600 mt-2">Features coming soon..</p>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Data from PHP for charts
        const monthlyLabels = <?php echo $monthlyLabels; ?>;
        const monthlyDataValues = <?php echo $monthlyDataValues; ?>;

        // --- Yearly Stats (Line Chart) ---
        const yearlyStatsCtx = document.getElementById('yearlyStatsChart').getContext('2d');
        new Chart(yearlyStatsCtx, {
            type: 'line',
            data: {
                labels: monthlyLabels,
                datasets: [{
                    label: 'Total Revenue',
                    data: monthlyDataValues,
                    backgroundColor: 'rgba(74, 144, 226, 0.2)',
                    borderColor: '#4A90E2',
                    borderWidth: 2,
                    pointRadius: 3,
                    pointBackgroundColor: '#4A90E2',
                    pointBorderColor: '#fff',
                    pointHoverRadius: 5,
                    pointHoverBackgroundColor: '#4A90E2',
                    pointHoverBorderColor: '#fff',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false,
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.7)',
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        borderColor: '#4A90E2',
                        borderWidth: 1,
                        cornerRadius: 4,
                        callbacks: {
                            label: function(context) {
                                return new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(context.parsed.y);
                            },
                            title: function(context) {
                                return context[0].label;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            display: true,
                            drawBorder: false,
                            color: 'rgba(200, 200, 200, 0.2)'
                        },
                        ticks: {
                            color: '#666',
                            font: {
                                size: 10
                            },
                             callback: function(value) {
                                return '$' + (value / 1000).toFixed(0) + 'k';
                            }
                        }
                    },
                    x: {
                        grid: {
                            display: false,
                            drawBorder: false
                        },
                        ticks: {
                            color: '#666',
                            font: {
                                size: 10
                            }
                        }
                    }
                }
            }
        });
    });
</script>