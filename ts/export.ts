import navigation from "./navigation";
import schulhof from "./schulhof/export";
import * as filter from "./filter";
import * as kern from "./kern";
import * as rechtebaum from "./rechtebaum";
import * as cookies from "./cookies";

export default {
  navigation: navigation,
  schulhof: schulhof,
  modul: {
    einstellungen: kern.einstellungen
  },
  filter: filter,
  rechtebaum: rechtebaum,
  cookies: cookies,
  konfiguration: kern.konfiguration
};