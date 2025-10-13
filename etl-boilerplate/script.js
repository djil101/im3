document.addEventListener("DOMContentLoaded", () => {
  const apiUrl = "https://velometer-im3.sonnenschlau.ch/etl-boilerplate/csunload.php";

  const canvas = document.getElementById("velometerChart");
  if (!canvas) {
    console.error("Canvas mit id=velometerChart nicht gefunden");
    return;
  }
  const ctx = canvas.getContext("2d");

  fetch(apiUrl)
    .then((res) => {
      if (!res.ok) throw new Error(`HTTP ${res.status}`);
      return res.json();
    })
    .then((data) => {
      console.log("Rohdaten:", data);

      if (!Array.isArray(data)) {
        throw new Error("API liefert kein Array. Erwartet: Array von Rows {station_name, date, bike_available_to_rent, …}");
      }

      // 1) Nach Station gruppieren
      const byStation = data.reduce((acc, row) => {
        const name = row.station_name;
        if (!acc[name]) acc[name] = [];
        acc[name].push(row);
        return acc;
      }, {});

      // 2) Zeitstempel sammeln & sortieren (globales, einheitliches Label-Set)
      const tsSet = new Set();
      data.forEach((row) => tsSet.add(row.date));
      const labelsRaw = Array.from(tsSet).sort((a, b) => new Date(a) - new Date(b));

      // 3) Schoen formatierte Labels (z. B. 06.10.2025 07:00)
      const labels = labelsRaw.map((d) =>
        new Date(d.replace(" ", "T")).toLocaleString("de-CH", {
          year: "numeric",
          month: "2-digit",
          day: "2-digit",
          hour: "2-digit",
          minute: "2-digit",
        })
      );

      // 4) Datensaetze je Station an Labels ausrichten
      const datasets = Object.entries(byStation).map(([stationName, rows]) => {
        // Map fuer schnellen Zugriff: isoString -> row
        const map = new Map(rows.map((r) => [r.date, r]));
        const series = labelsRaw.map((ts) => {
          const hit = map.get(ts);
          return hit ? Number(hit.bike_available_to_rent) : null; // Luecken als null
        });

        return {
          label: stationName,
          data: series,
          fill: false,
          borderColor: getCityColor(stationName),
          tension: 0.1,
          spanGaps: true, // null-Werte uebergehen
          pointRadius: 2,
        };
      });

      // 5) Chart rendern
      new Chart(ctx, {
        type: "line",
        data: {
          labels,
          datasets,
        },
        options: {
          responsive: true,
          interaction: { mode: "nearest", intersect: false },
          scales: {
            y: {
              beginAtZero: false,
              title: { display: true, text: "Verfuegbare Bikes" },
            },
            x: {
              title: { display: true, text: "Zeit" },
            },
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
    })
    .catch((err) => {
      console.error("Fetch/Parse-Fehler:", err);
      // Optional: Fallback ins UI schreiben
      const msg = document.createElement("p");
      msg.textContent = "Ups, die Velodaten konnten nicht geladen werden.";
      canvas.parentElement.appendChild(msg);
    });

  function getCityColor(station_name) {
    // exakte Namen wie sie im JSON stehen
    const cityColors = {
      "Bahnhofplatz": "#ffcf33",
      "Obere Au": "#33a3ff",
      "Kantonsspital": "#2edc07",
    };
    return cityColors[station_name] || getRandomColor();
  }

  function getRandomColor() {
    const letters = "0123456789ABCDEF";
    let color = "#";
    for (let i = 0; i < 6; i++) color += letters[Math.floor(Math.random() * 16)];
    return color;s
  }
});