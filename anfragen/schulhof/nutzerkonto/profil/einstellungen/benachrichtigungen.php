<?php
Anfrage::post("id", "notifikationen", "blog", "termin", "galerie");

if(!Kern\Check::angemeldet()) {
  Anfrage::addFehler(-2, true);
}

if(!UI\Check::istZahl($id,0) || !UI\Check::istToggle($notifikationen) || !UI\Check::istToggle($blog) || !UI\Check::istToggle($termin) || !UI\Check::istToggle($galerie)) {
  Anfrage::addFehler(-3, true);
}

$profil = new Kern\Profil(new Kern\Nutzerkonto($id));
$recht = $profil->istFremdzugriff();
if (!$DSH_BENUTZER->hatRecht("$recht.einstellungen.notifikationen")) {
  Anfrage::addFehler(-4, true);
}

$sql = "UPDATE kern_nutzereinstellungen SET notifikationsmail = [?], oeffentlichertermin = [?], oeffentlicherblog = [?], oeffentlichegalerie = [?] WHERE person = ?";
$anfrage = $DBS->anfrage($sql, "sssssi", $notifikationen, $nachrichten, $termin, $blog, $galerie, $id);
?>
