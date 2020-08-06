<?php
namespace Kern;
use DB;
use UI;

class Person {
  /** @var int ID */
  protected $id;
  /** @var string Art slvex*/
  protected $art;

  /** @var string Titel */
  protected $titel;
  /** @var string Vorname */
  protected $vorname;
  /** @var string Nachname */
  protected $nachname;
  /** @var string Geschlecht wmd*/
  protected $geschelcht;

  /** @var array Benutzerarten: s=Schüler, l=Lehrer, v=Verwaltung, e=Eltern, x=Extern */
  const ARTEN = ["s", "l", "v", "e", "x"];

  /** @var array Geschlechter: w=weiblich, m=männlich, d=divers */
  const GESCHLECHTER = ["w", "m", "d"];

  /**
   * Erstellt eine neue Person
   * @param string $titel    :)
   * @param string $vorname  :)
   * @param string $nachname :)
   */
  public function __construct($titel, $vorname, $nachname) {
    $this->id = null;
    $this->art = null;

    $this->titel = $titel;
    $this->vorname = $vorname;
    $this->nachname = $nachname;
    $this->geschelecht = null;
  }

  /**
   * ID setzen
   * @param  int $id :)
   * @return self             :)
   */
  public function setId($id) : self {
    $this->id = $id;
    return $this;
  }

  /**
   * Art setzen
   * @param  string $art :)
   * @return self      :)
   */
  public function setArt($art) : self {
    if (!in_array($art, self::ARTEN)) {
      throw new \Exception("Ungültige Benutzerart");
    }
    $this->art = $art;
    return $this;
  }

  /**
   * Geschlecht setzen
   * @param  string $geschelcht :)
   * @return self      :)
   */
  public function setGeschlecht($geschlecht) : self {
    if (!in_array($geschlecht, self::GESCHLECHTER)) {
      throw new \Exception("Ungültiges Geschlecht");
    }
    $this->geschlecht = $geschlecht;
    return $this;
  }

  /**
   * ID laden
   * @return int ID
   */
  public function getId() : int {
    return $this->id;
  }

  /**
   * Art laden
   * @return string Art
   */
  public function getArt() : string {
    return $this->art;
  }

  /**
   * Titel laden
   * @return string titel
   */
  public function getTitel() : string {
    return $this->titel;
  }

  /**
   * Vorname laden
   * @return string Vorname
   */
  public function getVorname() : string {
    return $this->vorname;
  }

  /**
   * Nachname laden
   * @return string Nachname
   */
  public function getNachname() : string {
    return $this->nachname;
  }

  /**
   * Geschlecht laden
   * @return string Geschlecht
   */
  public function getGeschlecht() : string {
    return $this->geschlecht;
  }

  /**
   * Anrede
   * @return string Anrede für diese Person
   */
  public function getAnrede() : string {
    $anrede = "Guten Tag ";
    if ($this->art != "s") {
      if ($this->geschelcht == "w") {
        $anrede .= "Frau ";
      } else if ($this->geschelcht == "m") {
        $anrede .= "Herr ";
      } else {
        if ($this->titel !== null) {
          $anrede .= "{$this->vorname}";
        }
      }
      if ($this->titel !== null) {
        $anrede .= "{$this->titel} {$this->nachname}";
      } else {
        $anrede .= "{$this->nachname}";
      }
    } else {
      $anrede .= "{$this->vorname}";
    }
    return $anrede;
  }

  /**
   * Zeile für eine Tabelle mit Personen
   * @return string :)
   */
  public function __toString () : string {
    return "KOMMT NOCH";
  }

  /**
   * Gibt die Arten von Personen zurück
   * @return array :)
   */
  public static function getArten() : array {
    return self::ARTEN;
  }

  /**
   * Gibt die Geschlecher von Personen zurück
   * @return array :)
   */
  public static function getGeschlechter() : array {
    return self::GESCHLECHTER;
  }
}

