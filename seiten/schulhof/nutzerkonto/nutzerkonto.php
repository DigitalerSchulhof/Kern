<?php
if(!Check::angemeldet()) {
  einbinden("Schulhof/Anmeldung");
  return;
}

$DSH_TITEL  = "Nutzerkonto";
$CODE[]     = new Kern\Aktionszeile();
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
$CODE[]     = new UI\Zeile($spalte);

$spalte1    = new UI\Spalte("A2");
$spalte1[]  = $DSH_BENUTZER->aktivitaetsanzeige("dshAktivitaetNutzerkonto");

$CODE[]     = new UI\Zeile($spalte1);
$CODE = "<br>";
$CODE .= var_export($DSH_BENUTZER->hatRecht("schulhof.verwaltung.nutzerkonten.löschen"), true)."<br>";                        // true
$CODE .= var_export($DSH_BENUTZER->hatRecht("schulhof.verwaltung.[&nutzerkonten,personen,sachen].loeschen"),  true)."<br>";   // true
$CODE .= var_export($DSH_BENUTZER->hatRecht("schulhof.dauerbrenner"),  true)."<br>";                                          // false
$CODE .= var_export($DSH_BENUTZER->hatRecht("schulhof.dauerbrenner.sehen"),  true)."<br>";                                    // false
$CODE .= var_export($DSH_BENUTZER->hatRecht("website"),  true)."<br>";                                                        // true
$CODE .= var_export($DSH_BENUTZER->hatRecht("website.elemente.[|elementa,elementb,elementc].anlegen"),  true)."<br>";         // true
$CODE .= var_export($DSH_BENUTZER->hatRecht("website.etwas"),  true)."<br>";                                                  // true
?>
