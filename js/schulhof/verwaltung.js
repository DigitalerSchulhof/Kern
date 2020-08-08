kern.schulhof.verwaltung = {
  module: {
    status: (id) => {
      var feld = $("#"+id);
      // TODO: Aktualität des Moduls prüfen und ggf. eine Aktualiseren-Knopf erzeugen
      var aktuell = "aktuell";
      if (aktuell == "aktuell") {
        var rueckgabe = ui.laden.Komponente({komponente:"IconKnopf", inhalt:"Aktuell", icon:"Konstanten::HAKEN"});
      } else {
        var version = "Aktualisieren zu "+aktuell;
        var klick = "kern.schulhof.verwaltung.module.update('"+modul+"')";
        var rueckgabe = ui.laden.Komponente({komponente:"IconKnopf", typ:"Erfolg", inhalt:version, icon:"Konstanten::UPDATE", klickaktion:klick});
      }
      feld.setHTML(rueckgabe);
    }
  }
};
