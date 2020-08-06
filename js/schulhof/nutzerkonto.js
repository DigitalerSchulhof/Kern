kern.schulhof.nutzerkonto = {
  anmelden: () => {
    var benutzer = $("#dshAnmeldungBenutzer").getWert();
    var passwort = $("#dshAnmeldungPasswort").getWert();
    core.ajax("Kern", 0, ["Anmeldung", "Anmeldedaten werden überprüft"], {benutzer: benutzer, passwort: passwort});
  },
  abmelden: {
    fragen: () => {
      ui.laden.meldung("Kern", 0, ["Abmeldung", "Bitte warten"]);
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
  },
  aendern: {
    persoenliches: () => {
      var id          = $("#dshProfilId").getWert();
      var art         = $("#dshProfilArt").getWert();
      var geschlecht  = $("#dshProfilGeschlecht").getWert();
      var titel       = $("#dshProfilTitel").getWert();
      var vorname     = $("#dshProfilVorname").getWert();
      var nachname    = $("#dshProfilNachname").getWert();
      var kuerzel     = $("#dshProfilKuerzel").getWert();
      core.ajax("Kern", 7, ["Profil ändern", "Bitte warten"], {id:id, art:art, geschlecht:geschlecht, titel:titel, vorname:vorname, nachname:nachname, kuerzel:kuerzel});
    },
    kontodaten: () => {
      var id          = $("#dshProfilId").getWert();
      var benutzer    = $("#dshProfilBenutzer").getWert();
      var email       = $("#dshProfilMail").getWert();
      core.ajax("Kern", 8, ["Profil ändern", "Bitte warten"], {id:id, benutzer:benutzer, email:email});
    },
    passwort: () => {
      var id           = $("#dshProfilId").getWert();
      var passwortalt  = $("#dshProfilPasswortAlt").getWert();
      var passwortneu  = $("#dshProfilPasswortNeu").getWert();
      var passwortneu2 = $("#dshProfilPasswortNeu2").getWert();
      core.ajax("Kern", 9, ["Profil ändern", "Bitte warten"], {id:id, passwortalt:passwortalt, passwortneu:passwortneu, passwortneu2:passwortneu2});
    },
    einstellungen: {
      nutzerkonto: () => {
        var id                  = $("#dshProfilId").getWert();
        var inaktivitaetszeit   = $("#dshProfilInaktivitaetszeit").getWert();
        var uebersichtselemente = $("#dshProfilElementeProUebersicht").getWert();
        core.ajax("Kern", 10, ["Profileinstellungen ändern", "Bitte warten"], {id:id, inaktivitaetszeit:inaktivitaetszeit, uebersichtselemente:uebersichtselemente});
      },
      benachrichtigungen: () => {
        var id             = $("#dshProfilId").getWert();
        var nachrichten    = $("#dshProfilNachrichtenmails").getWert();
        var notifikationen = $("#dshProfilNotifikationsmails").getWert();
        var blog           = $("#dshProfilOeffentlichBlog").getWert();
        var termin         = $("#dshProfilOeffentlichTermine").getWert();
        var galerie        = $("#dshProfilOeffentlichGalerien").getWert();
        core.ajax("Kern", 11, ["Profileinstellungen ändern", "Bitte warten"], {id:id, nachrichten:nachrichten, notifikationen:notifikationen, blog:blog, termin:termin, galerie:galerie});
      },
      postfach: () => {
        var id         = $("#dshProfilId").getWert();
        var postfach   = $("#dshProfilPostfachLoeschfrist").getWert();
        var papierkorb = $("#dshProfilPapierkorbLoeschfrist").getWert();
        core.ajax("Kern", 12, ["Profileinstellungen ändern", "Bitte warten"], {id:id, postfach:postfach, papierkorb:papierkorb});
      }
    }
  }
};
