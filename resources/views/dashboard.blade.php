<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-2xl mb-4">Welcome to the Dashboard</h3>

                    <!-- Scanned Object List -->
                    <div class="mb-4">
                        <h4 class="text-xl mb-2">Scanned Object List</h4>
                        <canvas id="radarChart" width="400" height="200"></canvas>
                    </div>

                    <div class="mb-4">
                        <h4 class="text-xl mb-2">Total Reduce CO2</h4>
                        <canvas id="co2Chart" width="400" height="200"></canvas>
                    </div>

                    <div class="mb-4">
                        <h4 class="text-xl mb-2">All User Total Reduce CO2</h4>
                        <canvas id="polarChart" width="400" height="200"></canvas>
                    </div>

                    <!-- Temperature Saved -->
                    <div class="mb-4">
                        <h4 class="text-xl mb-2">Temperature Saved</h4>
                        <canvas id="temperatureSavedChart" width="400" height="200"></canvas>
                    </div>

                    <!-- Carbon Generate Per Person Per Day -->
                    <div>
                        <h4 class="text-xl mb-2">Carbon Generate Per Person Per Day</h4>
                        <canvas id="generatcarbonChart" width="400" height="200"></canvas>
                    </div>

                    <!-- <div class="mb-4">
                        <h4 class="text-xl mb-2">Detected Objects</h4>
                        <canvas id="detectedObjectsChart" width="400" height="200"></canvas>
                    </div> -->

                    <button id="openCamera" class="mt-6 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Open Camera
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        // CO2 Reduction Chart Data
        const co2Labels = ['January', 'February', 'March', 'April', 'May', 'June', 'July'];
        const co2Data = [65, 59, 80, 81, 56, 55, 40];

        const co2Ctx = document.getElementById('co2Chart').getContext('2d');

        const co2Config = {
            type: 'bar',
            data: {
                labels: co2Labels,
                datasets: [{
                    label: 'CO2 Reduction (kg)',
                    data: co2Data,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(255, 159, 64, 0.2)',
                        'rgba(255, 205, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(201, 203, 207, 0.2)'
                    ],
                    borderColor: [
                        'rgb(255, 99, 132)',
                        'rgb(255, 159, 64)',
                        'rgb(255, 205, 86)',
                        'rgb(75, 192, 192)',
                        'rgb(54, 162, 235)',
                        'rgb(153, 102, 255)',
                        'rgb(201, 203, 207)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        };

        const co2Chart = new Chart(co2Ctx, co2Config);

        // Temperature Saved Chart Data
        const temperatureLabels = ['January', 'February', 'March', 'April', 'May', 'June', 'July'];
        const temperatureData = [65, 59, 80, 81, 56, 55, 40]; // Example temperature data

        const temperatureCtx = document.getElementById('generatcarbonChart').getContext('2d');

        const temperatureConfig = {
            type: 'line',
            data: {
                labels: temperatureLabels,
                datasets: [{
                    label: 'Temperature Saved (°C)',
                    data: temperatureData,
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1,
                    fill: false
                }]
            },
            options: {
                animations: {
                    tension: {
                        duration: 1000,
                        easing: 'linear',
                        from: 1,
                        to: 0,
                        loop: true
                    }
                },
                scales: {
                    y: {
                        min: 0,
                        max: 100
                    }
                }
            }
        };

        const generatcarbonChart = new Chart(temperatureCtx, temperatureConfig);

        // Scanned Object List Chart Data
        const labels = ['Eating', 'Drinking', 'Sleeping', 'Designing', 'Coding', 'Cycling', 'Running'];

        const radarData = {
            labels: labels,
            datasets: [{
                label: 'My First Radar Dataset',
                data: [65, 59, 90, 81, 56, 55, 40],
                fill: true,
                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                borderColor: 'rgb(255, 99, 132)',
                pointBackgroundColor: 'rgb(255, 99, 132)',
                pointBorderColor: '#fff',
                pointHoverBackgroundColor: '#fff',
                pointHoverBorderColor: 'rgb(255, 99, 132)'
            }, {
                label: 'My Second Dataset',
                data: [28, 48, 40, 19, 96, 27, 100],
                fill: true,
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgb(54, 162, 235)',
                pointBackgroundColor: 'rgb(54, 162, 235)',
                pointBorderColor: '#fff',
                pointHoverBackgroundColor: '#fff',
                pointHoverBorderColor: 'rgb(54, 162, 235)'
            }]
        };

        const radarCtx = document.getElementById('radarChart').getContext('2d');

        const radarConfig = {
            type: 'radar',
            data: radarData,
            options: {
                elements: {
                    line: {
                        borderWidth: 3
                    }
                }
            }
        };

        const radarChart = new Chart(radarCtx, radarConfig);

        // Polar Area Chart Data
        const polarData = {
            labels: ['Category 1', 'Category 2', 'Category 3', 'Category 4'],
            datasets: [{
                label: 'Total Reduced CO2 (kg)',
                data: [11, 16, 7, 3, 14],
                backgroundColor: [
                    'rgb(255, 99, 132)',
                    'rgb(75, 192, 192)',
                    'rgb(255, 205, 86)',
                    'rgb(201, 203, 207)',
                    'rgb(54, 162, 235)'
                ],
            }]
        };

        const polarCtx = document.getElementById('polarChart').getContext('2d');

        const polarConfig = {
            type: 'polarArea',
            data: polarData,
            options: {}
        };

        const polarChart = new Chart(polarCtx, polarConfig);

        // temperatureSavedChart
        const temperatureSavedCtx = document.getElementById('temperatureSavedChart').getContext('2d');

        const data = {
            labels: ['January', 'February', 'March', 'April'],
            datasets: [{
                type: 'bar',
                label: 'Bar Dataset',
                data: [10, 20, 30, 40],
                borderColor: 'rgb(255, 99, 132)',
                backgroundColor: 'rgba(255, 99, 132, 0.2)'
            }, {
                type: 'line',
                label: 'Line Dataset',
                data: [50, 50, 50, 50],
                fill: false,
                borderColor: 'rgb(54, 162, 235)'
            }]
        };

        const config = {
            type: 'scatter',
            data: data,
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        };

        const temperatureSavedChart = new Chart(temperatureSavedCtx, config);

        // Function to display the FormData object
        function displayFormData(data) {
            const showElement = document.getElementById("show");
            let htmlContent = "";

            if (data.Detect.length > 0) {
                for (let i = 0; i < data.Detect.length; i++) {
                    htmlContent += `
            <div style="margin-bottom: 10px; border: 1px solid #ccc; padding: 10px; background-color: #f9f9f9;">
                <strong>Object:</strong> ${data.Detect[i]}<br>
                <strong>Probability:</strong> ${data.Probability[i]}%<br>
                <strong>CO2D:</strong> ${data.CO2D[i]}<br>
                <strong>CO2C:</strong> ${data.CO2C[i]}<br>
                <strong>CO2R:</strong> ${data.CO2R[i]}
            </div>
        `;
                }
            } else {
                htmlContent = "No objects detected.";
            }

            showElement.innerHTML = htmlContent;

            // Update the chart
            const detectedObjectsData = {
                labels: data.Detect,
                datasets: [{
                    label: 'Probability',
                    data: data.Probability,
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 1
                }, {
                    label: 'CO2D',
                    data: data.CO2D,
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }, {
                    label: 'CO2C',
                    data: data.CO2C,
                    backgroundColor: 'rgba(255, 205, 86, 0.2)',
                    borderColor: 'rgba(255, 205, 86, 1)',
                    borderWidth: 1
                }, {
                    label: 'CO2R',
                    data: data.CO2R,
                    backgroundColor: 'rgba(201, 203, 207, 0.2)',
                    borderColor: 'rgba(201, 203, 207, 1)',
                    borderWidth: 1
                }]
            };

            const detectedObjectsCtx = document.getElementById('detectedObjectsChart').getContext('2d');

            const detectedObjectsConfig = {
                type: 'bar',
                data: detectedObjectsData,
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            };

            const detectedObjectsChart = new Chart(detectedObjectsCtx, detectedObjectsConfig);
        }
    </script>

</x-app-layout>