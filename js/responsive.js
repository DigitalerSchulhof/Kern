var resizecheck = () => {
  console.log("Breite:" + document.body.clientWidth);
  if(document.body.clientWidth >= 1024) {
    $("body").addKlasse("dshSeiteP");
    $("body").removeKlasse("dshSeiteT", "dshSeiteH");
  } else if(document.body.clientWidth >= 768) {
    $("body").addKlasse("dshSeiteT");
    $("body").removeKlasse("dshSeiteP", "dshSeiteH");
  } else {
    $("body").addKlasse("dshSeiteH");
    $("body").removeKlasse("dshSeiteP", "dshSeiteT");
  }
};
window.addEventListener("resize", resizecheck);
window.addEventListener("load", () => {
  window.dispatchEvent(new Event("resize"));
});
