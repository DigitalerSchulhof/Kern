kern.schulhof.nutzerkonto = {
  anmelden: () => {
    var benutzer = $("#dshAnmeldungBenutzer").getWert();
    var passwort = $("#dshAnmeldungPasswort").getWert();
    core.ajax("Kern", 0, ["Anmeldung", "Anmeldedaten werden überprüft"], {benutzer: benutzer, passwort: passwort});
  },
  abmelden: {
    fragen: () => {
      core.ajax("UI", 1, ["Abmeldung", "Bitte warten"], {art: "Warnung", titel: "Wirklich abmelden?", inhalt: "Damit die Ameldung erfolgen kann ist eine Bestätigung notwendig!", aktionen: [{inhalt: "Abmelden", art: "Warnung", ziel: "kern.schulhof.nutzerkonto.abmelden.ausfuehren()"}, {inhalt: "Abbrechen", ziel: "ui.laden.aus()"}]});
    },
    ausfuehren: () => {
      core.ajax("Kern", 1, ["Abmeldung", "Die Abmeldung wird durchgeführt"], null);
    }
  },
  session: {
    verlaengern: () => {
      core.ajax("Kern", 2, ["Session verlängern", "Die Verlängerung wird durchgeführt"], null).then((r) => {
        kern.schulhof.nutzerkonto.session.aktualisieren(r.Limit, r.Ende);
      });
    },
    aktualisieren: (limit, ende) => {
      var orte = ["dshAktivitaetNutzerkonto"];
      for (var i=0; i<= orte.length; i++) {

      }
    }
  }
};
