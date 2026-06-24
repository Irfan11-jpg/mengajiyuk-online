import Chart from 'chart.js/auto';

/**
 * Palet warna reusable untuk chart MengajiYuk.
 */
const WARNA = {
    emerald: '#10b981',
    emeraldLight: 'rgba(16, 185, 129, 0.15)',
    amber: '#b45309',
    amberLight: 'rgba(180, 83, 9, 0.15)',
    abu: '#e5e7eb',
};

/**
 * Buat doughnut chart untuk progress hafalan santri.
 *
 * @param {string} canvasId
 * @param {string[]} labels
 * @param {number[]} values
 */
export function buatDoughnutChart(canvasId, labels, values) {
    const canvas = document.getElementById(canvasId);
    if (!canvas) return null;

    return new Chart(canvas, {
        type: 'doughnut',
        data: {
            labels,
            datasets: [
                {
                    data: values,
                    backgroundColor: values.map((v) => (v >= 100 ? WARNA.emerald : WARNA.abu)),
                    borderWidth: 2,
                    borderColor: '#ffffff',
                },
            ],
        },
        options: {
            responsive: true,
            cutout: '70%',
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: { boxWidth: 12, font: { size: 11 } },
                },
            },
        },
    });
}

/**
 * Buat bar chart untuk jumlah setoran per hari (dashboard guru).
 *
 * @param {string} canvasId
 * @param {string[]} labels
 * @param {number[]} values
 */
export function buatBarChart(canvasId, labels, values) {
    const canvas = document.getElementById(canvasId);
    if (!canvas) return null;

    return new Chart(canvas, {
        type: 'bar',
        data: {
            labels,
            datasets: [
                {
                    label: 'Jumlah Setoran',
                    data: values,
                    backgroundColor: WARNA.amberLight,
                    borderColor: WARNA.amber,
                    borderWidth: 1.5,
                    borderRadius: 6,
                },
            ],
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false },
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { stepSize: 1, precision: 0 },
                },
            },
        },
    });
}