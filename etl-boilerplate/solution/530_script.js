document.addEventListener('DOMContentLoaded', () => {
    // Beispieltemperaturen für Bern
    const temperatures = [6, 8, 5, 10, 12, 9, 7, 11, 8, 6];
    const labels = temperatures.map((_, index) => `Punkt ${index + 1}`);

    // Erstellt ein Linien-Diagramm mit Chart.js
    const ctx = document.getElementById('weatherChart').getContext('2d');
    const weatherChart = new Chart(ctx, {
        type: 'line', // Diagrammtyp
        data: {
            labels: labels, // Beschriftungen für jeden Datenpunkt
            datasets: [{
                label: 'Temperatur (°C)',
                data: temperatures, // Datenpunkte
                fill: false,
                borderColor: 'rgb(75, 192, 192)',
                tension: 0.1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true // Beginnt die Y-Achse bei 0
                }
            }
        }
    });
});
