import ajax from "ts/ajax";
import { ToggleWert } from "ts/AnfrageDaten";
import { neuladen } from "ts/laden";

export const setzen = (aktiv: ToggleWert, typ: "DSH" | "EXT"): void => {
  ajax("Kern", 5, { titel: "Cookies setzen", beschreibung: "Cookies werden geändert" }, { aktiv: aktiv, typ: typ }).then(() => neuladen());
};
