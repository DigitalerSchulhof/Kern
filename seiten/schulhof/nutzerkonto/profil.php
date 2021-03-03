<?php
return new Kern\Seite(
  "Profil",
  new UI\Zeile(
    new UI\Spalte(
      ["art" => "A1"],
      new UI\SeitenUeberschrift("Profil von " . ($DSH_BENUTZER->getName())),
      (new Kern\Profil($DSH_BENUTZER))->getProfil()
    )
  )
);
