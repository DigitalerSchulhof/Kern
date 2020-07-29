var resizecheck = () => {
  console.log(document.body.clientWidth);
  if(document.body.clientWidth >= 1024) {
    $("body").classList.add("dshSeiteP");
    $("body").classList.remove("dshSeiteT", "dshSeiteH");
  } else if(document.body.clientWidth >= 768) {
    $("body").classList.add("dshSeiteT");
    $("body").classList.remove("dshSeiteP", "dshSeiteH");
  } else {
    $("body").classList.add("dshSeiteH");
    $("body").classList.remove("dshSeiteP", "dshSeiteT");
  }
};
window.addEventListener("resize", resizecheck);
window.addEventListener("load", resizecheck);