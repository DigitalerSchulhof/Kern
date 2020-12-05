import $ from "ts/eQuery";
import ajax, { AjaxAntwort, ANTWORTEN } from "ts/ajax";
import { rechte as rechtebaumRechte } from "module/Kern/ts/rechtebaum";
import { SortierParameter } from "module/UI/ts/elemente/tabelle";
import * as uiLaden from "module/UI/ts/elemente/laden";
import * as uiFenster from "module/UI/ts/elemente/fenster";

export const rechte = {

};

interface RolleI {
  bezeichnung: string;
  rechte: string[];
}

export const rollen = {
  suchen: (sortieren: SortierParameter): AjaxAntwort<ANTWORTEN["Kern"][43]> => ajax("Kern", 43, false, { ...sortieren }),
  loeschen: {
    fragen: (id: number): void => uiLaden.meldung("Kern", 35, "Rolle löschen", { id: id }),
    ausfuehren: (id: number): AjaxAntwort<ANTWORTEN["Kern"][45]> => ajax("Kern", 45, "Rolle löschen", { id: id }, 34, "dshVerwaltungRollen"),
  },
  daten: (id: string): RolleI => {
    return {
      bezeichnung: $("#" + id + "Bezeichnung").getWert(),
      rechte: rechtebaumRechte($("#" + id + "Rechtebaum"))
    };
  },
  bearbeiten: {
    fenster: (id: number): AjaxAntwort<ANTWORTEN["Kern"][49]> => uiFenster.laden("Kern", 49, { id: id }),
    speichern: (id: number): void => {
      const daten = rollen.daten("dshBearbeitenRolle" + id);
      ajax("Kern", 46, "Rolle bearbeiten", { id: id, ...daten }, 36, "dshVerwaltungRollen");
    },
  },
  neu: {
    fenster: (): AjaxAntwort<ANTWORTEN["Kern"][19]> => uiFenster.laden("Kern", 19),
    speichern: (): void => {
      const daten = rollen.daten("dshNeueRolle");
      ajax("Kern", 44, "Rolle anlegen", daten, 33, "dshVerwaltungRollen");
    }
  }
};

export default {
  rechte: rechte,
  rollen: rollen
};