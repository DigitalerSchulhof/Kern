<?php
$SEITE = new Kern\Seite("Kern", "kern.module.einstellungen");

$spalte = new UI\Spalte("A1", new UI\SeitenUeberschrift("Kern"));

$einstellungen = Kern\Einstellungen::ladenAlle("Kern");

$reiter = new UI\Reiter("dshModulKernEinstellungen");

$formular    = new UI\FormularTabelle();
$schulname   = (new UI\Textfeld("dshModulKernSchulname"))                 ->setWert($einstellungen["Schulname"]);
$schulort    = (new UI\Textfeld("dshModulKernSchulort"))                  ->setWert($einstellungen["Schulort"]);
$schulstrhnr = (new UI\Textfeld("dshModulKernSchulstrhnr"))               ->setWert($einstellungen["Schulstraße und -hausnr"]);
$schulplzort = (new UI\Textfeld("dshModulKernSchulplzort"))               ->setWert($einstellungen["SchulPLZ und -ort"]);
$schultele   = (new UI\Textfeld("dshModulKernSchultelefon"))              ->setWert($einstellungen["Schultelefonnummer"]);
$schulfax    = (new UI\Textfeld("dshModulKernSchulfax"))                  ->setWert($einstellungen["Schulfaxnummer"]);
$schulmail   = (new UI\Mailfeld("dshModulKernSchulmail"))                 ->setWert($einstellungen["Schulmail"]);
$schuldomain = (new UI\Textfeld("dshModulKernSchuldomain"))               ->setWert($einstellungen["Schuldomain"]);
$formular[]  = new UI\FormularFeld(new UI\InhaltElement("Schulname:"),             $schulname);
$formular[]  = new UI\FormularFeld(new UI\InhaltElement("Schulort:"),              $schulort);
$formular[]  = new UI\FormularFeld(new UI\InhaltElement("Hausnummer und Straße:"), $schulstrhnr);
$formular[]  = new UI\FormularFeld(new UI\InhaltElement("Postleitzhal und Ort:"),  $schulplzort);
$formular[]  = (new UI\FormularFeld(new UI\InhaltElement("Telefonnummer:"),         $schultele))->setOptional(true);
$formular[]  = (new UI\FormularFeld(new UI\InhaltElement("Faxnummer:"),             $schulfax))->setOptional(true);
$formular[]  = (new UI\FormularFeld(new UI\InhaltElement("eMail-Adresse:"),         $schulmail))->setOptional(true);
$formular[]  = new UI\FormularFeld(new UI\InhaltElement("Schuldomain:"),           $schuldomain);
$formular[]  = (new UI\Knopf("Änderungen speichern", "Erfolg"))           ->setSubmit(true);
$formular    ->addSubmit("kern.modul.einstellungen.schuldaten()");
$reiterkopf = new UI\Reiterkopf("Schuldaten");
$reiterspalte = new UI\Spalte("A1", $formular);
$reiterkoerper = new UI\Reiterkoerper($reiterspalte->addKlasse("dshUiOhnePadding"));
$reiter->addReitersegment(new UI\Reitersegment($reiterkopf, $reiterkoerper));

