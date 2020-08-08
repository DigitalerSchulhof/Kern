kern.schulhof.verwaltung = {
  module: {
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
      if (kern.schulhof.verwaltung.module.versionNeuer(version, rueck)) {
        var inhalt = "Aktualisieren zu "+reuck;
        var klick = "kern.schulhof.verwaltung.module.update('"+modulid+"')";
        ui.laden.Komponente({komponente:"IconKnopf", art:"Erfolg", inhalt:inhalt, icon:"Konstanten::UPDATE", klickaktion:klick}).then((r) => {
          feld.setHTML(r.code);
        });
      } else {
        ui.laden.Komponente({komponente:"IconKnopf", inhalt:"Aktuell", icon:"Konstanten::HAKEN"}).then((r) => {
          feld.setHTML(r.code);
        });
      }
    }
  }
};
