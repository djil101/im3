// Fügt dem Button einen Event Listener hinzu, der bei einem Klick ausgelöst wird
document.getElementById("fetch-data-btn").addEventListener("click", function () {
  // Holt den ausgewählten Wert aus dem Dropdown-Menü
  const location = document.getElementById("location-select").value;
  // Erstellt die URL für den API-Aufruf, einschließlich des ausgewählten Standorts
  const apiUrl = `https://im3.im-abc.ch/etl-boilerplate/solution/410_unload.php?location=${encodeURIComponent(location)}`;

  // Verwendet fetch(), um einen GET-Request an die angegebene URL zu senden
  fetch(apiUrl)
    .then((response) => response.json()) // Konvertiert die Antwort in JSON
    .then((data) => {
      // Holt das Div, in dem die Daten angezeigt werden sollen
      const displayDiv = document.getElementById("data-display");
      displayDiv.innerHTML = ""; // Löscht vorherige Ergebnisse

      // Überprüft, ob die Antwort Daten enthält
      if (Array.isArray(data) && data.length > 0) {
        // Erstellt ein neues Listenelement
        const list = document.createElement("ul");

        // Iteriert über jedes Element im Datenarray
        data.forEach((item) => {
          // Erstellt für jedes Element ein neues Listen-Item-Element
          const li = document.createElement("li");
          // Setzt den Text des Listen-Items, um die Bedingung, Temperatur und den Zeitstempel anzuzeigen
          li.textContent = `${item.weather_condition} - ${item.temperature_celsius}°C am ${item.created_at}`;
          // Fügt das Listen-Item zur Liste hinzu
          list.appendChild(li);
        });

        // Fügt die gefüllte Liste dem Anzeige-Div hinzu
        displayDiv.appendChild(list);
      } else {
        // Wenn keine Daten gefunden wurden, den Benutzer informieren
        displayDiv.textContent = "Keine Daten für den ausgewählten Standort gefunden.";
      }
    })
    .catch((error) => {
      // Loggt Fehler in die Konsole und zeigt eine Fehlermeldung an
      console.error("Fehler beim Abrufen:", error);
      document.getElementById("data-display").textContent = "Fehler beim Datenabruf.";
    });
});
