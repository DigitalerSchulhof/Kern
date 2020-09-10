<?php
namespace Kern;
use UI;

class Profil {
  /** @var Nutzerkonto ID */
  protected $person;

  /**
   * Erstellt eine neue Person
   * @param Person $person :)
   */
  public function __construct($person) {
    $this->person = $person;
  }

  public function getNutzer() : Nutzerkonto {
    return $this->person;
  }

  /**
   * Gibt den Balken für die Aktivitätsanzeige aus
   * @param  string $id ID der Aktivitätsanzeige
   * @return UI\Balken Balken der Aktivitätsanzeige
   */
  public function getAktivitaetsanzeige($id) : UI\Balken {
    $balken = new UI\Balken("Zeit", time(), $this->person->getSessiontimeout(), true, $this->person->getInaktivitaetszeit());
    $balken->setID($id);
    return $balken;
  }

  /**
   * Gibt den Balken der Aktivitätsanzeige und Hauptknöpfe des Nutzerkontos aus
   * @param  string $id ID des Balkens
   * @return string :)
   */
  public function getNutzerkontoKontrollpanele($id) : string {
    global $DSH_ALLEMODULE;
    $module = array_keys($DSH_ALLEMODULE);

    $code = $this->getAktivitaetsanzeige($id);
    $knoepfe = [];

    $knopf = new UI\Knopf("Verlängern", "Erfolg");
    $knopf->addFunktion("onclick", "kern.schulhof.nutzerkonto.session.verlaengern()");
    $knoepfe[] = $knopf;
    $knopf = new UI\Knopf("Mein Profil");
    $knopf->addFunktion("href", "Schulhof/Nutzerkonto/Profil");
    $knoepfe[] = $knopf;

    if (in_array("Postfach", $module)) {
      $knopf = new UI\Knopf("Postfach");
      $knopf->addFunktion("href", "Schulhof/Nutzerkonto/Postfach");
      $knoepfe[] = $knopf;
    }
    if (in_array("Kalender", $module)) {
      $knopf = new UI\Knopf("Kalender");
      $knopf->addFunktion("href", "Schulhof/Nutzerkonto/Kalender");
      $knoepfe[] = $knopf;
    }
    if (in_array("Stundenplanung", $module)) {
      $knopf = new UI\Knopf("Stundenplan");
      $knopf->addFunktion("href", "Schulhof/Nutzerkonto/Stundenplan");
      $knoepfe[] = $knopf;
    }

    $knopf = new UI\Knopf("Abmelden", "Warnung");
    $knopf->addFunktion("onclick", "kern.schulhof.nutzerkonto.abmelden.fragen()");
    $knoepfe[] = $knopf;
    $absatz = new UI\Absatz(join(" ", $knoepfe));
    $code .= $absatz;
    return $code;
  }

  /**
   * Gibt das Zugriffsrecht für Personen zurück, je nachdem, ob ein Fremdzugriff vorliegt
   * @return string String für die Rechteebene bei Fremdzugriff
   */
  public function istFremdzugriff() : string {
    global $DSH_BENUTZER;
    // Rechte Entscheidung:
    if ($DSH_BENUTZER->getId() != $this->person->getId()) {
      $recht = "personen.andere.profil";
      if (!$DSH_BENUTZER->hatRecht("$recht.sehen")) {
        throw new \Exception("Es liegt keine Berechitung für diese Funktion vor!");
      }
    } else {
      $recht = "personen.selbst.profil";
    }
    return $recht;
  }

