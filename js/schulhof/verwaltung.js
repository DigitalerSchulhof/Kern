kern.schulhof.verwaltung = {
  module: {
    versionNeuer: (versionalt, versionneu) => {
      var va = versionalt.split(".");
      var vn = versionneu.split(".");
      for (var i=0; i<vn.length; i++) {
        if (va[i] !== undefined) {
          if (vn[i] > va[i]) {
            return true;
          }
        } else {
          return true;
        }
      }
      return false;
    },
    status: (modulid, version) => {
      var feld = $("#dshVerwaltungModuleStatus"+modulid);
      // TODO: Aktualität des Moduls prüfen und ggf. eine Aktualiseren-Knopf erzeugen
      var rueck = version;
      if (kern.schulhof.module.versionNeuer(version, rueck)) {
        var inhalt = "Aktualisieren zu "+rueck;
        var klick = "kern.schulhof.module.update('"+modulid+"')";
        ui.laden.komponente({komponente:"IconKnopf", art:"Warnung", inhalt:inhalt, icon:"fas fa-sync-alt", klickaktion:klick}).then((r) => {
          feld.setHTML(r.Code);
        });
      } else {
        ui.laden.komponente({komponente:"IconKnopf", art:"Erfolg", inhalt:"Aktuell", icon:"fas fa-check"}).then((r) => {
          feld.setHTML(r.Code);
        });
      }
    },
    details: (modulname) => {
      ui.fenster.laden("Kern", 19, null, {modulname:modulname});
    },
    version: (modulname) => {
      ui.fenster.laden("Kern", 20, null, {modulname:modulname});
    },
    alteEinblenden: (id) => {
      var wert = $("#"+id).getWert();
      var feld = $("#"+id+"Feld");
      if (wert == "1") {
        feld.einblenden();
      } else {
        feld.ausblenden()
      }
    }
  },
  personen: {
    suche: (feld, id, sortieren) => {
      var vorname = $("#dshPersonenFilterVorname").getWert();
      var nachname = $("#dshPersonenFilterNachname").getWert();
      var klasse = $("#dshPersonenFilterKlasse").getWert();
      var schueler = $("#dshPersonenFilterSchueler").getWert();
      var lehrer = $("#dshPersonenFilterLehrer").getWert();
      var erzieher = $("#dshPersonenFilterErziehungsberechtigte").getWert();
      var verwaltung = $("#dshPersonenFilterVerwaltungsangestellte").getWert();
      var externe = $("#dshPersonenFilterExterne").getWert();
      core.ajax("Kern", 31, null, {vorname:vorname, nachname:nachname, klasse:klasse, schueler:schueler, lehrer:lehrer, erzieher:erzieher, verwaltung:verwaltung, externe:externe, ...sortieren}).then((r) => {
        if (r.Code) {
          feld.setHTML(r.Code);
        }
      });
    },
    profil: (id) => {
      ui.fenster.laden("Kern", 32, null, {id:id});
    },
    loeschen: {
      fragen: (id, nutzerkonto, laden) => {
        var laden = laden || '0';
        ui.laden.meldung("Kern", 25, "Person löschen", {id:id, laden:laden, nutzerkonto:nutzerkonto});
      },
      ausfuehren: (id, art, laden) => {
        core.ajax("Kern", 33, "Person löschen", {id:id, art:art}).then(() => {
          ui.laden.meldung("Kern", 26, null, {art:art});
          if (laden == '1') {
            ui.tabelle.sortieren(kern.schulhof.verwaltung.personen.suche, 'dshVerwaltungPersonen');
          }
        });
      }
    },
    neu: {
      person: () => {
        var art = $("#dshNeuePersonArt").getWert();
        var geschlecht = $("#dshNeuePersonGeschlecht").getWert();
        var titel = $("#dshNeuePersonTitel").getWert();
        var vorname = $("#dshNeuePersonVorname").getWert();
        var nachname = $("#dshNeuePersonNachname").getWert();
        var kuerzel = $("#dshNeuePersonKuerzel").getWert();
        var nutzerkonto = $("#dshNeuePersonNutzerkonto").getWert();
        var benutzername = $("#dshNeuePersonBenutzername").getWert();
        var mail = $("#dshNeuePersonMail").getWert();
        core.ajax("Kern", 34, "Neue Person erstellen", {art:art, geschlecht:geschlecht, titel:titel, vorname:vorname, nachname:nachname, kuerzel:kuerzel, nutzerkonto:nutzerkonto, benutzername:benutzername, mail:mail}).then((r) => {
          if (nutzerkonto == "1") {
            var id = r.ID;
            core.ajax("Kern", 35, "Neues Nutzerkonto erstellen", {id:id, benutzername:benutzername, mail:mail}).then((r) => {
              ui.laden.meldung("Kern", 29, null);
            });
          } else {
            ui.laden.meldung("Kern", 27, null);
          }
        });
      },
      nutzerkonto: {
        anzeigen: (id, laden) => {
          var laden = laden || '0';
          ui.fenster.laden("Kern", 36, null, {id:id, laden:laden});
        },
        erstellen: (id, laden) => {
          var laden = laden || '0';
          var benutzername = $("#dshNeuesNutzerkonto"+id+"Benutzername").getWert();
          var mail = $("#dshNeuesNutzerkonto"+id+"Mail").getWert();
          core.ajax("Kern", 35, "Neues Nutzerkonto erstellen", {id:id, benutzername:benutzername, mail:mail}).then((r) => {
            ui.laden.meldung("Kern", 28, null);
            if (laden == '1') {
              kern.schulhof.verwaltung.personen.suche();
            }
          });
        }
      }
    },
    benutzername: () => {
      var art = $("#dshNeuePersonArt").getWert();
      var feld = $("#dshNeuePersonBenutzername");
      var vorname = $("#dshNeuePersonVorname").getWert();
      var nachname = $("#dshNeuePersonNachname").getWert();
      vorname = vorname.replace(/ /g, "");
      nachname = nachname.replace(/ /g, "");

      if (art == "l") {
        feld.setWert(vorname.substr(0,1)+nachname.substr(0,7)+"-"+art.toUpperCase());
      } else {
        feld.setWert(nachname.substr(0,8)+vorname.substr(0,3)+"-"+art.toUpperCase());
      }

    },
    rechteundrollen: (id) => {
      ui.fenster.laden("Kern", 38, null, {id:id});
    },
    rechteneuladen: (id) => core.ajax("Kern", 41, "Rechte aktualisieren", {id: id}, 32),
    rechtgeben: (el) => {
      let recht = "";
      if(typeof el === "string") {
        recht = el;
      } else {
        el = $(el);
        let p = el;
        while((p = p.parentSelector("[data-knoten]")).existiert()) {
          recht = p.getAttr("data-knoten") + "." + recht;
        }
        recht = recht.substr(0, recht.length-1);
      }
      alert(recht);
    },
    rolle: (id, rolle) => {
      let wert = $("#dshVerwaltungRechteUndRollen"+id+"Rolle"+rolle).getWert();
      let aktion = "nehmen";
      if(wert == "1") {
        aktion = "vergeben";
      }
      core.ajax("Kern", 39, "Rolle "+aktion, {id: id, rolle: rolle, wert: wert}).then(() => ui.laden.aus(), r => {
        if(!r.Erfolg) {
          $("#dshVerwaltungRechteUndRollen"+id+"Rolle"+rolle+"Toggle").setKlasse(wert, "dshUiToggled");
          $("#dshVerwaltungRechteUndRollen"+id+"Rolle"+rolle).setWert(1-wert);
        }
      });
    }
  }
};

