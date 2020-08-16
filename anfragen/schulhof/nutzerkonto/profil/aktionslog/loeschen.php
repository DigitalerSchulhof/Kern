<?php
Anfrage::post("nutzerid", "logid");

if(!Kern\Check::angemeldet()) {
  Anfrage::addFehler(-2, true);
}

if ((!UI\Check::istZahl($nutzerid,0) && $nutzerid != 'alle') || (!UI\Check::istZahl($logid,0) && $logid != 'alle')) {
  Anfrage::addFehler(-3, true);
}

if ($nutzerid == "alle") {
  if (!$DSH_BENUTZER->hatRecht("kern.personen.profil.aktionslog.loeschen")) {
    Anfrage::addFehler(-4, true);
  }
} else {
  $profil = new Kern\Profil(new Kern\Nutzerkonto($nutzerid));
  $recht = $profil->istFremdzugriff();
  if (!$DSH_BENUTZER->hatRecht("$recht.aktionslog.loeschen")) {
    Anfrage::addFehler(-4, true);
  }
}

if ($nutzerid == 'alle') {
  if ($logid == 'alle') {
    $sql = "DELETE FROM kern_nutzeraktionslog";
    $anfrage = $DBS->anfrage($sql);
  } else {
    $sql = "DELETE FROM kern_nutzeraktionslog WHERE id = ?";
    $anfrage = $DBS->anfrage($sql, "i", $logid);
  }
} else {
  if ($logid == 'alle') {
    $sql = "DELETE FROM kern_nutzeraktionslog WHERE nutzer = ?";
    $anfrage = $DBS->anfrage($sql, "i", $nutzerid);
  } else {
    $sql = "DELETE FROM kern_nutzeraktionslog WHERE nutzer = ? AND id = ?";
    $anfrage = $DBS->anfrage($sql, "ii", $nutzerid, $logid);
  }
}
?>
