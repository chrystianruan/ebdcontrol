navigator.geolocation.getCurrentPosition((position) => {
    document.getElementById("latitude").value = position.coords.latitude;
    document.getElementById("longitude").value = position.coords.longitude;


}, (error) => {
    alert("É necessário permitir a localização para marcar a presença");
    console.error("Erro ao obter a localização:", error);
});
