import $ from "ts/eQuery";
import * as uiFenster from "module/UI/ts/elemente/fenster";
import * as uiLaden from "module/UI/ts/elemente/laden";
import ajax, { AjaxAntwort, ANTWORTEN } from "ts/ajax";
import { rechte as rechtebaumRechte } from "module/Kern/ts/rechtebaum";
import { SortierParameter } from "module/UI/ts/elemente/tabelle";
import { laden } from "module/UI/ts/generieren";

export const rechteundrollen = (id: number, ueberschreiben: boolean): AjaxAntwort<ANTWORTEN["Kern"][38]> => uiFenster.laden("Kern", 38, { id: id }, null, null, ueberschreiben);

export const rolleaktion = (id: number, rolle: string): void => {
  const wert = $("#dshVerwaltungRechteUndRollen" + id + "Rolle" + rolle).getWert();
  let aktion = "nehmen";
  if (wert === "1") {
    aktion = "vergeben";
  }
  ajax("Kern", 39, "Rolle " + aktion, { id: id, rolle: rolle, wert: wert }).then(() => rechteundrollen(id, true).then(() => uiLaden.aus()), r => {
    if (!r.Erfolg) {
      $("#dshVerwaltungRechteUndRollen" + id + "Rolle" + rolle + "Toggle").setKlasse(wert === "1", "dshUiToggled");
      $("#dshVerwaltungRechteUndRollen" + id + "Rolle" + rolle).setWert((1 - parseInt(wert)).toString());
    }
  });
};

export const rechtespeichern = (id: number): void => {
  const rechtebaum = $("#dshVerwaltungRechte" + id);
  const rechte = rechtebaumRechte(rechtebaum);
  ajax("Kern", 42, "Rechte vergeben", { id: id, rechte: rechte }, 13);
};

export const rechteneuladen = (id: number): AjaxAntwort<ANTWORTEN["Kern"][41]> => ajax("Kern", 41, "Rechte aktualisieren", { id: id }, 32);

export const suche = (sortieren: SortierParameter): AjaxAntwort<ANTWORTEN["Kern"][31]> => {
  const vorname = $("#dshPersonenFilterVorname").getWert();
  const nachname = $("#dshPersonenFilterNachname").getWert();
  const klasse = $("#dshPersonenFilterKlasse").getWert();
  const schueler = $("#dshPersonenFilterArtenSchueler").getWert();
  const lehrer = $("#dshPersonenFilterArtenLehrer").getWert();
  const erzieher = $("#dshPersonenFilterArtenErziehungsberechtigte").getWert();
  const verwaltung = $("#dshPersonenFilterArtenVerwaltungsangestellte").getWert();
  const externe = $("#dshPersonenFilterArtenExterne").getWert();
  return ajax("Kern", 31, false, { vorname: vorname, nachname: nachname, klasse: klasse, schueler: schueler, lehrer: lehrer, erzieher: erzieher, verwaltung: verwaltung, externe: externe, ...sortieren });
};


export const profil = (id: number): AjaxAntwort<ANTWORTEN["Kern"][32]> => uiFenster.laden("Kern", 32, { id: id });

export const loeschen = {
  fragen: (id: number, nutzerkonto: boolean): void => uiLaden.meldung("Kern", 25, "Person löschen", { id: id, nutzerkonto: nutzerkonto }),
  ausfuehren: (id: number, art: "person" | "nutzerkonto"): Promise<void> => ajax("Kern", 33, "Person löschen", { id: id, art: art }, null, "dshVerwaltungPersonen").then(() => {
    uiLaden.meldung("Kern", 26, null, { art: art });
  })
};

export const neu = {
  person: {
    fenster: (): AjaxAntwort<ANTWORTEN["Kern"][13]> => uiFenster.laden("Kern", 13),
    erstellen: (): void => {
      const art = $("#dshNeuePersonArt").getWert();
      const geschlecht = $("#dshNeuePersonGeschlecht").getWert();
      const titel = $("#dshNeuePersonTitel").getWert();
      const vorname = $("#dshNeuePersonVorname").getWert();
      const nachname = $("#dshNeuePersonNachname").getWert();
      const kuerzel = $("#dshNeuePersonKuerzel").getWert();
      const nutzerkonto = $("#dshNeuePersonNutzerkonto").getWert();
      const benutzername = $("#dshNeuePersonBenutzername").getWert();
      const mail = $("#dshNeuePersonMail").getWert();
      ajax("Kern", 34, "Neue Person erstellen", { art: art, geschlecht: geschlecht, titel: titel, vorname: vorname, nachname: nachname, kuerzel: kuerzel, nutzerkonto: nutzerkonto, benutzername: benutzername, mail: mail }, null, "dshVerwaltungPersonen").then((r) => {
        if (nutzerkonto == "1") {
          const id = r.ID;
          ajax("Kern", 35, "Neues Nutzerkonto erstellen", { id: id, benutzername: benutzername, mail: mail }, 29, "dshVerwaltungPersonen");
        } else {
          uiLaden.meldung("Kern", 27, null);
        }
      });
    },
  },
  nutzerkonto: {
    anzeigen: (id: number, laden: boolean): void => {
      laden = laden || false;
      uiFenster.laden("Kern", 36, { id: id, laden: laden });
    },
    erstellen: (id: number): void => {
      const benutzername = $("#dshNeuesNutzerkonto" + id + "Benutzername").getWert();
      const mail = $("#dshNeuesNutzerkonto" + id + "Mail").getWert();
      ajax("Kern", 35, "Neues Nutzerkonto erstellen", { id: id, benutzername: benutzername, mail: mail }, null, "dshVerwaltungPersonen").then(() => {
        uiLaden.meldung("Kern", 28, null, { id: id });
      });
    }
  }
};

