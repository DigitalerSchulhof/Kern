kern.schulhof.nutzerkonto = {
  anmelden: () => {
    var benutzer = $("#dshAnmeldungBenutzer").getWert();
    var passwort = $("#dshAnmeldungPasswort").getWert();
    core.ajax("Kern", 0, ["Anmeldung", "Anmeldedaten werden überprüft"], {benutzer: benutzer, passwort: passwort});
  },
  abmelden: {
    fragen: () => {
      core.ajax("UI", 1, ["Abmeldung", "Bitte warten"], {art: "Warnung", titel: "Wirklich abmelden?", inhalt: "Damit die Abmeldung erfolgen kann, ist eine Bestätigung notwendig!", aktionen: [{inhalt: "Abmelden", art: "Warnung", ziel: "kern.schulhof.nutzerkonto.abmelden.ausfuehren()"}, {typ: "Abbrechen"}]});
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
  },
  vergessen: {
    passwort: () => {
      var benutzer = $("#dshZugangsdatenPasswortBenutzer").getWert();
      var mail = $("#dshZugangsdatenPasswortMail").getWert();
      core.ajax("Kern", 3, ["Passwort vergessen", "Ein neues Passwort wird erzeugt und verschickt"], {benutzer: benutzer, mail: mail});
    },
    benutzername: () => {
      var mail = $("#dshZugangsdatenBenutzerMail").getWert();
      core.ajax("Kern", 4, ["Benutzername vergessen", "Der Benutzername wird verschickt"], {mail: mail});
    }
  }
};
