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
        var inhalt = "Aktualisieren zu "+rueck;
        var klick = "kern.schulhof.verwaltung.module.update('"+modulid+"')";
        ui.laden.komponente({komponente:"IconKnopf", art:"Warnung", inhalt:inhalt, icon:"fas fa-sync-alt", klickaktion:klick}).then((r) => {
          feld.setHTML(r.Code);
        });
      } else {
        ui.laden.komponente({komponente:"IconKnopf", art:"Erfolg", inhalt:"Aktuell", icon:"fas fa-check"}).then((r) => {
          console.log(r);
          feld.setHTML(r.Code);
        });
      }
    }
  }
};
