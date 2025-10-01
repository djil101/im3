document.addEventListener("DOMContentLoaded", () => {
  const apiUrl = "https://im3.im-abc.ch/etl-boilerplate/solution/530_unload.php?limit=10"; // URL bei Bedarf anpassen

  fetch(apiUrl)
    .then((response) => response.json())
    .then((data) => {
      if (Array.isArray(data) && data.length) {
        const temperatures = data.map((item) => item.temperature_celsius);
        const labels = data.map((item) => new Date(item.created_at).toLocaleString("de-DE"));

        const ctx = document.getElementById("weatherChart").getContext("2d");
        new Chart(ctx, {
          type: "line",
          data: {
            labels: labels.reverse(), // Umkehren, um die ältesten Daten zuerst anzuzeigen
            datasets: [
              {
                label: "Temperatur (°C) für Bern",
                data: temperatures.reverse(), // Sicherstellen, dass die Datenreihenfolge den Beschriftungen entspricht
                fill: false,
                borderColor: "rgb(75, 192, 192)",
                tension: 0.1,
              },
            ],
          },
          options: {
            scales: {
              y: {
                beginAtZero: false,
              },
            },
          },
        });
      } else {
        console.log("Keine Daten gefunden");
      }
    })
    .catch((error) => console.error("Fehler beim Abrufen:", error));
});
