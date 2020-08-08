<?php
Anfrage::post("id", "sessionid");

if(!Kern\Check::angemeldet()) {
  Anfrage::addFehler(-2, true);
}

if ((!UI\Check::istZahl($id,0) && $id != 'alle') || (!UI\Check::istZahl($sessionid,0) && $sessionid != 'alle')) {
  Anfrage::addFehler(-3, true);
}

if ($id == "alle") {
  if (!$DSH_BENUTZER->hatRecht("kern.personen.profil.sessionprotokoll.loeschen")) {
    Anfrage::addFehler(-4, true);
  }
} else {
  $profil = new Kern\Profil(new Kern\Nutzerkonto($id));
  $recht = $profil->istFremdzugriff();
  if (!$DSH_BENUTZER->hatRecht("$recht.sessionprotokoll.loeschen")) {
    Anfrage::addFehler(-4, true);
  }
}

if ($id == 'alle') {
  if ($sessionid == 'alle') {
    $sql = "DELETE FROM kern_nutzersessions";
    $anfrage = $DBS->anfrage($sql);
  } else {
    $sql = "DELETE FROM kern_nutzersessions WHERE id = ?";
    $anfrage = $DBS->anfrage($sql, "i", $sessionid);
  }
} else {
  if ($sessionid == 'alle') {
    $sql = "DELETE FROM kern_nutzersessions WHERE nutzer = ?";
    $anfrage = $DBS->anfrage($sql, "i", $id);
  } else {
    $sql = "DELETE FROM kern_nutzersessions WHERE nutzer = ? AND id = ?";
    $anfrage = $DBS->anfrage($sql, "ii", $id, $sessionid);
  }
}

Anfrage::setTyp("Meldung");
if ($sessionid != "alle") {
  Anfrage::setRueck("Meldung", new UI\Meldung("Session gelöscht!", "Die Session wurde entfernt.", "Erfolg"));
} else {
  if ($id == "alle") {
    Anfrage::setRueck("Meldung", new UI\Meldung("Session gelöscht!", "Alle Sessions wurden entfernt.", "Erfolg"));
  } else {
    Anfrage::setRueck("Meldung", new UI\Meldung("Session gelöscht!", "Alle Sessions dieses Nutzers wurden entfernt.", "Erfolg"));
  }
}
?>
