<?php
Anfrage::post("id", "logid");

if(!Kern\Check::angemeldet()) {
  Anfrage::addFehler(-2, true);
}

if ((!UI\Check::istZahl($id,0) && $id != 'alle') || (!UI\Check::istZahl($logid,0) && $logid != 'alle')) {
  Anfrage::addFehler(-3, true);
}

if ($id == "alle") {
  if (!$DSH_BENUTZER->hatRecht("kern.personen.profil.aktionslog.loeschen")) {
    Anfrage::addFehler(-4, true);
  }
} else {
  $profil = new Kern\Profil(new Kern\Nutzerkonto($id));
  $recht = $profil->istFremdzugriff();
  if (!$DSH_BENUTZER->hatRecht("$recht.aktionslog.loeschen")) {
    Anfrage::addFehler(-4, true);
  }
}

if ($id == 'alle') {
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
    $anfrage = $DBS->anfrage($sql, "i", $id);
  } else {
    $sql = "DELETE FROM kern_nutzeraktionslog WHERE nutzer = ? AND id = ?";
    $anfrage = $DBS->anfrage($sql, "ii", $id, $logid);
  }
}
?>
