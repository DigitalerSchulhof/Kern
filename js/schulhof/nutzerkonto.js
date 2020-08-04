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
      core.ajax("Kern", 1, ["Abmeldung", "Die Abmeldung wird durchgeführt"]);
    }
  },
  session: {
    verlaengern: () => {
      core.ajax("Kern", 2, ["Session verlängern", "Die Verlängerung wird durchgeführt"]).then((r) => {
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
  },
  registrierung: () => {
    var art          = $("#dshRegistrierenArt").getWert();
    var geschlecht   = $("#dshRegistrierenGeschlecht").getWert();
    var titel        = $("#dshRegistrierenTitel").getWert();
    var vorname      = $("#dshRegistrierenVorname").getWert();
    var nachname     = $("#dshRegistrierenNachname").getWert();
    var klasse       = $("#dshRegistrierenKlasse").getWert();
    var passwort     = $("#dshRegistrierenPasswort").getWert();
    var passwort2    = $("#dshRegistrierenPasswort2").getWert();
    var mail         = $("#dshRegistrierenMail").getWert();
    var datenschutz  = $("#dshRegistrierenDatenschutz").getWert();
    var entscheidung = $("#dshRegistrierenEntscheidung").getWert();
    var korrektheit  = $("#dshRegistrierenKorrektheit").getWert();
    var spamschutz   = $("#dshRegistrierenSpanschutz").getWert();
    var spamid       = $("#dshRegistrierenSpanschutzSpamid").getWert();

    core.ajax("Kern", 6, ["Registrieren", "Die Registrierung wird geprüft"], {art: art, geschlecht:geschlecht, titel:titel, vorname:vorname, nachname:nachname, klasse:klasse, passwort:passwort, passwort2:passwort2, mail:mail, datenschutz:datenschutz, entscheidung:entscheidung, korrektheit:korrektheit, spamschutz:spamschutz, spamid:spamid});
  }
};
