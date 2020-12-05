import $ from "ts/eQuery";
import ajax, { AnfrageErfolg } from "ts/ajax";
import seiteLaden, { neuladen } from "ts/laden";
import { navigationAnpassen } from "ts/navigation";
import * as uiLaden from "module/UI/ts/elemente/laden";
import * as uiTabelle from "module/UI/ts/elemente/tabelle";
import * as uiFenster from "module/UI/ts/elemente/fenster";
import { fuehrendeNull } from "module/UI/ts/generieren";
import {minuten as minutenGen, prozent as prozentGen} from "module/UI/ts/generieren";

export const anmelden = (): void => {
  const benutzer = $("#dshAnmeldungBenutzer").getWert();
  const passwort = $("#dshAnmeldungPasswort").getWert();
  ajax("Kern", 0, { titel: "Anmeldung", beschreibung: "Anmeldedaten werden überprüft" }, { benutzer: benutzer, passwort: passwort }).then((): void => {
    navigationAnpassen(null, true);
    neuladen();
  });
};

export const abmelden = {
  fragen: (): void => {
    uiLaden.meldung("Kern", 0, "Abmeldung");
  },
  ausfuehren: (auto: boolean): void => {
    if (auto) {
      ajax("Kern", 1, { titel: "Abmeldung", beschreibung: "Die Abmeldung wird durchgeführt" }, null, 31).then((): void => {
        navigationAnpassen(null, true);
        seiteLaden("Schulhof/Anmeldung");
      });
    } else {
      ajax("Kern", 1, { titel: "Abmeldung", beschreibung: "Die Abmeldung wird durchgeführt" }, null, 1).then((): void => {
        navigationAnpassen(null, true);
        seiteLaden("Schulhof/Anmeldung");
      });
    }
  }
};

export const session = {
  verlaengern: (): void => {
    ajax("Kern", 2, { titel: "Session verlängern", beschreibung: "Die Verlängerung wird durchgeführt" }, null, 17).then((r): void => {
      aktivitaetsanzeige.limit = r.Limit;
      aktivitaetsanzeige.timeout = r.Ende;
    });
  }
};

export const vergessen = {
  passwort: (): void => {
    const benutzer = $("#dshZugangsdatenPasswortBenutzer").getWert();
    const mail = $("#dshZugangsdatenPasswortMail").getWert();
    ajax("Kern", 3, { titel: "Passwort vergessen", beschreibung: "Ein neues Passwort wird erzeugt und verschickt" }, { benutzer: benutzer, mail: mail }, 5);
  },
  benutzername: (): void => {
    const mail = $("#dshZugangsdatenBenutzerMail").getWert();
    ajax("Kern", 4, { titel: "Benutzername vergessen", beschreibung: "Der Benutzername wird verschickt" }, { mail: mail }, 4);
  }
};

export const registrieren = (): void => {
  const art = $("#dshRegistrierungArt").getWert();
  const geschlecht = $("#dshRegistrierungGeschlecht").getWert();
  const titel = $("#dshRegistrierungTitel").getWert();
  const vorname = $("#dshRegistrierungVorname").getWert();
  const nachname = $("#dshRegistrierungNachname").getWert();
  const klasse = $("#dshRegistrierungKlasse").getWert();
  const passwort = $("#dshRegistrierungPasswort").getWert();
  const passwort2 = $("#dshRegistrierungPasswort2").getWert();
  const mail = $("#dshRegistrierungMail").getWert();
  const datenschutz = $("#dshRegistrierungDatenschutz").getWert();
  const entscheidung = $("#dshRegistrierungEntscheidung").getWert();
  const korrektheit = $("#dshRegistrierungKorrektheit").getWert();
  const spamschutz = $("#dshRegistrierungSpamschutz").getWert();
  const spamid = $("#dshRegistrierungSpamschutzSpamid").getWert();

  ajax("Kern", 6, { titel: "Registrieren", beschreibung: "Die Registrierung wird geprüft" }, { art: art, geschlecht: geschlecht, titel: titel, vorname: vorname, nachname: nachname, klasse: klasse, passwort: passwort, passwort2: passwort2, mail: mail, datenschutz: datenschutz, entscheidung: entscheidung, korrektheit: korrektheit, spamschutz: spamschutz, spamid: spamid }, 6);
};