interface PersonenDaten {
  id: number,
  pool: string;
  nutzerkonto: string;
  recht: string,
  gewaehlt: string,
  vorname: string;
  nachname: string,
  lehrer: string;
  schueler: string;
  erziehungsberechtigte: string;
  verwaltungsangestellte: string;
  externe: string;
}

export const benutzername = (): void => {
  const art = $("#dshNeuePersonArt").getWert();
  const feld = $("#dshNeuePersonBenutzername");
  const vorname = $("#dshNeuePersonVorname").getWert().replace(/ /g, "");
  const nachname = $("#dshNeuePersonNachname").getWert().replace(/ /g, "");

  if (art == "l") {
    feld.setWert(vorname.substr(0, 1) + nachname.substr(0, 7) + "-" + art.toUpperCase());
  } else {
    feld.setWert(nachname.substr(0, 8) + vorname.substr(0, 3) + "-" + art.toUpperCase());
  }
};
export const wahl = {
  daten: (id: number): PersonenDaten => ({
    id: id,
    pool: $("#" + id + "Pool").getWert(),
    nutzerkonto: $("#" + id + "Nutzerkonto").getWert(),
    recht: $("#" + id + "Recht").getWert(),
    gewaehlt: $("#" + id + "Gewaehlt").getWert(),
    vorname: $("#" + id + "Vorname").getWert(),
    nachname: $("#" + id + "Nachname").getWert(),
    schueler: $("#" + id + "ArtenSchueler").getWert(),
    lehrer: $("#" + id + "ArtenLehrer").getWert(),
    erziehungsberechtigte: $("#" + id + "ArtenErziehungsberechtigte").getWert(),
    verwaltungsangestellte: $("#" + id + "ArtenVerwaltungsangestellte").getWert(),
    externe: $("#" + id + "ArtenExterne").getWert()
  }),
  einzeln: {
    suchen: (id: number): void => {
      const feld = $("#" + id + "Suchergebnisse");
      feld.setHTML(laden.icon("Die Suche läuft..."));
      ajax("Kern", 50, false, { ...wahl.daten(id) }).then((r) => {
        feld.setHTML(r.ergebnisse);
      });
    },
    dazu: (feldid: number, personid: number, inhalt: string, perart: "person" | "nutzerkonto"): void => {
      const gewaehlt = $("#" + feldid + "Gewaehlt").getWert();
      if (gewaehlt.length != 0) {
        $("#" + feldid + "Gewaehlt").setWert(gewaehlt + "," + personid);
      } else {
        $("#" + feldid + "Gewaehlt").setWert(personid.toString());
      }
      const knopfid = feldid + "Person" + personid;
      ajax("UI", 2, false, { komponente: "IconKnopfPerson", inhalt: inhalt, personart: perart, id: knopfid, klickaktion: "kern.schulhof.verwaltung.personen.wahl.einzeln.entfernen('" + feldid + "', '" + personid + "', '" + knopfid + "')" }).then((r) => {
        const feld = $("#" + feldid + "GewaehltFeld");
        feld.setHTML(r.Code + " " + feld.getHTML());
      });
      wahl.einzeln.suchen(feldid);
    },
    entfernen: (feldid: number, personid: number, knopfid: string): void => {
      const gewaehlt = $("#" + feldid + "Gewaehlt").getWert();
      if (gewaehlt.length != 0) {
        const gewaehltArr = gewaehlt.split(",");
        gewaehltArr.splice(gewaehltArr.indexOf(personid.toString()), 1);
        $("#" + feldid + "Gewaehlt").setWert(gewaehltArr.join(","));
      }
      const knopf = document.getElementById(knopfid);
      knopf.parentElement.removeChild(knopf);
      wahl.einzeln.suchen(feldid);
    }
  }
};

export default {
  wahl: wahl,
  benutzername: benutzername,
  neu: neu,
  rolleaktion: rolleaktion,
  rechteneuladen: rechteneuladen,
  rechtespeichern: rechtespeichern,
  suche: suche,
  profil: profil,
  rechteundrollen: rechteundrollen,
  loeschen: loeschen
};