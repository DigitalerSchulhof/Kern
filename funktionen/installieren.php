<?php

$DBS->anfrage("CREATE FUNCTION `kern_anzeigename`(`person` BIGINT UNSIGNED, `schluessel` VARCHAR(500)) RETURNS VARCHAR(5000) NOT DETERMINISTIC CONTAINS SQL SQL SECURITY DEFINER BEGIN DECLARE titel VARCHAR(500); DECLARE vor VARCHAR(500); DECLARE nach VARCHAR(500); SET titel = (SELECT AES_DECRYPT(titel, schluessel) FROM kern_personen WHERE id = person); SET vor = (SELECT AES_DECRYPT(vorname, schluessel) FROM kern_personen WHERE id = person); SET nach = (SELECT AES_DECRYPT(nachname, schluessel) FROM kern_personen WHERE id = person); RETURN titel; END");

?>