export const aendern = {
  persoenliches: (id: string): void => {
    const art = $("#dshProfil" + id + "Art").getWert();
    const geschlecht = $("#dshProfil" + id + "Geschlecht").getWert();
    const titel = $("#dshProfil" + id + "Titel").getWert();
    const vorname = $("#dshProfil" + id + "Vorname").getWert();
    const nachname = $("#dshProfil" + id + "Nachname").getWert();
    const kuerzel = $("#dshProfil" + id + "Kuerzel").getWert();
    ajax("Kern", 7, "Profil ändern", { id: id, art: art, geschlecht: geschlecht, titel: titel, vorname: vorname, nachname: nachname, kuerzel: kuerzel }, 7, "dshVerwaltungPersonen");
  },
  kontodaten: (id: string): void => {
    const benutzer = $("#dshProfil" + id + "Benutzer").getWert();
    const email = $("#dshProfil" + id + "Mail").getWert();
    ajax("Kern", 8, "Profil ändern", { id: id, benutzer: benutzer, email: email }, 8);
  },
  passwort: (id: string): void => {
    const passwortalt = $("#dshProfil" + id + "PasswortAlt").getWert();
    const passwortneu = $("#dshProfil" + id + "PasswortNeu").getWert();
    const passwortneu2 = $("#dshProfil" + id + "PasswortNeu2").getWert();
    ajax("Kern", 9, "Profil ändern", { id: id, passwortalt: passwortalt, passwortneu: passwortneu, passwortneu2: passwortneu2 }, 9);
  },
  einstellungen: {
    nutzerkonto: (id: string): void => {
      const inaktivitaetszeit = $("#dshProfil" + id + "Inaktivitaetszeit").getWert();
      const uebersichtselemente = $("#dshProfil" + id + "ElementeProUebersicht").getWert();
      const wiki = $("#dshProfil" + id + "Wiki").getWert();
      ajax("Kern", 10, "Profileinstellungen ändern", { id: id, inaktivitaetszeit: inaktivitaetszeit, uebersichtselemente: uebersichtselemente, wiki: wiki }, 10);
    },
    notifikationen: (id: string): void => {
      const notifikationen = $("#dshProfil" + id + "Notifikationsmails").getWert();
      const blog = $("#dshProfil" + id + "OeffentlichBlog").getWert();
      const termin = $("#dshProfil" + id + "OeffentlichTermine").getWert();
      const galerie = $("#dshProfil" + id + "OeffentlichGalerien").getWert();
      ajax("Kern", 11, "Notifikationseinstellungen ändern", { id: id, notifikationen: notifikationen, blog: blog, termin: termin, galerie: galerie }, 11);
    }
  }
};
export const sessions = {
  loeschen: {
    fragen: (nutzerid: string, sessionid: string | "alle"): void => {
      uiLaden.meldung("Kern", 2, "Sessions löschen", { nutzerid: nutzerid, sessionid: sessionid });
    },
    ausfuehren: (nutzerid: string, sessionid: string | "alle"): void => {
      ajax("Kern", 14, "Sessions löschen", { nutzerid: nutzerid, sessionid: sessionid }).then((): void => {
        uiLaden.meldung("Kern", 15, null, { nutzerid: nutzerid, sessionid: sessionid });
        if (sessionid != "alle") {
          uiTabelle.sortieren("dshProfil" + nutzerid + "Sessionprotokoll");
        }
      });
    }
  },
  laden: (sortieren: { [key: string]: any; }, id: string | "alle"): Promise<AnfrageErfolg> => {
    if (id == "alle") {
      // @TODO: Filter laden
    }
    return ajax("Kern", 15, false, { id: id, ...sortieren });
  },
  beenden: {
    fragen: (): void => {
      uiLaden.meldung("Kern", 12, "Alle Sessions beenden");
    },
    ausfuehren: (): void => {
      ajax("Kern", 12, "Alle Sessions beenden", null, 1).then((): void => {
        seiteLaden("Schulhof/Anmeldung");
      });
    }
  }
};

