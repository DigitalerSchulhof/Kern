kern.schulhof.verwaltung.personen = {
  rechteundrollen: (id, ueberschreiben) => {
    return ui.fenster.laden("Kern", 38, null, {id:id}, null, null, ueberschreiben);
  },
  rolleaktion: (id, rolle) => {
    let wert = $("#dshVerwaltungRechteUndRollen"+id+"Rolle"+rolle).getWert();
    let aktion = "nehmen";
    if(wert == "1") {
      aktion = "vergeben";
    }
    core.ajax("Kern", 39, "Rolle "+aktion, {id: id, rolle: rolle, wert: wert}).then(() => kern.schulhof.verwaltung.personen.rechteundrollen(id, true).then(() => ui.laden.aus()), r => {
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
  suche: (feld, id, sortieren) => {
    var vorname = $("#dshPersonenFilterVorname").getWert();
    var nachname = $("#dshPersonenFilterNachname").getWert();
    var klasse = $("#dshPersonenFilterKlasse").getWert();
    var schueler = $("#dshPersonenFilterSchueler").getWert();
    var lehrer = $("#dshPersonenFilterLehrer").getWert();
    var erzieher = $("#dshPersonenFilterErziehungsberechtigte").getWert();
    var verwaltung = $("#dshPersonenFilterVerwaltungsangestellte").getWert();
    var externe = $("#dshPersonenFilterExterne").getWert();
    core.ajax("Kern", 31, null, {vorname:vorname, nachname:nachname, klasse:klasse, schueler:schueler, lehrer:lehrer, erzieher:erzieher, verwaltung:verwaltung, externe:externe, ...sortieren}).then((r) => {
      if (r.Code) {
        feld.setHTML(r.Code);
      }
    });
  },
  profil: (id) => {
    ui.fenster.laden("Kern", 32, null, {id:id});
  },
  loeschen: {
    fragen: (id, nutzerkonto, laden) => {
      var laden = laden || '0';
      ui.laden.meldung("Kern", 25, "Person löschen", {id:id, laden:laden, nutzerkonto:nutzerkonto});
    },
    ausfuehren: (id, art, laden) => {
      core.ajax("Kern", 33, "Person löschen", {id:id, art:art}).then(() => {
        ui.laden.meldung("Kern", 26, null, {art:art});
        if (laden == '1') {
          ui.tabelle.sortieren('dshVerwaltungPersonen');
        }
      });
    }
  },
  neu: {
    person: () => {
      var art = $("#dshNeuePersonArt").getWert();
      var geschlecht = $("#dshNeuePersonGeschlecht").getWert();
      var titel = $("#dshNeuePersonTitel").getWert();
      var vorname = $("#dshNeuePersonVorname").getWert();
      var nachname = $("#dshNeuePersonNachname").getWert();
      var kuerzel = $("#dshNeuePersonKuerzel").getWert();
      var nutzerkonto = $("#dshNeuePersonNutzerkonto").getWert();
      var benutzername = $("#dshNeuePersonBenutzername").getWert();
      var mail = $("#dshNeuePersonMail").getWert();
      core.ajax("Kern", 34, "Neue Person erstellen", {art:art, geschlecht:geschlecht, titel:titel, vorname:vorname, nachname:nachname, kuerzel:kuerzel, nutzerkonto:nutzerkonto, benutzername:benutzername, mail:mail}).then((r) => {
        if (nutzerkonto == "1") {
          var id = r.ID;
          core.ajax("Kern", 35, "Neues Nutzerkonto erstellen", {id:id, benutzername:benutzername, mail:mail}).then((r) => {
            ui.laden.meldung("Kern", 29, null);
          });
        } else {
          ui.laden.meldung("Kern", 27, null);
        }
      });
    },
    nutzerkonto: {
      anzeigen: (id, laden) => {
        var laden = laden || '0';
        ui.fenster.laden("Kern", 36, null, {id:id, laden:laden});
      },
      erstellen: (id, laden) => {
        var laden = laden || '0';
        var benutzername = $("#dshNeuesNutzerkonto"+id+"Benutzername").getWert();
        var mail = $("#dshNeuesNutzerkonto"+id+"Mail").getWert();
        core.ajax("Kern", 35, "Neues Nutzerkonto erstellen", {id:id, benutzername:benutzername, mail:mail}, 28, "dshVerwaltungPersonen");
      }
    }
  },
  benutzername: () => {
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
  }
};