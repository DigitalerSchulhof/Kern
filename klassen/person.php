<?php
namespace Kern;
use DB;
use UI;

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


  public function aktivitaetsanzeige($id) {
    $balken = new UI\Balken("Zeit", time(), $this->sessiontimeout, $this->inaktivitaetszeit);
    $balken->setID($id);
    $code  = $balken;
    $verlaengern = new UI\Knopf("Verlängern", "Erfolg");
    $verlaengern->addFunktion("onclick", "nutzerkonto.sessionVerlaengern()");
    $abmelden = new UI\Knopf("Abmelden", "Warnung");
    $abmelden->addFunktion("onclick", "nutzerkonto.abmelden()");
    $absatz = new UI\Absatz("{$verlaengern} {$abmelden}");
    $code .= $absatz;
    return $code;
  }
}


?>
