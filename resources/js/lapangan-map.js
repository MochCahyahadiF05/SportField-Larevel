import L from 'leaflet';
import 'leaflet/dist/leaflet.css';
import markerIcon2x from 'leaflet/dist/images/marker-icon-2x.png';
import markerIcon from 'leaflet/dist/images/marker-icon.png';
import markerShadow from 'leaflet/dist/images/marker-shadow.png';

// Fix default marker icon Leaflet yang sering rusak kalau di-bundle lewat Vite/Webpack.
delete L.Icon.Default.prototype._getIconUrl;
L.Icon.Default.mergeOptions({
    iconRetinaUrl: markerIcon2x,
    iconUrl: markerIcon,
    shadowUrl: markerShadow,
});

function debounce(fn, delay) {
    let timer = null;
    return (...args) => {
        clearTimeout(timer);
        timer = setTimeout(() => fn(...args), delay);
    };
}

async function reverseGeocode(lat, lng) {
    const url = `https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&zoom=18&addressdetails=1`;
    const response = await fetch(url, { headers: { Accept: 'application/json' } });

    if (!response.ok) {
        throw new Error('Gagal mengambil alamat dari koordinat.');
    }

    const data = await response.json();
    return data.display_name || '';
}

async function searchAddress(query) {
    const url = `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}&countrycodes=id&addressdetails=1&limit=6`;
    const response = await fetch(url, { headers: { Accept: 'application/json' } });

    if (!response.ok) {
        throw new Error('Gagal mencari alamat.');
    }

    return response.json();
}

function initLapanganMap() {
    const mapEl = document.getElementById('lapanganMap');

    // Kalau elemen peta gak ada di halaman ini (misal bukan halaman create/edit lapangan), skip.
    if (!mapEl) {
        return;
    }

    const defaultLat = parseFloat(mapEl.dataset.defaultLat) || -6.9175;
    const defaultLng = parseFloat(mapEl.dataset.defaultLng) || 107.6191;

    const latInput = document.getElementById('latitudeInput');
    const lngInput = document.getElementById('longitudeInput');
    const alamatInput = document.getElementById('alamatInput');
    const searchInput = document.getElementById('lapanganMapSearch');
    const searchResults = document.getElementById('lapanganMapSearchResults');

    const initialLat = parseFloat(latInput.value) || defaultLat;
    const initialLng = parseFloat(lngInput.value) || defaultLng;
    const hasInitialCoordinate = Boolean(latInput.value && lngInput.value);

    const map = L.map(mapEl).setView([initialLat, initialLng], hasInitialCoordinate ? 15 : 12);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; OpenStreetMap contributors',
    }).addTo(map);

    let marker = null;

    function placeMarker(lat, lng, { autofillAlamat = true } = {}) {
        latInput.value = lat.toFixed(7);
        lngInput.value = lng.toFixed(7);

        if (marker) {
            marker.setLatLng([lat, lng]);
        } else {
            marker = L.marker([lat, lng], { draggable: true }).addTo(map);
            marker.on('dragend', () => {
                const pos = marker.getLatLng();
                placeMarker(pos.lat, pos.lng);
            });
        }

        if (autofillAlamat && alamatInput) {
            reverseGeocode(lat, lng)
                .then((address) => {
                    if (address) {
                        alamatInput.value = address;
                    }
                })
                .catch(() => {
                    // Diamkan saja kalau reverse geocode gagal — alamat tetap bisa diisi manual.
                });
        }
    }

    // Titik awal (misal saat edit, koordinat sudah ada) TIDAK usah auto-reverse-geocode,
    // supaya alamat yang sudah ditulis admin sebelumnya tidak tertimpa saat halaman baru dibuka.
    if (hasInitialCoordinate) {
        placeMarker(initialLat, initialLng, { autofillAlamat: false });
    }

    map.on('click', (event) => {
        placeMarker(event.latlng.lat, event.latlng.lng);
    });

    function syncFromInputs() {
        const lat = parseFloat(latInput.value);
        const lng = parseFloat(lngInput.value);

        if (!Number.isNaN(lat) && !Number.isNaN(lng)) {
            placeMarker(lat, lng, { autofillAlamat: false });
            map.setView([lat, lng], map.getZoom());
        }
    }

    latInput.addEventListener('change', syncFromInputs);
    lngInput.addEventListener('change', syncFromInputs);

    // Perbaiki rendering peta yang kadang blank karena container belum
    // punya ukuran final saat pertama kali di-render.
    setTimeout(() => map.invalidateSize(), 200);

    // ---------- Cari alamat ----------
    if (searchInput && searchResults) {
        const showMessage = (text) => {
            searchResults.innerHTML = '';
            const item = document.createElement('li');
            item.className = 'px-4 py-3 text-sm text-gray-400';
            item.textContent = text;
            searchResults.appendChild(item);
            searchResults.classList.remove('hidden');
        };

        const runSearch = debounce((query) => {
            if (!query || query.trim().length < 3) {
                searchResults.innerHTML = '';
                searchResults.classList.add('hidden');
                return;
            }

            showMessage('Mencari...');

            searchAddress(query.trim())
                .then((results) => {
                    searchResults.innerHTML = '';

                    if (results.length === 0) {
                        showMessage('Alamat tidak ditemukan');
                        return;
                    }

                    results.forEach((result) => {
                        const item = document.createElement('li');
                        item.className = 'cursor-pointer px-4 py-2.5 text-sm hover:bg-green-50';
                        item.textContent = result.display_name;
                        item.addEventListener('click', () => {
                            const lat = parseFloat(result.lat);
                            const lng = parseFloat(result.lon);

                            map.setView([lat, lng], 16);
                            placeMarker(lat, lng, { autofillAlamat: false });

                            if (alamatInput) {
                                alamatInput.value = result.display_name;
                            }

                            searchInput.value = result.display_name;
                            searchResults.innerHTML = '';
                            searchResults.classList.add('hidden');
                        });
                        searchResults.appendChild(item);
                    });

                    searchResults.classList.remove('hidden');
                })
                .catch((error) => {
                    // eslint-disable-next-line no-console
                    console.error('Gagal mencari alamat (cek tab Network/Console):', error);
                    showMessage('Gagal mencari alamat. Cek koneksi internet / buka Console (F12) untuk detail error.');
                });
        }, 400);

        searchInput.addEventListener('input', (event) => runSearch(event.target.value));

        document.addEventListener('click', (event) => {
            if (!event.target.closest('#lapanganMapSearchWrapper')) {
                searchResults.classList.add('hidden');
            }
        });
    }
}

document.addEventListener('DOMContentLoaded', initLapanganMap);