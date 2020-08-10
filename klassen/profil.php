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
   * Gibt den Balken für die Aktivitätsanzeuge aus
   * @param  string $id ID der Aktivitätsanzeige
   * @return string     HTML-Code der Aktivitätsanzeige
   */
  public function aktivitaetsanzeige($id) {
    $balken = new UI\Balken("Zeit", time(), $this->person->getSessiontimeout(), $this->person->getInaktivitaetszeit());
    $balken->setID($id);
    $code  = $balken;
    $verlaengern = new UI\Knopf("Verlängern", "Erfolg");
    $verlaengern->addFunktion("onclick", "kern.schulhof.nutzerkonto.session.verlaengern()");
    $bearbeiten = new UI\Knopf("Mein Profil");
    $bearbeiten->addFunktion("href", "Schulhof/Nutzerkonto/Profil");
    $abmelden = new UI\Knopf("Abmelden", "Warnung");
    $abmelden->addFunktion("onclick", "kern.schulhof.nutzerkonto.abmelden.fragen()");
    $absatz = new UI\Absatz("{$verlaengern} {$bearbeiten} {$abmelden}");
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
      $recht = "kern.personen.profil";
      if (!$DSH_BENUTZER->hatRecht("$recht.sehen")) {
        throw new \Exception("Es liegt keine Berechitung für diese Funktion vor!");
      }
    } else {
      $recht = "kern.nutzerkonto.profil";
    }
    return $recht;
  }

  public function getSessionprotokollTabelle() : UI\Tabelle {
    global $DSH_BENUTZER, $DBS;
    $recht = $this->istFremdzugriff();
    $sql = "SELECT id, {sessionid}, {browser}, sessiontimeout, anmeldezeit FROM kern_nutzersessions WHERE nutzer = ? ORDER BY anmeldezeit DESC";
    $anfrage = $DBS->anfrage($sql, "i", $this->person->getId());

    $darfloeschen = $DSH_BENUTZER->hatRecht("$recht.sessionprotokoll.löschen");
    $titel = ["", "Sessionstatus", "Browser", "Sessiontimeout", "Anmeldezeit", " "];

    $zeilen = [];
    while ($anfrage->werte($id, $sessionid, $browser, $sessiontimeout, $anmeldezeit)) {
      $neuezeile = [];
      $neuezeile[""] = new UI\Icon("fas fa-history");
      if ($sessionid != null) {
        $neuezeile["Sessionstatus"] = "gültig";
      } else {
        $neuezeile["Sessionstatus"] = "<i>erloschen</i>";
      }
      $neuezeile["Browser"] = $browser;

      if ($sessiontimeout > 0) {
        if ($sessionid == $this->person->getSessionid() && $DSH_BENUTZER->getId() == $this->person->getId()) {
          $sessiontimeout = "<i>diese Session</i>";
        } else {
          $sessiontimeout = (new UI\Datum($sessiontimeout))->kurz();
        }
      } else {
        $sessiontimeout = "<i>abgemeldet</i>";
      }
      $neuezeile["Sessiontimeout"] = $sessiontimeout;
      $neuezeile["Anmeldezeit"] = (new UI\Datum($anmeldezeit))->kurz();
      $neuezeile[" "] = "";
      if ($darfloeschen) {
        $loeschenknopf = UI\MiniIconKnopf::loeschen();
        $loeschenknopf->addFunktion("onclick", "kern.schulhof.nutzerkonto.sessions.loeschen.fragen('$id')");
        $neuezeile[" "] = $loeschenknopf;
      }
      $zeilen[] = $neuezeile;
    }

    return new UI\Tabelle("dshProfilSessionprotokoll", $titel, ...$zeilen);
  }

  /**
   * Gibt das Sessionprotokoll für den Benutzer aus
   * @return array Elemente, die bei der Ausgabe erzeugt werden
   */
  public function getSessionprotokoll() : array{
    global $DSH_BENUTZER;
    $recht = $this->istFremdzugriff();
    $darfloeschen = $DSH_BENUTZER->hatRecht("$recht.sessionprotokoll.löschen");

    $rueck = [];
    $rueck[] = new UI\Meldung("Speicherdauer und Aufzeichnungserklärung", "<p>Sessions werden nach zwei Tagen automatisch gelöscht.</p><p>Sessions verwalten die Zugriffe auf dieses Nutzerkonto und entstehen mit jeder Anmeldung. Von hieraus können alte oder widerrechtliche Sessions geschlossen werden. Die letzten beiden Sessions werden bei der Anmeldung angezeigt, um mögliche Indentitätsdiebstähle zu identifizieren.</p>", "Information");

    $rueck[] = "<div id=\"dshProfilSessionprotokollLadebereich\">".$this->getSessionprotokollTabelle()."</div>";

    if ($darfloeschen) {
      $rueck[] = new UI\Absatz(new UI\Knopf("Alle Sessions löschen", "Warnung", "kern.schulhof.nutzerkonto.sessions.loeschen.fragen('alle')"));
    }
    return $rueck;
  }

  /**
   * Generiert die Tabelle des Aktionsprotokolls für den angegebenen Tag
   * @param  int       $datum Timestamp des Tages von dem das Aktionsprotokoll stammen soll
   * @return UI\Tabelle        :)
   */
  public function getAktionsportokollTag($datum) : UI\Tabelle {
    global $DBS, $DSH_BENUTZER;
    $recht = $this->istFremdzugriff();

    $tag = date("d", $datum);
    $monat = date("m", $datum);
    $jahr = date("Y", $datum);
    $anfang = mktime(0, 0, 0, $monat, $tag, $jahr);
    $ende = mktime(0, 0, 0, $monat, $tag+1, $jahr)-1;

    $darfloeschen = $DSH_BENUTZER->hatRecht("$recht.aktionsprotokoll.löschen");
    $darfdetails = $DSH_BENUTZER->hatRecht("$recht.aktionsprotokoll.details");
    $darfaktionen = $darfloeschen || $darfdetails;

    $titel = ["", "Datenbank / Pfad", "Aktion", "Zeit", " "];

    $sql = "SELECT id, {art}, {tabellepfad}, {aktion}, zeitpunkt FROM kern_nutzeraktionslog WHERE nutzer = ? AND (zeitpunkt BETWEEN ? AND ?) ORDER BY zeitpunkt DESC";
    $anfrage = $DBS->anfrage($sql, "iii", $this->person->getId(), $anfang, $ende);

    $zeilen = [];
    while ($anfrage->werte($id, $art, $tabellepfad, $aktion, $zeitpunkt)) {
      $neuezeile = [];
      if ($art == "DB") {
        $neuezeile[""] = new UI\Icon("fas fa-database");
      } else if ("Datei") {
        $neuezeile[""] = new UI\Icon("fas fa-archive");
      } else {
        $neuezeile[""] = new UI\Icon("fas fa-shoe-prints");
      }
      $neuezeile["Datenbank / Pfad"] = $tabellepfad;
      $neuezeile["Aktion"] = $aktion;
      $neuezeile["Zeit"] = (new UI\Datum($zeitpunkt))->kurz("MUs");
      $neuezeile[" "] = "";
      if ($darfdetails) {
        $detailknopf = new UI\MiniIconKnopf(new UI\Icon(UI\Konstanten::DETAILS), "Details anzeigen");
        $detailknopf->addFunktion("onclick", "kern.schulhof.nutzerkonto.aktionslog.details('$id')");
        $neuezeile[" "] .= "$detailknopf ";
      }
      if ($darfloeschen) {
        $loeschenknopf = UI\MiniIconKnopf::loeschen();
        $loeschenknopf->addFunktion("onclick", "kern.schulhof.nutzerkonto.aktionslog.loeschen.fragen('$id')");
        $neuezeile[" "] .= "$loeschenknopf ";
      }
      $zeilen[] = $neuezeile;
    }

    $protokoll = new UI\Tabelle("dshProfilAktionsprotokoll", $titel, ...$zeilen);
    return $protokoll;
  }

  /**
   * Gibt das Aktionsporokoll für den Benutzer aus
   * @return array Elemente, die bei der Ausgabe erzeugt werden
   */
  public function getAktionsprotokoll() : array{
    global $DSH_BENUTZER;
    $recht = $this->istFremdzugriff();

    $rueck = [];
    $rueck[] = new UI\Meldung("Speicherdauer und Aufzeichnungserklärung", "<p>Aktionen werden nach 30 Tagen automatisch gelöscht.</p><p>Über dieses Aktionsprotokoll lassen sich widerrechtlich durchgeführte Aktionen aufspüren. Es werden lediglich Änderungen an der Schulhof-Datenbank und am Dateisystem (Upload, Umbenennen, Löschen) aufgezeichnet. Es erfolgt ausdrücklich keine Erstellung eines »Bewegungsprotokolls«, das aufzeichnet, wer wann welche Seite aufruft oder welche Dateien herunterlädt.</p>", "Information");

    if (Einstellungen::laden("Kern", "Aktionslog") != "1") {
      $rueck[] = new UI\Meldung("Aktionsprotokoll deaktiviert", "<p>Die Aufzeichnung von Aktionen im Digitalen Schulhof ist.</p>", "Information");
    }

    $heute = time();
    $tag = date("d", $heute);
    $monat = date("m", $heute);
    $jahr = date("Y", $heute);
    $formular         = new UI\FormularTabelle();
    $tagwahl          = new UI\Auswahl("dshNutzerkontoAktivitaetsdatum");
    for ($i=0; $i<31; $i++) {
      $datum = mktime(0, 0, 0, $monat, $tag-$i, $jahr);
      $tagwahl->add((new UI\Datum($datum))->kurz("WM"), $datum);
    }
    $tagwahl->setWert(mktime(0, 0, 0, $monat, $tag, $jahr));
    $tagwahl->getAktionen()->addFunktion("onchange", "kern.schulhof.nutzerkonto.aktionslog.laden('{$this->person->getId()}')");
    $formular[]       = new UI\FormularFeld(new UI\InhaltElement("Datum:"),      $tagwahl);
    $formular[]       = (new UI\Knopf("Suchen"))  ->setSubmit(true);
    $formular         ->addSubmit("kern.schulhof.nutzerkonto.aktionslog.laden('{$this->person->getId()}')");
    $rueck[]         = $formular;


    $rueck[] = "<div id=\"dshProfilAktionslogLadebereich\">".$this->getAktionsportokollTag($heute)."</div>";

    if ($DSH_BENUTZER->hatRecht("$recht.aktionsprotokoll.löschen")) {
      $rueck[] = new UI\Absatz(new UI\Knopf("Alle Aktionen löschen", "Warnung", "kern.schulhof.nutzerkonto.aktionslog.loeschen.fragen('alle')"));
    }
    return $rueck;
  }

  /**
   * Erstellt ein bearbeitbares Profil dieses Benutzers
   * @param  bool   $fremdzugriff entscheidet, welche Rechte relevant sind
   *                              false: die des Nutzerkontos
   *                              true: die der Personen
   * @return UI\Reiter :)
   */
  public function getProfil() : UI\Reiter {
    global $DBS, $DSH_BENUTZER;
    $recht = $this->istFremdzugriff();

    $sql = "SELECT {email}, {notifikationsmail}, {postmail}, {postalletage}, {postpapierkorbtage}, {uebersichtsanzahl}, {oeffentlichertermin}, {oeffentlicherblog}, {oeffentlichegalerie}, {inaktivitaetszeit}, {wikiknopf}, {kuerzel}, kern_nutzerkonten.id, {emailaktiv}, {emailadresse}, {emailname}, {einganghost}, {eingangport}, {eingangnutzer}, {eingangpasswort}, {ausganghost}, {ausgangport}, {ausgangnutzer}, {ausgangpasswort} FROM kern_nutzerkonten JOIN kern_nutzereinstellungen ON kern_nutzerkonten.id = kern_nutzereinstellungen.person LEFT JOIN kern_lehrer ON kern_nutzerkonten.id = kern_lehrer.id WHERE kern_nutzerkonten.id = ?";
    $anfrage = $DBS->anfrage($sql, "i", $this->person->getId());
    $anfrage->werte($mail, $notifikationsmail, $postmail, $posttage, $papierkorbtage, $uebersicht, $oetermin, $oeblog, $oegalerie, $inaktiv, $wiki, $kuerzel, $nutzerkonto, $mailaktiv, $mailadresse, $mailname, $mailehost, $maileport, $mailenutzer, $mailepasswort, $mailahost, $mailaport, $mailanutzer, $mailapasswort);


    $reiter = new UI\Reiter("dshProfil");

    $formular         = new UI\FormularTabelle();
    $artF          = (new UI\Auswahl("dshProfilArt"))->setWert($this->person->getArt());
    $artF          ->add("Schüler", "s");
    $artF          ->add("Erziehungsberechtigte(r)", "e");
    $artF          ->add("Lehrkraft", "l");
    $artF          ->add("Verwaltung", "v");
    $artF          ->add("Externe(r)", "x");
    if (!$DSH_BENUTZER->hatRecht("$recht.art")) {
      $artF->setAttribut("disabled", "disabled");
    }

    $geschlechtF  = (new UI\Auswahl("dshProfilGeschlecht"))->setWert($this->person->getGeschlecht());
    $geschlechtF  ->add("Weiblich", "w");
    $geschlechtF  ->add("Männlich", "m");
    $geschlechtF  ->add("Divers", "d");
    if (!$DSH_BENUTZER->hatRecht("$recht.geschlecht")) {
      $geschlechtF->setAttribut("disabled", "disabled");
    }

    $titelF = (new UI\Textfeld("dshProfilTitel"))->setWert($this->person->getTitel());
    if ($DSH_BENUTZER->hatRecht("$recht.titel")) {
      $titelF->setAutocomplete("honorific-prefix");
    } else {
      $titelF->setAttribut("disabled", "disabled");
    }

    $vornameF = (new UI\Textfeld("dshProfilVorname"))->setWert($this->person->getVorname());
    if ($DSH_BENUTZER->hatRecht("$recht.vorname")) {
      $vornameF->setAutocomplete("given-name");
    } else {
      $vornameF->setAttribut("disabled", "disabled");
    }

    $nachnameF = (new UI\Textfeld("dshProfilNachname"))->setWert($this->person->getNachname());
    if ($DSH_BENUTZER->hatRecht("$recht.nachname")) {
      $nachnameF->setAutocomplete("family-name");
    } else {
      $nachnameF->setAttribut("disabled", "disabled");
    }

    if ($this->person->getArt() == "l") {
      $kuerzelF = (new UI\Textfeld("dshProfilKuerzel"))->setWert($kuerzel);
      if (!$DSH_BENUTZER->hatRecht("$recht.kuerzel")) {
        $kuerzelF->setAttribut("disabled", "disabled");
      }
    }

    $formular[]       = new UI\FormularFeld(new UI\InhaltElement("Art:"),                      $artF);
    $formular[]       = new UI\FormularFeld(new UI\InhaltElement("Geschlecht:"),               $geschlechtF);
    $formular[]       = (new UI\FormularFeld(new UI\InhaltElement("Titel:"),                   $titelF))->setOptional(true);
    $formular[]       = new UI\FormularFeld(new UI\InhaltElement("Vorname:"),                  $vornameF);
    $formular[]       = new UI\FormularFeld(new UI\InhaltElement("Nachname:"),                 $nachnameF);
    if ($this->person->getArt() == "l") {
      $formular[]       = (new UI\FormularFeld(new UI\InhaltElement("Kürzel:"),                      $kuerzelF))->setOptional(true);
    }

    $formular[]       = (new UI\Knopf("Änderungen speichern", "Erfolg"))  ->setSubmit(true);
    $formular         ->addSubmit("kern.schulhof.nutzerkonto.aendern.persoenliches()");
    $reiterkopf = new UI\Reiterkopf("Persönliches");
    $reiterspalte = new UI\Spalte("A1", $formular);
    $reiterspalte->add(new UI\Absatz(new UI\VerstecktesFeld("dshProfilId", $this->person->getId())));
    $reiterkoerper = new UI\Reiterkoerper($reiterspalte->addKlasse("dshUiOhnePadding"));
    $reiter->addReitersegment(new UI\Reitersegment($reiterkopf, $reiterkoerper));

    if ($nutzerkonto !== null) {
      $formular         = new UI\FormularTabelle();
      $mailF = (new UI\Mailfeld("dshProfilMail"))->setWert($mail);
      if ($DSH_BENUTZER->hatRecht("$recht.email")) {
        $mailF->setAutocomplete("email");
      } else {
        $mailF->setAttribut("disabled", "disabled");
      }

      $benutzerF = (new UI\Textfeld("dshProfilBenutzer"))->setWert($this->person->getBenutzer());
      if ($DSH_BENUTZER->hatRecht("$recht.benutzer")) {
        $benutzerF->setAutocomplete("username");
      } else {
        $benutzerF->setAttribut("disabled", "disabled");
      }

      $formular[]       = new UI\FormularFeld(new UI\InhaltElement("Benutzername:"),                   $benutzerF);
      $formular[]       = new UI\FormularFeld(new UI\InhaltElement("eMail:"),                          $mailF);

      $formular[]       = (new UI\Knopf("Änderungen speichern", "Erfolg"))  ->setSubmit(true);
      $formular         ->addSubmit("kern.schulhof.nutzerkonto.aendern.kontodaten()");
      $reiterkopf = new UI\Reiterkopf("Nutzerkonto");
      $reiterspalte = new UI\Spalte("A1");
      $ldap = Einstellungen::laden("Kern", "LDAP");
      if ($ldap == "1" && $DSH_BENUTZER->hatRecht("$recht.benutzer")) {
        $reiterspalte[] = new UI\Meldung("LDAP wird verwendet", "Eine Änderung des Benutzernamens kann dazu führen, dass die verschiedenen Systeme, die dieses Nutzerkonto verwenden, nicht mehr optimal zusammen funktionieren.", "Warnung");
      }
      $reiterspalte[] = $formular;
      $reiterkoerper = new UI\Reiterkoerper($reiterspalte->addKlasse("dshUiOhnePadding"));
      $reiter->addReitersegment(new UI\Reitersegment($reiterkopf, $reiterkoerper));


      if ($DSH_BENUTZER->hatRecht("$recht.passwort")) {
        $formular         = new UI\FormularTabelle();
        $passwortaltF = (new UI\Passwortfeld("dshProfilPasswortAlt"));
        $passwortneuF = (new UI\Passwortfeld("dshProfilPasswortNeu"));
        $passwortneu2F = (new UI\Passwortfeld("dshProfilPasswortNeu2", $passwortneuF));

        $passwortaltF->setAutocomplete("password");
        $passwortneuF->setAutocomplete("password");
        $passwortneu2F->setAutocomplete("password");

        $formular[]       = new UI\FormularFeld(new UI\InhaltElement("Altes Passwort:"),                 $passwortaltF);
        $formular[]       = new UI\FormularFeld(new UI\InhaltElement("Neues Passwort:"),                 $passwortneuF);
        $formular[]       = new UI\FormularFeld(new UI\InhaltElement("Neues Passwort bestätigen:"),      $passwortneu2F);

        $formular[]       = (new UI\Knopf("Änderungen speichern", "Erfolg"))  ->setSubmit(true);
        $formular         ->addSubmit("kern.schulhof.nutzerkonto.aendern.passwort()");
        $reiterkopf = new UI\Reiterkopf("Passwort");
        $reiterspalte = new UI\Spalte("A1", $formular);
        $reiterkoerper = new UI\Reiterkoerper($reiterspalte->addKlasse("dshUiOhnePadding"));
        $reiter->addReitersegment(new UI\Reitersegment($reiterkopf, $reiterkoerper));
      }

      if ($DSH_BENUTZER->hatRecht("$recht.einstellungen")) {
        $reiterspalte     = new UI\Spalte("A1");
        if ($DSH_BENUTZER->hatRecht("$recht.einstellungen.nutzerkonto")) {
          $reiterspalte[]   = new UI\Ueberschrift(3, "Nutzerkonto");
          $formular         = new UI\FormularTabelle();
          $formular[]       = new UI\FormularFeld(new UI\InhaltElement("Inaktivitätszeit (min):"),       (new UI\Zahlenfeld("dshProfilInaktivitaetszeit", 5, 300))->setWert($inaktiv));
          $formular[]       = new UI\FormularFeld(new UI\InhaltElement("Elemente pro Übersicht:"),       (new UI\Zahlenfeld("dshProfilElementeProUebersicht", 1, 10))->setWert($uebersicht));
          $formular[]       = new UI\FormularFeld(new UI\InhaltElement("Wiki-Knopf:"),          (new UI\IconToggle("dshProfilWiki", "Ich möchte angezeigt bekommen, wenn es zu einer Seite eine Anleitung gibt.", new UI\Icon(UI\Konstanten::HAKEN)))->setWert($wiki));

          $formular[]       = (new UI\Knopf("Änderungen speichern", "Erfolg"))  ->setSubmit(true);
          $formular         ->addSubmit("kern.schulhof.nutzerkonto.aendern.einstellungen.nutzerkonto()");
          $reiterspalte[]   = $formular;
        }
        if ($DSH_BENUTZER->hatRecht("$recht.einstellungen.benachrichtigungen")) {
          $reiterspalte[]   = new UI\Ueberschrift(3, "Benachrichtigungen");
          $formular         = new UI\FormularTabelle();
          $formular[]       = new UI\FormularFeld(new UI\InhaltElement("Nachrichten:"),          (new UI\IconToggle("dshProfilNachrichtenmails", "Ich möchte eine eMail-Benachrichtugung erhalten, wenn ich eine Nachricht im Postfach erhalte.", new UI\Icon(UI\Konstanten::HAKEN)))->setWert($postmail));
          $formular[]       = new UI\FormularFeld(new UI\InhaltElement("Notifikationsmails:"),          (new UI\IconToggle("dshProfilNotifikationsmails", "Ich möchte eine eMail-Benachrichtugung erhalten, wenn ich eine Notifikation erhalte.", (new UI\Icon(UI\Konstanten::HAKEN))))->setWert($notifikationsmail));
          $formular[]       = new UI\FormularFeld(new UI\InhaltElement("Öffentliche Blogeinträge:"),          (new UI\IconToggle("dshProfilOeffentlichBlog", "Ich möchte bei Änderungen an öffentlichen Blogeinträgen eine Notifikation erhalten.", new UI\Icon(UI\Konstanten::HAKEN)))->setWert($oeblog));
          $formular[]       = new UI\FormularFeld(new UI\InhaltElement("Öffentliche Termine:"),          (new UI\IconToggle("dshProfilOeffentlichTermine", "Ich möchte bei Änderungen an öffentlichen Terminen eine Notifikation erhalten.", new UI\Icon(UI\Konstanten::HAKEN)))->setWert($oetermin));
          $formular[]       = new UI\FormularFeld(new UI\InhaltElement("Öffentliche Galerien:"),          (new UI\IconToggle("dshProfilOeffentlichGalerien", "Ich möchte bei Änderungen an öffentlichen Galerien eine Notifikation erhalten.", new UI\Icon(UI\Konstanten::HAKEN)))->setWert($oegalerie));

          $formular[]       = (new UI\Knopf("Änderungen speichern", "Erfolg"))  ->setSubmit(true);
          $formular         ->addSubmit("kern.schulhof.nutzerkonto.aendern.einstellungen.benachrichtigungen()");
          $reiterspalte[]   = $formular;
        }
        if ($DSH_BENUTZER->hatRecht("$recht.einstellungen.postfach")) {
          $reiterspalte[]   = new UI\Ueberschrift(3, "Internes Postfach");
          $formular         = new UI\FormularTabelle();
          $formular[]       = new UI\FormularFeld(new UI\InhaltElement("Löschfrist Postfach (Tage):"),          (new UI\Zahlenfeld("dshProfilPostfachLoeschfrist", 1, 1000))->setWert($posttage));
          $formular[]       = new UI\FormularFeld(new UI\InhaltElement("Löschfrist Papierkorb (Tage):"),        (new UI\Zahlenfeld("dshProfilPapierkorbLoeschfrist", 1, 1000))->setWert($papierkorbtage));

          $formular[]       = (new UI\Knopf("Änderungen speichern", "Erfolg"))  ->setSubmit(true);
          $formular         ->addSubmit("kern.schulhof.nutzerkonto.aendern.einstellungen.postfach()");
          $reiterspalte[]   = $formular;
        }
        if ($DSH_BENUTZER->hatRecht("$recht.einstellungen.email")) {
          $reiterspalte[]   = new UI\Ueberschrift(3, "eMail-Postfach");
          $reiterspalte[]   = new UI\Meldung("eMails im Schulhof empfangen", "Achtung! Damit eMails im Schulhof empfangen werden können, müssen Zugangsdaten gespeichert werden. Dies geschieht natürlich verschlüsselt.", "Information");
          $formular         = new UI\FormularTabelle();
          $formular[]       = new UI\FormularFeld(new UI\InhaltElement("Aktiv:"),          (new UI\IconToggle("dshProfilEmailAktiv", "eMails über den Schulhof verwalten", new UI\Icon(UI\Konstanten::HAKEN)))->setWert($mailaktiv));
          $formular[]       = new UI\FormularFeld(new UI\InhaltElement("eMail-Adresse:"),             (new UI\Mailfeld("dshProfilEmailAdresse"))->setWert($mailadresse));
          $formular[]       = new UI\FormularFeld(new UI\InhaltElement("Anzeigename:"),               (new UI\Textfeld("dshProfilEmailName"))->setWert($mailname));
          $formular[]       = new UI\FormularFeld(new UI\InhaltElement("IMAP-Host (Posteingang):"),   (new UI\Textfeld("dshProfilEmailEingangHost"))->setWert($mailehost));
          $formular[]       = new UI\FormularFeld(new UI\InhaltElement("IMAP-Port (Posteingang):"),   (new UI\Zahlenfeld("dshProfilEmailEingangPort",0,65535))->setWert($maileport));
          $formular[]       = new UI\FormularFeld(new UI\InhaltElement("Benutzername (Posteingang):"),(new UI\Textfeld("dshProfilEmailEingangNutzer"))->setWert($mailenutzer));
          $formular[]       = (new UI\FormularFeld(new UI\InhaltElement("Passwort (Posteingang):"),    (new UI\Passwortfeld("dshProfilEmailEingangPasswort"))->setWert($mailepasswort)))->setOptional(true);
          $formular[]       = new UI\FormularFeld(new UI\InhaltElement("SMTP-Host (Postausgang):"),   (new UI\Textfeld("dshProfilEmailAusgangHost"))->setWert($mailahost));
          $formular[]       = new UI\FormularFeld(new UI\InhaltElement("SMTP-Port (Postausgang):"),   (new UI\Zahlenfeld("dshProfilEmailAusgangPort",0,65535))->setWert($mailaport));
          $formular[]       = (new UI\FormularFeld(new UI\InhaltElement("Benutzername (Postausgang):"),(new UI\Textfeld("dshProfilEmailAusgangNutzer"))->setWert($mailanutzer)))->setOptional(true);
          $formular[]       = (new UI\FormularFeld(new UI\InhaltElement("Passwort (Postausgang):"),    (new UI\Passwortfeld("dshProfilEmailAusgangPasswort"))->setWert($mailapasswort)))->setOptional(true);

          $formular[]       = (new UI\Knopf("Änderungen speichern", "Erfolg"))  ->setSubmit(true);
          $formular         ->addSubmit("kern.schulhof.nutzerkonto.aendern.einstellungen.email()");
          $reiterspalte[]   = $formular;
        }
        $reiterkopf = new UI\Reiterkopf("Einstellungen");
        $reiterkoerper = new UI\Reiterkoerper($reiterspalte->addKlasse("dshUiOhnePadding"));
        $reiter->addReitersegment(new UI\Reitersegment($reiterkopf, $reiterkoerper));
      }

      if ($DSH_BENUTZER->hatRecht("$recht.sessionprotokoll.sehen")) {
        $reiterspalte     = new UI\Spalte("A1");
        $sessionprotokoll = $this->getSessionprotokoll();
        foreach ($sessionprotokoll as $s) {
          $reiterspalte[]   = $s;
        }
        $reiterkopf = new UI\Reiterkopf("Sessionprotokoll");
        $reiterkoerper = new UI\Reiterkoerper($reiterspalte->addKlasse("dshUiOhnePadding"));
        $reiter->addReitersegment(new UI\Reitersegment($reiterkopf, $reiterkoerper));
      }

      if ($DSH_BENUTZER->hatRecht("$recht.aktionsprotokoll.sehen")) {
        $reiterspalte     = new UI\Spalte("A1");
        $aktionsprotokoll = $this->getAktionsprotokoll();
        foreach ($aktionsprotokoll as $a) {
          $reiterspalte[]   = $a;
        }
        $reiterkopf = new UI\Reiterkopf("Aktionsprotokoll");
        $reiterkoerper = new UI\Reiterkoerper($reiterspalte->addKlasse("dshUiOhnePadding"));
        $reiter->addReitersegment(new UI\Reitersegment($reiterkopf, $reiterkoerper));
      }
    }

    return $reiter;
  }
}
?>
