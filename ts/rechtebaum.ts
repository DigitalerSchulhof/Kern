import $, { eQuery } from "ts/eQuery";

// FIXME: Tut nicht
export const click = (elm: HTMLElement): void => {
  const el = $(elm);
  let p = el.parent();
  // Alle Kinder der Geschwister (Rechtebox) togglen
  if (el.hatKlasse("dshUiToggled")) {
    p.siblings().finde(".dshUiToggle").addKlasse("dshUiToggled");
    p.siblings().finde(".dshUiEingabefeld").setWert("1");
  } else {
    p.siblings().finde(".dshUiToggle").removeKlasse("dshUiToggled");
    p.siblings().finde(".dshUiEingabefeld").setWert("0");
  }
  // Eltern toggled geben/nehmen
  // Rechtebaum hochgehen
  while ((p = p.parent()).ist(".dshRechtebaumBox")) {
    const recht = p.parent().kinder(".dshRechtebaumRecht");
    // Alle benachbarten Rechte sind gesetzt?
    if (p.siblings(".dshRechtebaumBox").kinder(".dshRechtebaumRecht").finde(".dshUiToggle").hatKlasse("dshUiToggled") && p.kinder(".dshRechtebaumRecht").finde(".dshUiToggle").hatKlasse("dshUiToggled")) {
      // Elternteil Toggle setzen
      recht.finde(".dshUiToggle").addKlasse("dshUiToggled");
      recht.finde(".dshUiEingabefeld").setWert("1");
    } else {
      // Elternteil Toggle setzen
      recht.finde(".dshUiToggle").removeKlasse("dshUiToggled");
      recht.finde(".dshUiEingabefeld").setWert("0");
    }
  }
};
export const einausfahren = (elm: HTMLElement): void => {
  const el = $(elm);
  const r = el.parent();
  const ausfahren = r.parent().hatKlasse("dshRechtebaumEingefahren");
  if (ausfahren) {
    r.parent().removeKlasse("dshRechtebaumEingefahren");
    r.siblings().setCss("display", "");
  } else {
    r.parent().addKlasse("dshRechtebaumEingefahren");
    r.siblings().setCss("display", "none");
  }
};

export const rechte = (baum: eQuery): string[] => {
  const r: string[] = [];
  const rechtecheck = (box: eQuery, pfad: string) => {
    box.each(function () {
      const recht = this.kinder(".dshRechtebaumRecht");
      const kn = this.getAttr("data-knoten");
      if (recht.finde(".dshUiToggled").existiert()) {
        if (this.ist(".dshRechtebaumHatKinder")) {
          r.push((pfad + "." + kn + ".*").substr(2));
        } else {
          r.push((pfad + "." + kn).substr(2));
        }
      } else {
        if (this.ist(".dshRechtebaumHatKinder")) {
          rechtecheck(this.kinder(".dshRechtebaumBox"), pfad + "." + kn);
        }
      }
    });
  };
  rechtecheck(baum.kinder(".dshRechtebaumBox"), "");
  return r;
};