class Nutzerkonto extends Person {
  /** @var string Benutzername */
  private $benutzer;

  /** @var string SessionID */
  private $sessionid;
  /** @var int Sessiontimeout (Timestamp) */
  private $sessiontimeout;
  /** @var int Inaktivitätszeit (Minuten) */
  private $inaktivitaetszeit;

  /** @var int ID des aktiven Schuljahres */
  private $schuljahr;
  /** @var int Passworttimeout (Timestamp) */
  private $passworttimeout;
  /** @var int Anzahl an zu ladeneden Elementen pro Übersicht */
  private $uebersichtszahl;


  /**
   * Erstellt eine neues Nutzerkonto
   * @param string $titel    :)
   * @param string $vorname  :)
   * @param string $nachname :)
   */
  public function __construct($titel, $vorname, $nachname) {
    parent::__construct($titel, $vorname, $nachname);
    $this->benutzer = null;

    $this->sessionid = null;
    $this->sessiontimeout = null;
    $this->inaktivitaetszeit = null;

    $this->schuljahr = null;
    $this->passworttimeout = null;
    $this->uebersichtszahl = null;
  }

  /**
   * Benutzername setzen
   * @param  string $benutzer :)
   * @return self             :)
   */
  public function setBenutzer($benutzer) : self {
    $this->benutzer = $benutzer;
    return $this;
  }

  /**
   * Session setzen
   * @param  string $sessionid
   * @param  string $sessiontimeout    (Timestamp)
   * @param  string $inaktivitaetszeit (Minuten)
   * @return self                      :)
   */
  public function setSession($sessionid, $sessiontimeout, $inaktivitaetszeit) : self {
    $this->sessionid = $sessionid;
    $this->sessiontimeout = $sessiontimeout;
    $this->inaktivitaetszeit = $inaktivitaetszeit;
    return $this;
  }

  /**
   * Schuljahr setzen
   * @param  int  $schuljahr ID des aktiven Schuljahres
   * @return self            :)
   */
  public function setSchuljahr($schuljahr) : self {
    $this->schuljahr = $schuljahr;
    return $this;
  }

  /**
   * Passworttimeout setzen
   * @param  int  $passworttimeout (Timestamp)
   * @return self                  :)
   */
  public function setPassworttimeout($passworttimeout) : self {
    $this->passworttimeout = $passworttimeout;
    return $this;
  }

  /**
   * Übersichtsanzahl setzen
   * @param  int  $uebersichtszahl Anzahl zu ladender Elemente pro Liste
   * @return self                  :)
   */
  public function setUebersichtszahl($uebersichtszahl) : self {
    $this->uebersichtszahl = $uebersichtszahl;
    return $this;
  }

  /**
   * Benutzername laden
   * @return string Benutzername
   */
  public function getBenutzer() : string {
    return $this->benutzer;
  }

  /**
   * Sessionid laden
   * @return string SessionID
   */
  public function getSessionid() : string {
    return $this->sessionid;
  }

  /**
   * Sessiontimeout laden
   * @return int Sessiontimeout (Timestamp)
   */
  public function sessiontimeout() : int {
    return $this->sessiontimeout;
  }

  /**
   * Inaktivitätszeit laden
   * @return int Inaktivitätszeit (Minuten)
   */
  public function getInaktivitaetszeit() : int {
    return $this->inaktivitaetszeit;
  }

  /**
   * Schuljahr laden
   * @return int ID des aktiven Schuljahre
   */
  public function getSchuljahr() : int {
    return $this->schuljahr;
  }

  /**
   * Passworttimeout laden
   * @return int Passworttimeout (Timestamp)
   */
  public function getPassworttimeout() : int {
    return $this->passworttimeout;
  }

  /**
   * Übersichtszahl laden
   * @return int Anzahl Übersichtselemente
   */
  public function getUebersichtszahl() : int {
    return $this->uebersichtszahl;
  }

