<?php
switch ($id) {
  case 0:
    $gefunden = true;
    Anfrage::setTyp("Meldung");
    $titel = "Wirklich abmelden?";
    $inhalt = "Damit die Abmeldung erfolgen kann, ist eine Bestätigung notwendig!";
    Anfrage::setRueck("Meldung", new UI\Meldung($titel, $inhalt, "Warnung", $icon));
    $knoepfe[] = new UI\Knopf("Abmelden", "Warnung", "kern.schulhof.nutzerkonto.abmelden.ausfuehren()");
    $knoepfe[] = new UI\Knopf::abbrechen();
    Anfrage::setRueck("Knöpfe", $knoepfe);
    break;
}
?>