  public function getSessionprotokollTabelle($autoladen, $sortSeite, $sortDatenproseite, $sortSpalte, $sortRichtung) : UI\Tabelle {
    global $DSH_BENUTZER, $DBS;

    $profilid = $this->person->getId();

    $recht = $this->istFremdzugriff();
    $darflo = $DSH_BENUTZER->hatRecht("$recht.sessionprotokoll.löschen");

    $sql = "UPDATE kern_nutzersessions SET sessionid = null WHERE sessiontimeout < ?";
    $DBS->silentanfrage($sql, "i", time());

    $spalten = [["{sessionid} AS sessionid"], ["{browser} AS browser"], ["sessiontimeout"], ["anmeldezeit"], ["id"]];
    $sql = "SELECT ?? FROM kern_nutzersessions WHERE person = ? ORDER BY anmeldezeit DESC";

    $ta = new Tabellenanfrage($sql, $spalten, $sortSeite, $sortDatenproseite, $sortSpalte, $sortRichtung);
    $tanfrage = $ta->anfrage($DBS, "i", $this->person->getId());
    $anfrage = $tanfrage["Anfrage"];

    $tabellenid = "dshProfil{$profilid}Sessionprotokoll";
    $tabelle = new UI\Tabelle($tabellenid, 15, null, "Sessionstatus", "Browser", "Sessiontimeout", "Anmeldezeit");
    $tabelle ->setSortierfunktion("kern.schulhof.nutzerkonto.sessions.laden");
    $tabelle ->setSeiten($tanfrage);

    if ($autoladen) {
      $tabelle->setAutoladen(true);
    } else {
      while ($anfrage->werte($sessionid, $browser, $sessiontimeout, $anmeldezeit, $id)) {
        $zeile = new UI\Tabelle\Zeile();
        if ($sessionid != null) {
          $zeile["Sessionstatus"] = "gültig";
        } else {
          $zeile["Sessionstatus"] = "<i>erloschen</i>";
        }
        $zeile["Browser"] = $browser;

        if ($sessiontimeout > 0) {
          if ($sessionid == $this->person->getSessionid() && $DSH_BENUTZER->getId() == $this->person->getId()) {
            $sessiontimeout = "<i>diese Session</i>";
          } else {
            $sessiontimeout = (new UI\Datum($sessiontimeout))->kurz();
          }
        } else {
          $sessiontimeout = "<i>abgemeldet</i>";
        }
        $zeile["Sessiontimeout"] = $sessiontimeout;
        $zeile["Anmeldezeit"] = (new UI\Datum($anmeldezeit))->kurz();

        if ($darflo) {
          $knopf = UI\MiniIconKnopf::loeschen();
          $knopf ->addFunktion("onclick", "kern.schulhof.nutzerkonto.sessions.loeschen.fragen('$profilid', '$id')");
          $zeile ->addAktion($knopf);
        }
        $tabelle[] = $zeile;
      }
    }

    return $tabelle;
  }

  /**
   * Gibt das Sessionprotokoll für den Benutzer aus
   * @return array Elemente, die bei der Ausgabe erzeugt werden
   */
  public function getSessionprotokoll($autoladen = false, $sortSeite = 1, $sortDatenproseite = 25, $sortSpalte = 0, $sortRichtung = "ASC") : array{
    global $DSH_BENUTZER;
    $recht = $this->istFremdzugriff();
    $darfloeschen = $DSH_BENUTZER->hatRecht("$recht.sessionprotokoll.löschen");

    $profilid = $this->person->getId();

    $rueck = [];
    $rueck[] = new UI\Meldung("Speicherdauer und Aufzeichnungserklärung", "<p>Sessions werden nach zwei Tagen automatisch gelöscht.</p><p>Sessions verwalten die Zugriffe auf dieses Nutzerkonto und entstehen mit jeder Anmeldung. Von hieraus können alte oder widerrechtliche Sessions geschlossen werden. Die letzten beiden Sessions werden bei der Anmeldung angezeigt, um mögliche Indentitätsdiebstähle zu identifizieren.</p>", "Information");
    $rueck[] = $this->getSessionprotokollTabelle($autoladen, $sortSeite, $sortDatenproseite, $sortSpalte, $sortRichtung);
    $knoepfe = [];
    if ($darfloeschen) {
      $knoepfe[] = new UI\Knopf("Alle Sessions löschen", "Warnung", "kern.schulhof.nutzerkonto.sessions.loeschen.fragen('$profilid', 'alle')");
    }
    if ($profilid == $DSH_BENUTZER->getId()) {
      $knoepfe[] = new UI\Knopf("Alle Sessions beenden", "Warnung", "kern.schulhof.nutzerkonto.sessions.beenden.fragen()");
    }

    if (count($knoepfe) > 0) {
      $rueck[] = new UI\Absatz(join(" ", $knoepfe));
    }
    return $rueck;
  }

