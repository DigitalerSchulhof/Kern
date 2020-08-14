<?php
switch ($meldeid) {
  case 0:
    Anfrage::setRueck("Meldung", new UI\Meldung("Wirklich abmelden", "Bitte die Abmeldung bestätigen, um ein Versehen auszuschließen", "Warnung"));
    $knoepfe[] = new UI\Knopf("Abmelden", "Warnung", "kern.schulhof.nutzerkonto.abmelden.ausfuehren()");
    $knoepfe[] = UI\Knopf::abbrechen();
    Anfrage::setRueck("Knöpfe", $knoepfe);
    break;
  case 1:
    $website = new UI\Knopf("Zurück zur Website");
    $website ->addFunktion("href", "Website");
    $website ->addFunktion("onclick", "ui.laden.aus()");

    $schulhof = new UI\Knopf("Zurück zur Anmeldung");
    $schulhof ->addFunktion("href", "Schulhof/Anmeldung");
    $schulhof ->addFunktion("onclick", "ui.laden.aus()");

    $knoepfe = [$website, $schulhof];

    Anfrage::setRueck("Meldung", new UI\Meldung("Abmeldung erfolgreich!", "Die Abmeldung wurde durchgeführt. Bis bald!", "Information", new UI\Icon(UI\Konstanten::ABMELDEN)));
    Anfrage::setRueck("Knöpfe", $knoepfe);
    break;
  case 2:
    if ($parameter[0] == "alle") {
      $titel = "Wirklich alle Sessions löschen?";
    } else {
      $titel = "Wirklich diese Session löschen?";
    }
    Anfrage::setRueck("Meldung", new UI\Meldung($titel, "Bitte die Löschung bestätigen, um ein Versehen auszuschließen.", "Warnung"));
    $knoepfe[] = new UI\Knopf("Löschen", "Warnung", "kern.schulhof.nutzerkonto.sessions.loeschen.ausfuehren('{$parameter[0]}')");
    $knoepfe[] = UI\Knopf::abbrechen();
    Anfrage::setRueck("Knöpfe", $knoepfe);
    break;
  case 3:
    if ($parameter[0] == "alle") {
      $titel = "Wirklich alle Aktionslog-Einträge löschen?";
    } else {
      $titel = "Wirklich diesen Aktionslog-Eintrag löschen?";
    }
    Anfrage::setRueck("Meldung", new UI\Meldung($titel, "Bitte die Löschung bestätigen, um ein Versehen auszuschließen.", "Warnung"));
    $knoepfe[] = new UI\Knopf("Löschen", "Warnung", "kern.schulhof.nutzerkonto.aktionslog.loeschen.ausfuehren('{$parameter[0]}')");
    $knoepfe[] = UI\Knopf::abbrechen();
    Anfrage::setRueck("Knöpfe", $knoepfe);
    break;
  case 4:
    $schulhof = new UI\Knopf("Zurück zur Anmeldung");
    $schulhof->addFunktion("href", "Schulhof/Anmeldung");
    $schulhof->addFunktion("onclick", "ui.laden.aus()");
    $knoepfe = [$schulhof];
    Anfrage::setRueck("Meldung", new UI\Meldung("Benutzername verschickt!", "Die Benutzernamen aller Benutzer, die mit dieser Mailadresse verknüpft sind, wurden per eMail verschickt.", "Information", new UI\Icon(UI\Konstanten::VERSCHICKEN)));
    Anfrage::setRueck("Knöpfe", $knoepfe);
    break;
}
?>
