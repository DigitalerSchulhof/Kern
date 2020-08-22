kern.rechtebaum = {
  click: (el) => {
    el = $(el);
    let p = el.parent();
    // Alle Kinder der Geschwister (Rechtebox) togglen
    if(el.hatKlasse("dshUiToggled")) {
      p.siblings().finde(".dshUiToggle").addKlasse("dshUiToggled");
      p.siblings().finde(".dshUiEingabefeld").setWert("1");
    } else {
      p.siblings().finde(".dshUiToggle").removeKlasse("dshUiToggled");
      p.siblings().finde(".dshUiEingabefeld").setWert("0");
    }
    // Eltern toggled geben/nehmen
    // Rechtebaum hochgehen
    while((p = p.parent()).ist(".dshRechtebaumBox")) {
      let recht = p.parent().kinderSelector(".dshRechtebaumRecht");
      // Alle benachbarten Rechte sind gesetzt?
      if(p.siblingsSelector(".dshRechtebaumBox").kinderSelector(".dshRechtebaumRecht").finde(".dshUiToggle").hatKlasse("dshUiToggled") && p.kinderSelector(".dshRechtebaumRecht").finde(".dshUiToggle").hatKlasse("dshUiToggled")) {
        // Elternteil Toggle setzen
        recht.finde(".dshUiToggle").addKlasse("dshUiToggled");
        recht.finde(".dshUiEingabefeld").setWert("1");
      } else {
        // Elternteil Toggle setzen
        recht.finde(".dshUiToggle").removeKlasse("dshUiToggled");
        recht.finde(".dshUiEingabefeld").setWert("0");
      }
    }
  },
  einausfahren: (el) => {
    el = $(el);
    let r = el.parent();
    let ausfahren = r.parent().hatKlasse("dshRechtebaumEingefahren");
    if(ausfahren) {
      r.parent().removeKlasse("dshRechtebaumEingefahren");
      r.siblings().setCss("display", "");
    } else {
      r.parent().addKlasse("dshRechtebaumEingefahren");
      r.siblings().setCss("display", "none");
    }
  },
  rechte: (baum) => {
    let r = [];
    let rechtecheck = (box, pfad) => {
      box.each(b => {
        b = $(b);
        let recht = b.kinderSelector(".dshRechtebaumRecht");
        kn = b.getAttr("data-knoten");
        if(recht.finde(".dshUiToggled").existiert()) {
          if(b.ist(".dshRechtebaumHatKinder")) {
            r.push((pfad+"."+kn+".*").substr(2));
          } else {
            r.push((pfad+"."+kn).substr(2))
          }
        } else {
          if(b.ist(".dshRechtebaumHatKinder")) {
            rechtecheck(b.kinderSelector(".dshRechtebaumBox"), pfad+"."+kn);
          }
        }
      });
    };
    rechtecheck(baum.kinderSelector(".dshRechtebaumBox"), "");
    return r;
  }
};