  /**
   * Prüft, ob das übergebene Passwort zum Nutzer gehört
   * @param  string $passwort :)
   * @return bool             true, wenn das Passwort stimmt, false sonst
   */
  public function passwortPruefen($passwort) : bool {
    global $DBS;
    $sql = "SELECT COUNT(*) AS anzahl FROM kern_nutzerkonten WHERE passwort = SHA1(?) AND id = ?";
    $anfrage = $DBS->anfrage($sql, "si", $passwort, $this->id);
    if ($anfrage->getAnzahl() != 1) {
      return false;
    }
    $anfrage->werte($anz);
    if($anz != 1) {
      return false;
    }
    return true;
  }

  public function anmelden() : bool {
    global $DBS;
    // Alte Sessions mit dieser SessionID bearbeiten löschen
    $sql = "UPDATE kern_nutzersessions SET sessionid = null WHERE sessionid = [?]";
    $anfrage = $DBS->anfrage($sql, "s", $this->sessionid);

    $sql = "SELECT id FROM kern_nutzersessions WHERE nutzer = ? ORDER BY sessiontimeout LIMIT 2";
    $anfrage = $DBS->anfrage($sql, "i", $this->id);
    $sicheresessions = [];
    while ($anfrage->werte($sid)) {
      $sicheresessions[] = $sid;
    }
    if (count($sicheresessions) > 0) {
      $sicheresessionssql = implode(",", $sicheresessions);
      $sql = "DELETE FROM kern_nutzersessions WHERE id NOT IN ($sicheresessionssql) AND nutzer = ? AND sessiontimeout < ?";
      $timeoutlimit = time() - 60*60*24*2;
      $anfrage = $DBS->anfrage($sql, "ii", $this->id, $timeoutlimit);
    }

    $_SESSION['Benutzer'] = $this;
    $_SESSION['Letzte Anmeldung'] = true;
    \Check::einwilligung();
    $_COOKIE["EinwilligungDSH"] = "ja";

    // Neue Session eintragen
    $sessiondbid = $DBS->neuerDatensatz("kern_nutzersessions");
    $sql = "UPDATE kern_nutzersessions SET sessionid = [?], nutzer = ?, sessiontimeout = ?, anmeldezeit = ? WHERE id = ?";
    $anfrage = $DBS->anfrage($sql, "siiii", $this->sessionid, $this->id, $this->sessiontimeout, time(), $sessiondbid);

    // Postfachordner verwalten
    $this->postfachOrdnerAufraeumen();

    return true;
  }

  /**
   * Verlängert die Session bis jetzt + Inaktivitätszeit
   * @return bool true, wenn erfolgreich, sonst false
   */
  public function sessionVerlaengern() : bool {
    global $DBS;
    $zeit = time() + $this->inaktivitaetszeit*60;
    $sql = "UPDATE kern_nutzersessions SET sessiontimeout = ? WHERE nutzer = ? AND sessionid = [?]";
    $anfrage = $DBS->anfrage($sql, "iis", $zeit, $this->id, $this->sessionid);
    if ($anfrage->getAnzahl() > 0) {
      $this->sessiontimeout = $zeit;
      return true;
    }
    return false;
  }

  /**
   * Prüft, ob diese Person aktuell angemeldet ist
   * Erneuert die Session, wenn angemeldet
   * @return bool true, wenn angemeldet, sonst false
   */
  public function angemeldet() : bool {
    $angemeldet = false;
    global $DBS;

    $sql = "SELECT id FROM kern_nutzersessions WHERE nutzer = ? AND sessionid = [?] AND sessiontimeout > ?";
    $anfrage = $DBS->anfrage($sql, "isi", $this->id, $this->sessionid, time());
    if ($anfrage->getAnzahl() > 0) {
      $angemeldet = true;
    }

    if ($angemeldet) {
      $this->sessionVerlaengern();
    }
    return $angemeldet;
  }

