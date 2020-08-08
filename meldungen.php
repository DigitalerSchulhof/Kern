<?php
switch ($meldeid) {
  case 0:
    $gefunden = true;
    Anfrage::setTyp("Meldung");
    $titel = "Wirklich abmelden?";
    $inhalt = "Bitte die Abmeldung bestätigen, um ein Versehen auszuschließen.";
    Anfrage::setRueck("Meldung", new UI\Meldung($titel, $inhalt, "Warnung"));
    $knoepfe[] = new UI\Knopf("Abmelden", "Warnung", "kern.schulhof.nutzerkonto.abmelden.ausfuehren()");
    $knoepfe[] = UI\Knopf::abbrechen();
    Anfrage::setRueck("Knöpfe", $knoepfe);
    break;
  case 1:
    $gefunden = true;
    Anfrage::setTyp("Meldung");
    if ($parameter[0] == "alle") {
      $titel = "Wirklich alle Sessions löschen?";
    } else {
      $titel = "Wirklich diese Session löschen?";
    }
    $inhalt = "Bitte die Löschung bestätigen, um ein Versehen auszuschließen.";
    Anfrage::setRueck("Meldung", new UI\Meldung($titel, $inhalt, "Warnung"));
    $knoepfe[] = new UI\Knopf("Löschen", "Warnung", "kern.schulhof.nutzerkonto.sessions.loeschen.ausfuehren('{$parameter[0]}')");
    $knoepfe[] = UI\Knopf::abbrechen();
    Anfrage::setRueck("Knöpfe", $knoepfe);
    break;
  case 2:
    $gefunden = true;
    Anfrage::setTyp("Meldung");
    if ($parameter[0] == "alle") {
      $titel = "Wirklich alle Aktionslog-Einträge löschen?";
    } else {
      $titel = "Wirklich diesen Aktionslog-Eintrag löschen?";
    }
    $inhalt = "Bitte die Löschung bestätigen, um ein Versehen auszuschließen.";
    Anfrage::setRueck("Meldung", new UI\Meldung($titel, $inhalt, "Warnung"));
    $knoepfe[] = new UI\Knopf("Löschen", "Warnung", "kern.schulhof.nutzerkonto.aktionslog.loeschen.ausfuehren('{$parameter[0]}')");
    $knoepfe[] = UI\Knopf::abbrechen();
    Anfrage::setRueck("Knöpfe", $knoepfe);
    break;
}
?>