  /**
   * Generiert die Tabelle des Aktionsprotokolls für den angegebenen Tag
   * @param  int       $datum Timestamp des Tages von dem das Aktionsprotokoll stammen soll
   * @return UI\Tabelle        :)
   */
  public function getAktionsportokollTag($datum, $autoladen, $sortSeite, $sortDatenproseite, $sortSpalte, $sortRichtung) : UI\Tabelle {
    global $DBS, $DSH_BENUTZER;
    $recht = $this->istFremdzugriff();

    $profilid = $this->person->getId();

    $tag = date("d", $datum);
    $monat = date("m", $datum);
    $jahr = date("Y", $datum);
    $anfang = mktime(0, 0, 0, $monat, $tag, $jahr);
    $ende = mktime(0, 0, 0, $monat, $tag+1, $jahr)-1;

    $darfloeschen = $DSH_BENUTZER->hatRecht("$recht.aktionsprotokoll.löschen");
    $darfdetails = $DSH_BENUTZER->hatRecht("$recht.aktionsprotokoll.details");
    $darfaktionen = $darfloeschen || $darfdetails;

    $spalten = [["{tabellepfad} AS tabellenpfad"], ["{aktion} AS aktion"], ["zeitpunkt"], ["{art} AS art"], ["id"]];
    $sql = "SELECT ?? FROM kern_nutzeraktionslog WHERE person = ? AND (zeitpunkt BETWEEN ? AND ?) ORDER BY zeitpunkt DESC";
    $ta = new Tabellenanfrage($sql, $spalten, $sortSeite, $sortDatenproseite, $sortSpalte, $sortRichtung);
    $tanfrage = $ta->anfrage($DBS, "iii", $this->person->getId(), $anfang, $ende);
    $anfrage = $tanfrage["Anfrage"];

    $tabellenid = "dshProfil{$profilid}Aktionsprotokoll";
    $tabelle = new UI\Tabelle($tabellenid, 18, null, "Datenbank / Pfad", "Aktion", "Zeit");
    $tabelle ->setSortierfunktion("kern.schulhof.nutzerkonto.aktionslog.laden");
    $tabelle ->setSeiten($tanfrage);

    if ($autoladen) {
      $tabelle->setAutoladen(true);
    } else {
      while ($anfrage->werte($tabellepfad, $aktion, $zeitpunkt, $art, $id)) {
        $zeile = new UI\Tabelle\Zeile();

        if ($art == "DB") {
          $zeile->setIcon(new UI\Icon("fas fa-database"));
        } else if ("Datei") {
          $zeile->setIcon(new UI\Icon("fas fa-archive"));
        } else {
          $zeile->setIcon(new UI\Icon("fas fa-shoe-prints"));
        }
        $zeile["Datenbank / Pfad"] = $tabellepfad;
        $zeile["Aktion"] = $aktion;
        $zeile["Zeit"] = (new UI\Datum($zeitpunkt))->kurz("MUs");

        if ($darfdetails) {
          $knopf = new UI\MiniIconKnopf(new UI\Icon(UI\Konstanten::DETAILS), "Details anzeigen");
          $knopf ->addFunktion("onclick", "kern.schulhof.nutzerkonto.aktionslog.details('{$profilid}', '$id')");
          $zeile ->addAktion($knopf);
        }
        if ($darfloeschen) {
          $knopf = UI\MiniIconKnopf::loeschen();
          $knopf ->addFunktion("onclick", "kern.schulhof.nutzerkonto.aktionslog.loeschen.fragen('{$profilid}', '$id')");
          $zeile ->addAktion($knopf);
        }
        $tabelle[] = $zeile;
      }
    }

    return $tabelle;
  }

