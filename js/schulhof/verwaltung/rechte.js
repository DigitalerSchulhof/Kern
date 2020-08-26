kern.schulhof.verwaltung.rechte = {

};
kern.schulhof.verwaltung.rollen = {
  suchen: (sortieren) => core.ajax("Kern", 43, null, {...sortieren}),
  loeschen: {
    fragen: (id) => ui.laden.meldung("Kern", 35, "Rolle löschen", {id: id}),
    ausfuehren: (id) => core.ajax("Kern", 45, "Rolle löschen", {id: id}, 34, "dshVerwaltungRollen"),
  },
  daten: (id) => {
    let r = {};
    r.bezeichnung = $("#"+id+"Bezeichnung").getWert();
    r.rechte = kern.rechtebaum.rechte($("#"+id+"Rechtebaum"));
    return r;
  },
  bearbeiten: {
    fenster: (id) => {
      ui.fenster.laden("Kern", 49, null, {id: id});
    },
    speichern: (id) => {
      let daten = kern.schulhof.verwaltung.rollen.daten("dshBearbeitenRolle"+id);
      core.ajax("Kern", 46, "Rolle bearbeiten", {id: id, ...daten}, 36, "dshVerwaltungRollen");
    },
  },
  neu: {
    fenster: () => ui.fenster.laden("Kern", 19, null),
    speichern: () => {
      let daten = kern.schulhof.verwaltung.rollen.daten("dshNeueRolle");
      core.ajax("Kern", 44, "Rolle anlegen", daten, 33, "dshVerwaltungRollen");
    }
  }
}