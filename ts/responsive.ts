import $ from "ts/eQuery";

export const resizecheck = (): void => {
  if (document.body.clientWidth >= 1024) {
    $("body").addKlasse("dshSeiteP");
    $("body").removeKlasse("dshSeiteT", "dshSeiteH");
  } else if (document.body.clientWidth >= 768) {
    $("body").addKlasse("dshSeiteT");
    $("body").removeKlasse("dshSeiteP", "dshSeiteH");
  } else {
    $("body").addKlasse("dshSeiteH");
    $("body").removeKlasse("dshSeiteP", "dshSeiteT");
  }
};

export const resize = resizecheck;

export const load = ():void => {
  window.dispatchEvent(new Event("resize"));
};