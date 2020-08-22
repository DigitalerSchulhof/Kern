kern.schulhof.verwaltung.rechte = {

};
kern.schulhof.verwaltung.rollen = {
  suche: (feld, id, sortieren) => {
    core.ajax("Kern", 43, null, {...sortieren}).then((r) => {
      if (r.Code) {
        feld.setHTML(r.Code);
      }
    });
  },
  loeschen: (id) => core.ajax("Kern", 45, "Rolle löschen", {id: id}, 34),
  daten: (id) => {
    let r = {};
    r.bezeichnung = $("#"+id+"Bezeichnung").getWert();
    r.rechte = kern.rechtebaum.rechte($("#"+id+"Rechtebaum"));
    return r;
  },
  speichern: () => {

  },
  neu: () => {
    let daten = kern.schulhof.verwaltung.rollen.daten("dshNeueRolle");
    core.ajax("Kern", 44, "Rolle anlegen", daten, 33);
  }
}