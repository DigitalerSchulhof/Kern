import { ToggleWert } from "module/UI/ts/_export";
import ajax from "ts/ajax";
import $ from "ts/eQuery";

export const einstellungen = {
  schuldaten: (): void => {
    const schulname = $("#dshModulKernSchulname").getWert();
    const schulort = $("#dshModulKernSchulort").getWert();
    const strhnr = $("#dshModulKernSchulstrhnr").getWert();
    const plzort = $("#dshModulKernSchulplzort").getWert();
    const telefon = $("#dshModulKernSchultelefon").getWert();
    const fax = $("#dshModulKernSchulfax").getWert();
    const mail = $("#dshModulKernSchulmail").getWert();
    const domain = $("#dshModulKernSchuldomain").getWert();
    ajax("Kern", 21, "Schuldaten ändern", { schulname: schulname, schulort: schulort, strhnr: strhnr, plzort: plzort, telefon: telefon, fax: fax, mail: mail, domain: domain }, 23);
  },
  vertreter: (): void => {
    const slname = $("#dshModulKernLeiterName").getWert();
    const slmail = $("#dshModulKernLeiterMail").getWert();
    const daname = $("#dshModulKernDatemschutzName").getWert();
    const damail = $("#dshModulKernDatemschutzMail").getWert();
    const prname = $("#dshModulKernPresseName").getWert();
    const prmail = $("#dshModulKernPresseMail").getWert();
    const wename = $("#dshModulKernWebName").getWert();
    const wemail = $("#dshModulKernWebMail").getWert();
    const adname = $("#dshModulKernAdminName").getWert();
    const admail = $("#dshModulKernAdminMail").getWert();
    ajax("Kern", 22, "Vertreter ändern", { slname: slname, slmail: slmail, daname: daname, damail: damail, prname: prname, prmail: prmail, wename: wename, wemail: wemail, adname: adname, admail: admail }, 24);
  },
  aktionslog: (): void => {
    const aktionslog = $("#dshModulKernLogAktiv").getWert() as ToggleWert;
    ajax("Kern", 25, "Aktionslog ändern", { aktionslog: aktionslog }, 22);
  },
  mail: {
    aendern: (): void => {
      const mailadresse = $("#dshModulKernMailadresse").getWert();
      const mailtitel = $("#dshModulKernMailtitel").getWert();
      const mailuser = $("#dshModulKernMailbenutzer").getWert();
      const mailpass = $("#dshModulKernMailpasswort").getWert();
      const mailhost = $("#dshModulKernMailhost").getWert();
      const mailport = Number($("#dshModulKernMailport").getWert());
      const mailauth = $("#dshModulKernMailauthentifizierung").getWert() as ToggleWert;
      const mailsigp = $("#dshModulKernMailsignaturPlain").getWert();
      const mailsigh = $("#dshModulKernMailsignaturHTML").getWert();
      ajax("Kern", 23, "eMailadresse ändern", { mailadresse: mailadresse, mailtitel: mailtitel, mailuser: mailuser, mailpass: mailpass, mailhost: mailhost, mailport: mailport, mailauth: mailauth, mailsigp: mailsigp, mailsigh: mailsigh }, 20);
    },
    testen: (): void => {
      const mailadresse = $("#dshModulKernMailadresse").getWert();
      const mailtitel = $("#dshModulKernMailtitel").getWert();
      const mailuser = $("#dshModulKernMailbenutzer").getWert();
      const mailpass = $("#dshModulKernMailpasswort").getWert();
      const mailhost = $("#dshModulKernMailhost").getWert();
      const mailport = Number($("#dshModulKernMailport").getWert());
      const mailauth = $("#dshModulKernMailauthentifizierung").getWert() as ToggleWert;
      const mailsigp = $("#dshModulKernMailsignaturPlain").getWert();
      const mailsigh = $("#dshModulKernMailsignaturHTML").getWert();
      ajax("Kern", 26, "eMailadresse testen", { mailadresse: mailadresse, mailtitel: mailtitel, mailuser: mailuser, mailpass: mailpass, mailhost: mailhost, mailport: mailport, mailauth: mailauth, mailsigp: mailsigp, mailsigh: mailsigh }, 21);
    }
  },
  ldap: {
    aendern: (): void => {
      const ldapaktiv = $("#dshModulKernLdapAktiv").getWert() as ToggleWert;
      const ldapuser = $("#dshModulKernLdapBenutzer").getWert();
      const ldappass = $("#dshModulKernLdapPasswort").getWert();
      const ldaphost = $("#dshModulKernLdapHost").getWert();
      const ldapport = Number($("#dshModulKernLdapPort").getWert());
      ajax("Kern", 24, "LDAP ändern", { ldapaktiv: ldapaktiv, ldapuser: ldapuser, ldappass: ldappass, ldaphost: ldaphost, ldapport: ldapport }, 19);
    },
    testen: (): void => {
      // 27
    }
  },
  modulverwaltung: (): void => {
    const daten = {
      poolKennung: $("#dshModulKernUpdaterPoolKennung").getWert(),
      poolToken: $("#dshModulKernUpdaterPoolToken").getWert(),
    };
    ajax("Kern", 48, "Modulverwaltung ändern", { ...daten }, 37);
  }
};

export const konfiguration = {
  verzeichnisse: (): void => {
    const art = "Verzeichnisse";
    const basis = $("#dshKonfigBasis").getWert();
    ajax("Kern", 29, "Basisverzeichnis ändern", { art: art, basis: basis }, 18);
  },
  datenbanken: {
    schulhof: (): void => {
      const art = "Schulhof";
      const host = $("#dshKonfigDatenbankShHost").getWert();
      const port = Number($("#dshKonfigDatenbankShPort").getWert());
      const datenbank = $("#dshKonfigDatenbankShDatenbank").getWert();
      const benutzer = $("#dshKonfigDatenbankShBenutzer").getWert();
      const passwort = $("#dshKonfigDatenbankShPasswort").getWert();
      const schluessel = $("#dshKonfigDatenbankShSchluessel").getWert();
      ajax("Kern", 29, "Schulhofdatenbank ändern", { art: art, host: host, port: port, datenbank: datenbank, benutzer: benutzer, passwort: passwort, schluessel: schluessel }, 18);
    }
  }
};