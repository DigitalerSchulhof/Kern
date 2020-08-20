<?php
Anfrage::post("nutzerid", "sessionid");

if(!Kern\Check::angemeldet()) {
  Anfrage::addFehler(-2, true);
}

if ((!UI\Check::istZahl($nutzerid,0) && $nutzerid != 'alle') || (!UI\Check::istZahl($sessionid,0) && $sessionid != 'alle')) {
  Anfrage::addFehler(-3, true);
}

if ($nutzerid == "alle") {
  if (!$DSH_BENUTZER->hatRecht("personen.andere.profil.sessionprotokoll.löschen")) {
    Anfrage::addFehler(-4, true);
  }
} else {
  $profil = new Kern\Profil(new Kern\Nutzerkonto($nutzerid));
  $recht = $profil->istFremdzugriff();
  if (!$DSH_BENUTZER->hatRecht("$recht.sessionprotokoll.löschen")) {
    Anfrage::addFehler(-4, true);
  }
}

if ($nutzerid == 'alle') {
  if ($sessionid == 'alle') {
    $sql = "DELETE FROM kern_nutzersessions";
    $anfrage = $DBS->anfrage($sql);
  } else {
    $sql = "DELETE FROM kern_nutzersessions WHERE id = ?";
    $anfrage = $DBS->anfrage($sql, "i", $sessionid);
  }
} else {
  if ($sessionid == 'alle') {
    $sql = "DELETE FROM kern_nutzersessions WHERE person = ?";
    $anfrage = $DBS->anfrage($sql, "i", $nutzerid);
  } else {
    $sql = "DELETE FROM kern_nutzersessions WHERE person = ? AND id = ?";
    $anfrage = $DBS->anfrage($sql, "ii", $nutzerid, $sessionid);
  }
}
?>
