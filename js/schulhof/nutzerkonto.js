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
  registrieren: () => {
    var art          = $("#dshRegistrierungArt").getWert();
    var geschlecht   = $("#dshRegistrierungGeschlecht").getWert();
    var titel        = $("#dshRegistrierungTitel").getWert();
    var vorname      = $("#dshRegistrierungVorname").getWert();
    var nachname     = $("#dshRegistrierungNachname").getWert();
    var klasse       = $("#dshRegistrierungKlasse").getWert();
    var passwort     = $("#dshRegistrierungPasswort").getWert();
    var passwort2    = $("#dshRegistrierungPasswort2").getWert();
    var mail         = $("#dshRegistrierungMail").getWert();
    var datenschutz  = $("#dshRegistrierungDatenschutz").getWert();
    var entscheidung = $("#dshRegistrierungEntscheidung").getWert();
    var korrektheit  = $("#dshRegistrierungKorrektheit").getWert();
    var spamschutz   = $("#dshRegistrierungSpamschutz").getWert();
    var spamid       = $("#dshRegistrierungSpamschutzSpamid").getWert();

    core.ajax("Kern", 6, ["Registrieren", "Die Registrierung wird geprüft"], {art: art, geschlecht:geschlecht, titel:titel, vorname:vorname, nachname:nachname, klasse:klasse, passwort:passwort, passwort2:passwort2, mail:mail, datenschutz:datenschutz, entscheidung:entscheidung, korrektheit:korrektheit, spamschutz:spamschutz, spamid:spamid});
  }
};
