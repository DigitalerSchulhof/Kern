import navigation from "./navigation";
import schulhof from "./schulhof/export";
import * as filter from "./filter";
import * as kern from "./kern";
import * as rechtebaum from "./rechtebaum";
import * as cookies from "./cookies";
import { SortierParameter } from "module/UI/ts/elemente/tabelle";
import { AnfrageAntwortCode, AnfrageAntwortLeer, AnfrageDatenLeer } from "ts/ajax";
import { PersonenArt, PersonenGeschlecht, ProfilArt, ToggleWert } from "module/UI/ts/_export";

export interface Antworten {
  0: AnfrageAntwortLeer;
  1: AnfrageAntwortLeer;
  2: {
    Limit: number;
    Ende: number;
  };
  3: AnfrageAntwortLeer;
  4: AnfrageAntwortLeer;
  5: AnfrageAntwortLeer;
  6: AnfrageAntwortLeer;
  7: AnfrageAntwortLeer;
  8: AnfrageAntwortLeer;
  9: AnfrageAntwortLeer;
  10: AnfrageAntwortLeer;
  11: AnfrageAntwortLeer;
  12: AnfrageAntwortLeer;
  13: AnfrageAntwortCode;
  14: AnfrageAntwortLeer;
  15: AnfrageAntwortCode;
  16: AnfrageAntwortLeer;
  17: AnfrageAntwortCode;
  18: AnfrageAntwortCode;
  19: AnfrageAntwortCode;
  20: AnfrageAntwortCode;
  21: AnfrageAntwortLeer;
  22: AnfrageAntwortLeer;
  23: AnfrageAntwortLeer;
  24: AnfrageAntwortLeer;
  25: AnfrageAntwortLeer;
  26: AnfrageAntwortLeer;
  27: AnfrageAntwortLeer;
  28: AnfrageAntwortLeer;
  29: AnfrageAntwortLeer;
  30: {
    Meldung: string;
    Knoepfe: string;
  };
  31: AnfrageAntwortCode;
  32: AnfrageAntwortCode;
  33: AnfrageAntwortLeer;
  34: {
    ID: number;
  };
  35: AnfrageAntwortCode;
  36: AnfrageAntwortLeer;
  37: {
    Limit: number;
    Ende: number;
  };
  38: AnfrageAntwortCode;
  39: AnfrageAntwortLeer;
  40: AnfrageAntwortLeer;
  41: AnfrageAntwortLeer;
  42: AnfrageAntwortLeer;
  43: AnfrageAntwortCode;
  44: AnfrageAntwortLeer;
  45: AnfrageAntwortLeer;
  46: AnfrageAntwortLeer;
  47: AnfrageAntwortCode;
  48: AnfrageAntwortLeer;
  49: AnfrageAntwortCode;
  50: {
    ergebnisse: string;
  }
}

interface PersonenDaten {
  art: PersonenArt,
  geschlecht: PersonenGeschlecht,
  titel: string,
  vorname: string;
  nachname: string,
}

