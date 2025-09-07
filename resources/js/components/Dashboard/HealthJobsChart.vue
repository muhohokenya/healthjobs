<template>
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Health Jobs by Location</h3>
        <div class="h-80">
            <LineChartJS
                id="health-jobs-chart"
                :options="chartOptions"
                :data="chartData"
            />
        </div>
    </div>
</template>

<script lang="ts">
import { Line as LineChartJS } from 'vue-chartjs'
import {
    Chart as ChartJS,
    Title,
    Tooltip,
    Legend,
    LineElement,
    CategoryScale,
    LinearScale,
    PointElement
} from 'chart.js'

ChartJS.register(Title, Tooltip, Legend, LineElement, CategoryScale, LinearScale, PointElement)

interface HealthJobData {
    location: string;
    count: number;
}

interface ChartDataProps {
    labels: string[];
    data: number[];
}

export default {
    name: 'HealthJobsChart',
    components: { LineChartJS },
    props: {
        healthJobsData: {
            type: Array as () => HealthJobData[],
            default: () => []
        },
        chartDataProp: {
            type: Object as () => ChartDataProps,
            default: () => ({ labels: [], data: [] })
        }
    },
    computed: {
        chartData() {
            // Use props data if available, otherwise use default
            const labels = this.chartDataProp.labels.length > 0
                ? this.chartDataProp.labels
                : this.healthJobsData.map(item => item.location);

            const data = this.chartDataProp.data.length > 0
                ? this.chartDataProp.data
                : this.healthJobsData.map(item => item.count);

            return {
                labels,
                datasets: [
                    {
                        label: 'Health Jobs Count',
                        data,
                        backgroundColor: 'rgba(34, 197, 94, 0.2)',
                        borderColor: 'rgba(34, 197, 94, 1)',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: 'rgba(34, 197, 94, 1)',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 5,
                        pointHoverRadius: 7
                    }
                ]
            }
        },
        chartOptions() {
            return {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    title: {
                        display: false
                    },
                    legend: {
                        display: true,
                        position: 'bottom' as const,
                        labels: {
                            usePointStyle: true,
                            padding: 20
                        }
                    },
                    tooltip: {
                        mode: 'index' as const,
                        intersect: false,
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        borderColor: 'rgba(34, 197, 94, 1)',
                        borderWidth: 1,
                        callbacks: {
                            label: (context: any) => {
                                return `${context.dataset.label}: ${context.parsed.y} jobs`;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            display: true,
                            color: 'rgba(0, 0, 0, 0.1)'
                        },
                        ticks: {
                            stepSize: 1,
                            callback: function(value: any) {
                                return Math.floor(value) === value ? value : '';
                            }
                        },
                        title: {
                            display: true,
                            text: 'Number of Jobs'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        },
                        title: {
                            display: true,
                            text: 'Locations'
                        },
                        ticks: {
                            maxRotation: 45,
                            minRotation: 0
                        }
                    }
                },
                interaction: {
                    mode: 'nearest' as const,
                    axis: 'x' as const,
                    intersect: false
                },
                elements: {
                    point: {
                        radius: 4,
                        hoverRadius: 6
                    }
                }
            }
        }
    }
}
</script>
