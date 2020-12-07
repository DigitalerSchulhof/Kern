import $ from "ts/eQuery";

export const anzeigen = (id: string): void => {
  const wert = $("#" + id + "Anzeigen").getWert();
  const feld = $("#" + id + "A");
  if (wert === "0") {
    feld.ausblenden();
  } else {
    feld.einblenden();
  }
};