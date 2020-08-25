<?php
namespace Kern;

/**
* Eine Datenbankverbindung
*/
class DB {
  /** @var mysqli Enthält die Datenbankverbindung */
  private $db;
  /** @var string Enthält den Schlüssel der Datenbank */
  private $schluessel;
  /** @var bool wenn true, wird in Aktionslog gespeichert, wenn die DB verändert wird, false sonst */
  private $log;
  /** @var string Name der Datenbank*/
  private $dbname;

	/**
	* @param string $host :)
	* @param string $benutzer :)
  * @param int    $port :)
	* @param string $passwort :)
	* @param string $datenbank :)
	* @param string $schluessel :)
	* @param string $aktionslog 1
	*/
  public function __construct($host, $port, $benutzer, $passwort, $datenbank, $schluessel) {
    $this->db = new \mysqli($host, $benutzer, $passwort, $datenbank, $port);
  	$this->db->set_charset("utf8");
    $this->schluessel = $schluessel;
    $this->dbname = $datenbank;
    $this->log = false;
  }

  /**
	* Schließt die Verbindung zur Datenbank
	* @return bool true wenn geschlossen, sonst false
	*/
  public function trennen() : bool {
    return $this->db->close();
  }

  /**
   * Aktionslog an- / ausschalten
   * @param  bool $log true = an, false = aus
   * @return self      :)
   */
  public function setLog($log) : self {
    $this->log = $log;
    return $this;
  }

  /**
   * Gibt den Namen der Datenbank zurück
   * @return string Name der Datenbank
   */
  public function getDatenbankname() : string {
    return $this->dbname;
  }

  /**
   * Setzt in allen Datenbanken die LOG-Variable
   */
  public static function log() {
    global $DBS, $DSH_DB;
    $log = Einstellungen::laden("Kern", "Aktionslog");
    if ($log == "1") {
      $log = true;
    } else {
      $log = false;
    }
    foreach($DSH_DB as $db) {
      $db->setLog($log);
    }
  }

  public function silentanfrage($anfrage, $parameterarten = "", ...$werte) : Anfrage {
    $alterlog = $this->log;
    $this->setLog(false);
    $anfrage = $this->anfrage($anfrage, $parameterarten, ...$werte);
    $this->log = $alterlog;
    return $anfrage;
  }

