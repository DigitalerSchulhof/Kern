kern.schulhof.verwaltung.module = {
  details: (modulname) => {
    ui.fenster.laden("Kern", 19, null, {modulname:modulname});
  },
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