  /**
   * Gibt den Balken für die Aktivitätsanzeuge aus
   * @param  string $id ID der Aktivitätsanzeige
   * @return string     HTML-Code der Aktivitätsanzeige
   */
  public function aktivitaetsanzeige($id) {
    $balken = new UI\Balken("Zeit", time(), $this->sessiontimeout, $this->inaktivitaetszeit);
    $balken->setID($id);
    $code  = $balken;
    $verlaengern = new UI\Knopf("Verlängern", "Erfolg");
    $verlaengern->addFunktion("onclick", "kern.schulhof.nutzerkonto.session.verlaengern()");
    $bearbeiten = new UI\Knopf("Profil bearbeiten");
    $bearbeiten->addFunktion("href", "Schulhof/Nutzerkonto/Profil");
    $abmelden = new UI\Knopf("Abmelden", "Warnung");
    $abmelden->addFunktion("onclick", "kern.schulhof.nutzerkonto.abmelden.fragen()");
    $absatz = new UI\Absatz("{$verlaengern} {$bearbeiten} {$abmelden}");
    $code .= $absatz;
    return $code;
  }

  /**
   * Löscht die Temporären Dateien im Postfach
   * @return bool true, wenn erfolgreich, sonst false
   */
  public function postfachOrdnerAufraeumen() : bool {
    global $ROOT;
    $erfolg = true;
    if (file_exists("$ROOT/dateien/Kern/personen/{$this->id}/postfach/temp")) {
      $erfolg = $erfolg && Dateisystem::ordnerLoeschen("$ROOT/dateien/Kern/personen/{$this->id}/postfach/temp");
    }
    if (!file_exists("$ROOT/dateien/Kern/personen/{$this->id}/postfach/temp")) {
      $erfolg = $erfolg && mkdir("$ROOT/dateien/Kern/personen/{$this->id}/postfach/temp", 0775, true);
    }
    return $erfolg;
  }

  /**
   * Verlängert die Session des Benutzers
   * @return mixed false wenn Fehlerhaft, Array mit neuem Ende und Inaktivitätszeit
   */
  public function sessiontimeoutVerlaengern() {
    global $DBS;
    $this->sessiontimeout = time()+60*$this->inaktivitaetszeit;
    $sql = "UPDATE kern_nutzersessions SET sessiontimeout = ? WHERE sessionid = [?] AND nutzer = ?";
    $anfrage = $DBS->anfrage($sql, "isi", $this->sessiontimeout, $this->sessionid, $this->id);
    $param = [];
    $param["Limit"] = $this->inaktivitaetszeit;
    $param["Ende"] = $this->sessiontimeout;
    return $param;
  }

  /**
   * Benutzer abmelden
   * @return bool true, wenn erfolgreich, sonst false
   */
  public function abmelden() : bool {
    global $DBS;
    $sql = "UPDATE kern_nutzersessions SET sessiontimeout = 0 WHERE sessionid = [?] AND nutzer = ?";
    $anfrage = $DBS->anfrage($sql, "si", $this->sessionid, $this->id);
    $this->postfachOrdnerAufraeumen();
    if ($anfrage->getAnzahl() == 0) {
      return false;
    }
    unset($_SESSION);
    return true;
  }

