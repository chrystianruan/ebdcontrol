function obterLocalizacao() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition((position) => {
            document.getElementById("latitude").value = position.coords.latitude;
            document.getElementById("longitude").value = position.coords.longitude;

            mostrarDados(position.coords.latitude, position.coords.longitude);
        }, (error) => {
            alert("É necessário permitir a localização cadastrar o endereço.");
            console.error("Erro ao obter a localização:", error);
        });
    } else {
        console.log("Geolocation is not supported by this browser.");
    }
}

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

            document.getElementById("form-localizacao").style = 'display: block';



            const resultList = document.getElementById("result");
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
            document.getElementById("result").textContent = "Erro ao obter o endereço.";
        });

}

document.getElementById("btn-obter-localizacao").addEventListener("click", obterLocalizacao);
