import $ from "ts/eQuery";

export const einblenden = (elm: HTMLElement): void => {
  const el = $(elm);
  const m = el.getID()?.match(/(.+)(?:Kopf|Koerper)(\d+)/);
  if(m) {
    const id = m[1];
    const nr = m[2];
    $("#" + id).finde(".dshUiReiterKoerper>.dshUiReiterKoerper").addKlasse("dshUiReiterKoerperInaktiv").removeKlasse("dshUiReiterKoerperAktiv");
    $("#" + id + "Koerper" + nr).addKlasse("dshUiReiterKoerperAktiv").removeKlasse("dshUiReiterKoerperInaktiv");
  }
};

export const ausblenden = (elm: HTMLElement | true, ev: MouseEvent): void => {
  if (elm === true) {
    if ($(ev.target).hatAttr("href") || $(ev.target).parent("[href]").existiert()) {
      $("#dshHauptnavigationReiter").kinder(".dshUiReiterKoerper").kinder(".dshUiReiterKoerperAktiv").toggleKlasse("dshUiReiterKoerperInaktiv", "dshUiReiterKoerperAktiv");
    }
    return;
  }
  const el = $(elm);
  const m = el.getID()?.match(/(.+)(?:Kopf|Koerper)(\d+)/);
  if(m) {
    const id = m[1];
    const nr = m[2];
    $("#" + id).finde(".dshUiReiterKoerper>.dshUiReiterKoerper").removeKlasse("dshUiReiterKoerperInaktiv").addKlasse("dshUiReiterKoerperAktiv");
    $("#" + id + "Koerper" + nr).removeKlasse("dshUiReiterKoerperAktiv").addKlasse("dshUiReiterKoerperInaktiv");
  }
};

export default {
  einblenden: einblenden,
  ausblenden: ausblenden,
};