  /**
   * Gibt das Aktionsporokoll für den Benutzer aus
   * @return array Elemente, die bei der Ausgabe erzeugt werden
   */
  public function getAktionsprotokoll($autoladen = false, $sortSeite = 1, $sortDatenproseite = 25, $sortSpalte = 0, $sortRichtung = "ASC") : array {
    global $DSH_BENUTZER;
    $recht = $this->istFremdzugriff();

    $profilid = $this->person->getId();

    $rueck = [];
    $rueck[] = new UI\Meldung("Speicherdauer und Aufzeichnungserklärung", "<p>Aktionen werden nach 30 Tagen automatisch gelöscht.</p><p>Über dieses Aktionsprotokoll lassen sich widerrechtlich durchgeführte Aktionen aufspüren. Es werden lediglich Änderungen an der Schulhof-Datenbank und am Dateisystem (Upload, Umbenennen, Löschen) aufgezeichnet. Es erfolgt ausdrücklich keine Erstellung eines »Bewegungsprotokolls«, das aufzeichnet, wer wann welche Seite aufruft oder welche Dateien herunterlädt.</p>", "Information");

    if (Einstellungen::laden("Kern", "Aktionslog") != "1") {
      $rueck[] = new UI\Meldung("Aktionsprotokoll deaktiviert", "<p>Die Aufzeichnung von Aktionen im Digitalen Schulhof ist.</p>", "Information");
    }

    $tabelleid = "dshProfil{$profilid}Aktionsprotokoll";

    $heute = time();
    $tag = date("d", $heute);
    $monat = date("m", $heute);
    $jahr = date("Y", $heute);
    $formular         = new UI\FormularTabelle();
    $tagwahl          = new UI\Auswahl("{$tabelleid}Datum");
    for ($i=0; $i<31; $i++) {
      $datum = mktime(0, 0, 0, $monat, $tag-$i, $jahr);
      $tagwahl->add((new UI\Datum($datum))->kurz("WM"), $datum);
    }
    $tagwahl->setWert(mktime(0, 0, 0, $monat, $tag, $jahr));
    $tagwahl->addFunktion("oninput", "ui.tabelle.sortieren('$tabelleid')");
    $formular[]       = new UI\FormularFeld(new UI\InhaltElement("Datum:"),      $tagwahl);
    $formular[]       = (new UI\Knopf("Suchen"))  ->setSubmit(true);
    $formular         ->addSubmit("ui.tabelle.sortieren('$tabelleid')");
    $rueck[]         = $formular;

    $rueck[] = $this->getAktionsportokollTag($heute, $autoladen, $sortSeite, $sortDatenproseite, $sortSpalte, $sortRichtung);

    if ($DSH_BENUTZER->hatRecht("$recht.aktionsprotokoll.löschen")) {
      $rueck[] = new UI\Absatz(new UI\Knopf("Alle Aktionen löschen", "Warnung", "kern.schulhof.nutzerkonto.aktionslog.loeschen.fragen('$profilid', 'alle')"));
    }
    return $rueck;
  }

