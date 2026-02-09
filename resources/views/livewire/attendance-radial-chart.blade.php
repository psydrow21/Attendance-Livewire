<div>
    {{-- The Master doesn't talk, he acts. --}}

    {{-- Radial Chart --}}
    <div class="max-w-sm w-full bg-white rounded-lg shadow-sm dark:bg-gray-800 p-4 md:p-6"  wire:ignore>
    <div class="flex justify-between mb-3">
        <div class="flex items-center">
        <div class="flex justify-center items-center">
            <h5 class="text-xl font-bold leading-none text-gray-900 dark:text-white pe-1">Attendance Overall Report</h5>

        </div>
        </div>
    </div>

    <div class="bg-gray-50 dark:bg-gray-700 p-3 rounded-lg">
        <div class="grid grid-cols-3 gap-3 mb-2">
        <dl class="bg-blue-50 dark:bg-gray-600 rounded-lg flex flex-col items-center justify-center h-[78px]">
            <dt class="w-8 h-8 rounded-full bg-blue-100 dark:bg-gray-500 text-blue-600 dark:text-blue-300 text-sm font-medium flex items-center justify-center mb-1">{{ $filter_late_count }}</dt>
            <dd class="text-blue-600 dark:text-blue-300 text-sm font-medium">Late</dd>
        </dl>

        <dl class="bg-orange-50 dark:bg-gray-600 rounded-lg flex flex-col items-center justify-center h-[78px]">
            <dt class="w-8 h-8 rounded-full bg-orange-100 dark:bg-gray-500 text-orange-600 dark:text-orange-300 text-sm font-medium flex items-center justify-center mb-1">{{ $filter_undertime_count }}</dt>
            <dd class="text-orange-600 dark:text-orange-300 text-sm font-medium">Undertime</dd>
        </dl>

        <dl class="bg-teal-50 dark:bg-gray-600 rounded-lg flex flex-col items-center justify-center h-[78px]">
            <dt class="w-8 h-8 rounded-full bg-teal-100 dark:bg-gray-500 text-teal-600 dark:text-teal-300 text-sm font-medium flex items-center justify-center mb-1">{{ $filter_period_count }}</dt>
            <dd class="text-teal-600 dark:text-teal-300 text-sm font-medium">On Time</dd>
        </dl>

        </div>
        <button data-collapse-toggle="more-details" type="button" class="hover:underline text-xs text-gray-500 dark:text-gray-400 font-medium inline-flex items-center">Show more details <svg class="w-2 h-2 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/>
        </svg>
        </button>

        <div id="more-details" class="border-gray-200 border-t dark:border-gray-600 pt-3 mt-3 space-y-2 hidden">
            <dl class="flex items-center justify-between">
                <dt class="text-gray-500 dark:text-gray-400 text-sm font-normal">Average On Time Attendance:</dt>
                <dd class="bg-green-100 text-green-800 text-xs font-medium inline-flex items-center px-2.5 py-1 rounded-md dark:bg-green-900 dark:text-green-300">
                <svg class="w-2.5 h-2.5 me-1.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 14">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13V1m0 0L1 5m4-4 4 4"/>
                </svg> {{ $filter_period_percentage }}%
                </dd>
            </dl>
            <dl class="flex items-center justify-between">
                <dt class="text-gray-500 dark:text-gray-400 text-sm font-normal">Average Late Attendance:</dt>
                <dd class="bg-red-100 text-red-800 text-xs font-medium inline-flex items-center px-2.5 py-1 rounded-md dark:bg-red-900 dark:text-red-300">
                <svg class="w-2.5 h-2.5 me-1.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 14">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13V1m0 0L1 5m4-4 4 4"/>
                </svg> {{ $filter_period_late_percentage }}%
                </dd>

            </dl>
            <dl class="flex items-center justify-between">
                <dt class="text-gray-500 dark:text-gray-400 text-sm font-normal">Average Undertime Attendance:</dt>
                <dd class="bg-orange-100 text-orange-800 text-xs font-medium inline-flex items-center px-2.5 py-1 rounded-md dark:bg-orange-900 dark:text-orange-300">
                <svg class="w-2.5 h-2.5 me-1.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 14">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13V1m0 0L1 5m4-4 4 4"/>
                </svg> {{ $filter_period_undertime_percentage }}%
                </dd>
            </dl>

        </div>
    </div>

    <!-- Radial Chart -->
    <div class="py-6" id="radial-chart"></div>

    </div>

    <script>
    document.addEventListener("DOMContentLoaded", () => {
        // Radial Chart
        const radialChartOption = () => {
        return {
            series: [{{ $filter_period_percentage }} , {{ $filter_period_undertime_percentage }}, {{ $filter_period_late_percentage }}],
            colors: ["#16BDCA" , "#FDBA8C" , "#1C64F2"],
            chart: {
            height: "350px",
            width: "100%",
            type: "radialBar",
            sparkline: {
                enabled: true,
            },
            },
            plotOptions: {
            radialBar: {
                track: {
                background: '#E5E7EB',
                },
                dataLabels: {
                show: false,
                },
                hollow: {
                margin: 0,
                size: "32%",
                }
            },
            },
            grid: {
            show: false,
            strokeDashArray: 4,
            padding: {
                left: 2,
                right: 2,
                top: -23,
                bottom: -20,
            },
            },
            labels: ["On Time", "Undertime", "Late"],
            legend: {
            show: true,
            position: "bottom",
            fontFamily: "Inter, sans-serif",
            },
            tooltip: {
            enabled: true,
            x: {
                show: false,
            },
            },
            yaxis: {
            show: false,
            labels: {
                formatter: function (value) {
                return value + '%';
                }
            }
            }
        }
        }

        if (document.getElementById("radial-chart") && typeof ApexCharts !== 'undefined') {
        const chart = new ApexCharts(document.querySelector("#radial-chart"), radialChartOption());
        chart.render();
        }
    });

    //     document.addEventListener('livewire:navigated', () => {
    //     // reinitialize JS, refetch data, reset state

    //     // 🔥 DESTROY OLD CHART
    //     if (window.radialChart) {
    //         window.radialChart.destroy();
    //         window.radialChart = null;
    //     }

    //     // Radial Chart
    //     const radialChartOption = () => {
    //     return {
    //         series: [{{ $filter_period_percentage }} , {{ $filter_period_undertime_percentage }}, {{ $filter_period_late_percentage }}],
    //         colors: ["#16BDCA" , "#FDBA8C" , "#1C64F2"],
    //         chart: {
    //         height: "350px",
    //         width: "100%",
    //         type: "radialBar",
    //         sparkline: {
    //             enabled: true,
    //         },
    //         },
    //         plotOptions: {
    //         radialBar: {
    //             track: {
    //             background: '#E5E7EB',
    //             },
    //             dataLabels: {
    //             show: false,
    //             },
    //             hollow: {
    //             margin: 0,
    //             size: "32%",
    //             }
    //         },
    //         },
    //         grid: {
    //         show: false,
    //         strokeDashArray: 4,
    //         padding: {
    //             left: 2,
    //             right: 2,
    //             top: -23,
    //             bottom: -20,
    //         },
    //         },
    //         labels: ["On Time", "Undertime", "Late"],
    //         legend: {
    //         show: true,
    //         position: "bottom",
    //         fontFamily: "Inter, sans-serif",
    //         },
    //         tooltip: {
    //         enabled: true,
    //         x: {
    //             show: false,
    //         },
    //         },
    //         yaxis: {
    //         show: false,
    //         labels: {
    //             formatter: function (value) {
    //             return value + '%';
    //             }
    //         }
    //         }
    //     }
    //     }

    //     if (document.getElementById("radial-chart") && typeof ApexCharts !== 'undefined') {
    //     const chart = new ApexCharts(document.querySelector("#radial-chart"), radialChartOption());
    //     chart.render();
    //     }
    // });
    </script>
</div>