kern.modul = {
  einstellungen: {
    schuldaten: () => {
      var schulname = $("#dshModulKernSchulname").getWert();
      var schulort  = $("#dshModulKernSchulort").getWert();
      var strhnr    = $("#dshModulKernSchulstrhnr").getWert();
      var plzort    = $("#dshModulKernSchulplzort").getWert();
      var telefon   = $("#dshModulKernSchultelefon").getWert();
      var fax       = $("#dshModulKernSchulfax").getWert();
      var mail      = $("#dshModulKernSchulmail").getWert();
      var domain    = $("#dshModulKernSchuldomain").getWert();
      core.ajax("Kern", 21, "Schuldaten ändern", {schulname:schulname, schulort:schulort, strhnr:strhnr, plzort:plzort, telefon:telefon, fax:fax, mail:mail, domain:domain}, 23);
    },
    vertreter: () => {
      var slname = $("#dshModulKernLeiterName").getWert();
      var slmail = $("#dshModulKernLeiterMail").getWert();
      var daname = $("#dshModulKernDatemschutzName").getWert();
      var damail = $("#dshModulKernDatemschutzMail").getWert();
      var prname = $("#dshModulKernPresseName").getWert();
      var prmail = $("#dshModulKernPresseMail").getWert();
      var wename = $("#dshModulKernWebName").getWert();
      var wemail = $("#dshModulKernWebMail").getWert();
      var adname = $("#dshModulKernAdminName").getWert();
      var admail = $("#dshModulKernAdminMail").getWert();
      core.ajax("Kern", 22, "Vertreter ändern", {slname:slname, slmail:slmail, daname:daname, damail:damail, prname:prname, prmail:prmail, wename:wename, wemail:wemail, adname:adname, admail:admail}, 24);
    },
    aktionslog: () => {
      var aktionslog = $("#dshModulKernLogAktiv").getWert();
      core.ajax("Kern", 25, "Aktionslog ändern", {aktionslog:aktionslog}, 22);
    },
    mail: {
      aendern: () => {
        var mailadresse = $("#dshModulKernMailadresse").getWert();
        var mailtitel   = $("#dshModulKernMailtitel").getWert();
        var mailuser    = $("#dshModulKernMailbenutzer").getWert();
        var mailpass    = $("#dshModulKernMailpasswort").getWert();
        var mailhost    = $("#dshModulKernMailhost").getWert();
        var mailport    = $("#dshModulKernMailport").getWert();
        var mailauth    = $("#dshModulKernMailauthentifizierung").getWert();
        var mailsigp    = $("#dshModulKernMailsignaturPlain").getWert();
        var mailsigh    = $("#dshModulKernMailsignaturHTML").getWert();
        core.ajax("Kern", 23, "eMailadresse ändern", {mailadresse:mailadresse, mailtitel:mailtitel, mailuser:mailuser, mailpass:mailpass, mailhost:mailhost, mailport:mailport, mailauth:mailauth, mailsigp:mailsigp, mailsigh:mailsigh}, 21);
      },
      testen: () => {
        var mailadresse = $("#dshModulKernMailadresse").getWert();
        var mailtitel   = $("#dshModulKernMailtitel").getWert();
        var mailuser    = $("#dshModulKernMailbenutzer").getWert();
        var mailpass    = $("#dshModulKernMailpasswort").getWert();
        var mailhost    = $("#dshModulKernMailhost").getWert();
        var mailport    = $("#dshModulKernMailport").getWert();
        var mailauth    = $("#dshModulKernMailauthentifizierung").getWert();
        var mailsigp    = $("#dshModulKernMailsignaturPlain").getWert();
        var mailsigh    = $("#dshModulKernMailsignaturHTML").getWert();
        core.ajax("Kern", 26, "eMailadresse testen", {mailadresse:mailadresse, mailtitel:mailtitel, mailuser:mailuser, mailpass:mailpass, mailhost:mailhost, mailport:mailport, mailauth:mailauth, mailsigp:mailsigp, mailsigh:mailsigh}, 20);
      }
    },
    ldap: {
      aendern: () => {
        var ldapaktiv   = $("#dshModulKernLdapAktiv").getWert();
        var ldapuser    = $("#dshModulKernLdapBenutzer").getWert();
        var ldappass    = $("#dshModulKernLdapPasswort").getWert();
        var ldaphost    = $("#dshModulKernLdapHost").getWert();
        var ldapport    = $("#dshModulKernLdapPort").getWert();
        core.ajax("Kern", 24, "LDAP ändern", {ldapaktiv:ldapaktiv, ldapuser:ldapuser, ldappass:ldappass, ldaphost:ldaphost, ldapport:ldapport}, 19);
      },
      testen: () => {
        // 27
      }
    }
  }
};