  /**
   * Setzt ein neues Passwort für den Benutzer, das für 1h gültig ist
   * @param  string  $mail    Mail-Adresse des Empfängers
   * @param  integer $stellen Anzahl der stellen des neuen Passworts
   * @param  string  $salt    Zufällige Verlängerung des Passworts
   * @return bool             true, wenn Setzen erfolgreich, sonst false
   */
  public function neuesPasswort($mail, $stellen = 10, $salt = "") : bool {
    global $DBS;
    $passwort = $this->generierePasswort($stellen);
    $passworttimeout = time() + 60*60;

    // Neues Passwort setzen
    $sql = "UPDATE kern_nutzerkonten SET passwort = SHA1(?), passworttimeout = ? WHERE id = ?";
    $DBS->anfrage($sql, "sii", $passwort.$salt, $passworttimeout, $this->id);

    // Nachricht verschicken
  	$betreff = "Passwort vergessen";
  	$anrede = $this->getAnrede();
    $empfaenger = $this->getName();

  	$text = "<p>$anrede</p>";
  	$text .= "<p>Es wurde ein neues Passwort generiert. Hier sind die Zugangsdaten:<br>";
  	$text .= "Benutzername: {$this->benutzer}<br>";
  	$text .= "Passwort: {$passwort}<br>";
  	$text .= "eMailadresse: {$mail}</p>";
  	$text .= "<p><b>Achtung!</b> Dieses Passwort ist aus Sicherheitsgründen ab jetzt nur <b>eine Stunde</b> gültig. Verstreicht diese Zeit, ohne dass eine Änderung am Passwort vorgenommen wurde, muss bei der Anmeldung über <i>Passwort vergessen?</i> ein neues Passwort angefordert werden. Dazu werden die Angaben <i>Benutzername</i> und <i>eMailadresse</i> benötigt. Das neue Passwort ist dann auch nur eine Stunde gültig.</p>";
  	$text .= "<p><b>Kurz:</b> Das Passwort sollte sobald wie möglich geändert werden!!</p>";
  	$text .= "<p>Viel Spaß mit dem neuen Zugang!</p>";

    $brief = new Mail();
  	return $brief->senden($empfaenger, $mail, $betreff, $text);
  }

  /**
   * Gibt ein zufälliges Passwort zurück
   * @param  int     $stellen Anzahl Stellen des Passworts
   * @return string           Zufälliges Passwort angegebener Länge
   */
  public function generierePasswort($stellen = 10) : string {
    $pool = "abcdefhkmnpqrstuvwxyz2345678ABCDEFGHKLMNPQRSTUVWXYZ!_-+";
    $passwort = "";
    srand ((double)microtime()*1000000);
    for($i = 0; $i < $stellen; $i++) {
        $passwort .= substr($pool,(rand()%(strlen ($pool))), 1);
    }
    return $passwort;
  }

  /**
   * Erzeugt ein Salt für ein Kennwort
   * @param  integer $stellen Anzahl an Zeichen
   * @return string           :)
   */
  public static function generiereSalt($stellen = 32) : string {
    $pool = "abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ!_-+";
    $salt = "";
    srand ((double)microtime()*1000000);
    for($i = 0; $i < $stellen; $i++) {
        $salt .= substr($pool,(rand()%(strlen ($pool))), 1);
    }
    return $salt;
  }

  /**
   * Erzeugt den Namen der Person
   * @return string Name der Person
   */
  public function getName() : string {
    return "{$this->titel} {$this->vorname} {$this->nachname}";
  }

  /**
   * Läd die letzten Sessions
   * @param  int $anzahl Anzahl der zu ladenden Sessions
   * @return array       Timestamps der letzten Sessions
   */
  public function getLetzteSessions($anzahl = null) : array {
    global $DBS;
    $anmeldungen = [];

    $sql = "SELECT anmeldezeit FROM kern_nutzersessions WHERE nutzer = ? ORDER BY anmeldezeit DESC";
    if ($anzahl !== null) {
      $anzahl ++;
      $sql .= " LIMIT ?";
      $anfrage = $DBS->anfrage($sql, "ii", $this->id, $anzahl);
    } else {
      $anfrage = $DBS->anfrage($sql, "i", $this->id);
    }

    while ($anfrage->werte($zeit)) {
      $anmeldungen[] = $zeit;
    }

    if (count($anmeldungen) > 0) {
      array_shift($anmeldungen);
    }

    return $anmeldungen;
  }

