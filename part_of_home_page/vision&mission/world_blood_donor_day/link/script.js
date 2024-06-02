document.addEventListener('DOMContentLoaded', function () {
    var map = L.map('map').setView([6.9898, 81.0561], 10); // Center of Badulla District

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    var hospitals = [
        {
            name: "Badulla General Hospital",
            phone: "055-2237001",
            lat: 6.9898,
            lng: 81.0561
        },
        {
            name: "Diyatalawa Base Hospital",
            phone: "055-2267001",
            lat: 6.8261,
            lng: 80.9938
        },
        {
            name: "Haldummulla District Hospital",
            phone: "055-2286001",
            lat: 6.7022,
            lng: 80.9101
        }
        // Add more hospitals here
    ];

    hospitals.forEach(function (hospital) {
        var marker = L.marker([hospital.lat, hospital.lng]).addTo(map);
        marker.bindPopup(
            `<b>${hospital.name}</b><br>` +
            `<a href="tel:${hospital.phone}">${hospital.phone}</a>`
        );
    });
});
