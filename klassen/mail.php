<?php
namespace Kern;

class Mail {
  /** @var string Adrese des SMTP-Hosts */
  private $host;
  /** @var int Port des SMTP-Hosts */
  private $port;
  /** @var string Angezeigter Name der Mailadresse */
  private $titel;
  /** @var bool true, wenn Authentifizierung benötigt wird, false sonst */
  private $authentifizierung;
  /** @var string Mailadresse des Zugangs */
  private $adresse;
  /** @var string Benutzername des Zugangs */
  private $benutzer;
  /** @var string Passwort des Zugangs */
  private $passwort;
  /** @var string Signatur als Plain-Text */
  private $signaturPlain;
  /** @var string Signatur als HTML-Code */
  private $signaturHTML;

  /** @var string Name der Schule */
  private $schulname;
  /** @var string Ort der Schule */
  private $schulort;
  /** @var string Schuldomain */
  private $schuldoamin;

  /**
   * Erstellt eine neue Mailverbindung
   */
  public function __construct() {
    $einstellungen  = Einstellungen::ladenAlle("kern");
    $this->host     = $einstellungen["MailSmtpServer"];
    $this->port     = $einstellungen["MailSmtpPort"];
    $this->titel    = $einstellungen["MailTitel"];
    if ($einstellungen["MailSmtpAuthentifizierung"] == "1") {
      $this->authentifizierung = true;
    } else {
      $this->authentifizierung = false;
    }
    $this->adresse = $einstellungen["Mailadresse"];
    $this->benutzer = $einstellungen["Mailbenutzer"];
    $this->passwort = $einstellungen["Mailpasswort"];
    $this->signaturPlain = $einstellungen["MailSignaturPlain"];
    $this->signaturHTML = $einstellungen["MailSignaturHTML"];

    $this->schulname = $einstellungen["Schulname"];
    $this->schulort = $einstellungen["Schulort"];
    $this->schuldomain = $einstellungen["Schuldomain"];
  }

  public function setAttribute($host, $port, $titel, $auth, $adresse, $benutzer, $passwort, $signaturP, $signaturH) {
    $this->host     = $host;
    $this->port     = $port;
    $this->titel    = $titel;
    if ($auth == "1") {
      $this->authentifizierung = true;
    } else {
      $this->authentifizierung = false;
    }
    $this->adresse = $adresse;
    $this->benutzer = $benutzer;
    $this->passwort = $passwort;
    $this->signaturPlain = $signaturP;
    $this->signaturHTML = $signaturH;
  }

  /**
   * Gibt die Adresse und den zugeörogen Titel zurück
   * @return string :)
   */
  public function __toString() : string {
    return "{$this->adresse} ({$this->titel})";
  }

  /**
   * Verschickt eine Nachricht vom Schulhofmailaccount
   * @param  string  $empfaenger     Name des Empfängers
   * @param  string  $mailempfaenger Adresse des Empfängers
   * @param  string  $betreff        Betreff der Nachricht
   * @param  string  $text           Text der Nachricht in HTML
   * @param  string  $textPlain      Text der Nachricht als Plaintext
   * @param  boolean $signatur       Soll eine Signatur mitgeschickt werden
   * @return bool                    true, wenn Versand erfolgreich, sonst false
   */
  public function senden($empfaenger, $mailempfaenger, $betreff, $text, $textPlain = null, $signatur = true) : bool {
  	global $ROOT;

    require_once "$ROOT/core/phpmailer/PHPMailerAutoload.php";

  	// Vorbereitungen treffen
  	$umschlag = new \PHPMailer();
  	$umschlag->CharSet  = 'utf-8';
  	$umschlag->IsSMTP();
    $umschlag->Host     = $this->host;
  	$umschlag->SMTPAuth = $this->authentifizierung;
  	$umschlag->Username = $this->benutzer;
  	$umschlag->Password = $this->passwort;
      $umschlag->From     = $this->adresse;
      $umschlag->FromName = $this->titel;
  	$umschlag->AddAddress($mailempfaenger, $empfaenger);
  	$umschlag->Subject = $betreff;
  	$umschlag->IsHTML(true);

  	// HTML-Nachricht zusammenbauen
  	$HTML = "<html>";
  	$HTML .= "<body style=\"background: #ffffff;font-family: sans-serif;font-size: 13px;font-weight: normal;padding: 0;margin: 0;list-style-type: none;line-height: 1.2em;text-decoration: none;box-sizing: border-box;\">";
  	$HTML .= "<div style=\"width:100%;padding: 10px;margin-bottom: 10px; border-bottom: 3px solid #000000;text-align: left;box-sizing: border-box;\">";
  		$HTML .= "<a style=\"display:inline-block;text-decoration:none;font-size:inherit; text-align: left;\" href=\"{$this->schuldomain}\">";
  		  $HTML .= "<img style=\"float:left;padding-right:10px; color: #000000;\" src=\"{$this->schuldomain}/dateien/schulspezifisch/logo.png\"/>";
  	    $HTML .= "<span style=\"float:left;display:block; color: #000000;\">";
  	      $HTML .= "<span style=\"font-weight:bold;font-size:22px;height:28px;padding:2px 0 0 0;display:block;line-height:1\">{$this->schulname}</span>";
  	      $HTML .= "<span style=\"font-size:22px;height:28px;padding:2px 0 0 0;display:block;line-height:1\">{$this->schulort}</span>";
  	    $HTML .= "</span>";
  			$HTML .= "<div style=\"clear:both\"></div>";
  	  $HTML .= "</a>";
  	$HTML .= "</div>";
  	$HTML .= "<div style=\"width:100%;padding: 10px;margin-bottom: 10px;box-sizing: border-box;\">";
  	$HTML .= $text;
  	if ($signatur) {$HTML .= $this->signaturHTML;}
  	$HTML .= "</div>";
  	$HTML .= "</body>";
  	$HTML .= "</html>";
  	$umschlag->Body = $HTML;

  	// HTML in Plain umwandeln
  	if ($textPlain === null) {
  		$plain = str_replace("<p>", "", $text);
  		$plain = str_replace("</p>", "<br>", $plain);
  		$plain = str_replace("<i>", "›", $plain);
  		$plain = str_replace("</i>", "‹", $plain);
  		$plain = str_replace("<b>", "»", $plain);
  		$plain = str_replace("</b>", "«", $plain);
  	}
  	else {
  		$plain = $textPlain;
  	}
  	if ($signatur) {$plain .= $this->signaturPlain;}
  	$umschlag->AltBody  =  $plain;

  	return $umschlag->Send();
  }
}
?>