  /**
   * Gibt das Zugriffsrecht für Personen zurück, je nachdem, ob ein Fremdzugriff vorliegt
   * @return string String für die Rechteebene bei Fremdzugriff
   */
  public function istFremdzugriff() : string {
    global $DSH_BENUTZER;
    // Rechte Entscheidung:
    if ($DSH_BENUTZER->getId() != $this->id) {
      $recht = "kern.personen.profil";
      if (!$DSH_BENUTZER->hatRecht("$recht.sehen")) {
        throw new \Exception("Es liegt keine Berechitung für diese Funktion vor!");
      }
    } else {
      $recht = "kern.nutzerkonto.profil";
    }
    return $recht;
  }

  /**
   * Gibt das Sessionprotokoll für den Benutzer aus
   * @return array Elemente, die bei der Ausgabe erzeugt werden
   */
  public function getSessionprotokoll() : array{
    global $DSH_BENUTZER, $DBS;
    $recht = $this->istFremdzugriff();

    $rueck = [];
    $rueck[] = new UI\Meldung("Speicherdauer", "Sessions werden nach zwei Tagen automatisch gelöscht.", "Information");

    $sql = "SELECT id, {sessionid}, sessiontimeout, anmeldezeit FROM kern_nutzersessions WHERE nutzer = ? ORDER BY anmeldezeit DESC";
    $anfrage = $DBS->anfrage($sql, "i", $this->id);

    $darfloeschen = $DSH_BENUTZER->hatRecht("$recht.sessionprotokoll.löschen");
    $titel = ["", "Session-ID", "Sessiontimeout", "Anmeldezeit"];
    if ($darfloeschen) {$titel[] = "Aktionen";}

    $zeilen = [];
    while ($anfrage->werte($id, $sessionid, $sessiontimeout, $anmeldezeit)) {
      $neuezeile = [];
      $neuezeile[""] = new UI\Icon("fas fa-history");
      if ($sessionid != null) {
        $neuezeile["Session-ID"] = $sessionid;
      } else {
        $neuezeile["Session-ID"] = "<i>erloschen</i>";
      }

      if ($sessiontimeout > 0) {
        if ($sessionid == $this->sessionid && $DSH_BENUTZER->getId() == $this->id) {
          $sessiontimeout = "<i>laufende Session</i>";
        } else {
          $sessiontimeout = (new UI\Datum($sessiontimeout))->kurz();
        }
      } else {
        $sessiontimeout = "<i>abgemeldet</i>";
      }
      $neuezeile["Sessiontimeout"] = $sessiontimeout;
      $neuezeile["Anmeldezeit"] = (new UI\Datum($anmeldezeit))->kurz();
      if ($darfloeschen) {
        $loeschenknopf = UI\MiniIconKnopf::loeschen();
        $loeschenknopf->addFunktion("onclick", "kern.personen.sessions.loeschen('$id')");
        $neuezeile["Aktionen"] = $loeschenknopf;
      }
      $zeilen[] = $neuezeile;
    }

    $rueck[] = new UI\Tabelle("dshProfilSessionprotokoll", $titel, ...$zeilen);

    if ($darfloeschen) {
      $rueck[] = new UI\Absatz(new UI\Knopf("Alle Sessions löschen", "Warnung", "kern.personen.sessions.loeschen('alle')"));
    }
    return $rueck;
  }