kern.konfiguration = {
  verzeichnisse: () => {
    var art = "Verzeichnisse";
    var basis   = $("#dshKonfigBasis").getWert();
    core.ajax("Kern", 29, "Basisverzeichnis ändern", {art:art, basis:basis}, 18);
  },
  datenbanken: {
    schulhof: () => {
      var art        = "Schulhof";
      var host       = $("#dshKonfigDatenbankShHost").getWert();
      var port       = $("#dshKonfigDatenbankShPort").getWert();
      var datenbank  = $("#dshKonfigDatenbankShDatenbank").getWert();
      var benutzer   = $("#dshKonfigDatenbankShBenutzer").getWert();
      var passwort   = $("#dshKonfigDatenbankShPasswort").getWert();
      var schluessel = $("#dshKonfigDatenbankShSchluessel").getWert();
      core.ajax("Kern", 29, "Schulhofdatenbank ändern", {art:art, host:host, port:port, datenbank:datenbank, benutzer:benutzer, passwort:passwort, schluessel:schluessel}, 18);
    },
    personen: () => {
      var art        = "Personen";
      var host       = $("#dshKonfigDatenbankPeHost").getWert();
      var port       = $("#dshKonfigDatenbankPePort").getWert();
      var datenbank  = $("#dshKonfigDatenbankPeDatenbank").getWert();
      var benutzer   = $("#dshKonfigDatenbankPeBenutzer").getWert();
      var passwort   = $("#dshKonfigDatenbankPePasswort").getWert();
      var schluessel = $("#dshKonfigDatenbankPeSchluessel").getWert();
      core.ajax("Kern", 29, "Personendatenbank ändern", {art:art, host:host, port:port, datenbank:datenbank, benutzer:benutzer, passwort:passwort, schluessel:schluessel}, 18);
    }
  }
};

kern.filter = {
  anzeigen: (id) => {
    var wert = $("#"+id+"Anzeigen").getWert();
    var feld = $("#"+id+"A");
    if (wert == 0) {
      feld.ausblenden();
    } else {
      feld.einblenden();
    }
  },
  anlegen: () => {

  }
}
