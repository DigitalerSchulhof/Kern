import $ from "ts/eQuery";
import { SortierParameter } from "module/UI/ts/elemente/tabelle";
import ajax, { AjaxAntwort, ANTWORTEN } from "ts/ajax";
import * as uiFenster from "module/UI/ts/elemente/fenster";

export const suchen = (sortieren: SortierParameter): AjaxAntwort<ANTWORTEN["Kern"][47]> => ajax("Kern", 47, false, { ...sortieren });
export const version = (modulname: string): AjaxAntwort<ANTWORTEN["Kern"][20]> => uiFenster.laden("Kern", 20, { modulname: modulname });

export const alteEinblenden = (id: number): void => {
  const wert = $("#" + id).getWert();
  const feld = $("#" + id + "Feld");
  if (wert == "1") {
    feld.einblenden();
  } else {
    feld.ausblenden();
  }
};

export default {
  suchen: suchen,
  version: version,
  alteEinblenden: alteEinblenden,
};