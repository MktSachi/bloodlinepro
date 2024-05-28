// Initialize the map
const map = L.map('map').setView([6.9936, 81.055], 10); // Centered in Uva Province, Sri Lanka

// Add a tile layer to the map (OpenStreetMap)
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19,
}).addTo(map);

// Define hospital locations with corresponding blood type data
const hospitals = [
    { name: 'Badulla Hospital', coords: [6.9896, 81.055], data: [12, 19, 3, 5, 2, 3, 7, 8] },
    { name: 'Diyathalawa Hospital', coords: [6.8295, 80.989], data: [5, 10, 8, 6, 3, 7, 4, 6] },
    { name: 'Welimada Hospital', coords: [6.9072, 80.9488], data: [8, 15, 6, 10, 4, 9, 5, 7] },
    { name: 'Mahiyangana Hospital', coords: [7.3273, 80.9923], data: [7, 13, 5, 9, 3, 6, 4, 8] },
    { name: 'Monaragala Hospital', coords: [6.8716, 81.3458], data: [9, 14, 4, 11, 5, 8, 6, 9] },
    { name: 'Bibila Hospital', coords: [7.1328, 81.2283], data: [6, 12, 5, 8, 3, 7, 5, 6] },
    { name: 'Wellawaya Hospital', coords: [6.7313, 81.1066], data: [10, 18, 6, 12, 4, 11, 7, 10] }
];

// Initialize Chart.js chart
const ctx = document.getElementById('barChart').getContext('2d');
let barChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'],
        datasets: [{
            label: 'Number of Units',
            data: [],
            backgroundColor: 'light red',
            borderColor: 'rgba(255, 99, 132, 1)',
            borderWidth: 1,
            
        }]
    },
    options: {
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});

// Function to update chart data
function updateChart(data) {
    barChart.data.datasets[0].data = data;
    barChart.update();
}

// Add markers for each hospital
hospitals.forEach(hospital => {
    const marker = L.marker(hospital.coords).addTo(map);
    marker.bindPopup(`<b>${hospital.name}</b>`);
    
    // Show chart on marker hover
    marker.on('mouseover', function() {
        updateChart(hospital.data);
        document.getElementById('chart-container').style.display = 'block';
    });

    // Hide chart when mouse leaves marker
    marker.on('mouseout', function() {
        document.getElementById('chart-container').style.display = 'none';
    });
});
