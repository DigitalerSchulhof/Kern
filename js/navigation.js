kern.navigation = {
  einblenden: (el) => {
    el = $(el);
    let m = el.getID().match(/(.+)(?:Kopf|Koerper)(\d+)/);
    let id = m[1];
    let nr = m[2];
    $("#"+id).finde(".dshUiReiterKoerper>.dshUiReiterKoerper").addKlasse("dshUiReiterKoerperInaktiv").removeKlasse("dshUiReiterKoerperAktiv");
    $("#"+id+"Koerper"+nr).addKlasse("dshUiReiterKoerperAktiv").removeKlasse("dshUiReiterKoerperInaktiv");
  },
  ausblenden: (el) => {
    el = $(el);
    let m = el.getID().match(/(.+)(?:Kopf|Koerper)(\d+)/);
    let id = m[1];
    let nr = m[2];
    $("#"+id).finde(".dshUiReiterKoerper>.dshUiReiterKoerper").removeKlasse("dshUiReiterKoerperInaktiv").addKlasse("dshUiReiterKoerperAktiv");
    $("#"+id+"Koerper"+nr).removeKlasse("dshUiReiterKoerperAktiv").addKlasse("dshUiReiterKoerperInaktiv");
  }
};