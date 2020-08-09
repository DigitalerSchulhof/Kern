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
    },
    details: (modulname) => {
      core.ajax("Kern", 19, null, {modulname:modulname}).then((r) => {
        ui.fenster.anzeigen(r.Code);
      });
    },
    version: (modulname) => {
      core.ajax("Kern", 20, null, {modulname:modulname}).then((r) => {
        ui.fenster.anzeigen(r.Code);
      });
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
  }
};

kern.modul = {
  einstellungen: {
    schuldaten: () => {
      var schulname = $("#dshModulKernSchulname").getWert();
      var schulort  = $("#dshModulKernSchulort").getWert();
      var strhnr    = $("#dshModulKernSchulstrhnr").getWert();
      var plzort    = $("#dshModulKernSchulplzort").getWert();
      var telefon   = $("#dshModulKernSchultelefon").getWert();
      var fax       = $("#dshModulKernSchulfax").getWert();
      var mail      = $("#dshModulKernSchulmail").getWert();
      var domain    = $("#dshModulKernSchuldomain").getWert();
      core.ajax("Kern", 21, "Schuldaten ändern", {schulname:schulname, schulort:schulort, strhnr:strhnr, plzort:plzort, telefon:telefon, fax:fax, mail:mail, domain:domain});
    },
    vertreter: () => {

    },
    mail: {
      aendern: () => {
      },
      testen: () => {

      }
    },
    ldap: {
      aendern: () => {
      },
      testen: () => {

      }
    }
  }
}
