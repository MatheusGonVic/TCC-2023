
function toggleMapAndLocation() {
    var addLocationSelect = document.getElementById('add_location');
    var mapContainer = document.getElementById('map-container');
    var locationContainer = document.getElementById('location-container');

    if (addLocationSelect.value === 'yes') {
        mapContainer.style.display = 'block';
        locationContainer.style.display = 'block';
        initializeMap();
    } else {
        mapContainer.style.display = 'none';
        locationContainer.style.display = 'none';
    }
}

function toggleMap() {
    var addLocationSelect = document.getElementById('add_location');
    var mapContainer = document.getElementById('map-container');

    if (addLocationSelect.value === 'yes') {
        mapContainer.style.display = 'block';
        initializeMap();
    } else {
        mapContainer.style.display = 'none';
    }
}

function initializeMap() {
    var map = L.map('map').setView([0, 0], 2);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    var marker = L.marker([0, 0], { draggable: true }).addTo(map);

    marker.on('dragend', function (event) {
        var position = marker.getLatLng();
        document.getElementById('latitude').value = position.lat;
        document.getElementById('longitude').value = position.lng;
    });

    var locationInput = document.getElementById('localizacao');
    locationInput.addEventListener('input', function () {
        var address = this.value;
        if (address) {
            fetch('https://nominatim.openstreetmap.org/search?format=json&q=' + encodeURIComponent(address))
                .then(response => response.json())
                .then(data => {
                    if (data.length > 0) {
                        var lat = parseFloat(data[0].lat);
                        var lon = parseFloat(data[0].lon);
                        marker.setLatLng([lat, lon]);
                        map.setView([lat, lon], 10);
                        document.getElementById('latitude').value = lat;
                        document.getElementById('longitude').value = lon;

                        // Atualize o campo localizacao com o texto digitado
                        document.getElementById('localizacao').value = address;
                    }
                })
                .catch(error => {
                    console.error('Error fetching location:', error);
                });
        }
    });
}
