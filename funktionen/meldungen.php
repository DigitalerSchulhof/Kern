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
    parameter("sessionid");
    if ($sessionid == "alle") {
      $titel = "Wirklich alle Sessions löschen?";
    } else {
      $titel = "Wirklich diese Session löschen?";
    }
    Anfrage::setRueck("Meldung", new UI\Meldung($titel, "Bitte die Löschung bestätigen, um ein Versehen auszuschließen.", "Warnung"));
    $knoepfe[] = new UI\Knopf("Löschen", "Warnung", "kern.schulhof.nutzerkonto.sessions.loeschen.ausfuehren('$sessionid')");
    $knoepfe[] = UI\Knopf::abbrechen();
    Anfrage::setRueck("Knöpfe", $knoepfe);
    break;
  case 3:
    parameter("logid");
    if ($logid == "alle") {
      $titel = "Wirklich alle Aktionslog-Einträge löschen?";
    } else {
      $titel = "Wirklich diesen Aktionslog-Eintrag löschen?";
    }
    Anfrage::setRueck("Meldung", new UI\Meldung($titel, "Bitte die Löschung bestätigen, um ein Versehen auszuschließen.", "Warnung"));
    $knoepfe[] = new UI\Knopf("Löschen", "Warnung", "kern.schulhof.nutzerkonto.aktionslog.loeschen.ausfuehren('$logid')");
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
  case 5:
    $schulhof = new UI\Knopf("Zurück zur Anmeldung");
    $schulhof->addFunktion("href", "Schulhof/Anmeldung");
    $schulhof->addFunktion("onclick", "ui.laden.aus()");
    $knoepfe = [$schulhof];
    Anfrage::setRueck("Meldung", new UI\Meldung("Passwort verschickt!", "Das neue Passwort wurde per eMail verschickt. Es ist nur für kurze Zeit gültig. Eine umgehende Änderung wird empfohlen.", "Information", new UI\Icon(UI\Konstanten::VERSCHICKEN)));
    Anfrage::setRueck("Knöpfe", $knoepfe);
    break;
  case 6:
    $website = new UI\Knopf("Zurück zur Website");
    $website->addFunktion("href", "Website");
    $website->addFunktion("onclick", "ui.laden.aus()");
    $schulhof = new UI\Knopf("Zurück zur Anmeldung");
    $schulhof->addFunktion("href", "Schulhof/Anmeldung");
    $schulhof->addFunktion("onclick", "ui.laden.aus()");
    $knoepfe = [$website, $schulhof];
    Anfrage::setRueck("Meldung", new UI\Meldung("Registrierung erfolgreich!", "Die Registrierung wurde durchgeführt. Ein Administrator muss noch die Verknüpfung mit einer Person des Schulhofs durchführen. Sobald das Nutzerkonto bereitsteht wird eine eMail mit dem zugehörigen Benutzernamen versendet.", "Information"));
    Anfrage::setRueck("Knöpfe", $knoepfe);
    break;
  case 7:
    $knopf = new UI\Knopf("OK");
    $knopf->addFunktion("onclick", "core.neuladen()");
    Anfrage::setRueck("Meldung", new UI\Meldung("Änderungen erfolgreich!", "Die Änderungen der persönlichen Informationen wurden vorgenomen.", "Erfolg"));
    Anfrage::setRueck("Knöpfe", [$knopf]);
    break;
  case 8:
    Anfrage::setRueck("Meldung", new UI\Meldung("Änderungen erfolgreich!", "Die Änderungen der Nutzerkonto-Informationen wurden vorgenomen.", "Erfolg"));
    break;
  case 9:
    Anfrage::setRueck("Meldung", new UI\Meldung("Änderungen erfolgreich!", "Das Passwort wurde geändert. Aus Sicherheitsgründen wird eine Benachrichtigung per eMail verschickt.", "Erfolg"));
    break;
  case 10:
    Anfrage::setRueck("Meldung", new UI\Meldung("Änderungen erfolgreich!", "Die Änderungen der Nutzerkonto-Einstellungen wurden vorgenomen.", "Erfolg"));
    break;
  case 11:
    Anfrage::setRueck("Meldung", new UI\Meldung("Änderungen erfolgreich!", "Die Änderungen der Benachrichtigungseinstellungen wurden vorgenomen.", "Erfolg"));
    break;
  case 12:
    Anfrage::setRueck("Meldung", new UI\Meldung("Änderungen erfolgreich!", "Die Änderungen der Postfach-Einstellungen wurden vorgenomen.", "Erfolg"));
    break;
  case 13:
    Anfrage::setRueck("Meldung", new UI\Meldung("Änderungen erfolgreich!", "Die Änderungen der eMail-Einstellungen wurden vorgenomen.", "Erfolg"));
    break;
  case 14:
    Anfrage::setRueck("Meldung", new UI\Meldung("Änderungen erfolgreich!", "Der Identitätsdiebstahl wurde gemeldet. Das Passwort wurde geändert. Aus Sicherheitsgründen wird eine Benachrichtigung per eMail verschickt.", "Erfolg"));
    break;
  case 15:
    parameter("sessionid");
    if ($sessionid != "alle") {
      Anfrage::setRueck("Meldung", new UI\Meldung("Session gelöscht!", "Die Session wurde entfernt.", "Erfolg"));
    } else {
      if ($id == "alle") {
        Anfrage::setRueck("Meldung", new UI\Meldung("Session gelöscht!", "Alle Sessions wurden entfernt. Damit geht die Abmeldung dieses Nutzerkontos einher.", "Erfolg"));
      } else {
        Anfrage::setRueck("Meldung", new UI\Meldung("Session gelöscht!", "Alle Sessions dieses Nutzers wurden entfernt.", "Erfolg"));
      }
    }
    break;
  case 16:
    parameter("logid");
    if ($logid != "alle") {
      Anfrage::setRueck("Meldung", new UI\Meldung("Aktionslog gelöscht!", "Die aufgezeichnete Aktion wurde entfernt.", "Erfolg"));
    } else {
      if ($id == "alle") {
        Anfrage::setRueck("Meldung", new UI\Meldung("Aktionslog gelöscht!", "Alle aufgezeichneten Aktionen wurden entfernt.", "Erfolg"));
      } else {
        Anfrage::setRueck("Meldung", new UI\Meldung("Aktionslog gelöscht!", "Alle aufgezeichneten Aktionen dieses Nutzers wurden entfernt.", "Erfolg"));
      }
    }
    break;
  case 17:
    Anfrage::setRueck("Meldung", new UI\Meldung("Erfolg!", "Die Session wurde verlängert!", "Erfolg"));
    break;
  case 18:
    Anfrage::setRueck("Meldung", new UI\Meldung("Änderungen erfolgreich!", "Die Änderungen der Konfiguration wurden vorgenomen.", "Erfolg"));
    break;
  case 19:
    Anfrage::setRueck("Meldung", new UI\Meldung("Änderungen erfolgreich!", "Die Änderungen an den LDAP-Einstellungen wurden vorgenomen.", "Erfolg"));
    break;
  case 20:
    Anfrage::setRueck("Meldung", new UI\Meldung("Änderungen erfolgreich!", "Die Änderungen an der eMailadresse des Schulhofs wurden vorgenomen.", "Erfolg"));
    break;
  case 21:
    Anfrage::setRueck("Meldung", new UI\Meldung("Testmail verschickt!", "Eine Testmail wurden an die neuen Zugansdaten versendet. Wenn diese eMail angekommen ist, können die neuen Zugansdaten verwendet werden.", "Information"));
    break;
  case 22:
    Anfrage::setRueck("Meldung", new UI\Meldung("Änderungen erfolgreich!", "Die Änderungen am Aktionslog wurden vorgenomen.", "Erfolg"));
    break;
  case 23:
    Anfrage::setRueck("Meldung", new UI\Meldung("Änderungen erfolgreich!", "Die Änderungen der Schuldaten wurden vorgenomen.", "Erfolg"));
    break;
  case 24:
    Anfrage::setRueck("Meldung", new UI\Meldung("Änderungen erfolgreich!", "Die Änderungen der Vertreter wurden vorgenomen.", "Erfolg"));
    break;
}
?>
