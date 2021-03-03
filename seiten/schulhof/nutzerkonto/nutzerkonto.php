<?php

$letzteAnmeldung = null;

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
  $meldung .= "<p>Sind diese Angaben falsch? Wenn ja, könnte ein Identitätsdiebstahl vorliegen. " . (new UI\Link("Identitätsdiebstahl melden", "Schulhof/Nutzerkonto/Identitätsdiebstahl")) . "</p>";
  $letzteAnmeldung = new UI\Meldung("Zuletzt angemeldet", $meldung, "Information");
  $_SESSION["Letzte Anmeldung"] = false;
  $DSH_BENUTZER->sessionprotokollLoeschen();
}

$passwortTimeout = null;
if ($DSH_BENUTZER->getPassworttimeout() !== 0) {
  $passwortTimeout = new UI\Meldung(
    "Passwort läuft ab",
    new UI\Text(
      "Das Kennwort ist nur noch bis ",
      new UI\Datum($DSH_BENUTZER->getPassworttimeout()),
      " gültig. Bitte jetzt das ",
      new UI\Link("Passwort ändern", "Schulhof/Nutzerkonto/Profil"),
      "!",
    ),
    "Warnung"
  );
}

return new Kern\Seite(
  "Nutzerkonto",
  new UI\Zeile(
    new UI\Spalte(
      new UI\SeitenUeberschrift("Willkommen " . ($DSH_BENUTZER->getName()) . "!"),
      $letzteAnmeldung,
      $passwortTimeout
    )
  ),
  new UI\Zeile(
    new UI\Spalte(
      (new Kern\Profil($DSH_BENUTZER))->getNutzerkontoKontrollpanele("dshAktivitaetNutzerkonto")
    )
  )
);
