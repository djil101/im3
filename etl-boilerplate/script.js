document.addEventListener("DOMContentLoaded", () => {
  const apiUrl = "https://velometer-im3.sonnenschlau.ch/etl-boilerplate/csunload.php";

  const canvas = document.getElementById("velometerChart");
  if (!canvas) {
    console.error("Canvas mit id=velometerChart nicht gefunden");
    return;
  }
  const ctx = canvas.getContext("2d");

  let myChart = null;
  let currentStation = "Bahnhofplatz"; // Default
  let currentWeekday = 0; // Default: Montag

  // Event-Listener für Standortwahl
  document.querySelectorAll(".location-option").forEach((btn) => {
    btn.addEventListener("click", (e) => {
      e.preventDefault();
      currentStation = e.target.textContent.trim();
      console.log("Station gewählt:", currentStation);
      loadData();
      updateLiveCount();
    });
  });

  // Event-Listener für Wochentagswahl
  document.querySelectorAll(".day-option").forEach((btn) => {
    btn.addEventListener("click", (e) => {
      e.preventDefault();
      const dayMap = {
        Mo: 0,
        Di: 1,
        Mi: 2,
        Do: 3,
        Fr: 4,
        Sa: 5,
        So: 6,
      };
      const day = e.target.textContent.trim();
      currentWeekday = dayMap[day] ?? 0;
      console.log("Wochentag gewählt:", day, "→", currentWeekday);
      loadData();
    });
  });

  // Initiales Laden
  loadData();
  updateLiveCount();
  // Live-Count alle 5 Minuten aktualisieren
  setInterval(updateLiveCount, 5 * 60 * 1000);

  function loadData() {
    const url = `${apiUrl}?action=avg_by_weekday&weekday=${currentWeekday}`;
    console.log("Lade Daten von:", url);

    fetch(url)
      .then((res) => {
        if (!res.ok) throw new Error(`HTTP ${res.status}`);
        return res.json();
      })
      .then((json) => {
        console.log("API-Response:", json);

        if (!json.data) {
          throw new Error("Keine 'data' im JSON gefunden");
        }

        const stationData = json.data[currentStation];
        if (!stationData) {
          console.warn(`Keine Daten für Station "${currentStation}" gefunden`);
          return;
        }

        console.log("Station-Daten:", stationData);

        // Labels: 00 bis 23
        const labels = Array.from({ length: 24 }, (_, i) => String(i).padStart(2, "0"));
        
        // Prüfe ob stationData ein Array oder Objekt ist
        let values;
        if (Array.isArray(stationData)) {
          values = stationData;
        } else {
          values = Array.from({ length: 24 }, (_, i) => stationData[String(i)] ?? null);
        }

        console.log("Werte für Chart:", values);

        const dataset = {
          label: `${currentStation} (Durchschnitt)`,
          data: values,
          fill: false,
          borderColor: getCityColor(currentStation),
          backgroundColor: getCityColor(currentStation),
          tension: 0.3,
          spanGaps: true,
          pointRadius: 3,
        };

        // Chart aktualisieren oder neu erstellen
        if (myChart) {
          myChart.data.labels = labels;
          myChart.data.datasets = [dataset];
          myChart.update();
        } else {
          myChart = new Chart(ctx, {
            type: "line",
            data: { labels, datasets: [dataset] },
            options: {
              responsive: true,
              maintainAspectRatio: true,
              scales: {
                y: {
                  beginAtZero: false,
                  title: { display: true, text: "Verfügbare Bikes" },
                },
                x: { title: { display: true, text: "Uhrzeit" } },
              },
              plugins: {
                legend: { position: "top" },
                tooltip: {
                  callbacks: {
                    label: (ctx) => `${ctx.dataset.label}: ${ctx.parsed.y ?? "keine Daten"}`,
                  },
                },
              },
            },
          });
        }

        // Beste Zeit berechnen
        updateBestTime(values);
      })
      .catch((err) => {
        console.error("Fetch/Parse-Fehler:", err);
        const msg = document.createElement("p");
        msg.textContent = "Ups, die Velodaten konnten nicht geladen werden.";
        msg.style.color = "red";
        canvas.parentElement.appendChild(msg);
      });
  }

  function updateLiveCount() {
    const url = `${apiUrl}?action=current`;

    fetch(url)
      .then((res) => {
        if (!res.ok) throw new Error(`HTTP ${res.status}`);
        return res.json();
      })
      .then((data) => {
        console.log("Live-Daten:", data);

        // Finde die Daten für den aktuell gewählten Standort
        const stationData = data.find((row) => row.station_name === currentStation);
        
        const counterElement = document.querySelector(".counter");
        if (counterElement && stationData) {
          counterElement.textContent = stationData.bike_available_to_rent || "0";
        }
      })
      .catch((err) => {
        console.error("Live-Count-Fehler:", err);
      });
  }

  function updateBestTime(values) {
    console.log("Berechne beste Zeit für Werte:", values);
    
    // Filtere null-Werte raus und erstelle Array mit [stunde, wert]
    const validData = values
      .map((val, hour) => ({ hour, val }))
      .filter((d) => d.val !== null && d.val !== undefined);

    console.log("Gültige Daten:", validData);

    if (validData.length === 0) {
      const timeBox = document.querySelector(".time-box");
      if (timeBox) {
        timeBox.innerHTML = "Keine Daten verfügbar";
      }
      return;
    }

    // Sortiere nach Wert absteigend
    validData.sort((a, b) => b.val - a.val);

    // Nimm die besten 30% der Stunden (mindestens 3, maximal 8)
    const topCount = Math.max(3, Math.min(8, Math.ceil(validData.length * 0.3)));
    const topHours = validData.slice(0, topCount).map((d) => d.hour);
    topHours.sort((a, b) => a - b);

    console.log("Top Stunden:", topHours);

    // Gruppiere zusammenhängende Stunden
    const ranges = [];
    let start = topHours[0];
    let end = topHours[0];

    for (let i = 1; i < topHours.length; i++) {
      if (topHours[i] === end + 1) {
        end = topHours[i];
      } else {
        ranges.push({ start, end });
        start = topHours[i];
        end = topHours[i];
      }
    }
    ranges.push({ start, end });

    console.log("Zeiträume:", ranges);

    // Maximal 2 Zeiträume anzeigen
    const displayRanges = ranges.slice(0, 2);

    // Formatiere als Text
    const text = displayRanges
      .map((r) => {
        if (r.start === r.end) {
          return `${String(r.start).padStart(2, "0")}:00`;
        } else {
          return `${String(r.start).padStart(2, "0")}:00 - ${String(r.end + 1).padStart(2, "0")}:00`;
        }
      })
      .join(" / ");

    console.log("Beste Zeit Text:", text);

    // Aktualisiere HTML
    const timeBox = document.querySelector(".time-box");
    if (timeBox) {
      timeBox.innerHTML = text;
    }
  }

  function getCityColor(station_name) {
    const cityColors = {
      Bahnhofplatz: "#ffcf33",
      "Obere Au": "#33a3ff",
      Kantonsspital: "#2edc07",
    };
    return cityColors[station_name] || getRandomColor();
  }

  function getRandomColor() {
    const letters = "0123456789ABCDEF";
    let color = "#";
    for (let i = 0; i < 6; i++) color += letters[Math.floor(Math.random() * 16)];
    return color;
  }
});