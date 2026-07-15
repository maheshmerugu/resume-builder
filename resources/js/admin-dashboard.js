import {
    Chart,
    ArcElement,
    BarController,
    BarElement,
    CategoryScale,
    DoughnutController,
    Filler,
    Legend,
    LineController,
    LineElement,
    LinearScale,
    PointElement,
    Tooltip,
} from 'chart.js';

Chart.register(
    ArcElement,
    BarController,
    BarElement,
    CategoryScale,
    DoughnutController,
    Filler,
    Legend,
    LineController,
    LineElement,
    LinearScale,
    PointElement,
    Tooltip,
);

function chartColors() {
    const dark = document.documentElement.classList.contains('dark');

    return {
        text: dark ? '#94a3b8' : '#64748b',
        grid: dark ? '#334155' : '#e2e8f0',
        surface: dark ? '#1e293b' : '#ffffff',
    };
}

function baseOptions() {
    const colors = chartColors();

    return {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                labels: {
                    color: colors.text,
                    boxWidth: 12,
                    padding: 16,
                },
            },
            tooltip: {
                backgroundColor: colors.surface,
                titleColor: colors.text,
                bodyColor: colors.text,
                borderColor: colors.grid,
                borderWidth: 1,
            },
        },
        scales: {
            x: {
                ticks: { color: colors.text },
                grid: { color: colors.grid },
            },
            y: {
                ticks: { color: colors.text },
                grid: { color: colors.grid },
                beginAtZero: true,
            },
        },
    };
}

function initAdminDashboardCharts() {
    const root = document.getElementById('admin-dashboard-charts');

    if (!root || !window.adminChartData) {
        return;
    }

    const data = window.adminChartData;

    const revenueCanvas = document.getElementById('revenueChart');
    if (revenueCanvas) {
        new Chart(revenueCanvas, {
            type: 'line',
            data: {
                labels: data.revenueByMonth.labels,
                datasets: [
                    {
                        label: 'Revenue (₹)',
                        data: data.revenueByMonth.values,
                        borderColor: '#f59e0b',
                        backgroundColor: 'rgba(245, 158, 11, 0.15)',
                        fill: true,
                        tension: 0.35,
                        pointRadius: 4,
                        pointBackgroundColor: '#f59e0b',
                    },
                ],
            },
            options: {
                ...baseOptions(),
                plugins: {
                    ...baseOptions().plugins,
                    legend: { display: false },
                },
            },
        });
    }

    const planCanvas = document.getElementById('planRevenueChart');
    if (planCanvas) {
        new Chart(planCanvas, {
            type: 'doughnut',
            data: {
                labels: data.revenueByPlan.labels,
                datasets: [
                    {
                        data: data.revenueByPlan.values,
                        backgroundColor: ['#6366f1', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#06b6d4'],
                        borderWidth: 0,
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: baseOptions().plugins,
            },
        });
    }

    const usersCanvas = document.getElementById('userSignupsChart');
    if (usersCanvas) {
        new Chart(usersCanvas, {
            type: 'bar',
            data: {
                labels: data.userSignups.labels,
                datasets: [
                    {
                        label: 'New users',
                        data: data.userSignups.values,
                        backgroundColor: 'rgba(99, 102, 241, 0.7)',
                        borderRadius: 8,
                    },
                ],
            },
            options: {
                ...baseOptions(),
                plugins: {
                    ...baseOptions().plugins,
                    legend: { display: false },
                },
            },
        });
    }

    const subsCanvas = document.getElementById('subscriptionStatusChart');
    if (subsCanvas) {
        new Chart(subsCanvas, {
            type: 'bar',
            data: {
                labels: data.subscriptionStatus.labels,
                datasets: [
                    {
                        label: 'Subscriptions',
                        data: data.subscriptionStatus.values,
                        backgroundColor: ['#10b981', '#f59e0b', '#ef4444'],
                        borderRadius: 8,
                    },
                ],
            },
            options: {
                ...baseOptions(),
                plugins: {
                    ...baseOptions().plugins,
                    legend: { display: false },
                },
            },
        });
    }
}

document.addEventListener('DOMContentLoaded', initAdminDashboardCharts);
