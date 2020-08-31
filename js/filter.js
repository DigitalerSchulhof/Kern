kern.filter = {
  anzeigen: (id) => {
    var wert = $("#"+id+"Anzeigen").getWert();
    var feld = $("#"+id+"A");
    if (wert == 0) {
      feld.ausblenden();
    } else {
      feld.einblenden();
    }
  },
  anlegen: _ => {

  }
};