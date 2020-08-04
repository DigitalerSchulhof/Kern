<?php
namespace Kern;
use DB;
use UI;
use Mail;

class Person {
  /** @var int ID */
  protected $id;
  /** @var char Art slvex*/
  protected $art;

  /** @var string Titel */
  protected $titel;
  /** @var string Vorname */
  protected $vorname;
  /** @var string Nachname */
  protected $nachname;
  /** @var char Geschlecht wmd*/
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
   * @param  char $art :)
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
   * @param  char $art :)
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
   * @return char Art
   */
  public function getArt() : char {
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
    return $this->string;
  }

  /**
   * Geschlecht laden
   * @return char Geschlecht
   */
  public function getGeschlecht() : char {
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
   * Name mit Titel der Person
   * @return int ID
   */
  public function __toString () : string {
    return "{$this->titel} {$this->vorname} {$this->nachname}";
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
    $_SESSION['DSGVO_FENSTERWEG'] = true;
    $_SESSION['DSGVO_EINWILLIGUNG_A'] = true;

    // Neue Session eintragen
    $sessiondbid = $DBS->neuerDatensatz("kern_nutzersessions");
    $sql = "UPDATE kern_nutzersessions SET sessionid = [?], nutzer = ?, sessiontimeout = ? WHERE id = ?";
    $anfrage = $DBS->anfrage($sql, "siis", $this->sessionid, $this->id, $this->sessiontimeout, $sessiondbid);

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
    $abmelden = new UI\Knopf("Abmelden", "Warnung");
    $abmelden->addFunktion("onclick", "kern.schulhof.nutzerkonto.abmelden.fragen()");
    $absatz = new UI\Absatz("{$verlaengern} {$abmelden}");
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
    $empfaenger = $this->__toString();

  	$text = "<p>$anrede</p>";
  	$text .= "<p>Es wurde ein neues Passwort generiert. Hier sind die Zugangsdaten:<br>";
  	$text .= "Benutzername: {$this->benutzer}<br>";
  	$text .= "Passwort: {$passwort}<br>";
  	$text .= "eMailadresse: {$mail}</p>";
  	$text .= "<p><b>Achtung!</b> Dieses Passwort ist aus Sicherheitsgründen ab jetzt nur <b>eine Stunde</b> gültig. Verstreicht diese Zeit, ohne dass eine Änderung am Passwort vorgenommen wurde, muss bei der Anmeldung über <i>Passwort vergessen?</i> ein neues Passwort angefordert werden. Dazu werden die Angaben <i>Benutzername</i> und <i>eMailadresse</i> benötigt. Das neue Passwort ist dann auch nur eine Stunde gültig.</p>";
  	$text .= "<p><b>Kurz:</b> Das Passwort sollte sobald wie möglich geändert werden!!</p>";
  	$text .= "<p>Viel Spaß mit dem neuen Zugang!";

  	return Mail::senden($empfaenger, $mail, $betreff, $text);
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
