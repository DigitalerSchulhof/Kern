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