# Changelog

## 12.10.2026 - Version 2.0

### Neu
- Ohne eingetragenes MQTT-Topic zeigt die Instanz den Status "MQTT-Topic fehlt".
- Variablen und Konfiguration sind ins Deutsche übersetzt.
- Neue Variable "Heizstatus" mit Aus, Zieltemperatur erreicht, Heizt und Wartet auf Pumpe. Sie ersetzt "Heizung grün" und "Heizung rot".
- Neue Variable "Fehlercode" zeigt den Fehlerstatus der Pumpe (0 = kein Fehler).
- Spendenblock im Konfigurationsformular.

### Fixes
- Topics mit "/" werden beim Empfang zuverlässig erkannt.
- Unvollständige oder ungültige Pool-Meldungen führen nicht mehr zu Fehlern.
- Die Variable "Temperatur" hat eine eigene Position und liegt nicht mehr auf der Position der Zieltemperatur.
- Zieltemperatur und aktuelle Temperatur sind jetzt Ganzzahlen und werden in ganzen Grad angezeigt. Die Zieltemperatur wird auch in ganzen Grad gesendet.
- Achtung: "Heizung grün" und "Heizung rot" werden nicht mehr aktualisiert. Bestehende Variablen bleiben stehen, Archivdaten und Verknüpfungen darauf sollten auf "Heizstatus" umgestellt werden. Die alten Variablen können gelöscht werden.
- Achtung: Die Variablen "Zieltemperatur" und "Temperatur" werden beim Update neu angelegt. Archivdaten und Verknüpfungen auf die alten Variablen gehen verloren und müssen neu gesetzt werden.
- Achtung: Die Mindestversion ist jetzt IP-Symcon 9.0.
