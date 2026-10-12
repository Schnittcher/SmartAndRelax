# SmartAndRelax
Instanz für einen Lay-Z-Spa-Whirlpool. Sie empfängt den Status des Pools per MQTT, legt die Variablen automatisch an und sendet Kommandos für Luft, Heizung, Pumpe und Zieltemperatur. Sie wird manuell angelegt und bekommt einen MQTT-Client oder MQTT-Server als Parent.

## Inhaltsverzeichnis
- [SmartAndRelax](#smartandrelax)
  - [Inhaltsverzeichnis](#inhaltsverzeichnis)
  - [1. Konfiguration](#1-konfiguration)
  - [2. Variablen](#2-variablen)
  - [3. Spenden](#3-spenden)
  - [4. Lizenz](#4-lizenz)

## 1. Konfiguration

Feld | Beschreibung
------------ | ----------------
MQTT Topic | Basis-Topic des Pools, Standard `layzspa`. Die Instanz empfängt auf `<Topic>/message` und sendet Kommandos an `<Topic>/command`. Bleibt das Feld leer, steht die Instanz im Status "MQTT-Topic fehlt".

## 2. Variablen

Variable | Ident | Typ | Beschreibung
------------ | ------------ | ------------ | ----------------
Sperre | `Lock` | Ja/Nein | Tastensperre des Pools (gesperrt / entsperrt)
Ein/Aus | `Power` | Ja/Nein | Pool ein oder aus
Luft | `Air` | Ja/Nein | Luftsprudler, steuerbar
Heizung | `Heat` | Ja/Nein | Heizung ein/aus, steuerbar. Zeigt "an", sobald die Heizung heizt oder die Zieltemperatur erreicht ist
Heizstatus | `HeatState` | Ganzzahl | Nur Anzeige: Aus (0), Zieltemperatur erreicht (1), Heizt (2) oder Wartet auf Pumpe (3, Heizung angefordert, aber die Filterpumpe läuft nicht)
Pumpe | `Pump` | Ja/Nein | Filterpumpe, steuerbar
Zieltemperatur | `TargetTemperature` | Ganzzahl (°C) | Gewünschte Wassertemperatur in ganzen Grad, steuerbar
Temperatur | `Temperature` | Ganzzahl (°C) | Aktuelle Wassertemperatur in ganzen Grad, nur Anzeige
Fehlercode | `ErrorCode` | Ganzzahl | Fehlerstatus der Pumpe, nur Anzeige. 0 bedeutet kein Fehler, ein Wert größer 0 weist auf einen Fehlerzustand hin

## 3. Spenden
Dieses Modul ist für die nicht kommerzielle Nutzung kostenlos, Schenkungen als Unterstützung für den Autor werden hier akzeptiert:

[![PayPal](https://img.shields.io/badge/PayPal-Spenden-00457C?logo=paypal&logoColor=white&style=for-the-badge)](https://www.paypal.com/donate?hosted_button_id=EK4JRP87XLSHW) [![Amazon Wunschzettel](https://img.shields.io/badge/Amazon-Wunschzettel-FF9900?logo=amazon&logoColor=white&style=for-the-badge)](https://www.amazon.de/hz/wishlist/ls/3JVWED9SZMDPK?ref_=wl_share)

## 4. Lizenz

[CC BY-NC-SA 4.0](https://creativecommons.org/licenses/by-nc-sa/4.0/)
