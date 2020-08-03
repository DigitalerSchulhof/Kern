kern.schulhof.nutzerkonto = {
  anmelden: () => {
    var benutzer = $("#dshAnmeldungBenutzer").getWert();
    var passwort = $("#dshAnmeldungPasswort").getWert();
    core.ajax("Kern", 0, ["Anmeldung", "Anmeldedaten werden überprüft"], {benutzer: benutzer, passwort: passwort});
  },
  abmelden: {
    fragen: () => {
      core.ajax("UI", 1, ["Abmeldung", "Bitte warten"], {art: "Warnung", titel: "Wirklich abmelden?", inhalt: "Damit die Ameldung erfolgen kann ist eine Bestätigung notwendig!", aktionen: 2, knopfinhalt0: "Abmelden", knopfart0: "Warnung", knopfziel0: "kern.schulhof.nutzerkonto.abmelden.ausfuehren()", knopfinhalt1: "Abbrechen", knopfziel1: "ui.laden.aus()"});
    },
    ausfuehren: () => {
      core.ajax("Kern", 1, ["Abmeldung", "Die Abmeldung wird durchgeführt"], null);
    }
  }
};