  /**
  * Stellt eine Anfrage an die Datenbank
  * @param string $anfrage SQL-Anfrage {x} wird entschlüsselt, [y] wird verschlüsselt
	* @param string $parameterarten Datentypen der übergebenen Werte für den Prepared-Request
  * @param string $parameterarten :)
	* @param array $werte Array mit den Werten, die übergeben werden
  * @return Anfrage Enstprechendes Anfrageobjekt
  */
  public function anfrage($anfrage, $parameterarten = "", ...$zwerte) : Anfrage {
    $ergebnis = [];

    try {
      // Ziel: Bringe das Array Werte auf die Form
      // $werte[Anfragennummer] = array mit den Werten;
      $werte = [];
      // Anfrage ohne Werte
      if (is_array($zwerte) && count($zwerte) == 0) {
        $werte[] = [];
      }
      else if (is_array($zwerte) && is_array($zwerte[0]) && count($zwerte[0]) == 0) {
        $werte[] = [];
      }
      // Einfache Anfrage - es wurden einzelne Werte übergeben
      else if (is_array($zwerte) && count($zwerte) > 0 && !is_array($zwerte[0])) {
        $werte[] = $zwerte;
      }
      // Mehrfache Anfrage - es wurden mehrere Arrays übergeben
      else if (is_array($zwerte) && is_array($zwerte[0]) && count($zwerte[0]) > 0 && !is_array($zwerte[0][0])) {
        $werte = $zwerte;
      }
      // Mehrfache Anfrage - es wurde ein Array mit Arrays übergeben
      else if (is_array($zwerte) && is_array($zwerte[0]) && is_array($zwerte[0][0]) && !is_array($zwerte[0][0][0]) && count($zwerte) == 1) {
        $werte = $zwerte[0];
      }
      // FEHLER
      else {
        throw new \Exception("Übergebene Paramter in unzulässigem Format");
      }
    } catch (Exception $e) {
      throw new \Exception("Übergebene Paramter in unzulässigem Format: {$e->getMessage()}");
    }


    // Parameter prüfen
    $werteproanfrage = strlen($parameterarten);
    $paramfehler = false;
    foreach ($werte as $w) {
      if (count($w) != $werteproanfrage) {
        $paramfehler = true;
      }
    }

    if ($paramfehler) {
      throw new \Exception("Übergebene Paramter passen nicht zu übergebenen Parametertypen");
    }

    // Referenzen für bind auf die Werte erstellen
    $ref = [];
    // Durchsuche alle Anfragen
    for ($i = 0; $i<count($werte); $i++) {
      // Setze Referenzen für die Werte innerhalb der Anfrage
      $refneu = [];
      for ($j = 0; $j<count($werte[$i]); $j++) {
        $refneu[] = &$werte[$i][$j];
      }
      $ref[] = $refneu;
    }

    // Verschlüsselungsersetungen vornehmen
    $anfragewertlos = $anfrage;
    $anfragewertlos = str_replace("{", "", $anfragewertlos);
    $anfragewertlos = str_replace("}", "", $anfragewertlos);
    $anfragewertlos = str_replace("[", "", $anfragewertlos);
    $anfragewertlos = str_replace("]", "", $anfragewertlos);
    $anfrage = str_replace("{", "AES_DECRYPT(", $anfrage);
    $anfrage = str_replace("}", ", '$this->schluessel')", $anfrage);
    $anfrage = str_replace("[", "AES_ENCRYPT(", $anfrage);
    $anfrage = str_replace("]", ", '$this->schluessel')", $anfrage);
    $anfrage = str_replace("§", "'$this->schluessel'", $anfrage);

    // Stelle Anfrage
    $anzahl = 0;
    $sql = $this->db->prepare($anfrage);
    if ($sql) {
      foreach ($ref as $r) {
        if (strlen($parameterarten) > 0) {
          $sql->bind_param($parameterarten, ...$r);
        }
        if ($sql->execute()) {
          $dbergebnis = $sql->get_result();
          // Anzahl beeinflusster Zeilen ausgeben, falls keine Rückgabe vorhanden
          $anzahl = $this->db->affected_rows;
          // Rückgabeart bestimmen, falls es eine Rückgabe gibt
          if ($dbergebnis) {
            while ($e = $dbergebnis->fetch_row()) {
              array_push($ergebnis, $e);
            }
          }
        }
      }
      $sql->close();
    }
    else {
      // @TODO: Enfernen!
      var_dump(\debug_backtrace(), 1);
      throw new \Exception("Ungültige Anfrage\nFehler: ".mysqli_error($this->db)."<br>\n".$anfrage);
    }

    // Aus Anfrage Logeintrag basteln
    $anfragenteile = explode(" ", $anfragewertlos);
    if (count($anfragenteile) >= 3) {
      if (strtoupper($anfragenteile[1]) == "INTO" || strtoupper($anfragenteile[1]) == "FROM") {
        $tabellepfad = $anfragenteile[2];
      } else {
        $tabellepfad = $anfragenteile[1];
      }

      $aktion = [];
      if (strpos(strtoupper($anfragenteile[0]), "INSERT") !== false) {
        $aktion[] = "Neuer Datensatz";
      }
      if (strpos(strtoupper($anfragenteile[0]), "UPDATE") !== false) {
        $aktion[] = "Änderung";
      }
      if (strpos(strtoupper($anfragenteile[0]), "DELETE") !== false) {
        $aktion[] = "Löschung";
      }

      if (count($aktion) > 0) {
        $aktion = join(", ", $aktion);
        global $DBS;
        $DBS->logZugriff("DB", $tabellepfad, $anfragewertlos, $aktion, $werte);
      }
    }

    return new Anfrage($anzahl, $ergebnis);
  }

  /**
  * Prüft, ob ein Datensatz, welcher der Bedingung entspricht, schon in der übergeben Tabelle vorhanden ist
  * @param string $tabelle Die Tabelle, in welcher geprüft wird
	* @param string $bedingung Die SQL-Bedingung <b>ohne WHERE</b>, welche zu prüfen ist
  * @param string $parameterarten :)
	* @param array $werte Array mit den Werten, die übergeben werden
  * @return bool
  */
  public function existiert($tabelle, $bedingung, $parameterarten = "", ...$werte) : bool {
    $sql = "SELECT COUNT(*) FROM $tabelle WHERE $bedingung";
    $sql = $this->anfrage($sql, $parameterarten, ...$werte);
    $sql->werte($anzahl);
    return $anzahl > 0;
  }

