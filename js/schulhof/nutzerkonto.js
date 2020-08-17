kern.schulhof.nutzerkonto = {
  anmelden: () => {
    var benutzer = $("#dshAnmeldungBenutzer").getWert();
    var passwort = $("#dshAnmeldungPasswort").getWert();
    core.ajax("Kern", 0, ["Anmeldung", "Anmeldedaten werden überprüft"], {benutzer: benutzer, passwort: passwort}).then(() => core.neuladen());
  },
  abmelden: {
    fragen: () => {
      ui.laden.meldung("Kern", 0, "Abmeldung");
    },
    ausfuehren: () => {
      core.ajax("Kern", 1, ["Abmeldung", "Die Abmeldung wird durchgeführt"], null, 1);
    }
  },
  session: {
    verlaengern: () => {
      core.ajax("Kern", 2, ["Session verlängern", "Die Verlängerung wird durchgeführt"], null, 1).then((r) => {
        kern.schulhof.nutzerkonto.aktivitaetsanzeige.limit = r.Limit;
        kern.schulhof.nutzerkonto.aktivitaetsanzeige.timeout = r.Ende;
      });
    }
  },
  vergessen: {
    passwort: () => {
      var benutzer = $("#dshZugangsdatenPasswortBenutzer").getWert();
      var mail = $("#dshZugangsdatenPasswortMail").getWert();
      core.ajax("Kern", 3, ["Passwort vergessen", "Ein neues Passwort wird erzeugt und verschickt"], {benutzer: benutzer, mail: mail}, 5);
    },
    benutzername: () => {
      var mail = $("#dshZugangsdatenBenutzerMail").getWert();
      core.ajax("Kern", 4, ["Benutzername vergessen", "Der Benutzername wird verschickt"], {mail: mail}, 4);
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

    core.ajax("Kern", 6, ["Registrieren", "Die Registrierung wird geprüft"], {art: art, geschlecht:geschlecht, titel:titel, vorname:vorname, nachname:nachname, klasse:klasse, passwort:passwort, passwort2:passwort2, mail:mail, datenschutz:datenschutz, entscheidung:entscheidung, korrektheit:korrektheit, spamschutz:spamschutz, spamid:spamid}, 6);
  },
  aendern: {
    persoenliches: (id) => {
      var art         = $("#dshProfil"+id+"Art").getWert();
      var geschlecht  = $("#dshProfil"+id+"Geschlecht").getWert();
      var titel       = $("#dshProfil"+id+"Titel").getWert();
      var vorname     = $("#dshProfil"+id+"Vorname").getWert();
      var nachname    = $("#dshProfil"+id+"Nachname").getWert();
      var kuerzel     = $("#dshProfil"+id+"Kuerzel").getWert();
      core.ajax("Kern", 7, "Profil ändern", {id:id, art:art, geschlecht:geschlecht, titel:titel, vorname:vorname, nachname:nachname, kuerzel:kuerzel}, 7).then((r) => {
        if ($("#dshVerwaltungPersonen") !== null) {
          ui.tabelle.sortieren(kern.schulhof.verwaltung.personen.suche, 'dshVerwaltungPersonen');
        }
      });
    },
    kontodaten: (id) => {
      var benutzer    = $("#dshProfil"+id+"Benutzer").getWert();
      var email       = $("#dshProfil"+id+"Mail").getWert();
      core.ajax("Kern", 8, "Profil ändern", {id:id, benutzer:benutzer, email:email}, 8);
    },
    passwort: (id) => {
      var passwortalt  = $("#dshProfil"+id+"PasswortAlt").getWert();
      var passwortneu  = $("#dshProfil"+id+"PasswortNeu").getWert();
      var passwortneu2 = $("#dshProfil"+id+"PasswortNeu2").getWert();
      core.ajax("Kern", 9, "Profil ändern", {id:id, passwortalt:passwortalt, passwortneu:passwortneu, passwortneu2:passwortneu2}, 9);
    },
    einstellungen: {
      nutzerkonto: (id) => {
        var inaktivitaetszeit   = $("#dshProfil"+id+"Inaktivitaetszeit").getWert();
        var uebersichtselemente = $("#dshProfil"+id+"ElementeProUebersicht").getWert();
        var wiki                = $("#dshProfil"+id+"Wiki").getWert();
        core.ajax("Kern", 10, "Profileinstellungen ändern", {id:id, inaktivitaetszeit:inaktivitaetszeit, uebersichtselemente:uebersichtselemente, wiki:wiki}, 10);
      },
      benachrichtigungen: (id) => {
        var nachrichten    = $("#dshProfil"+id+"Nachrichtenmails").getWert();
        var notifikationen = $("#dshProfil"+id+"Notifikationsmails").getWert();
        var blog           = $("#dshProfil"+id+"OeffentlichBlog").getWert();
        var termin         = $("#dshProfil"+id+"OeffentlichTermine").getWert();
        var galerie        = $("#dshProfil"+id+"OeffentlichGalerien").getWert();
        core.ajax("Kern", 11, "Profileinstellungen ändern", {id:id, nachrichten:nachrichten, notifikationen:notifikationen, blog:blog, termin:termin, galerie:galerie}, 11);
      },
      postfach: (id) => {
        var postfach   = $("#dshProfil"+id+"PostfachLoeschfrist").getWert();
        var papierkorb = $("#dshProfil"+id+"PapierkorbLoeschfrist").getWert();
        core.ajax("Kern", 12, "Profileinstellungen ändern", {id:id, postfach:postfach, papierkorb:papierkorb}, 12);
      },
      email: (id) => {
        var aktiv     = $("#dshProfil"+id+"EmailAktiv").getWert();
        var adresse   = $("#dshProfil"+id+"EmailAdresse").getWert();
        var name      = $("#dshProfil"+id+"EmailName").getWert();
        var ehost     = $("#dshProfil"+id+"EmailEingangHost").getWert();
        var eport     = $("#dshProfil"+id+"EmailEingangPort").getWert();
        var enutzer   = $("#dshProfil"+id+"EmailEingangNutzer").getWert();
        var epasswort = $("#dshProfil"+id+"EmailEingangPasswort").getWert();
        var ahost     = $("#dshProfil"+id+"EmailAusgangHost").getWert();
        var aport     = $("#dshProfil"+id+"EmailAusgangPort").getWert();
        var anutzer   = $("#dshProfil"+id+"EmailAusgangNutzer").getWert();
        var apasswort = $("#dshProfil"+id+"EmailAusgangPasswort").getWert();
        core.ajax("Kern", 13, "Profileinstellungen ändern", {id:id, aktiv:aktiv, adresse:adresse, name:name, ehost:ehost, eport:eport, enutzer:enutzer, epasswort:epasswort, ahost:ahost, aport:aport, anutzer:anutzer, apasswort:apasswort}, 13);
      }
    }
  },
  sessions: {
    loeschen: {
      fragen: (nutzerid, sessionid) => {
        ui.laden.meldung("Kern", 2, "Sessions löschen", {nutzerid:nutzerid, sessionid:sessionid});
      },
      ausfuehren: (nutzerid, sessionid) => {
        core.ajax("Kern", 14, "Sessions löschen", {nutzerid:nutzerid, sessionid:sessionid}).then(() => {
          ui.laden.meldung("Kern", 15, null, {nutzerid:nutzerid, sessionid:sessionid});
          if (sessionid != 'alle') {
            kern.schulhof.nutzerkonto.sessions.laden(nutzerid);
          }
        });
      }
    },
    laden: (feld, id, sortieren) => {
      if (id == "alle") {
        // @TODO: Filter laden
      }
      feld.setHTML(ui.generieren.laden.icon("Offene Sessions werden ermittelt"));
      core.ajax("Kern", 15, null, {id:id, ...sortieren}).then((r) => {
        if (r.Code) {
          feld.setHTML(r.Code);
        }
      });
    }
  },
  aktionslog: {
    loeschen: {
      fragen: (nutzerid, logid) => {
        ui.laden.meldung("Kern", 3, "Aktionslog löschen", {nutzerid:nutzerid, logid: logid});
      },
      ausfuehren: (nutzerid, logid) => {
        core.ajax("Kern", 16, "Aktionslog löschen", {nutzerid:nutzerid, logid:logid}).then(() => {
          ui.laden.meldung("Kern", 16, null, {nutzerid:nutzerid, logid:logid});
          kern.schulhof.nutzerkonto.aktionslog.laden(nutzerid);
        });
      }
    },
    details: (nutzerid, logid) => {
      core.ajax("Kern", 17, null, {nutzerid:nutzerid, logid:logid}).then((r) => {
        ui.fenster.anzeigen(r.Code, r.Fensterid);
      });
    },
    laden: (feld, id, sortieren) => {
      if (id == "alle") {
        // @TODO: Filter laden
      } else {
        var datum = $("#"+id+"Datum").getWert();
      }
      feld.setHTML(ui.generieren.laden.icon("Aktionslog wird geladen"));
      core.ajax("Kern", 18, null, {id:id, datum:datum, ...sortieren}).then((r) => {
        if (r.Code) {
          feld.setHTML(r.Code);
        }
      });
    }
  },
  identitaetsdiebstahl: () => {
    var passwortalt  = $("#dshIdentitaetPasswortAlt").getWert();
    var passwortneu  = $("#dshIdentitaetPasswortNeu").getWert();
    var passwortneu2 = $("#dshIdentitaetPasswortNeu2").getWert();
    var hinweise     = $("#dshIdentitaetHinweise").getWert();
    core.ajax("Kern", 28, "Identitätsdiebstahl melden", {passwortalt:passwortalt, passwortneu:passwortneu, passwortneu2:passwortneu2, hinweise:hinweise}, 14);
  },
  aktivitaetsanzeige: {
    limit: 0,
    timeout: 0,
    ids: [],
    hinzufuegen: (id) => {
      if (kern.schulhof.nutzerkonto.aktivitaetsanzeige.ids.indexOf(id) == -1) {
        kern.schulhof.nutzerkonto.aktivitaetsanzeige.ids.push(id);
      }
    },
    aktualisieren: () => {
      var jetzt = (new Date()).getTime();
      var timeout = kern.schulhof.nutzerkonto.aktivitaetsanzeige.timeout;
      var limit = kern.schulhof.nutzerkonto.aktivitaetsanzeige.limit;
      var freiezeit = timeout*1000 - jetzt;
      var minuten = ui.generieren.minuten(freiezeit);
      var prozent = ui.generieren.prozent(freiezeit, limit*1000*60);

      for (var i = 0; i<kern.schulhof.nutzerkonto.aktivitaetsanzeige.ids.length; i++) {
        var balken = $("#"+kern.schulhof.nutzerkonto.aktivitaetsanzeige.ids[i]+"I");
        var uebrig = $("#"+kern.schulhof.nutzerkonto.aktivitaetsanzeige.ids[i]+"UebrigAbs");
        if (balken) {
          balken.setCss("width", prozent+"%");
        }
        if (uebrig) {
          if (minuten == 1) {
            uebrig.setHTML("etwa eine Minute");
          } else if (minuten == 0) {
            uebrig.setHTML("weniger als eine Minute");
          } else {
            uebrig.setHTML(minuten+" Minuten");
          }
        }
      }

      if (minuten < 0) {
        kern.schulhof.nutzerkonto.abmelden.ausfuehren();
        return;
      } else if (minuten < 2) {
        ui.laden.meldung("Kern", 30, "Session verlängern?");

        core.ajax("Kern", 37, null, null).then((r) => {
          kern.schulhof.nutzerkonto.aktivitaetsanzeige.limit = r.Limit;
          kern.schulhof.nutzerkonto.aktivitaetsanzeige.timeout = r.Ende;
          var ende = new Date (r.Ende*1000);

          // Text neu schreiben
          var datum = ui.generieren.fuehrendeNull(ende.getDate())+".";
          datum += ui.generieren.fuehrendeNull(ende.getMonth()+1)+"."+ende.getFullYear();
          var zeit = ui.generieren.fuehrendeNull(ende.getHours())+":";
          zeit += ui.generieren.fuehrendeNull(ende.getMinutes());

          var minuten = ui.generieren.minuten(ende.getTime() - jetzt);

          for (var i = 0; i<kern.schulhof.nutzerkonto.aktivitaetsanzeige.ids.length; i++) {
            var text = "Aktiv bis "+datum+" um "+zeit+" Uhr - noch ";
            text += "<span id=\""+kern.schulhof.nutzerkonto.aktivitaetsanzeige.ids[i]+"Infotext"+"\">";
            if (minuten == 1) {
              text += "etwa eine Minute";
            } else if (minuten == 0) {
              text += "weniger als eine Minute";
            } else {
              text += minuten+" Minuten";
            }
            text += "</span>.";
            $("#"+kern.schulhof.nutzerkonto.aktivitaetsanzeige.ids[i]+"Infotext").setHTML(text);
          }
        });
        setTimeout(() => {kern.schulhof.nutzerkonto.aktivitaetsanzeige.aktualisieren()}, 10000);
      } else {
        setTimeout(() => {kern.schulhof.nutzerkonto.aktivitaetsanzeige.aktualisieren()}, 45000);
      }
    }
  }
};
