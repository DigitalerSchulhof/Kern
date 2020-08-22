kern.schulhof.verwaltung.module = {
  versionNeuer: (versionalt, versionneu) => {
    var va = versionalt.split(".");
    var vn = versionneu.split(".");
    for (var i=0; i<vn.length; i++) {
      if (va[i] !== undefined) {
        if (vn[i] > va[i]) {
          return true;
        }
      } else {
        return true;
      }
    }
    return false;
  },
  status: (modulid, version) => {
    var feld = $("#dshVerwaltungModuleStatus"+modulid);
    // TODO: Aktualität des Moduls prüfen und ggf. eine Aktualiseren-Knopf erzeugen
    var rueck = version;
    if (kern.schulhof.module.versionNeuer(version, rueck)) {
      var inhalt = "Aktualisieren zu "+rueck;
      var klick = "kern.schulhof.module.update('"+modulid+"')";
      ui.laden.komponente({komponente:"IconKnopf", art:"Warnung", inhalt:inhalt, icon:"fas fa-sync-alt", klickaktion:klick}).then((r) => {
        feld.setHTML(r.Code);
      });
    } else {
      ui.laden.komponente({komponente:"IconKnopf", art:"Erfolg", inhalt:"Aktuell", icon:"fas fa-check"}).then((r) => {
        feld.setHTML(r.Code);
      });
    }
  },
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