$formular     = new UI\FormularTabelle();
$persleitname = (new UI\Textfeld("dshModulKernLeiterName"))               ->setWert($einstellungen["Schulleitung Name"]);
$persleitmail = (new UI\Textfeld("dshModulKernLeiterMail"))               ->setWert($einstellungen["Schulleitung Mail"]);
$dateleitname = (new UI\Textfeld("dshModulKernDatemschutzName"))          ->setWert($einstellungen["Datenschutz Name"]);
$dateleitmail = (new UI\Textfeld("dshModulKernDatemschutzMail"))          ->setWert($einstellungen["Datenschutz Mail"]);
$presleitname = (new UI\Textfeld("dshModulKernLeiterName"))               ->setWert($einstellungen["Presserecht Name"]);
$presleitmail = (new UI\Textfeld("dshModulKernLeiterMail"))               ->setWert($einstellungen["Presserecht Mail"]);
$webmleitname = (new UI\Textfeld("dshModulKernLeiterName"))               ->setWert($einstellungen["Webmaster Name"]);
$webmleitmail = (new UI\Textfeld("dshModulKernLeiterMail"))               ->setWert($einstellungen["Webmaster Mail"]);
$admileitname = (new UI\Textfeld("dshModulKernLeiterName"))               ->setWert($einstellungen["Administration Name"]);
$admileitmail = (new UI\Textfeld("dshModulKernLeiterMail"))               ->setWert($einstellungen["Administration Mail"]);
$formular[]  = new UI\FormularFeld(new UI\InhaltElement("Schulleitung Name:"),     $persleitname);
$formular[]  = new UI\FormularFeld(new UI\InhaltElement("Schulleitung eMail:"),    $persleitmail);
$formular[]  = new UI\FormularFeld(new UI\InhaltElement("Datenschutz Name:"),      $dateleitname);
$formular[]  = new UI\FormularFeld(new UI\InhaltElement("Datenschutz eMail:"),     $dateleitmail);
$formular[]  = new UI\FormularFeld(new UI\InhaltElement("Presserecht Name:"),      $presleitname);
$formular[]  = new UI\FormularFeld(new UI\InhaltElement("Presserecht eMail:"),     $presleitmail);
$formular[]  = (new UI\FormularFeld(new UI\InhaltElement("Webmaster Name:"),        $webmleitname))->setOptional(true);
$formular[]  = (new UI\FormularFeld(new UI\InhaltElement("Webmaster eMail:"),       $webmleitmail))->setOptional(true);
$formular[]  = (new UI\FormularFeld(new UI\InhaltElement("Administration Name:"),   $admileitname))->setOptional(true);
$formular[]  = (new UI\FormularFeld(new UI\InhaltElement("Administration eMail:"),  $admileitmail))->setOptional(true);
$formular[]  = (new UI\Knopf("Änderungen speichern", "Erfolg"))           ->setSubmit(true);
$formular    ->addSubmit("kern.modul.einstellungen.vertreter()");
$reiterkopf = new UI\Reiterkopf("Vertreter");
$reiterspalte = new UI\Spalte("A1", $formular);
$reiterkoerper = new UI\Reiterkoerper($reiterspalte->addKlasse("dshUiOhnePadding"));
$reiter->addReitersegment(new UI\Reitersegment($reiterkopf, $reiterkoerper));

$formular     = new UI\FormularTabelle();
$mailadresse  = (new UI\Textfeld("dshModulKernMailadresse"))               ->setWert($einstellungen["Mailadresse"]);
$mailtitel    = (new UI\Textfeld("dshModulKernMailtitel"))                 ->setWert($einstellungen["MailTitel"]);
$mailbenutzer = (new UI\Textfeld("dshModulKernMailbenutzer"))              ->setWert($einstellungen["Mailbenutzer"]);
$mailpass     = (new UI\Textfeld("dshModulKernMailpasswort"))              ->setWert($einstellungen["Mailpasswort"]);
$mailhost     = (new UI\Textfeld("dshModulKernMailhost"))                  ->setWert($einstellungen["MailSmtpServer"]);
$mailport     = (new UI\Textfeld("dshModulKernMailport"))                  ->setWert($einstellungen["MailSmtpPort"]);
$mailauth     = (new UI\IconToggle("dshModulKernMailauthentifizierung", "Der Mailversand erfordert eine Authentifizierung", new UI\Icon(UI\Konstanten::HAKEN)))->setWert($einstellungen["MailSmtpAuthentifizierung"]);
$mailsignP    = (new UI\Textarea("dshModulKernMailsignaturPlain"))         ->setWert($einstellungen["MailSignaturPlain"]);
$mailsignH    = (new UI\Textarea("dshModulKernMailsignaturHTML"))          ->setWert($einstellungen["MailSignaturHTML"]);
$formular[]   = new UI\FormularFeld(new UI\InhaltElement("eMail-Adresse:"),           $mailadresse);
$formular[]   = new UI\FormularFeld(new UI\InhaltElement("Titel der Adresse:"),       $mailtitel);
$formular[]   = new UI\FormularFeld(new UI\InhaltElement("Benutzername:"),            $mailbenutzer);
$formular[]   = new UI\FormularFeld(new UI\InhaltElement("Passwort:"),                $mailpass);
$formular[]   = new UI\FormularFeld(new UI\InhaltElement("SMTP-Host (Postausgang):"), $mailhost);
$formular[]   = new UI\FormularFeld(new UI\InhaltElement("SMTP-Port (Postausgang):"), $mailport);
$formular[]   = new UI\FormularFeld(new UI\InhaltElement("SMTP-Authentifizierung:"),  $mailauth);
$formular[]   = (new UI\FormularFeld(new UI\InhaltElement("Plain-Text Signatur:"),     $mailsignP))->setOptional(true);
$formular[]   = (new UI\FormularFeld(new UI\InhaltElement("HTML Signatur:"),           $mailsignH))->setOptional(true);
$formular[]   = (new UI\Knopf("Änderungen speichern", "Erfolg"))                      ->setSubmit(true);
$formular[]   = (new UI\Knopf("Änderungen testen", "Information"))                    ->addFunktion("onclick", "kern.modul.einstellungen.mail.testen()");
$formular     ->addSubmit("kern.modul.einstellungen.mail.aendern()");
$reiterkopf = new UI\Reiterkopf("Mailversand");
$reiterspalte = new UI\Spalte("A1", $formular);
$reiterkoerper = new UI\Reiterkoerper($reiterspalte->addKlasse("dshUiOhnePadding"));
$reiter->addReitersegment(new UI\Reitersegment($reiterkopf, $reiterkoerper));