export const aktionslog = {
  loeschen: {
    fragen: (nutzerid: string, logid: string): void => {
      uiLaden.meldung("Kern", 3, "Aktionslog löschen", { nutzerid: nutzerid, logid: logid });
    },
    ausfuehren: (nutzerid: string, logid: string): void => {
      ajax("Kern", 16, "Aktionslog löschen", { nutzerid: nutzerid, logid: logid }, null, "dshProfil" + nutzerid + "Aktionsprotokoll").then((): void => {
        uiLaden.meldung("Kern", 16, null, { nutzerid: nutzerid, logid: logid });
      });
    }
  },
  details: (nutzerid: string, logid: string): void => {
    uiFenster.laden("Kern", 17, { nutzerid: nutzerid, logid: logid });
  },
  laden: (sortieren: { [key: string]: any }, id: string | "alle"): Promise<AnfrageErfolg> => {
    let datum: string;
    if (id == "alle") {
      // @TODO: Filter laden
    } else {
      datum = $("#" + id + "Datum").getWert();
    }
    return ajax("Kern", 18, false, { id: id, datum: datum, ...sortieren });
  }
};

export const identitaetsdiebstahl = (): void => {
  const passwortalt = $("#dshIdentitaetPasswortAlt").getWert();
  const passwortneu = $("#dshIdentitaetPasswortNeu").getWert();
  const passwortneu2 = $("#dshIdentitaetPasswortNeu2").getWert();
  const hinweise = $("#dshIdentitaetHinweise").getWert();
  ajax("Kern", 28, "Identitätsdiebstahl melden", { passwortalt: passwortalt, passwortneu: passwortneu, passwortneu2: passwortneu2, hinweise: hinweise }, 14);
};

export const aktivitaetsanzeige = {
  limit: 0,
  timeout: 0,
  ids: [] as string[],
  hinzufuegen: (id: string): void => {
    if (aktivitaetsanzeige.ids.indexOf(id) == -1) {
      aktivitaetsanzeige.ids.push(id);
    }
  },
  aktualisieren: (): void => {
    const jetzt = (new Date()).getTime();
    const timeout = aktivitaetsanzeige.timeout;
    const limit = aktivitaetsanzeige.limit;
    const freiezeit = timeout * 1000 - jetzt;
    const minuten = minutenGen(freiezeit);
    const prozent = prozentGen(freiezeit, limit * 1000 * 60);

    for (let i = 0; i < aktivitaetsanzeige.ids.length; i++) {
      const balken = $("#" + aktivitaetsanzeige.ids[i] + "I");
      const uebrig = $("#" + aktivitaetsanzeige.ids[i] + "UebrigAbs");
      if (balken) {
        balken.setCss("width", prozent + "%");
      }
      if (uebrig) {
        if (minuten == 1) {
          uebrig.setHTML("etwa eine Minute");
        } else if (minuten == 0) {
          uebrig.setHTML("weniger als eine Minute");
        } else {
          uebrig.setHTML(minuten + " Minuten");
        }
      }
    }

    if (minuten < 0) {
      abmelden.ausfuehren(true);
      return;
    } else if (minuten < 2) {
      uiLaden.meldung("Kern", 30, "Session verlängern?");

      ajax("Kern", 37, false, null).then((r): void => {
        aktivitaetsanzeige.limit = r.Limit;
        aktivitaetsanzeige.timeout = r.Ende;
        const ende = new Date(r.Ende * 1000);

        // Text neu schreiben
        let datum = fuehrendeNull(ende.getDate()) + ".";
        datum += fuehrendeNull(ende.getMonth() + 1) + "." + ende.getFullYear();
        let zeit = fuehrendeNull(ende.getHours()) + ":";
        zeit += fuehrendeNull(ende.getMinutes());

        const minuten = minutenGen(ende.getTime() - jetzt);

        for (let i = 0; i < aktivitaetsanzeige.ids.length; i++) {
          let text = "Aktiv bis " + datum + " um " + zeit + " Uhr - noch ";
          text += "<span id=\"" + aktivitaetsanzeige.ids[i] + "Infotext" + "\">";
          if (minuten == 1) {
            text += "etwa eine Minute";
          } else if (minuten == 0) {
            text += "weniger als eine Minute";
          } else {
            text += minuten + " Minuten";
          }
          text += "</span>.";
          $("#" + aktivitaetsanzeige.ids[i] + "Infotext").setHTML(text);
        }
      });
      setTimeout((): void => { aktivitaetsanzeige.aktualisieren(); }, 10000);
    } else {
      setTimeout((): void => { aktivitaetsanzeige.aktualisieren(); }, 45000);
    }
  }
};