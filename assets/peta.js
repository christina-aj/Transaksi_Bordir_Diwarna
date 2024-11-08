// Inisialisasi peta dan atur pusatnya di Indonesia
var map = L.map('map').setView([-2.548926, 118.0148634], 5); // Koordinat pusat Indonesia

// Tambahkan layer peta dari OpenStreetMap
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
}).addTo(map);

// Contoh marker: Jakarta
L.marker([-6.2088, 106.8456]).addTo(map) // Koordinat Jakarta
    .bindPopup('Jakarta, Indonesia')
    .openPopup();

// Tambahkan marker lain di lokasi-lokasi di Indonesia
var locations = [
    { title: "Yogyakarta", position: [-7.797068, 110.370529] },
    { title: "Manado", position: [1.48218, 124.84899] },
    { title: "Bali", position: [-8.409518, 115.188919] }
];

// Loop untuk menambahkan marker pada setiap lokasi
locations.forEach(function (location) {
    L.marker(location.position).addTo(map)
        .bindPopup(location.title);
});