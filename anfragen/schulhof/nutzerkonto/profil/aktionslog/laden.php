<?php
Anfrage::post("id", "datum");

if(!Kern\Check::angemeldet()) {
  Anfrage::addFehler(-2, true);
}

if ((!UI\Check::istZahl($id,0) && $id != 'alle') || !UI\Check::istZahl($datum,0)) {
  Anfrage::addFehler(-3, true);
}

if ($id == "alle") {
  if (!$DSH_BENUTZER->hatRecht("kern.personen.profil.aktionslog.sehen")) {
    Anfrage::addFehler(-4, true);
  }
  // @TODO: Filtereigenschaften laden
} else {
  $profil = new Kern\Profil(new Kern\Nutzerkonto($id));
  $recht = $profil->istFremdzugriff();
  if (!$DSH_BENUTZER->hatRecht("$recht.aktionslog.sehen")) {
    Anfrage::addFehler(-4, true);
  }
}

$code = "";
if ($id == "alle") {
  // @TODO: Aktionslog für alle laden - Filter anwenden
} else {
  if ($id == $DSH_BENUTZER->getId()) {
    $profil->getNutzer()->setSessionid($DSH_BENUTZER->getSessionid());
  }
  $code = $profil->getAktionsportokollTag($datum);
}

Anfrage::setTyp("Code");
Anfrage::setRueck("Code", $code);
?>