  /**
   * Schreibt einen Zugriff ins Aktionslog
   * @param  string $art            DB oder Datei
   * @param  string $tabellepfad    Tabelle oder Pfad auf den zugegriffen wurde
   * @param  string $datensatzdatei Datei die geschrieben wurde
   * @param  string $aktion         Aktion die ausgeführt wurde
   * @param  array  $werte          Werte einer Anfrage
   */
  public function logZugriff($art, $tabellepfad, $datensatzdatei, $aktion, $werte = []) {
    if (!$this->log) {
      return;
    }
    global $DBS, $DSH_BENUTZER;
    if ($art == "DB") {
      $log = "<b>Anfrage:</b><br>$datensatzdatei<br><br>";
      if (count($werte) > 0) {
        $log .= "<b>Werte:</b><br>";
        $logw = [];
        if (!is_array($werte[0])) {
          $nr = 1;
          foreach ($werte as $w) {
            $logw[] = "[$nr] $w";
            $nr++;
          }
        } else {
          foreach ($werte as $wert) {
            $nr = 1;
            $logw = [];
            foreach ($wert as $w) {
              $logw[] = "[$nr] $w";
              $nr++;
            }
            $log .= join(", ", $logw)."<br>";
          }
        }
      }
      $datensatzdatei = $log;
    }

    $zeitpunkt = time();
    $neueid = $DBS->neuerDatensatz("kern_nutzeraktionslog", array(), "", true, true);
    if ($DSH_BENUTZER !== null) {
      $nutzerid = $DSH_BENUTZER->getId();
      $sql = $this->db->prepare("UPDATE kern_nutzeraktionslog SET person = ?, art = AES_ENCRYPT(?, '{$this->schluessel}'), tabellepfad = AES_ENCRYPT(?, '{$this->schluessel}'),  datensatzdatei = AES_ENCRYPT(?, '{$this->schluessel}'), aktion = AES_ENCRYPT(?, '{$this->schluessel}'),  zeitpunkt = ? WHERE id = ?");
      $sql->bind_param("issssii", $nutzerid, $art, $tabellepfad, $datensatzdatei, $aktion, $zeitpunkt, $neueid);
    } else {
      $sql = $this->db->prepare("UPDATE kern_nutzeraktionslog SET art = AES_ENCRYPT(?, '{$this->schluessel}'), tabellepfad = AES_ENCRYPT(?, '{$this->schluessel}'),  datensatzdatei = AES_ENCRYPT(?, '{$this->schluessel}'), aktion = AES_ENCRYPT(?, '{$this->schluessel}'),  zeitpunkt = ? WHERE id = ?");
      $sql->bind_param("ssssii", $art, $tabellepfad, $datensatzdatei, $aktion, $zeitpunkt, $neueid);
    }
    $sql->execute();
    $sql->close();
  }

  /**
   * Legt einen leeren Datensatz an
   * @param  string $tabelle :)
   * @param  array  $fehldr Auszufüllende Felder und deren Werte
   * <code>["Feld1" => "Wert1", Feld2 => "[?]"]
   * @param  string $parameterarten Siehe DB::anfrage() :)
   * @param  mixed  ...$parameter Siehe DB::anfrage() :)
   * Wenn mehr Parameter als Parameterarten übergeben werden, sind die jeweils letzen Parameter <code>$anonym</code> und <code>$silent</code>
   * param  bool   $anonym Wenn true, wird der Datensatz ohne Nutzerverbindung angelegt
   * param  bool   $silent verzichtet auf den Aktionslog
   * @return int    Wert der Spalte <code>id</code> des neuen Datensatzes
   */
  public function neuerDatensatz($tabelle, $felder = array(), $parameterarten = "", ...$parameter) {
    $fehler = false;

    $anonym = false;
    $silent = false;
    if(count($parameter) > strlen($parameterarten)) {
      $anonym = array_pop($parameter);
    }
    if(count($parameter) > strlen($parameterarten)) {
      $silent = array_pop($parameter);
    }

    if($anonym) {
      $sql = $this->db->prepare("SELECT MAX(person) FROM kern_nutzerkonten");
      if ($sql->execute()) {
        $sql->bind_result($benutzer);
        $sql->fetch();
        $benutzer++;
      }
      $sql->close();
    } else {
      if (isset($_SESSION['Benutzer'])) {
        $benutzer = $_SESSION['Benutzer']->getId();
      } else {
        throw new \Exception("Nicht identifizierter Benutzer versucht");
      }
    }

    // Neue ID bestimmten und eintragen
    $jetzt = time();
    $sql = $this->db->prepare("SET FOREIGN_KEY_CHECKS = 0;");
    $sql->execute();
    $sql->close();

    $sql = $this->db->prepare("INSERT INTO $tabelle (id, idvon, idzeit) SELECT id, idvon, idzeit FROM (SELECT IFNULL(id*0,0)+? AS idvon, IFNULL(id*0,0)+? AS idzeit, IFNULL(MIN(id)+1,1) AS id FROM $tabelle WHERE id+1 NOT IN (SELECT id FROM $tabelle)) AS vorherigeid");
    $sql->bind_param("ii", $benutzer, $jetzt);
    $sql->execute();
    $sql->close();

  	$sql = $this->db->prepare("SET FOREIGN_KEY_CHECKS = 1;");
    $sql->execute();
    $sql->close();

    // ID zurückgewinnen
    $id = null;
    $sql = $this->db->prepare("SELECT id FROM $tabelle WHERE idvon = ? AND idzeit = ?");
    $sql->bind_param("ii", $benutzer, $jetzt);
    if ($sql->execute()) {
      $sql->bind_result($id);
      $sql->fetch();
    }
    else {$fehler = true;}
    $sql->close();
    // Persönliche Daten löschen
    if ($id !== null) {
      $sql = $this->db->prepare("UPDATE $tabelle SET idvon = NULL, idzeit = NULL WHERE id = ?");
      $sql->bind_param("i", $id);
      $sql->execute();
      $sql->close();
    }

    if (!$silent) {
      global $DBS;
      $DBS->logZugriff("DB", $tabelle, $id, "Neuer Datensatz");
    }

    if($id === null) {
      throw new \Exception("Es konnte kein neuer Datensatz angelegt werden");
    }

    if(count($felder) > 0) {
      $sql = "UPDATE $tabelle SET ";
      foreach($felder as $feld => $wert) {
        $sql .= "$feld = $wert, ";
      }
      $sql = substr($sql, 0, -2);
      $sql .= " WHERE id = ?";
      if($silent) {
        $this->silentanfrage($sql, "{$parameterarten}i", array_merge($parameter, [$id]));
      } else {
        $this->anfrage($sql, "{$parameterarten}i", array_merge($parameter, [$id]));
      }
    }

    return $id;
  }

