document.addEventListener('DOMContentLoaded', (event) => {
    // Animate progress bars
    const progressBars = document.querySelectorAll('.progress-bar');
    progressBars.forEach((bar) => {
        const width = bar.style.width;
        bar.style.width = '0%';
        setTimeout(() => {
            bar.style.width = width;
        }, 500);
    });
});

function initMap() {
    const colombo = { lat: 6.9271, lng: 79.8612 };
    const map = new google.maps.Map(document.getElementById("map"), {
        zoom: 12,
        center: colombo,
    });

    const bloodBank1 = new google.maps.Marker({
        position: { lat: 6.9200, lng: 79.8544 },
        map: map,
        title: "National Blood Center"
    });

}

window.onload = initMap;