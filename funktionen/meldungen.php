<?php
switch ($meldeid) {
  case 0:
    Anfrage::setRueck("Meldung", new UI\Meldung("Wirklich abmelden", "Bitte die Abmeldung bestätigen, um ein Versehen auszuschließen.", "Warnung"));
    $knoepfe[] = new UI\Knopf("Abmelden", "Warnung", "kern.schulhof.nutzerkonto.abmelden.ausfuehren()");
    $knoepfe[] = UI\Knopf::abbrechen();
    Anfrage::setRueck("Knöpfe", $knoepfe);
    break;
  case 1:
    $website = new UI\Knopf("Zurück zur Website");
    $website ->addFunktion("href", "Website");
    $website ->addFunktion("onclick", "ui.laden.aus()");

    $schulhof = new UI\Knopf("Zurück zur Anmeldung");
    $schulhof ->addFunktion("onclick", "ui.laden.aus()");

    $knoepfe = [$website, $schulhof];

    Anfrage::setRueck("Meldung", new UI\Meldung("Abmeldung erfolgreich!", "Die Abmeldung wurde durchgeführt. Bis bald!", "Information", new UI\Icon(UI\Konstanten::ABMELDEN)));
    Anfrage::setRueck("Knöpfe", $knoepfe);
    break;
  case 2:
    parameter("nutzerid", "sessionid");
    if ($sessionid == "alle") {
      $titel = "Wirklich alle Sessions löschen?";
    } else {
      $titel = "Wirklich diese Session löschen?";
    }
    Anfrage::setRueck("Meldung", new UI\Meldung($titel, "Bitte die Löschung bestätigen, um ein Versehen auszuschließen.", "Warnung"));
    $knoepfe[] = new UI\Knopf("Löschen", "Warnung", "kern.schulhof.nutzerkonto.sessions.loeschen.ausfuehren('$nutzerid', '$sessionid')");
    $knoepfe[] = UI\Knopf::abbrechen();
    Anfrage::setRueck("Knöpfe", $knoepfe);
    break;
  case 3:
    parameter("nutzerid", "logid");
    if ($logid == "alle") {
      $titel = "Wirklich alle Aktionslog-Einträge löschen?";
    } else {
      $titel = "Wirklich diesen Aktionslog-Eintrag löschen?";
    }
    Anfrage::setRueck("Meldung", new UI\Meldung($titel, "Bitte die Löschung bestätigen, um ein Versehen auszuschließen.", "Warnung"));
    $knoepfe[] = new UI\Knopf("Löschen", "Warnung", "kern.schulhof.nutzerkonto.aktionslog.loeschen.ausfuehren('$nutzerid', '$logid')");
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
    Anfrage::setRueck("Meldung", new UI\Meldung("Änderungen erfolgreich!", "Die Änderungen der persönlichen Informationen wurden vorgenomen.", "Erfolg"));
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
    Anfrage::setRueck("Meldung", new UI\Meldung("Änderungen erfolgreich!", "Die Änderungen der Notifikationseinstellungen wurden vorgenomen.", "Erfolg"));
    break;
  case 12:
    Anfrage::setRueck("Meldung", new UI\Meldung("Alle Sessions beenden", "Sollen wirklich alle Sessions beendet werden?", "Warnung"));
    $knoepfe[] = new UI\Knopf("Alle Beenden", "Warnung", "kern.schulhof.nutzerkonto.sessions.beenden.ausfuehren()");
    $knoepfe[] = UI\Knopf::abbrechen();
    Anfrage::setRueck("Knöpfe", $knoepfe);
    break;
  case 13:
    Anfrage::setRueck("Meldung", new UI\Meldung("Rechte vergeben", "Die Rechte wurden der Person vergeben.", "Erfolg"));
    break;
  case 14:
    Anfrage::setRueck("Meldung", new UI\Meldung("Änderungen erfolgreich!", "Der Identitätsdiebstahl wurde gemeldet. Das Passwort wurde geändert. Aus Sicherheitsgründen wird eine Benachrichtigung per eMail verschickt.", "Erfolg"));
    break;
  case 15:
    parameter("nutzerid", "sessionid");
    if ($sessionid != "alle") {
      Anfrage::setRueck("Meldung", new UI\Meldung("Session gelöscht!", "Die Session wurde entfernt.", "Erfolg"));
    } else {
      if ($nutzerid == "alle") {
        Anfrage::setRueck("Meldung", new UI\Meldung("Session gelöscht!", "Alle Sessions wurden entfernt. Damit geht die Abmeldung dieses Nutzerkontos einher.", "Erfolg"));
      } else {
        Anfrage::setRueck("Meldung", new UI\Meldung("Session gelöscht!", "Alle Sessions dieses Nutzers wurden entfernt.", "Erfolg"));
      }
    }
    break;
  case 16:
    parameter("nutzerid", "logid");
    if ($logid != "alle") {
      Anfrage::setRueck("Meldung", new UI\Meldung("Aktionslog gelöscht!", "Die aufgezeichnete Aktion wurde entfernt.", "Erfolg"));
    } else {
      if ($nutzerid == "alle") {
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
  case 25:
    parameter("id", "laden", "nutzerkonto");
    $wenn = "";
    if($nutzerkonto == "1") {
      $wenn = " Wenn ja, nur das Nutzerkonto oder die gesamte Person?";
    }
    Anfrage::setRueck("Meldung", new UI\Meldung("Diese Person wirklich löschen", "Soll die Person wirklich gelöscht werden?$wenn", "Warnung"));
    $knoepfe[] = new UI\Knopf("Person löschen", "Fehler", "kern.schulhof.verwaltung.personen.loeschen.ausfuehren('$id', 'person', '$laden')");
    if ($nutzerkonto == "1") {
      $knoepfe[] = new UI\Knopf("Nutzerkonto löschen", "Warnung", "kern.schulhof.verwaltung.personen.loeschen.ausfuehren('$id', 'nutzerkonto', '$laden')");
    }
    $knoepfe[] = UI\Knopf::abbrechen();
    Anfrage::setRueck("Knöpfe", $knoepfe);
    break;
  case 26:
    parameter("art");
    if ($art == "person") {$art = "Person";}
    else {$art = "Nutzerkonto";}
    Anfrage::setRueck("Meldung", new UI\Meldung("$art gelöscht!", "Die $art wurde gelöscht.", "Erfolg"));
    break;
  case 27:
    Anfrage::setRueck("Meldung", new UI\Meldung("Person erstellt", "Die Person wurde angelegt.", "Erfolg"));
    $knoepfe = [UI\Knopf::ok()];
    $knoepfe[0]->addFunktion("onclick", "ui.fenster.schliessen('dshVerwaltungNeuePerson')");
    $knoepfe[0]->addFunktion("onclick", "ui.tabelle.sortieren('dshVerwaltungPersonen')");
    Anfrage::setRueck("Knöpfe", $knoepfe);
    break;
  case 28:
    Anfrage::setRueck("Meldung", new UI\Meldung("Nutzerkonto erstellt", "Das Nutzerkonto wurde angelegt. An die angegebene eMailadresse wurde ein Kennwort verschickt, das nun 24 Stunden gültig ist.", "Erfolg"));
    $knoepfe = [UI\Knopf::ok()];
    $knoepfe[0]->addFunktion("onclick", "core.rueck()");
    Anfrage::setRueck("Knöpfe", $knoepfe);
    break;
  case 29:
    Anfrage::setRueck("Meldung", new UI\Meldung("Person und Nutzerkonto erstellt", "Die Person und das Nutzerkonto wurden angelegt. An die angegebene eMailadresse wurde ein Kennwort verschickt, das nun 24 Stunden gültig ist.", "Erfolg"));
    $knoepfe = [UI\Knopf::ok()];
    $knoepfe[0]->addFunktion("onclick", "core.rueck()");
    Anfrage::setRueck("Knöpfe", $knoepfe);
    break;
  case 30:
    Anfrage::setRueck("Meldung", new UI\Meldung("Session endet bald", "Die Session endet bald und die Abmeldung droht. Soll diese Session verlängert werden?", "Warnung"));
    $knoepfe[] = UI\Knopf::abbrechen();
    $knoepfe[] = new UI\Knopf("Verlängern!", "Erfolg", "kern.schulhof.nutzerkonto.session.verlaengern()");
    Anfrage::setRueck("Knöpfe", $knoepfe);
    break;
  case 31:
    $website = new UI\Knopf("Zurück zur Website");
    $website ->addFunktion("href", "Website");
    $website ->addFunktion("onclick", "ui.laden.aus()");

    $schulhof = new UI\Knopf("Wieder anmelden");
    $schulhof ->addFunktion("onclick", "ui.laden.aus()");

    $knoepfe = [$website, $schulhof];

    Anfrage::setRueck("Meldung", new UI\Meldung("Automatische Abmeldung", "Aufgrund von Inaktivität ist automatisch eine Abmeldung durchgeführt worden.", "Information", new UI\Icon("fas fa-clock")));
    Anfrage::setRueck("Knöpfe", $knoepfe);
    break;
  case 32:
    Anfrage::setRueck("Meldung", new UI\Meldung("Rechte aktualisiert", "Die Rechte der Person wurden aktualisiert.", "Erfolg"));
    break;
  case 33:
    $ok = UI\Knopf::ok();
    $ok ->addFunktion("href", "Schulhof/Verwaltung/Rollen");
    Anfrage::setRueck("Meldung", new UI\Meldung("Rolle anlegen", "Die Rolle wurde angelegt.", "Erfolg"));
    Anfrage::setRueck("Knöpfe", [$ok]);
    break;
  case 34:
    Anfrage::setRueck("Meldung", new UI\Meldung("Rolle löschen", "Die Rolle wurde gelöscht.", "Erfolg"));
    break;
  case 35:
    parameter("id");
    Anfrage::setRueck("Meldung", new UI\Meldung("Diese Rolle wirklich löschen", "Soll die Rolle wirklich gelöscht werden? Dadurch verlieren alle Personen, die diese Rolle besitzen, sofort alle verbunden Rechte.", "Warnung"));
    $knoepfe[] = new UI\Knopf("Rolle löschen", "Fehler", "kern.schulhof.verwaltung.rollen.loeschen.ausfuehren($id)");
    $knoepfe[] = UI\Knopf::abbrechen();
    Anfrage::setRueck("Knöpfe", $knoepfe);
    break;
  case 36:
    $ok = UI\Knopf::ok();
    $ok ->addFunktion("href", "Schulhof/Verwaltung/Rollen");
    Anfrage::setRueck("Meldung", new UI\Meldung("Rolle bearbeiten", "Die Rolle wurde bearbeitet.", "Erfolg"));
    Anfrage::setRueck("Knöpfe", [$ok]);
    break;
  case 37:
    Anfrage::setRueck("Meldung", new UI\Meldung("Änderungen erfolgreich!", "Die Änderungen der Modulverwaltung wurden vorgenomen.", "Erfolg"));
    break;
}
?>
