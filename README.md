[![Version](https://img.shields.io/badge/Symcon-PHPModul-red.svg)](https://www.symcon.de/service/dokumentation/entwicklerbereich/sdk-tools/sdk-php/)
![Version](https://img.shields.io/badge/Symcon%20Version-9.0%20%3E-blue.svg)
[![License](https://img.shields.io/badge/License-CC%20BY--NC--SA%204.0-green.svg)](https://creativecommons.org/licenses/by-nc-sa/4.0/)
[![Check Style](https://github.com/Schnittcher/SmartAndRelax/actions/workflows/style.yml/badge.svg)](https://github.com/Schnittcher/SmartAndRelax/actions/workflows/style.yml)
[![Tests](https://github.com/Schnittcher/SmartAndRelax/actions/workflows/tests.yml/badge.svg)](https://github.com/Schnittcher/SmartAndRelax/actions/workflows/tests.yml)

# SmartAndRelax
Bibliothek zur Steuerung von Lay-Z-Spa-Whirlpools über MQTT (Steuerung von MK-Innovations).

## Inhaltsverzeichnis
- [SmartAndRelax](#smartandrelax)
  - [Inhaltsverzeichnis](#inhaltsverzeichnis)
  - [1. Voraussetzungen](#1-voraussetzungen)
  - [2. Funktionsumfang](#2-funktionsumfang)
  - [3. Enthaltene Module](#3-enthaltene-module)
  - [4. Installation](#4-installation)
  - [5. Konfiguration in IP-Symcon](#5-konfiguration-in-ip-symcon)
  - [6. Spenden](#6-spenden)
  - [7. Lizenz](#7-lizenz)

## 1. Voraussetzungen

* mindestens IPS Version 9.0
* ein MQTT-Client oder MQTT-Server in IP-Symcon
* ein Lay-Z-Spa, das seine Daten per MQTT sendet und Kommandos empfängt

## 2. Funktionsumfang
* Anzeige von Sperre, Ein/Aus, Luft, Heizung, Heizstatus, Pumpe, Zieltemperatur, aktueller Temperatur und Fehlercode
* Schalten von Luft, Heizung und Pumpe sowie Setzen der Zieltemperatur

Die einzelnen Funktionen stehen bei den Instanzen unter "Enthaltene Module" und in der README des jeweiligen Moduls.

## 3. Enthaltene Module

* [SmartAndRelax](SmartAndRelax/README.md)
  * Instanz für einen Lay-Z-Spa, die den Status empfängt und Kommandos per MQTT sendet.
  * Wird über den MQTT-Client oder MQTT-Server als Parent versorgt.

## 4. Installation
Die Installation erfolgt über den IP-Symcon Module Store.

## 5. Konfiguration in IP-Symcon
Eine Instanz "SmartAndRelax" anlegen. Ist noch kein MQTT-Client oder -Server vorhanden, bietet IP-Symcon an, einen anzulegen. Anschließend in der Instanz das MQTT-Topic des Pools eintragen.

## 6. Spenden
Dieses Modul ist für die nicht kommerzielle Nutzung kostenlos, Schenkungen als Unterstützung für den Autor werden hier akzeptiert:

[![PayPal](https://img.shields.io/badge/PayPal-Spenden-00457C?logo=paypal&logoColor=white&style=for-the-badge)](https://www.paypal.com/donate?hosted_button_id=EK4JRP87XLSHW) [![Amazon Wunschzettel](https://img.shields.io/badge/Amazon-Wunschzettel-FF9900?logo=amazon&logoColor=white&style=for-the-badge)](https://www.amazon.de/hz/wishlist/ls/3JVWED9SZMDPK?ref_=wl_share)

## 7. Lizenz

[CC BY-NC-SA 4.0](https://creativecommons.org/licenses/by-nc-sa/4.0/)