  /**
   * Gibt das Aktionsporokoll für den Benutzer aus
   * @return array Elemente, die bei der Ausgabe erzeugt werden
   */
  public function getAktionsprotokoll() : array{
    global $DSH_BENUTZER, $DBS;
    $recht = $this->istFremdzugriff();

    $rueck = [];
    $rueck[] = new UI\Meldung("Speicherdauer", "Sessions werden nach zwei Tagen automatisch gelöscht.", "Information");

    $sql = "SELECT id, [sessionid], sessiontimeout, anmeldezeit FROM kern_nutzersessions WHERE nutzer = ? ORDER BY anmeldezeit DESC";
    $anfrage = $DBS->anfrage($sql, "i", $this->id);

    $darfloeschen = $DSH_BENUTZER->hatRecht("$recht.sessionprotokoll.löschen");
    $titel = ["", "Session-ID", "Sessiontimeout", "Anmeldezeit"];
    if ($darfloeschen) {$titel[] = "Aktionen";}

    $zeilen = [];
    while ($anfrage->werte($id, $sessionid, $sessiontimeout, $anmeldezeit)) {
      $neuezeile = [];
      $neuezeile[""] = new UI\Icon("fas fa-history");
      $neuezeile["Session-ID"] = $sessionid;
      if ($sessiontimeout > 0) {
        if ($sessionid == $this->sessionid && $DSH_BENUTZER->getId == $this->id) {
          $sessiontimeout = "<i>laufende Session</i>";
        } else {
          $sessiontimeout = new UI\Datum($sessiontimeout);
        }
      } else {
        $sessiontimeout = "<i>abgelaufen</i>";
      }
      $neuezeile["Sessiontimeout"] = $sessiontimeout;
      $neuezeile["Anmeldezeit"] = new UI\Datum($anmeldezeit);
      if ($darfloeschen) {
        $loeschenknopf = UI\MiniIconKnopf::loeschen();
        $loeschenknopf->addFunktion("onclick", "kern.personen.sessions.loeschen('$id')");
        $neuezeile["Anmeldezeit"] = $loeschenknopf;
      }
      $zeilen[] = $neuezeile;
    }

    $rueck[] = new UI\Tabelle("dshProfilSessionprotokoll", $titel, $zeilen);

    if ($darfloeschen) {
      $rueck[] = new UI\Absatz(new UI\Knopf("Alle Sessions löschen", "Warnung", "kern.personen.sessions.loeschen('alle')"));
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

    $sql = "SELECT {email}, {notifikationsmail}, {postmail}, {postalletage}, {postpapierkorbtage}, {uebersichtsanzahl}, {oeffentlichertermin}, {oeffentlicherblog}, {oeffentlichegalerie}, {inaktivitaetszeit}, {wikiknopf}, {kuerzel} FROM kern_nutzerkonten JOIN kern_nutzereinstellungen ON kern_nutzerkonten.id = kern_nutzereinstellungen.person LEFT JOIN kern_lehrer ON kern_nutzerkonten.id = kern_lehrer.id WHERE kern_nutzerkonten.id = ?";
    $anfrage = $DBS->anfrage($sql, "i", $this->id);
    $anfrage->werte($mail, $notifikationsmail, $postmail, $posttage, $papierkorbtage, $uebersicht, $oetermin, $oeblog, $oegalerie, $inaktiv, $wiki, $kuerzel);


    $reiter = new UI\Reiter("dshProfil");

    $formular         = new UI\FormularTabelle();
    $titelF = (new UI\Textfeld("dshProfilTitel"))->setWert($this->titel);
    if ($DSH_BENUTZER->hatRecht("$recht.titel")) {
      $titelF->setAutocomplete("honorific-prefix");
    } else {
      $titelF->setAttribut("disabled", "disabled");
    }

    $vornameF = (new UI\Textfeld("dshProfilVorname"))->setWert($this->vorname);
    if ($DSH_BENUTZER->hatRecht("$recht.vorname")) {
      $vornameF->setAutocomplete("given-name");
    } else {
      $vornameF->setAttribut("disabled", "disabled");
    }

    $nachnameF = (new UI\Textfeld("dshProfilNachname"))->setWert($this->nachname);
    if ($DSH_BENUTZER->hatRecht("$recht.nachname")) {
      $nachnameF->setAutocomplete("family-name");
    } else {
      $nachnameF->setAttribut("disabled", "disabled");
    }

    if ($this->getArt() == "l") {
      $kuerzelF = (new UI\Textfeld("dshProfilKuerzel"))->setWert($kuerzel);
      if (!$DSH_BENUTZER->hatRecht("$recht.kuerzel")) {
        $kuerzelF->setAttribut("disabled", "disabled");
      }
    }

    $formular[]       = (new UI\FormularFeld(new UI\InhaltElement("Titel:"),                     $titelF))->setOptional(true);
    $formular[]       = new UI\FormularFeld(new UI\InhaltElement("Vorname:"),                   $vornameF);
    $formular[]       = new UI\FormularFeld(new UI\InhaltElement("Nachname:"),                  $nachnameF);
    if ($this->getArt() == "l") {
      $formular[]       = (new UI\FormularFeld(new UI\InhaltElement("Kürzel:"),                      $kuerzelF))->setOptional(true);
    }

    $formular[]       = (new UI\Knopf("Änderungen speichern", "Erfolg"))  ->setSubmit(true);
    $formular         ->addSubmit("kern.schulhof.nutzerkonto.aendern.persoenliches()");
    $reiterkopf = new UI\Reiterkopf("Persönliches");
    $reiterspalte = new UI\Spalte("A1", $formular);
    $reiterkoerper = new UI\Reiterkoerper($reiterspalte->addKlasse("dshUiOhnePadding"));
    $reiter->addReitersegment(new UI\Reitersegment($reiterkopf, $reiterkoerper));


    $formular         = new UI\FormularTabelle();
    $mailF = (new UI\Mailfeld("dshProfilMail"))->setWert($mail);
    if ($DSH_BENUTZER->hatRecht("$recht.email")) {
      $mailF->setAutocomplete("email");
    } else {
      $mailF->setAttribut("disabled", "disabled");
    }

    $benutzerF = (new UI\Textfeld("dshProfilBenutzer"))->setWert($this->benutzer);
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
        $formular[]       = new UI\FormularFeld(new UI\InhaltElement("Inaktivitätszeit (min):"),       (new UI\Zahlenfeld("dshProfilInaktivitätszeit", 5, 300))->setWert($inaktiv));
        $formular[]       = new UI\FormularFeld(new UI\InhaltElement("Elemente pro Übersicht:"),       (new UI\Zahlenfeld("dshProfilElementeProUebersicht", 1, 10))->setWert($uebersicht));

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
        $reiterspalte[]   = new UI\Ueberschrift(3, "Postfach");
        $formular         = new UI\FormularTabelle();
        $formular[]       = new UI\FormularFeld(new UI\InhaltElement("Löschfrist Postfach:"),          (new UI\Zahlenfeld("dshProfilPostfachLoeschfrist", 1, 1000))->setWert($posttage));
        $formular[]       = new UI\FormularFeld(new UI\InhaltElement("Löschfrist Papierkorb:"),        (new UI\Zahlenfeld("dshProfilPapierkorbLoeschfrist", 1, 1000))->setWert($papierkorbtage));

        $formular[]       = (new UI\Knopf("Änderungen speichern", "Erfolg"))  ->setSubmit(true);
        $formular         ->addSubmit("kern.schulhof.nutzerkonto.aendern.einstellungen.postfach()");
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

    // if ($DSH_BENUTZER->hatRecht("$recht.aktionsprotokoll.sehen")) {
    //   $reiterspalte     = new UI\Spalte("A1");
    //   $aktionsprotokoll = $this->getAktionsprotokoll();
    //   foreach ($aktionsprotokoll as $a) {
    //     $reiterspalte[]   = $a;
    //   }
    //   $reiterkopf = new UI\Reiterkopf("Aktionsprotokoll");
    //   $reiterkoerper = new UI\Reiterkoerper($reiterspalte->addKlasse("dshUiOhnePadding"));
    //   $reiter->addReitersegment(new UI\Reitersegment($reiterkopf, $reiterkoerper));
    // }

    return $reiter;
  }

  /**
   * Prüft, ob der Benutzer das Recht hat, die Spezifizierte Aktion auszuführen
   * @param  string $aktion YAML-Syntax für Rechte
   * @return bool   true, wenn das Recht vorhanden ist, false sonst
   */
  public function hatRecht() : bool {
    // @TODO: Rechte auslesen!
    return true;
  }

}


?>
