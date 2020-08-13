<?php
Anfrage::post("id", "aktiv", "adresse", "name", "ehost", "eport", "enutzer", "epasswort", "ahost", "aport", "anutzer", "apasswort");

if(!Kern\Check::angemeldet()) {
  Anfrage::addFehler(-2, true);
}

if(!UI\Check::istZahl($id,0) || !UI\Check::istToggle($aktiv)) {
  Anfrage::addFehler(-3, true);
}

$profil = new Kern\Profil(new Kern\Nutzerkonto($id));
$recht = $profil->istFremdzugriff();
if (!$DSH_BENUTZER->hatRecht("$recht.einstellungen.email")) {
  Anfrage::addFehler(-4, true);
}

if(!UI\Check::istMail($adresse)) {
  Anfrage::addFehler(41);
}
if(!UI\Check::istText($name)) {
  Anfrage::addFehler(42);
}
if(!UI\Check::istText($ehost)) {
  Anfrage::addFehler(43);
}
if(!UI\Check::istZahl($eport,0,65535)) {
  Anfrage::addFehler(44);
}
if(!UI\Check::istText($enutzer)) {
  Anfrage::addFehler(45);
}

if(!UI\Check::istText($ahost)) {
  Anfrage::addFehler(46);
}
if(!UI\Check::istZahl($aport,0,65535)) {
  Anfrage::addFehler(47);
}

Anfrage::checkFehler();

$sql = "UPDATE kern_nutzereinstellungen SET emailaktiv = [?], emailadresse = [?], emailname = [?], einganghost = [?], eingangport = [?], eingangnutzer = [?], eingangpasswort = [?], ausganghost = [?], ausgangport = [?], ausgangnutzer = [?], ausgangpasswort = [?] WHERE person = ?";
$anfrage = $DBS->anfrage($sql, "sssssssssssi", $aktiv, $adresse, $name, $ehost, $eport, $enutzer, $epasswort, $ahost, $aport, $anutzer, $apasswort, $id);

Anfrage::setTyp("Meldung");
Anfrage::setRueck("Meldung", new UI\Meldung("Änderungen erfolgreich!", "Die Änderungen der eMail-Einstellungen wurden vorgenomen.", "Erfolg"));
?>
