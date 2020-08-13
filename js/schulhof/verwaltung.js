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
      if (kern.schulhof.verwaltung.module.versionNeuer(version, rueck)) {
        var inhalt = "Aktualisieren zu "+rueck;
        var klick = "kern.schulhof.verwaltung.module.update('"+modulid+"')";
        ui.laden.komponente({komponente:"IconKnopf", art:"Warnung", inhalt:inhalt, icon:"fas fa-sync-alt", klickaktion:klick}).then((r) => {
          feld.setHTML(r.Code);
        });
      } else {
        ui.laden.komponente({komponente:"IconKnopf", art:"Erfolg", inhalt:"Aktuell", icon:"fas fa-check"}).then((r) => {
          console.log(r);
          feld.setHTML(r.Code);
        });
      }
    },
    details: (modulname) => {
      core.ajax("Kern", 19, null, {modulname:modulname}).then((r) => {
        ui.fenster.anzeigen(r.Code);
      });
    },
    version: (modulname) => {
      core.ajax("Kern", 20, null, {modulname:modulname}).then((r) => {
        ui.fenster.anzeigen(r.Code);
      });
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
      core.ajax("Kern", 21, "Schuldaten ändern", {schulname:schulname, schulort:schulort, strhnr:strhnr, plzort:plzort, telefon:telefon, fax:fax, mail:mail, domain:domain});
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
      core.ajax("Kern", 22, "Vertreter ändern", {slname:slname, slmail:slmail, daname:daname, damail:damail, prname:prname, prmail:prmail, wename:wename, wemail:wemail, adname:adname, admail:admail});
    },
    aktionslog: () => {
      var aktionslog = $("#dshModulKernLogAktiv").getWert();
      core.ajax("Kern", 25, "Aktionslog ändern", {aktionslog:aktionslog});
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
        core.ajax("Kern", 23, "eMailadresse ändern", {mailadresse:mailadresse, mailtitel:mailtitel, mailuser:mailuser, mailpass:mailpass, mailhost:mailhost, mailport:mailport, mailauth:mailauth, mailsigp:mailsigp, mailsigh:mailsigh});
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
        core.ajax("Kern", 26, "eMailadresse testen", {mailadresse:mailadresse, mailtitel:mailtitel, mailuser:mailuser, mailpass:mailpass, mailhost:mailhost, mailport:mailport, mailauth:mailauth, mailsigp:mailsigp, mailsigh:mailsigh});
      }
    },
    ldap: {
      aendern: () => {
        var ldapaktiv   = $("#dshModulKernLdapAktiv").getWert();
        var ldapuser    = $("#dshModulKernLdapBenutzer").getWert();
        var ldappass    = $("#dshModulKernLdapPasswort").getWert();
        var ldaphost    = $("#dshModulKernLdapHost").getWert();
        var ldapport    = $("#dshModulKernLdapPort").getWert();
        core.ajax("Kern", 24, "LDAP ändern", {ldapaktiv:ldapaktiv, ldapuser:ldapuser, ldappass:ldappass, ldaphost:ldaphost, ldapport:ldapport});
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
    core.ajax("Kern", 29, "Basisverzeichnis ändern", {art:art, basis:basis});
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
      core.ajax("Kern", 29, "Basisverzeichnis ändern", {art:art, host:host, port:port, datenbank:datenbank, benutzer:benutzer, passwort:passwort, schluessel:schluessel});
    },
    personen: () => {
      var art        = "Personen";
      var host       = $("#dshKonfigDatenbankPeHost").getWert();
      var port       = $("#dshKonfigDatenbankPePort").getWert();
      var datenbank  = $("#dshKonfigDatenbankPeDatenbank").getWert();
      var benutzer   = $("#dshKonfigDatenbankPeBenutzer").getWert();
      var passwort   = $("#dshKonfigDatenbankPePasswort").getWert();
      var schluessel = $("#dshKonfigDatenbankPeSchluessel").getWert();
      core.ajax("Kern", 29, "Basisverzeichnis ändern", {art:art, host:host, port:port, datenbank:datenbank, benutzer:benutzer, passwort:passwort, schluessel:schluessel});
    }
  }
};

kern.personen = {
  anlegen: () => {

  }
}
