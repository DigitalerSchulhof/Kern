kern.schulhof.verwaltung.rechte = {

};
kern.schulhof.verwaltung.rollen = {
  loeschen: {
    fragen: (id) => ui.laden.meldung("Kern", 35, "Rolle löschen", {id: id}),
    ausfuehren: (id) => core.ajax("Kern", 45, "Rolle löschen", {id: id}, 34, ["dshVerwaltungRollen"]),
  },
  daten: (id) => {
    let r = {};
    r.bezeichnung = $("#"+id+"Bezeichnung").getWert();
    r.rechte = kern.rechtebaum.rechte($("#"+id+"Rechtebaum"));
    return r;
  },
  speichern: (id) => {
    let daten = kern.schulhof.verwaltung.rollen.daten("dshBearbeitenRolle");
    core.ajax("Kern", 46, "Rolle bearbeiten", {id: id, ...daten}, 36);
  },
  neu: () => {
    let daten = kern.schulhof.verwaltung.rollen.daten("dshNeueRolle");
    core.ajax("Kern", 44, "Rolle anlegen", daten, 33);
  }
}