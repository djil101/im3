// Dieses Skript wird ausgeführt, sobald das DOM vollständig geladen ist
document.addEventListener("DOMContentLoaded", function () {
  // URL der API, von der die Wetterdaten abgefragt werden
  const apiUrl = "https://im3.im-abc.ch/etl-boilerplate/solution/410_unload.php?location=Bern";

  // Führt einen Fetch-Request an die angegebene URL durch
  fetch(apiUrl)
    .then((response) => response.json()) // Wandelt die Antwort in JSON um
    .then((data) => {
      // Gibt die Wetterdaten für Bern in der Konsole aus
      console.log("Wetterdaten für Bern:", data);
    })
    .catch((error) => {
      // Gibt Fehlermeldungen in der Konsole aus, falls der Fetch-Request scheitert
      console.error("Fehler beim Abrufen:", error);
    });
});
