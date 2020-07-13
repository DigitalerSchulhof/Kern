### Neue Module anlegen
```
git submodule add git@github.com:DigitalerSchulhof/MODULNAME.git module/MODULNAME
git submodule update --init module/MODULNAME
```

### Konfiguration
```
modul:
  # Name des Moduls - Angezeigt in der Modulliste (string)
  name: Prototyp

  # Beschreibung des Moduls - Angezeigt in der Modulliste (string|array)
  beschreibung: Ein Prototyp für Module

  # Modul ist im Lehrernetz (bool)
  lehrernetz: false

  # Autor[en] des Moduls (string|array)
  autor: Digitaler Schulhof

  # Version des Moduls (string) ! Muss SemVer sein (https://semver.org/) !
  version: 1.0

  # Skript, das aufgerufen wird, wenn das Modul geladen wird (string)
  # Standard: funktionen/geladen.php
  geladen: funktionen/geladen.php

  # Anfrageziele für das Modul (string)
  # Standard: anfragen/ziele.php
  ziele: anfragen/ziele.php

  # Rechte für das Modul (string)
  # Standard: funktionen/rechte.yml
  rechte: funktionen/rechte.yml

  # Seiten für das Modul (string)
  # Standard: seiten/seiten.php
  seiten: seiten/seiten.php

  # Priorirät des Moduls in der Seitenliste (int) - Aus Performancegründen, da die Daten oben->unten geprüft werden
  # Standard: 0
  seitenPrio: 0

  # Speicherort für Dateien des Moduls (string)
  # Standard: dateien/%NAMESPACE%
  speicher: dateien/Proto

  # Einstellungen des Moduls (string)
  # Standard: funktionen/einstellungen.yml
  einstellungen: funktionen/einstellungen.yml

  # Für das Modul nötige Datenbanken (array)
  # Standard: [schulhof]
  datendanken:
    - schulhof
    - personen

  # Module, die vor dem Modul geladen werden müssen (array)
  # Standard: []
  benötigt:
    - DSH/Modul1
    - DSH/Modul2

  # Module, die vor dem Modul geladen werden sollten (array)
  # Standard: []
  erweitert:
    - DSH/Modul3
    - DSH/Modul4
```