  /**
   * Erstellt ein bearbeitbares Profil dieses Benutzers
   * @return UI\Reiter :)
   */
  public function getProfil() : UI\Reiter {
    global $DBS, $DSH_BENUTZER, $DSH_ALLEMODULE;
    $recht = $this->istFremdzugriff();

    $profilid = $this->person->getId();

    $sql      = "SELECT {benutzername}, {email}, {notifikationsmail}, {uebersichtsanzahl}, {oeffentlichertermin}, {oeffentlicherblog}, {oeffentlichegalerie}, {inaktivitaetszeit}, {wikiknopf}, kn.person, {kuerzel} FROM kern_nutzerkonten as kn LEFT JOIN kern_nutzereinstellungen as kne ON kn.person = kne.person LEFT JOIN kern_lehrer as kl ON kl.id = kn.person WHERE kn.person = ?";
    $anfrage  = $DBS->anfrage($sql, "i", $this->person->getId());
    $anfrage  ->werte($benutzername, $mail, $notifikationsmail, $uebersicht, $oetermin, $oeblog, $oegalerie, $inaktiv, $wiki, $nutzerkonto, $kuerzel);

    $reiter = new UI\Reiter("dshProfil$profilid");
    /*
     * Persönliches
     */
    $formular      = new UI\FormularTabelle();
    $artF          = (new UI\Auswahl("dshProfil{$profilid}Art"))->setWert($this->person->getArt());
    $artF          ->add("Schüler", "s");
    $artF          ->add("Erziehungsberechtigte(r)", "e");
    $artF          ->add("Lehrkraft", "l");
    $artF          ->add("Verwaltung", "v");
    $artF          ->add("Externe(r)", "x");
    $artF          ->addFunktion("oninput", "ui.formular.anzeigenwenn('dshProfil{$profilid}Art', 'l', 'dshProfil{$profilid}KuerzelFeld')");
    if (!$DSH_BENUTZER->hatRecht("$recht.art")) {
      $artF->setDisabled(true);
    }

    $geschlechtF  = (new UI\Auswahl("dshProfil{$profilid}Geschlecht"))->setWert($this->person->getGeschlecht());
    $geschlechtF  ->add("Weiblich", "w");
    $geschlechtF  ->add("Männlich", "m");
    $geschlechtF  ->add("Divers", "d");
    if (!$DSH_BENUTZER->hatRecht("$recht.geschlecht")) {
      $geschlechtF->setDisabled(true);
    }

    $titelF = (new UI\Textfeld("dshProfil{$profilid}Titel"))->setWert($this->person->getTitel());
    if ($DSH_BENUTZER->hatRecht("$recht.titel")) {
      $titelF->setAutocomplete("honorific-prefix");
    } else {
      $titelF->setDisabled(true);
    }

    $vornameF = (new UI\Textfeld("dshProfil{$profilid}Vorname"))->setWert($this->person->getVorname());
    if ($DSH_BENUTZER->hatRecht("$recht.vorname")) {
      $vornameF->setAutocomplete("given-name");
    } else {
      $vornameF->setDisabled(true);
    }

    $nachnameF = (new UI\Textfeld("dshProfil{$profilid}Nachname"))->setWert($this->person->getNachname());
    if ($DSH_BENUTZER->hatRecht("$recht.nachname")) {
      $nachnameF->setAutocomplete("family-name");
    } else {
      $nachnameF->setDisabled(true);
    }

    $kuerzelF = (new UI\Textfeld("dshProfil{$profilid}Kuerzel"))->setWert($kuerzel);
    if (!$DSH_BENUTZER->hatRecht("$recht.kuerzel")) {
      $kuerzelF->setDisabled(true);
    }

    $formular[]       = new UI\FormularFeld(new UI\InhaltElement("Art:"),                      $artF);
    $formular[]       = new UI\FormularFeld(new UI\InhaltElement("Geschlecht:"),               $geschlechtF);
    $formular[]       = (new UI\FormularFeld(new UI\InhaltElement("Titel:"),                   $titelF))->setOptional(true);
    $formular[]       = new UI\FormularFeld(new UI\InhaltElement("Vorname:"),                  $vornameF);
    $formular[]       = new UI\FormularFeld(new UI\InhaltElement("Nachname:"),                 $nachnameF);
    $kuerzel = new UI\FormularFeld(new UI\InhaltElement("Kürzel:"),                      $kuerzelF);
    $kuerzel->setOptional(true);
    $kuerzel->addKlasse("dshProfil{$profilid}KuerzelFeld");
    if ($this->person->getArt() != "l") {
      $kuerzel->addKlasse("dshUiUnsichtbar");
    }
    $formular[]       = $kuerzel;

    $formular[]       = (new UI\Knopf("Änderungen speichern", "Erfolg"))  ->setSubmit(true);
    $formular         ->addSubmit("kern.schulhof.nutzerkonto.aendern.persoenliches('{$profilid}')");

    $reiterkopf     = new UI\Reiterkopf("Persönliches", new UI\Icon(UI\Konstanten::PERSON));
    $reiterspalte   = new UI\Spalte("A1", $formular);
    $reiterkoerper  = new UI\Reiterkoerper($reiterspalte->addKlasse("dshUiOhnePadding"));
    $reiter[]       = new UI\Reitersegment($reiterkopf, $reiterkoerper);

    /*
     * Nutzerkonto
     */

    if ($nutzerkonto !== null) {
      $formular         = new UI\FormularTabelle();
      $mailF = (new UI\Mailfeld("dshProfil{$profilid}Mail"))->setWert($mail);
      if ($DSH_BENUTZER->hatRecht("$recht.email")) {
        $mailF->setAutocomplete("email");
      } else {
        $mailF->setDisabled(true);
      }

      $benutzerF = (new UI\Textfeld("dshProfil{$profilid}Benutzer"))->setWert($benutzername);
      if ($DSH_BENUTZER->hatRecht("$recht.benutzer")) {
        $benutzerF->setAutocomplete("username");
      } else {
        $benutzerF->setDisabled(true);
      }

      $formular[]       = new UI\FormularFeld(new UI\InhaltElement("Benutzername:"),                   $benutzerF);
      $formular[]       = new UI\FormularFeld(new UI\InhaltElement("eMail:"),                          $mailF);

      $formular[]       = (new UI\Knopf("Änderungen speichern", "Erfolg"))  ->setSubmit(true);
      $formular         ->addSubmit("kern.schulhof.nutzerkonto.aendern.kontodaten('{$profilid}')");

      $reiterkopf     = new UI\Reiterkopf("Nutzerkonto", new UI\Icon("fas fa-address-card"));
      $reiterspalte   = new UI\Spalte("A1");
      $ldap = Einstellungen::laden("Kern", "LDAP");
      if ($ldap == "1" && $DSH_BENUTZER->hatRecht("$recht.benutzer")) {
        $reiterspalte[] = new UI\Meldung("LDAP wird verwendet", "Eine Änderung des Benutzernamens kann dazu führen, dass die verschiedenen Systeme, die dieses Nutzerkonto verwenden, nicht mehr optimal zusammen funktionieren.", "Warnung");
      }
      $reiterspalte[] = $formular;
      if ($DSH_BENUTZER->hatRecht("$recht.einstellungen.nutzerkonto")) {
        $reiterspalte[]   = new UI\Ueberschrift(3, "Nutzerkonto");
        $formular         = new UI\FormularTabelle();
        $formular[]       = new UI\FormularFeld(new UI\InhaltElement("Inaktivitätszeit (min):"),       (new UI\Zahlenfeld("dshProfil{$profilid}Inaktivitaetszeit", 5, 300))->setWert($inaktiv));
        $formular[]       = new UI\FormularFeld(new UI\InhaltElement("Elemente pro Übersicht:"),       (new UI\Zahlenfeld("dshProfil{$profilid}ElementeProUebersicht", 1, 10))->setWert($uebersicht));
        $formular[]       = new UI\FormularFeld(new UI\InhaltElement("Wiki-Knopf:"),          (new UI\IconToggle("dshProfil{$profilid}Wiki", "Ich möchte angezeigt bekommen, wenn es zu einer Seite eine Anleitung gibt.", new UI\Icon(UI\Konstanten::HAKEN)))->setWert($wiki));

        $formular[]       = (new UI\Knopf("Änderungen speichern", "Erfolg"))  ->setSubmit(true);
        $formular         ->addSubmit("kern.schulhof.nutzerkonto.aendern.einstellungen.nutzerkonto('{$profilid}')");
        $reiterspalte[]   = $formular;
      }
      if ($DSH_BENUTZER->hatRecht("$recht.einstellungen.benachrichtigungen")) {
        $reiterspalte[]   = new UI\Ueberschrift(3, "Notifikationen");
        $formular         = new UI\FormularTabelle();
        $formular[]       = new UI\FormularFeld(new UI\InhaltElement("Notifikationsmails:"),          (new UI\IconToggle("dshProfil{$profilid}Notifikationsmails", "Ich möchte eine eMail-Benachrichtugung erhalten, wenn ich eine Notifikation erhalte.", (new UI\Icon(UI\Konstanten::HAKEN))))->setWert($notifikationsmail));
        $formular[]       = new UI\FormularFeld(new UI\InhaltElement("Öffentliche Blogeinträge:"),          (new UI\IconToggle("dshProfil{$profilid}OeffentlichBlog", "Ich möchte bei Änderungen an öffentlichen Blogeinträgen eine Notifikation erhalten.", new UI\Icon(UI\Konstanten::HAKEN)))->setWert($oeblog));
        $formular[]       = new UI\FormularFeld(new UI\InhaltElement("Öffentliche Termine:"),          (new UI\IconToggle("dshProfil{$profilid}OeffentlichTermine", "Ich möchte bei Änderungen an öffentlichen Terminen eine Notifikation erhalten.", new UI\Icon(UI\Konstanten::HAKEN)))->setWert($oetermin));
        $formular[]       = new UI\FormularFeld(new UI\InhaltElement("Öffentliche Galerien:"),          (new UI\IconToggle("dshProfil{$profilid}OeffentlichGalerien", "Ich möchte bei Änderungen an öffentlichen Galerien eine Notifikation erhalten.", new UI\Icon(UI\Konstanten::HAKEN)))->setWert($oegalerie));

        $formular[]       = (new UI\Knopf("Änderungen speichern", "Erfolg"))  ->setSubmit(true);
        $formular         ->addSubmit("kern.schulhof.nutzerkonto.aendern.einstellungen.notifikationen('{$profilid}')");
        $reiterspalte[]   = $formular;
      }

      $reiterkoerper      = new UI\Reiterkoerper($reiterspalte->addKlasse("dshUiOhnePadding"));
      $reiter[]           = new UI\Reitersegment($reiterkopf, $reiterkoerper);

      /*
       * Passwort
       */

      if ($DSH_BENUTZER->hatRecht("$recht.passwort")) {
        $formular         = new UI\FormularTabelle();
        $passwortaltF   = (new UI\Passwortfeld("dshProfil{$profilid}PasswortAlt"));
        $passwortneuF   = (new UI\Passwortfeld("dshProfil{$profilid}PasswortNeu"));
        $passwortneu2F  = (new UI\Passwortfeld("dshProfil{$profilid}PasswortNeu2", $passwortneuF));

        $passwortaltF   ->setAutocomplete("password");
        $passwortneuF   ->setAutocomplete("new-password");
        $passwortneu2F  ->setAutocomplete("new-password");

        $formular[]       = new UI\FormularFeld(new UI\InhaltElement("Altes Passwort:"),                 $passwortaltF);
        $formular[]       = new UI\FormularFeld(new UI\InhaltElement("Neues Passwort:"),                 $passwortneuF);
        $formular[]       = new UI\FormularFeld(new UI\InhaltElement("Neues Passwort bestätigen:"),      $passwortneu2F);

        $formular[]       = (new UI\Knopf("Änderungen speichern", "Erfolg"))  ->setSubmit(true);
        $formular         ->addSubmit("kern.schulhof.nutzerkonto.aendern.passwort('{$profilid}')");

        $reiterkopf       = new UI\Reiterkopf("Passwort", new UI\Icon("fas fa-key"));
        $reiterspalte     = new UI\Spalte("A1", $formular);
        $reiterkoerper    = new UI\Reiterkoerper($reiterspalte->addKlasse("dshUiOhnePadding"));
        $reiter[]         = new UI\Reitersegment($reiterkopf, $reiterkoerper);
      }

      new Wurmloch("funktionen/einstellungen.php", array("recht" => $recht, "profilid" => $profilid), function($r) use ($reiter) {
        $reiter->addReitersegment(...$r);
      }, true);

      /*
       * Sessionprokoll
       */

      if ($DSH_BENUTZER->hatRecht("$recht.sessionprotokoll.sehen")) {
        $reiterspalte     = new UI\Spalte("A1");
        $sessionprotokoll = $this->getSessionprotokoll(true, 1, 25, 2, "DESC");
        foreach ($sessionprotokoll as $s) {
          $reiterspalte[]   = $s;
        }
        $reiterkopf     = new UI\Reiterkopf("Sessionprotokoll", new UI\Icon("fas fa-user-clock"));
        $reiterkoerper  = new UI\Reiterkoerper($reiterspalte->addKlasse("dshUiOhnePadding"));
        $reiter[]       = new UI\Reitersegment($reiterkopf, $reiterkoerper);
      }

      /*
       * Aktionsprotokoll
       */

      if ($DSH_BENUTZER->hatRecht("$recht.aktionsprotokoll.sehen")) {
        $reiterspalte     = new UI\Spalte("A1");
        $aktionsprotokoll = $this->getAktionsprotokoll(true, 1, 25, 2, "DESC");
        foreach ($aktionsprotokoll as $a) {
          $reiterspalte[]   = $a;
        }
        $reiterkopf       = new UI\Reiterkopf("Aktionsprotokoll", new UI\Icon("fas fa-clipboard-list"));
        $reiterkoerper    = new UI\Reiterkoerper($reiterspalte->addKlasse("dshUiOhnePadding"));
        $reiter[]         = new UI\Reitersegment($reiterkopf, $reiterkoerper);
      }
    }

    return $reiter;
  }
}
?>