kern.cookies = {
  setzen: (aktiv, typ) => {
    core.ajax("Kern", 5, ["Cookies setzen", "Cookies werden geändert"], {aktiv: aktiv, typ: typ}).then(_ => core.neuladen());
  }
};
