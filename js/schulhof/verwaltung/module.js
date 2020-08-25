kern.schulhof.verwaltung.module = {
  suchen: (sortieren) => core.ajax("Kern", 47, null, {...sortieren}),
  version: (modulname) => {
    ui.fenster.laden("Kern", 20, null, {modulname:modulname});
  },
  alteEinblenden: (id) => {
    var wert = $("#"+id).getWert();
    var feld = $("#"+id+"Feld");
    if (wert == "1") {
      feld.einblenden();
    } else {
      feld.ausblenden()
    }
  }
};