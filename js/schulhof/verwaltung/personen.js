kern.schulhof.verwaltung.personen = {
  rechteundrollen: (id, ueberschreiben) => {
    return ui.fenster.laden("Kern", 38, {id:id}, null, null, ueberschreiben);
  },
  rolleaktion: (id, rolle) => {
    let wert = $("#dshVerwaltungRechteUndRollen"+id+"Rolle"+rolle).getWert();
    let aktion = "nehmen";
    if(wert == "1") {
      aktion = "vergeben";
    }
    core.ajax("Kern", 39, "Rolle "+aktion, {id: id, rolle: rolle, wert: wert}).then(_ => kern.schulhof.verwaltung.personen.rechteundrollen(id, true).then(_ => ui.laden.aus()), r => {
      if(!r.Erfolg) {
        $("#dshVerwaltungRechteUndRollen"+id+"Rolle"+rolle+"Toggle").setKlasse(wert, "dshUiToggled");
        $("#dshVerwaltungRechteUndRollen"+id+"Rolle"+rolle).setWert(1-wert);
      }
    });
  },
  rechtespeichern: (id) => {
    let rechtebaum = $("#dshVerwaltungRechte"+id);
    let rechte = kern.rechtebaum.rechte(rechtebaum);
    core.ajax("Kern", 42, "Rechte vergeben", {id: id, rechte: rechte}, 13);
  },
  rechteneuladen: (id) => core.ajax("Kern", 41, "Rechte aktualisieren", {id: id}, 32),
  suche: (sortieren, id) => {
    var vorname = $("#dshPersonenFilterVorname").getWert();
    var nachname = $("#dshPersonenFilterNachname").getWert();
    var klasse = $("#dshPersonenFilterKlasse").getWert();
    var schueler = $("#dshPersonenFilterArtenSchueler").getWert();
    var lehrer = $("#dshPersonenFilterArtenLehrer").getWert();
    var erzieher = $("#dshPersonenFilterArtenErziehungsberechtigte").getWert();
    var verwaltung = $("#dshPersonenFilterArtenVerwaltungsangestellte").getWert();
    var externe = $("#dshPersonenFilterArtenExterne").getWert();
    return core.ajax("Kern", 31, null, {vorname:vorname, nachname:nachname, klasse:klasse, schueler:schueler, lehrer:lehrer, erzieher:erzieher, verwaltung:verwaltung, externe:externe, ...sortieren});
  },
  profil: (id) => {
    ui.fenster.laden("Kern", 32, {id:id});
  },
  loeschen: {
    fragen: (id, nutzerkonto) => ui.laden.meldung("Kern", 25, "Person löschen", {id:id, nutzerkonto:nutzerkonto}),
    ausfuehren: (id, art) => core.ajax("Kern", 33, "Person löschen", {id:id, art:art}, null, "dshVerwaltungPersonen").then(_ => {
      ui.laden.meldung("Kern", 26, null, {art:art});
    }),
  },
  neu: {
    person: {
      fenster: _ => {
        ui.fenster.laden("Kern", 13);
      },
      erstellen: _ => {
        var art = $("#dshNeuePersonArt").getWert();
        var geschlecht = $("#dshNeuePersonGeschlecht").getWert();
        var titel = $("#dshNeuePersonTitel").getWert();
        var vorname = $("#dshNeuePersonVorname").getWert();
        var nachname = $("#dshNeuePersonNachname").getWert();
        var kuerzel = $("#dshNeuePersonKuerzel").getWert();
        var nutzerkonto = $("#dshNeuePersonNutzerkonto").getWert();
        var benutzername = $("#dshNeuePersonBenutzername").getWert();
        var mail = $("#dshNeuePersonMail").getWert();
        core.ajax("Kern", 34, "Neue Person erstellen", {art:art, geschlecht:geschlecht, titel:titel, vorname:vorname, nachname:nachname, kuerzel:kuerzel, nutzerkonto:nutzerkonto, benutzername:benutzername, mail:mail}, null, "dshVerwaltungPersonen").then((r) => {
          if (nutzerkonto == "1") {
            var id = r.ID;
            core.ajax("Kern", 35, "Neues Nutzerkonto erstellen", {id:id, benutzername:benutzername, mail:mail}, 29, "dshVerwaltungPersonen");
          } else {
            ui.laden.meldung("Kern", 27, null);
          }
        });
      },
    },
    nutzerkonto: {
      anzeigen: (id, laden) => {
        var laden = laden || '0';
        ui.fenster.laden("Kern", 36, {id:id, laden:laden});
      },
      erstellen: (id, laden) => {
        var laden = laden || '0';
        var benutzername = $("#dshNeuesNutzerkonto"+id+"Benutzername").getWert();
        var mail = $("#dshNeuesNutzerkonto"+id+"Mail").getWert();
        core.ajax("Kern", 35, "Neues Nutzerkonto erstellen", {id:id, benutzername:benutzername, mail:mail}, null, "dshVerwaltungPersonen").then(_ => {
          ui.laden.meldung("Kern", 28, null, {id: id});
        });
      }
    }
  },
  benutzername: _ => {
    var art = $("#dshNeuePersonArt").getWert();
    var feld = $("#dshNeuePersonBenutzername");
    var vorname = $("#dshNeuePersonVorname").getWert();
    var nachname = $("#dshNeuePersonNachname").getWert();
    vorname = vorname.replace(/ /g, "");
    nachname = nachname.replace(/ /g, "");

    if (art == "l") {
      feld.setWert(vorname.substr(0,1)+nachname.substr(0,7)+"-"+art.toUpperCase());
    } else {
      feld.setWert(nachname.substr(0,8)+vorname.substr(0,3)+"-"+art.toUpperCase());
    }
  },
  wahl: {
    daten: (id) => ({
      id: id,
      pool: $("#"+id+"Pool").getWert(),
      nutzerkonto: $("#"+id+"Nutzerkonto").getWert(),
      recht: $("#"+id+"Recht").getWert(),
      gewaehlt: $("#"+id+"Gewaehlt").getWert(),
      vorname: $("#"+id+"Vorname").getWert(),
      nachname: $("#"+id+"Nachname").getWert(),
      schueler: $("#"+id+"ArtenSchueler").getWert(),
      lehrer: $("#"+id+"ArtenLehrer").getWert(),
      erziehungsberechtigte: $("#"+id+"ArtenErziehungsberechtigte").getWert(),
      verwaltungsangestellte: $("#"+id+"ArtenVerwaltungsangestellte").getWert(),
      externe: $("#"+id+"ArtenExterne").getWert()
    }),
    einzeln: {
      suchen: (id) => {
        var feld = $("#"+id+"Suchergebnisse");
        feld.setHTML(ui.generieren.laden.icon("Die Suche läuft..."));
        core.ajax("Kern", 50, null, {...kern.schulhof.verwaltung.personen.wahl.daten(id)}).then((r) => {
          feld.setHTML(r.ergebnisse);
        });
      },
      dazu: (feldid, personid, inhalt, perart) => {
        var gewaehlt = $("#"+feldid+"").getWert();
        if (gewaehlt.length != 0) {
          $("#"+id+"").setWert(gewaehlt+","+personid);
        } else {
          $("#"+id+"").setWert(personid);
        }
        var knopfid = feldid+"Person"+personid;
        core.ajax("UI", 2, null, {komponente:"IconKnopfPerson", inhalt:inhalt, personart:perart, id:knopfid, klickaktion:"kern.schulhof.verwaltung.personen.wahl.einzeln.entfernen('"+personid+"', '"+knopfid+"')"}).then((r) => {
          feld = $("#"+id+"GewaehltFeld");
          feld.anhaengen(r.Code);
        });
        kern.schulhof.verwaltung.personen.wahl.einzeln.suchen(feldid);
      }
    }
  }
};