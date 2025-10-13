document.addEventListener("DOMContentLoaded", () => {
  const apiUrl = "https://velometer-im3.sonnenschlau.ch/etl-boilerplate/csunload.php";
  
  const canvas = document.getElementById("velometerChart");
  if (!canvas) {
    console.error("Canvas mit id=velometerChart nicht gefunden");
    return;
  }
  const ctx = canvas.getContext("2d");

  let chartInstance = null; // Chart-Instanz speichern für Updates
  let currentStation = "Bahnhofplatz"; // Standardwert
  let currentWeekday = 0; // Montag (0=Mo, 1=Di, ..., 6=So)

  // Mapping: Deutscher Wochentagsname -> Weekday-Number (für MySQL WEEKDAY())
  const weekdayMap = {
    "Montag": 0,
    "Dienstag": 1,
    "Mittwoch": 2,
    "Donnerstag": 3,
    "Freitag": 4,
    "Samstag": 5,
    "Sonntag": 6
  };

  // Initiales Laden
  loadChartData();

  // Event-Listener für Standort-Auswahl
  const locationOptions = document.querySelectorAll('.location-option');
  locationOptions.forEach(option => {
    option.addEventListener('click', (e) => {
      const selectedLocation = e.target.textContent.trim();
      currentStation = selectedLocation;
      loadChartData();
    });
  });

  // Event-Listener für Wochentags-Auswahl
  const dayOptions = document.querySelectorAll('.day-option');
  dayOptions.forEach(option => {
    option.addEventListener('click', (e) => {
      const selectedDay = e.target.textContent.trim();
      currentWeekday = weekdayMap[selectedDay];
      loadChartData();
    });
  });

  function loadChartData() {
    const url = `${apiUrl}?action=avg_by_weekday&weekday=${currentWeekday}`;
    
    fetch(url)
      .then((res) => {
        if (!res.ok) throw new Error(`HTTP ${res.status}`);
        return res.json();
      })
      .then((response) => {
        console.log("API Response:", response);

        if (!response.data || typeof response.data !== 'object') {
          throw new Error("API liefert kein 'data'-Objekt");
        }

        // Prüfen, ob der gewählte Standort in den Daten vorhanden ist
        if (!response.data[currentStation]) {
          console.warn(`Keine Daten für Station "${currentStation}" gefunden`);
          updateChart([], []);
          return;
        }

        // Daten für den gewählten Standort extrahieren
        const stationData = response.data[currentStation];
        
        // Labels: 00:00 bis 23:00
        const labels = [];
        const values = [];
        
        for (let hour = 0; hour < 24; hour++) {
          labels.push(`${String(hour).padStart(2, '0')}:00`);
          const avgValue = stationData[String(hour)];
          values.push(avgValue !== null ? parseFloat(avgValue.toFixed(2)) : null);
        }

        updateChart(labels, values);
      })
      .catch((err) => {
        console.error("Fetch/Parse-Fehler:", err);
        const msg = document.createElement("p");
        msg.textContent = "Ups, die Velodaten konnten nicht geladen werden.";
        msg.style.color = "red";
        canvas.parentElement.appendChild(msg);
      });
  }

  function updateChart(labels, values) {
    const dataset = {
      label: `${currentStation} - Durchschnitt`,
      data: values,
      fill: false,
      borderColor: getCityColor(currentStation),
      backgroundColor: getCityColor(currentStation),
      tension: 0.3,
      spanGaps: true,
      pointRadius: 3,
      pointHoverRadius: 5,
    };

    // Beste Zeit berechnen und anzeigen
    updateBestTime(values);

    if (chartInstance) {
      // Chart existiert bereits -> Update
      chartInstance.data.labels = labels;
      chartInstance.data.datasets = [dataset];
      chartInstance.update();
    } else {
      // Chart erstmalig erstellen
      chartInstance = new Chart(ctx, {
        type: "line",
        data: {
          labels: labels,
          datasets: [dataset],
        },
        options: {
          responsive: true,
          maintainAspectRatio: true,
          interaction: { 
            mode: "index", 
            intersect: false 
          },
          scales: {
            y: {
              beginAtZero: true,
              title: { 
                display: true, 
                text: "Ø Verfügbare Bikes",
                font: { size: 14 }
              },
              ticks: {
                stepSize: 2
              }
            },
            x: {
              title: { 
                display: true, 
                text: "Uhrzeit",
                font: { size: 14 }
              },
            },
          },
          plugins: {
            legend: { 
              position: "top",
              labels: {
                font: { size: 13 }
              }
            },
            tooltip: {
              callbacks: {
                label: (ctx) => {
                  const value = ctx.parsed.y;
                  return value !== null 
                    ? `Ø ${value.toFixed(1)} Bikes` 
                    : "Keine Daten";
                },
              },
            },
          },
        },
      });
    }
  }

  function getCityColor(station_name) {
    const cityColors = {
      "Bahnhofplatz": "#ffcf33",
      "Obere Au": "#33a3ff",
      "Kantonsspital": "#2edc07",
    };
    return cityColors[station_name] || "#999999";
  }

  function updateBestTime(values) {
    // Finde die besten Zeiträume (höchste Verfügbarkeit)
    const timeBox = document.querySelector('.time-box');
    
    if (!timeBox) return;

    // Filtere null-Werte raus und erstelle Array mit {hour, value}
    const hoursWithValues = values
      .map((val, hour) => ({ hour, value: val }))
      .filter(item => item.value !== null);

    if (hoursWithValues.length === 0) {
      timeBox.textContent = "Keine Daten verfügbar";
      return;
    }

    // Sortiere nach Verfügbarkeit (höchste zuerst)
    hoursWithValues.sort((a, b) => b.value - a.value);

    // Nimm die Top 30% der Stunden (mindestens 3, maximal 8)
    const topCount = Math.max(3, Math.min(8, Math.ceil(hoursWithValues.length * 0.3)));
    const topHours = hoursWithValues.slice(0, topCount).map(item => item.hour).sort((a, b) => a - b);

    // Gruppiere zusammenhängende Stunden zu Zeiträumen
    const timeRanges = [];
    let rangeStart = topHours[0];
    let rangeEnd = topHours[0];

    for (let i = 1; i < topHours.length; i++) {
      if (topHours[i] === rangeEnd + 1) {
        // Fortsetzung des aktuellen Zeitraums
        rangeEnd = topHours[i];
      } else {
        // Neuer Zeitraum beginnt
        timeRanges.push({ start: rangeStart, end: rangeEnd });
        rangeStart = topHours[i];
        rangeEnd = topHours[i];
      }
    }
    // Letzten Zeitraum hinzufügen
    timeRanges.push({ start: rangeStart, end: rangeEnd });

    // Formatiere Zeiträume als String
    const formattedRanges = timeRanges.map(range => {
      const startStr = `${String(range.start).padStart(2, '0')}:00`;
      const endStr = `${String(range.end + 1).padStart(2, '0')}:00`;
      return range.start === range.end ? startStr : `${startStr} - ${endStr}`;
    });

    // Zeige maximal 2 Zeiträume an
    const displayText = formattedRanges.slice(0, 2).join(' / ');
    timeBox.textContent = displayText;
  }
});