export interface Daten {
  0: {
    benutzer: string;
    passwort: string;
  },
  1: AnfrageDatenLeer,
  2: AnfrageDatenLeer,
  3: {
    benutzer: string;
    mail: string;
  },
  4: {
    mail: string;
  },
  5: {
    aktiv: ToggleWert,
    typ: "DSH" | "EXT",
  },
  6: PersonenDaten & {
    klasse: string,
    passwort: string,
    passwort2: string,
    mail: string,
    datenschutz: ToggleWert,
    entscheidung: ToggleWert,
    korrektheit: ToggleWert,
    spamschutz: string,
    spamid: string;
  },
  7: {
    id: number;
    kuerzel: string;
  } & PersonenDaten,
  8: {
    id: number,
    benutzer: string;
    email: string;
  },
  9: {
    id: number;
    passwortalt: string;
    passwortneu: string;
    passwortneu2: string;
  },
  10: {
    id: number;
    inaktivitaetszeit: number;
    uebersichtselemente: number;
    wiki: ToggleWert,
  },
  11: {
    id: number,
    notifikationen: ToggleWert,
    blog: ToggleWert,
    termin: ToggleWert,
    galerie: ToggleWert,
  },
  12: AnfrageDatenLeer,
  13: AnfrageDatenLeer,
  14: {
    nutzerid: number;
    sessionid: number | "alle";
  },
  15: {
    id: number | "alle",
  } & SortierParameter,
  16: {
    nutzerid: number | "alle",
    logid: number | "alle",
  },
  17: {
    nutzerid: number,
    logid: number | "alle",
  },
  18: {
    id: number | "alle",
    datum: number,
  } & SortierParameter,
  19: {
    id: number,
    datum: number,
  } & SortierParameter,
  20: {
    modulname: string;
  },
  21: {
    schulname: string;
    schulort: string;
    strhnr: string;
    plzort: string;
    telefon: string;
    fax: string;
    mail: string;
    domain: string;
  },
  22: {
    slname: string;
    slmail: string;
    daname: string;
    damail: string;
    prname: string
    prmail: string;
    wename: string;
    wemail: string;
    adname: string;
    admail: string;
  },
  23: {
    mailadresse: string;
    mailtitel: string;
    mailuser: string;
    mailpass: string,
    mailhost: string;
    mailport: number;
    mailauth: ToggleWert;
    mailsigp: string;
    mailsigh: string;
  },
  24: {
    ldapaktiv: ToggleWert;
    ldapuser: string;
    ldappass: string;
    ldaphost: string;
    ldapport: number;
  },
  25: {
    aktionslog: ToggleWert
  },
  26: {
    mailadresse: string;
    mailtitel: string;
    mailuser: string;
    mailpass: string,
    mailhost: string;
    mailport: number;
    mailauth: ToggleWert;
    mailsigp: string;
    mailsigh: string;
  },
  28: {
    passwortalt: string;
    passwortneu: string;
    passwortneu2: string;
    hinweise: string;
  },
  29: {
    art: "Verzeichnisse" | "Schulhof";
  } & ({
    basis: string;
  } | {
    host: string;
    port: number;
    datenbank: string;
    benutzer: string;
    passwort: string
    schluessel: string;
  }),
  30: {
    fehler: [string, number][]
  },
  31: {
    vorname: string;
    nachname: string;
    klasse: string;
    schueler: ToggleWert,
    lehrer: ToggleWert;
    erzieher: ToggleWert;
    verwaltung: ToggleWert;
    externe: ToggleWert;
  },
  32: {
    id: number
  },
  33: {
    id: number,
    art: ProfilArt,
  },
  34: PersonenDaten & {
    kuerzel: string;
    nutzerkonto: ToggleWert;
    benutzername: string;
    mail: string;
  },
  35: {
    id: number;
    benutzername: string;
    mail: string;
  },
  36: {
    id: number,
  },
  37: AnfrageDatenLeer,
  38: {
    id: number
  },
  39: {
    id: number,
    rolle: number;
    wert: ToggleWert;
  }
  41: {
    id: number;
  },
  42: {
    id: number,
    rechte: string[]
  },
  43: SortierParameter,
  44: {
    bezeichnung: string;
    rechte: string[];
  },
  45: {
    id: number
  },
  46: {
    id: number;
    bezeichnung: string;
    rechte: string[];
  },
  47: SortierParameter,
  48: {
    poolKennung: string;
    poolToken: string
  },
  49: {
    id: number;
  }
  50: {
    id: number;
    pool: string;
    gewaehlt: string;
    vorname: string;
    nachname: string;
    schueler: ToggleWert,
    lehrer: ToggleWert,
    erziehungsberechtigte: ToggleWert,
    verwaltungsangestellte: ToggleWert,
    externe: ToggleWert,
    nutzerkonto: ToggleWert,
    recht: string,
  }
}

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