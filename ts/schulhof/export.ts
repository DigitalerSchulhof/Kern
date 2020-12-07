import { anmelden, aktivitaetsanzeige, abmelden, sessions, aktionslog, session, aendern, identitaetsdiebstahl, registrieren, vergessen } from "./nutzerkonto";
import { browsercheck } from "./oeffentlich";
import verwaltung from "./verwaltung/export";

export default {
  verwaltung: verwaltung,
  oeffentlich: {
    browsercheck: browsercheck
  },
  nutzerkonto: {
    anmelden: anmelden,
    abmelden: abmelden,
    session: session,
    aendern: aendern,
    sessions: sessions,
    aktionslog: aktionslog,
    aktivitaetsanzeige: aktivitaetsanzeige,
    identitaetsdiebstahl: identitaetsdiebstahl,
    registrieren: registrieren,
    vergessen: vergessen
  }
};