$formular     = new UI\FormularTabelle();
$ldapaktiv    = (new UI\IconToggle("dshModulKernLdapAktiv", "Für die Nutzerauthentifizierung einen LDAP-Server verwenden", new UI\Icon(UI\Konstanten::HAKEN)))->setWert($einstellungen["LDAP"]);
$ldapbenutzer = (new UI\Textfeld("dshModulKernLdapBenutzer"))                ->setWert($einstellungen["LDAP-User"]);
$ldappass     = (new UI\Textfeld("dshModulKernLdapPasswort"))                ->setWert($einstellungen["LDAP-Passwort"]);
$ldaphost     = (new UI\Textfeld("dshModulKernLdapHost"))                    ->setWert($einstellungen["LDAP-Host"]);
$ldapport     = (new UI\Textfeld("dshModulKernLdapPort"))                    ->setWert($einstellungen["LDAP-Port"]);
$formular[]   = new UI\FormularFeld(new UI\InhaltElement("LDAP aktiv:"),     $ldapaktiv);
$formular[]   = new UI\FormularFeld(new UI\InhaltElement("LDAP-Admin:"),     $ldapbenutzer);
$formular[]   = new UI\FormularFeld(new UI\InhaltElement("LDAP-Passwort:"),  $ldappass);
$formular[]   = new UI\FormularFeld(new UI\InhaltElement("LDAP-Host:"),      $ldaphost);
$formular[]   = new UI\FormularFeld(new UI\InhaltElement("LDAP-Port:"),      $ldapport);
$formular[]   = (new UI\Knopf("Änderungen speichern", "Erfolg"))             ->setSubmit(true);
$formular[]   = (new UI\Knopf("Änderungen testen", "Information"))           ->addFunktion("onclick", "kern.modul.einstellungen.ldap.testen()");
$formular     ->addSubmit("kern.modul.einstellungen.ldap.aendern()");
$reiterkopf = new UI\Reiterkopf("LDAP");
$reiterspalte = new UI\Spalte("A1", $formular);
$reiterkoerper = new UI\Reiterkoerper($reiterspalte->addKlasse("dshUiOhnePadding"));
$reiter->addReitersegment(new UI\Reitersegment($reiterkopf, $reiterkoerper));

$formular     = new UI\FormularTabelle();
$logaktiv     = (new UI\IconToggle("dshModulKernLogAktiv", "Alle Änderungen an der Datenbank und dem Dateisystem aufzeichnen", new UI\Icon(UI\Konstanten::HAKEN)))->setWert($einstellungen["Aktionslog"]);
$formular[]   = new UI\FormularFeld(new UI\InhaltElement("Aktionslog aktiv:"),     $logaktiv);
$formular[]   = (new UI\Knopf("Änderungen speichern", "Erfolg"))             ->setSubmit(true);
$formular     ->addSubmit("kern.modul.einstellungen.log()");
$reiterkopf = new UI\Reiterkopf("Aktionslog");
$reiterspalte = new UI\Spalte("A1", $formular);
$reiterkoerper = new UI\Reiterkoerper($reiterspalte->addKlasse("dshUiOhnePadding"));
$reiter->addReitersegment(new UI\Reitersegment($reiterkopf, $reiterkoerper));

$spalte[] = $reiter;

$SEITE[] = new UI\Zeile($spalte);
?>
