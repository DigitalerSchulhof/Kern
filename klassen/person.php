<?php
namespace Kern;
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
  protected $geschlecht;

  /** @var array Benutzerarten: s=Schüler, l=Lehrer, v=Verwaltung, e=Eltern, x=Extern */
  const ARTEN = ["s", "l", "v", "e", "x"];

  /** @var array Geschlechter: w=weiblich, m=männlich, d=divers */
  const GESCHLECHTER = ["w", "m", "d"];

  /**
   * Erstellt eine neue Person
   * @param int    $id
   * @param string $titel
   * @param string $vorname
   * @param string $nachname
   */
  public function __construct($id, $titel = null, $vorname = null, $nachname = null) {
    $this->id = $id;
    $this->art = null;

    $this->titel = $titel;
    $this->vorname = $vorname;
    $this->nachname = $nachname;
    $this->geschlecht = null;
  }

  /**
   * Erzeugt aus der ID eine vollständige Person
   * @param  int $id Die Personenid
   * @return Person Die Person mit allen Variablen korrekt gesetzt
   */
  public static function vonID($id) : ?Person {
    global $DBS;
    $sql = "SELECT {art}, {geschlecht}, {vorname}, {nachname}, {titel} FROM kern_personen WHERE id = ?";
    $anfrage = $DBS->anfrage($sql, "i", $id);
    $anfrage->werte($art, $geschlecht, $vorname, $nachname, $titel);

    $person = new Person($id, $titel, $vorname, $nachname);
    $person->setArt($art);
    $person->setGeschlecht($geschlecht);
    return $person;
  }

  /**
   * ID setzen
   * @param  int $id
   * @return self             :)
   */
  public function setId($id) : self {
    $this->id = $id;
    return $this;
  }

  /**
   * Art setzen
   * @param  string $art
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
   * @param  string $geschlecht
   * @return self      :)
   */
  public function setGeschlecht($geschlecht) : self {
    if (!in_array($geschlecht, self::GESCHLECHTER)) {
      throw new \Exception("Ungültiges Geschlecht");
    }
    $this->geschlecht = $geschlecht;
    return $this;
  }

  public function generiereBenutzername() {
    $vor = str_replace(" ", "", $this->vorname);
    $nach = str_replace(" ", "", $this->nachname);

    if ($this->art == "l") {
      return substr($vor,0,1).substr($nach,0,7)."-".strtoupper($this->art);
    } else {
      return substr($nach,0,8).substr($vor,0,3)."-".strtoupper($this->art);
    }
  }

  /**
   * Titel setzen
   * @param  string $titel
   * @return self      :)
   */
  public function setTitel($titel) : self {
    $this->titel = $titel;
    return $this;
  }

  /**
   * Vorname setzen
   * @param  string $vorname
   * @return self      :)
   */
  public function setVorname($vorname) : self {
    $this->vorname = $vorname;
    return $this;
  }

  /**
   * Nachname setzen
   * @param  string $nachname
   * @return self      :)
   */
  public function setNachname($nachname) : self {
    $this->nachname = $nachname;
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
      if ($this->geschlecht == "w") {
        $anrede .= "Frau ";
      } else if ($this->geschlecht == "m") {
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
    return $this->getName();
  }

  /**
   * Erzeugt den Namen der Person
   * @return string Name der Person
   */
  public function getName() : string {
    $r = "";
    if($this->titel !== null) {
      $r .= "{$this->titel} ";
    }
    if($this->vorname !== null) {
      $r .= "{$this->vorname} ";
    }
    if($this->nachname !== null) {
      $r .= "{$this->nachname}";
    }
    return $r;
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

  /** @var mixed Rechtebaum des Nutzers */
  private $rechte;
  /** @var bool[] Rechtecache des Nutzers - Zuordnung [Recht => hatRecht]*/
  private $rechtecache;

  /**
   * Erstellt eine neues Nutzerkonto
   * @param id     $id
   * @param string $titel
   * @param string $vorname
   * @param string $nachname
   */
  public function __construct($id, $titel = null, $vorname = null, $nachname = null) {
    parent::__construct($id, $titel, $vorname, $nachname);
    $this->benutzer = null;

    $this->sessionid = null;
    $this->sessiontimeout = null;
    $this->inaktivitaetszeit = null;

    $this->schuljahr = null;
    $this->passworttimeout = null;
    $this->uebersichtszahl = null;

    $this->rechtecache = [];
    $this->rechteLaden();
  }

  /**
   * Erzeugt aus der ID ein vollständiges Nutzerkonto
   * @param  int $id Die Personenid
   * @return Nutzerkonto Das Nutzerkonto mit allen Variablen korrekt gesetzt
   */
  public static function vonID($id) : ?Nutzerkonto {
    global $DBS;
    $sql = "SELECT {art}, {geschlecht}, {vorname}, {nachname}, {titel} FROM kern_personen WHERE id = ?";
    $anfrage = $DBS->anfrage($sql, "i", $id);
    if(!$anfrage->werte($art, $geschlecht, $vorname, $nachname, $titel)) {
      return null;
    }

    $person = new Nutzerkonto($id, $titel, $vorname, $nachname);
    $person->setArt($art);
    $person->setGeschlecht($geschlecht);
    return $person;
  }

  /**
   * Benutzername setzen
   * @param  string $benutzer
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
   * Sessionid setzen
   * @param  string $sessionid
   * @return self              :)
   */
  public function setSessionid($sessionid) : self {
    $this->sessionid = $sessionid;
    return $this;
  }

  /**
   * Inaktivitätszeit setzen
   * @param  int $inaktivitaetszeit
   * @return self              :)
   */
  public function setInaktivitaetszeit($inaktivitaetszeit) : self {
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
  public function getBenutzer() {
    return $this->benutzer;
  }

  /**
   * Sessionid laden
   * @return string SessionID
   */
  public function getSessionid() {
    return $this->sessionid;
  }

  /**
   * Sessiontimeout laden
   * @return int Sessiontimeout (Timestamp)
   */
  public function getSessiontimeout() : int {
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
    return $this->passworttimeout ?? 0;
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
   * @param  string $passwort
   * @return bool             true, wenn das Passwort stimmt, false sonst
   */
  public function passwortPruefen($passwort) : bool {
    global $DBS;
    $sql = "SELECT COUNT(*) AS anzahl FROM kern_nutzerkonten WHERE passwort = SHA1(?) AND person = ?";
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

  /**
   * Meldet diesen Nutzer an
   * @return bool true, wenn erfolgreich, sonst false
   */
  public function anmelden() : bool {
    global $DBS;
    $this->aktionsprotokollLoeschen();

    // Alte Sessions mit dieser SessionID bearbeiten
    $sql = "UPDATE kern_nutzersessions SET sessionid = null WHERE sessionid = [?]";
    $anfrage = $DBS->silentanfrage($sql, "s", $this->sessionid);

    $sql = "SELECT id FROM kern_nutzersessions WHERE person = ? ORDER BY sessiontimeout LIMIT 2";
    $anfrage = $DBS->anfrage($sql, "i", $this->id);
    $sicheresessions = [];
    while ($anfrage->werte($sid)) {
      $sicheresessions[] = $sid;
    }
    if (count($sicheresessions) > 0) {
      $sicheresessionssql = implode(",", $sicheresessions);
      $sql = "DELETE FROM kern_nutzersessions WHERE id NOT IN ($sicheresessionssql) AND person = ? AND sessiontimeout < ?";
      $timeoutlimit = time() - 60*60*24*2;
      $anfrage = $DBS->silentanfrage($sql, "ii", $this->id, $timeoutlimit);
    }

    $_SESSION["Benutzer"] = $this;
    $_SESSION["Letzte Anmeldung"] = true;
    Check::einwilligung();
    $_COOKIE["EinwilligungDSH"] = "ja";

    $browser = Check::systeminfo();

    // Neue Session eintragen
    $sessiondbid = $DBS->neuerDatensatz("kern_nutzersessions", array(), "", false, true);
    $sql = "UPDATE kern_nutzersessions SET sessionid = [?], browser = [?], person = ?, sessiontimeout = ?, anmeldezeit = ? WHERE id = ?";
    $anfrage = $DBS->silentanfrage($sql, "ssiiii", $this->sessionid, $browser, $this->id, $this->sessiontimeout, time(), $sessiondbid);

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
    $sql = "UPDATE kern_nutzersessions SET sessiontimeout = ? WHERE person = ? AND sessionid = [?]";
    $anfrage = $DBS->silentanfrage($sql, "iis", $zeit, $this->id, $this->sessionid);
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
  public function angemeldet($verlaengern = true) : bool {
    $angemeldet = false;
    global $DBS;

    $sql = "SELECT id FROM kern_nutzersessions WHERE person = ? AND sessionid = [?] AND sessiontimeout > ?";
    $anfrage = $DBS->anfrage($sql, "isi", $this->id, $this->sessionid, time());
    if ($anfrage->getAnzahl() > 0) {
      $angemeldet = true;
    }

    if ($angemeldet && $verlaengern) {
      $this->sessionVerlaengern();
    }
    return $angemeldet;
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
    $sql = "UPDATE kern_nutzersessions SET sessiontimeout = ? WHERE sessionid = [?] AND person = ?";
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
    $sql = "UPDATE kern_nutzersessions SET sessiontimeout = 0 WHERE sessionid = [?] AND person = ?";
    $anfrage = $DBS->silentanfrage($sql, "si", $this->sessionid, $this->id);
    $this->postfachOrdnerAufraeumen();
    if ($anfrage->getAnzahl() == 0) {
      return false;
    }
    unset($_SESSION);
    session_destroy();
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
    $DBS->silentanfrage($sql, "sii", $passwort.$salt, $passworttimeout, $this->id);

    $DBS->logZugriff("DB", "kern_nutzerkonten", "UPDATE kern_nutzerkonten SET passwort = SHA1(?), passworttimeout = ? WHERE id = ?", "Änderung", [["*****", $passworttimeout, $this->id]]);

    // Nachricht verschicken
  	$betreff = "Passwort vergessen";
  	$anrede = $this->getAnrede();
    $empfaenger = $this->getName();

  	$text = "<p>$anrede,</p>";
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
   * Lädt die letzten Sessions
   * @param  int $anzahl Anzahl der zu ladenden Sessions
   * @return array       Timestamps der letzten Sessions
   */
  public function getLetzteSessions($anzahl = null) : array {
    global $DBS;
    $anmeldungen = [];

    $sql = "SELECT anmeldezeit FROM kern_nutzersessions WHERE person = ? ORDER BY anmeldezeit DESC";
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
   * Löscht das Sessionprotokoll dieses Benutzers
   * @param  int $id -1 für alles, sonst id der Session
   * @return bool    true wenn erfolgreich, false sonst
   */
  public function sessionprotokollLoeschen($id = null) {
    global $DBS;

    if ($id === null) {
      // 2 Tage-Frist
      $frist = time()-2*60*60*24;
      $sql = "DELETE FROM kern_nutzersessions WHERE sessiontimeout < ? AND person = ?";
      $anfrage = $DBS->silentanfrage($sql, "ii", $frist, $this->id);
    } else if ($id === -1) {
      $sql = "DELETE FROM kern_nutzersessions WHERE person = ?";
      $anfrage = $DBS->anfrage($sql, "i", $this->id);
    } else {
      $sql = "DELETE FROM kern_nutzersessions WHERE person = ? AND id = ?";
      $anfrage = $DBS->anfrage($sql, "ii", $this->id, $id);
    }

    return $anfrage->getAnzahl() > 0;
  }

  /**
   * Löscht das Sessionprotokoll dieses Benutzers
   * @param  int $id -1 für alles, sonst id der Session
   * @return bool    true wenn erfolgreich, false sonst
   */
  public function aktionsprotokollLoeschen($id = null) {
    global $DBS;

    if ($id === null) {
      // 30 Tage-Frist
      $frist = time()-30*60*60*24;
      $sql = "DELETE FROM kern_nutzeraktionslog WHERE zeitpunkt < ?";
      $anfrage = $DBS->silentanfrage($sql, "i", $frist);
    } else if ($id === -1) {
      $sql = "DELETE FROM kern_nutzeraktionslog WHERE person = ?";
      $anfrage = $DBS->anfrage($sql, "i", $this->id);
    } else {
      $sql = "DELETE FROM kern_nutzeraktionslog WHERE person = ? AND id = ?";
      $anfrage = $DBS->anfrage($sql, "ii", $this->id, $id);
    }

    return $anfrage->getAnzahl() > 0;
  }

  /**
   * Lädt die Rechte aus der Datenbank und setzt den Rechtecache zurück
   */
  public function rechteLaden() {
    global $DBS;
    $anfrage = $DBS->anfrage("SELECT {recht} FROM kern_nutzerrechte as nr WHERE person = ? UNION SELECT {recht} FROM kern_rollenrechte as rr JOIN kern_rollenzuordnung as rz ON rz.rolle = rr.rolle WHERE rz.person = ?", "ii", $this->id, $this->id);
    $rechte = [];
    while($anfrage->werte($recht)) {
      $rechte[] = $recht;
    }
    $this->rechte = Rechtehelfer::array2Baum($rechte);
    $this->rechte = Rechtehelfer::baumSortieren($this->rechte);
    $this->rechtecache = [];
  }

  /**
   * Gibt den Rechtebaum des Nutzers zurück
   * @return string[]
   */
  public function getRechte() {
    return $this->rechte;
  }

  /**
   * Prüft, ob der Benutzer das Recht hat, die Spezifizierte Aktion auszuführen
   * @param  string $recht Das Recht
   * @param  bool   $cache Ob der Rechtecache verwendet werden darf
   * @return bool   true, wenn das Recht vorhanden ist, false sonst
   */
  public function hatRecht($recht, $cache = true) : bool {
    if($cache) {
      if(!isset($this->rechtecache[$recht])) {
        $this->rechtecache[$recht] = Rechtehelfer::hatRecht($this->rechte, $recht);
      }
      return $this->rechtecache[$recht];
    }
    return Rechtehelfer::hatRecht($this->rechte, $recht);
  }
}
?>
