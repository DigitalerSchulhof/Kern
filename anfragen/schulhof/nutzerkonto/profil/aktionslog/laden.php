<?php
//print_r($_POST);
Anfrage::post("id", "datum");
Anfrage::postSort();

if(!Kern\Check::angemeldet()) {
  Anfrage::addFehler(-2, true);
}

$reinid = str_replace("dshProfil", "", $id);
$reinid = str_replace("Aktionsprotokoll", "", $reinid);

if ((!UI\Check::istZahl($reinid,0) && $reinid != 'alle') || !UI\Check::istZahl($datum,0)) {
  Anfrage::addFehler(-3, true);
}

if ($reinid == "alle") {
  if (!$DSH_BENUTZER->hatRecht("kern.personen.profil.aktionslog.sehen")) {
    Anfrage::addFehler(-4, true);
  }
  // @TODO: Filtereigenschaften laden
} else {
  $profil = new Kern\Profil(new Kern\Nutzerkonto($reinid));
  $recht = $profil->istFremdzugriff();
  if (!$DSH_BENUTZER->hatRecht("$recht.aktionslog.sehen")) {
    Anfrage::addFehler(-4, true);
  }
}

$code = "";
if ($reinid == "alle") {
  // @TODO: Aktionslog für alle laden - Filter anwenden
} else {
  if ($reinid == $DSH_BENUTZER->getId()) {
    $profil->getNutzer()->setSessionid($DSH_BENUTZER->getSessionid());
  }
  $code = $profil->getAktionsportokollTag($datum, false, $sortSeite, $sortDatenproseite,$sortSpalte, $sortRichtung);
}

Anfrage::setRueck("Code", (string) $code);
?>
