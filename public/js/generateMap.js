
document.addEventListener('DOMContentLoaded', function() {
    const map = L.map('map');

    map.setView([51.505, -0.09], 13);

    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '© OpenStreetMap'
    }).addTo(map);

    let marker, circle, zoomed;


    const latitude = document.getElementById("latitude_congregacao").value;
    const longitude = document.getElementById("longitude_congregacao").value;
    const accuracy = 1;

    mostrarDados(latitude, longitude);

    if (marker) {
        map.removeLayer(marker);
        map.removeLayer(circle);
    }

    marker = L.marker([latitude, longitude]).addTo(map);
    circle = L.circle([latitude, longitude], { radius: accuracy }).addTo(map);

    if (!zoomed) {
        zoomed = map.fitBounds(circle.getBounds());
    }
    map.setView([latitude, longitude]);

    function mostrarDados(latitude, longitude) {


        const url = `https://nominatim.openstreetmap.org/reverse?lat=${latitude}&lon=${longitude}&format=json`;

        fetch(url)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`Erro na API: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {

                const address = data.address;
                const street = address.road || "Rua não encontrada";
                const city = address.city || "Cidade não encontrada";
                const suburb = address.suburb || "Bairro não encontrado";
                const state = address.state || "Estado não encontrado";
                const country = address.country || "País não encontrado";


                const resultList = document.getElementById("result_dados");
                resultList.innerHTML = `
            <li><strong>Rua:</strong> ${street}</li>
            <li><strong>Bairro:</strong> ${suburb}</li>
            <li><strong>Cidade:</strong> ${city}</li>
            <li><strong>Estado:</strong> ${state}</li>
            <li><strong>País:</strong> ${country}</li>
          `;
            })
            .catch(error => {
                console.error(error);
                document.getElementById("result_dados").textContent = "Erro ao obter o endereço.";
            });

    }


});


