<?php
$SEITE = new Kern\Seite("Nutzerkonto", null);

$spalte     = new UI\Spalte();
$spalte[]   = new UI\SeitenUeberschrift("Willkommen ".($DSH_BENUTZER->getName())."!");


if (isset($_SESSION["Letzte Anmeldung"]) && $_SESSION["Letzte Anmeldung"]) {
  $anmeldungen = $DSH_BENUTZER->getLetzteSessions(2);
  if (count($anmeldungen) > 0) {
    $meldung = "<p>Die letzten Anmeldungen mit diesem Nutzerkonto erfolgten:</p>";
    $liste = new UI\Liste("UL");
    foreach ($anmeldungen as $a) {
      $liste->add((new UI\InhaltElement(new UI\Datum($a)))->setTag("span"));
    }
    $meldung .= $liste;
  } else {
    $meldung = "<p>Bisher sind keine vorherigen Anmeldungen erfasst.</p>";
  }
  $meldung .= "<p>Sind diese Angaben falsch? Wenn ja, könnte ein Identitätsdiebstahl vorliegen. ".(new UI\Link("Identitätsdiebstahl melden", "Schulhof/Nutzerkonto/Identitätsdiebstahl"))."</p>";
  $spalte[] = new UI\Meldung("Zuletzt angemeldet", $meldung, "Information");
  $_SESSION["Letzte Anmeldung"] = false;
  $DSH_BENUTZER->sessionprotokollLoeschen();
}


$passworttimeout = $DSH_BENUTZER->getPassworttimeout();
if ($passworttimeout !== 0) {
  $spalte[] = new UI\Meldung("Passwort läuft ab", "Das Kennwort ist nur noch bis ".(new UI\Datum($passworttimeout))." gültig. Bitte jetzt das ".(new UI\Link("Passwort ändern", "Schulhof/Nutzerkonto/Profil"))."!", "Warnung");
}
$SEITE[]     = new UI\Zeile($spalte);

$profil     = new Kern\Profil($DSH_BENUTZER);
$spalte1    = new UI\Spalte("A2");
$spalte1[]  = $profil->getAktivitaetsanzeige("dshAktivitaetNutzerkonto");

$SEITE[]     = new UI\Zeile($spalte1);
?>
