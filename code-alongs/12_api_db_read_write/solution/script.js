// Abfrage aller Daten durch den einfachen Aufruf von unload.php
async function getAll() {
  await fetch("read_all.php/?userlist=true")
    .then((response) => response.json())
    .then((data) => createTable(data))
    .catch((error) => console.error(error));
}
getAll();

// ----------------------------
// Abfrage eines Datensatzes mit id
async function getId(id) {
  url = "read_id.php/?id=" + id;
  console.log(url);
  await fetch(url)
    .then((response) => response.json())
    .then((data) => createTable([data]))
    .catch((error) => console.error(error));
}

let searchid = document.querySelector("#searchid");
let isSearchBtn = document.querySelector("#idSearchBtn");
isSearchBtn.addEventListener("click", function (event) {
  event.preventDefault();
  const id = searchid.value;
  getId(id);
});

// ----------------------------
// Abfrage aller Datensätze, die den String $string in firstname, lastname oder email enthalten
async function getSearch(string) {
  url = "search.php/?string=" + string;
  console.log(url);
  await fetch(url)
    .then((response) => response.json())
    .then((data) => createTable(data))
    .catch((error) => console.error(error));
}

let searchString = document.querySelector("#searchString");
let stringSearchBtn = document.querySelector("#stringSearchBtn");
stringSearchBtn.addEventListener("click", function (event) {
  event.preventDefault();
  const string = searchString.value;
  getSearch(string);
});

// ----------------------------
// Tabelle aus Daten erstellen
function createTable(list) {
  const tabEl = document.getElementById("output");

  const titles = Object.keys(list[0])
    .map((key) => `<th>${key}</th>`)
    .join("");

  const rows = list
    .map(
      (obj) =>
        `<tr>${Object.values(obj)
          .map((val) => `<td>${val}</td>`)
          .join("")}</tr>`
    )
    .join("");

  tabEl.innerHTML = `
  <table class="table">
    <thead>
    <tr>
      ${titles}
    </tr>
    </thead>
    <tbody>
     ${rows}
    </tbody>
    
  </table>
  `;
}

// ----------------------------
// Einfügen eines neuen Datensatzes
/*let insertForm = document.querySelector("#insert");

insertForm.addEventListener("submit", function (event) {
  event.preventDefault();

  const formData = getFormData(insertForm);

  send("write.php", formData)
    .then((data) => {
      console.log(data);
      showAll();
    })
    .catch((fehler) => console.error(fehler));
});

// Funktion zum Senden von Daten an den Server
async function send(url, formData) {
  try {
    // ReadableStreamBYOBRequest;
    const response = await fetch(url, {
      method: "POST", // Methode: POST
      body: formData, // Hier verwenden wir FormData direkt
      // Keine 'Content-Type' Header notwendig; der Browser setzt einen passenden Wert
    });

    if (!response.ok) {
      throw new Error("Netzwerkantwort war nicht ok.");
    }

    const responseData = await response.json(); // Antwort in JSON umwandeln
    return responseData; // Antwort zurückgeben
  } catch (error) {
    console.error("Fehler beim Senden der Daten: ", error);
  }
}

// ----------------------------

function getFormData(form) {
  const formData = new FormData();
  // Iteration durch alle Elemente im Formular
  Array.from(form.elements).forEach((element) => {
    // Array.from() wandelt ein Array-ähnliches Objekt in ein echtes Array um (hier: eine NodeList).
    // Ignorieren von Elementen ohne 'name'-Attribut
    if (element.name) {
      formData.append(element.name, element.value);
    }
  });
  return formData;
}
*/
