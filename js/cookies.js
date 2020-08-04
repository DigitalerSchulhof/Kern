kern.cookies = {
  setzen: (aktiv, typ) => {
    if (ui.check.toggle(aktiv) && (typ == "DSH" || typ == "EXT")) {
      core.ajax("Kern", 5, ["Cookies setzen", "Cookies werden geändert"], {aktiv: aktiv, typ: typ});
    }
  }
};
