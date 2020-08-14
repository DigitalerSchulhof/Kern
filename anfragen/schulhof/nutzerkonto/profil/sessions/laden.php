<?php
Anfrage::post("id");

if(!Kern\Check::angemeldet()) {
  Anfrage::addFehler(-2, true);
}

if (!UI\Check::istZahl($id,0) && $id != 'alle') {
  Anfrage::addFehler(-3, true);
}

if ($id == "alle") {
  if (!$DSH_BENUTZER->hatRecht("kern.personen.profil.sessionprotokoll.sehen")) {
    Anfrage::addFehler(-4, true);
  }
  // @TODO: Filtereigenschaften laden
} else {
  $profil = new Kern\Profil(new Kern\Nutzerkonto($id));
  $recht = $profil->istFremdzugriff();
  if (!$DSH_BENUTZER->hatRecht("$recht.sessionprotokoll.sehen")) {
    Anfrage::addFehler(-4, true);
  }
}

$code = "";
if ($id == "alle") {
  // @TODO: Sessions für alle laden - Filter anwenden
} else {
  if ($id == $DSH_BENUTZER->getId()) {
    $profil->getNutzer()->setSessionid($DSH_BENUTZER->getSessionid());
  }
  $code = $profil->getSessionprotokollTabelle();
}

Anfrage::setRueck("Code", (string) $code);
?>