  /**
   * Ändert den Datensatz mit der ID <code>$id</code> um die Felder <code>$felder</code>.
   * @param  string $tabelle      :)
   * @param  integer|array $id
   * Wenn <code>int</code>:  Die ID des Datensatzes (Wird zu <code>["id" => $id]</code>)
   * Wenn <code>array/code>: [Index-Feld => Wert]
   * @param  array $felder        :)
   * @param  string $parameterarten Siehe DB::anfrage() :)
   * @param  array $parameter     Siehe DB::anfrage() :)
   * Wenn mehr Parameter als Parameterarten übergeben werden, wird letze Parameter <code>$silent</code>
   * param  bool   $silent verzichtet auf den Aktionslog
   */
  public function datensatzBearbeiten($tabelle, $id, $felder, $parameterarten, ...$parameter) {
    if(!is_array($id)) {
      $id = array("id" => $id);
    }
    $silent = false;
    if(count($parameter) > strlen($parameterarten)) {
      $silent = array_pop($parameter);
    }

    $sql = "UPDATE $tabelle SET ";
    foreach($felder as $feld => $wert) {
      $sql .= "$feld = $wert, ";
    }
    $sql = substr($sql, 0, -2);
    $sql .= " WHERE ".array_keys($id)[0]." = ?";
    if($silent) {
      $this->silentanfrage($sql, "{$parameterarten}i", array_merge($parameter, [array_values($id)[0]]));
    } else {
      $this->anfrage($sql, "{$parameterarten}i", array_merge($parameter, [array_values($id)[0]]));
    }
  }

  /**
   * Löscht den Datensatz mit der ID aus der Tabelle.
   * @param  string  $tabelle :)
   * @param  integer|array $id
   * Wenn <code>int</code>:  Die ID des Datensatzes (Wird zu <code>["id" => $id]</code>)
   * Wenn <code>array/code>: [Index-Feld => Wert]
   * @param  boolean $silent  Verzicht auf den Aktionslog
   */
  public function datensatzLoeschen($tabelle, $id, $silent = false) {
    if(!is_array($id)) {
      $id = array("id" => $id);
    }
    if($silent) {
      $this->silentanfrage("DELETE FROM $tabelle WHERE ".array_keys($id)[0]." = ?", "i", array_values($id)[0]);
    } else {
      $this->anfrage("DELETE FROM $tabelle WHERE ".array_keys($id)[0]." = ?", "i", array_values($id)[0]);
    }
  }

  /**
   * Öffnet alle gefragten Datenbankenverbindungen und setzt die Variablen
   */
  public static function datenbankenLaden() {
    global $DSH_DB, $EINSTELLUNGEN;
    if(!isset($DSH_DB)) {
      $DSH_DB = array();
    }

    // Schon Geladene nicht nochmal laden
    if(!isset($DSH_DB["schulhof"])) {
      global $DBS;
      $e = $EINSTELLUNGEN["Datenbanken"]["Schulhof"];
      $DBS = new DB($e["Host"], $e["Port"], $e["Benutzer"], $e["Passwort"], $e["DB"], $e["Schluessel"]);
      $DSH_DB["schulhof"] = $DBS;
    }

    DB::log();
  }
}
?>
