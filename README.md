# Timelapse Kundenportal

Das Timelapse Kundenportal soll die Hauptseite um diverse Verwaltungs- und Anzeigefunktionen erweitern. Manager können
sowohl Projekte, als auch Kunden und Firmen anlegen und zuweisen, Kunden können auf die zugewiesenen Projekte
zugreifen und diverse Daten zu den jeweiligen Projekten abrufen. Die Webanwendung wird nach dem Model-View-Controller
Prinzip mit PHP und Laravel als Framework realisiert.

## Benutzerrollen

Standardmäßig werden drei Rollen implementiert: Standardnutzer, Manager und Administrator. Benutzerrollen regeln den
Zugriff auf Funktionen und Ansichten der Webanwendung. Im Folgenden sollen die Benutzerrollen und deren Möglichkeiten
ausführlicher erläutert werden. Standardnutzer sieht im Projekt ohne Zusatzleistung nur aktuelles Bild und 30 Sekunden
Zeitraffer der letzten Bilder.

### Standardnutzer

Standardnutzer sind Projektkunden und besitzen dadurch die geringste Funktionalität. Es können lediglich die
persönlichen Daten geändert werden (E-Mail Adresse, Passwort; Name, Titel, Anrede und Firmenzugehörigkeit auf
Anfrage), als auch auf zugewiesene Projekte zugegriffen werden. Standardnutzer können Projektdaten lediglich abrufen,
aber nicht verändern. Bilder und Kurzvideos stehen zum Download zur Verfügung.

### Manager

Manager sind Verwalter der Projekte, Kunden und Firmen. Es können Projekte, Firmen und Standardnutzer angelegt und
editiert werden. Manager besitzen die Möglichkeit alle Daten in einer Übersicht zu betrachten und zu editieren, als auch
Projekte inaktiv zu schalten, sodass Standardnutzer keinen Zugriff mehr besitzen, die Projektdaten jedoch nicht aus
der Datenbank entfernt werden.

### Administratoren

Administratoren besitzen dieselben Berechtigungen wie Manager, können aber weiters Benutzerrollen verteilen und
tiefgreifende Änderungen an der Webanwendung vornehmen.

## Datenbank

Eine MySQL-Datenbank bildet das Gründgerüst des Modells und speichert jegliche persistente Daten der Webanwendung.
Aufgrund vieler Suchanfragen und vergleichsweise geringer Datenmenge bietet eine relationale Datenbank durch die
klare Struktur und schnelle Suchvorgänge ein ideales Grundgerüst. Weiters ist MySQL bedingt durch die höhere
Geschwindigkeit gegenüber PostgreSQL zu bevorzugen. Die wichtigsten Tabellen werden in den nächsten Unterkapiteln
genauer beleuchtet. Die Tabellen stellen die gespeicherten Daten dazu in übersichtlicher Form dar.

### Users-Tabelle

Die User-Tabelle speichert jegliche dem Benutzer zugehörige Daten. Dazu zählen Vor- als auch Familienname, Titel und
Anrede, das Passwort in verschlüsselter Form, als auch ein Eintrag für das Erstellungsdatum und das Änderungsdatum.
Weiters befindet sich darin ein Eintrag, ob der Benutzer inaktiv gesetzt wurde (dadurch kann der Benutzer auf den
zugehörigen Account nicht mehr zugreifen). Verwiesen wird in einer eigenen Spalte auf die Firma, als auch auf die
zugehörige Benutzerrechte.

|**Name**           |**Beschreibung** (* bedeutet verpflichtend)  |
|:------------------|---------------------------------------------|
|Anrede *           |Anrede der Person (Frau oder Herr)           |
|Titel *            |Titel (Dipl.-Ing., Mag., Dr., etc.)          |
|Vorname *          |Vorname der Person                           |
|Name *             |Familienname der Person                      |
|Passwort *         |mit Hashfunktion verschlüsselt               |
|Inaktiv *          |regelt Zugriff des Benutzers auf den Account |
|Firmen-ID          |Verweis auf die Firma                        |
|Berechtigungs-ID * |Verweis auf die Benutzerrechte               |
|Erstellt *         |Erstellungsdatum                             |
|Zuletzt geändert * |Datum der letzten Änderung                   |

### Roles-Tabelle

In der Roles-Tabelle werden alle Berechtigungsstufen mit einer kurzen Beschreibung abgespeichert. Diese Tabelle kann
nicht in der Webanwendung verändert werden, da sie für die Zugriffsrechte zuständig und somit höchst
Sicherheitsrelevant ist.

|**Name**           |**Beschreibung** (* bedeutet verpflichtend)    |
|:------------------|-----------------------------------------------|
|Bezeichnung *      |Ein-Wort-Zusammenfassung der Rolle             |
|Beschreibung       |Detailliertere Beschreibung der Berechtigungen |

### Companies-Tabelle

Hierbei wird lediglich der Name mit einem Verweis auf die zugehörige Addresse in der Addresses-Tabelle abgelegt.

|**Name**           |**Beschreibung** (* bedeutet verpflichtend)|
|:------------------|-------------------------------------------|
|Firmenname *       |Name der Firma                             |
|Adress-ID *        |Verweis auf die Adresse unter Addresses    |

### Addresses-Tabelle

Die Addresses Tabelle speichert jegliche Adressen, bestehend aus Straße, Hausnummer, PLZ, Ort, Bundesland und Land,
welche verpflichtend sind und den optionalen Einträgen Stiege und Tür. Die Tabelle ist relevant für das Abspeichern
des Firmenstandortes und der Kamerstandorte.

|**Name**           |**Beschreibung** (* bedeutet verpflichtend)            |
|:------------------|-------------------------------------------------------|
|Straße *           |Genauer Straßenname mit Umlauten                       |
|Hausnummer *       |Hausnummer (Buchstaben erlaubt z.B. 65a)               |
|Stiege             |Stiegenbezeichnung (nur Zahlen)                        |
|Tür                |Türnummer (nur Zahlen)                                 |
|PLZ *              |Postleitzahl (auch ausländische, nur Zahlen)           |
|Ort *              |Ortsname mit Umlauten                                  |
|Bundesland         |Bezeichnung des Bundeslandes zur genaueren Eingrenzung |
|Land *             |Name des Landes                                        |

### Projekts-Tabelle

Speichert alle einem Projektdaten. Diese bestehen aus dem Projektnamen, dem Aktivitätsstatus (ist das Projekt aktiv
und sichtbar für den Kunden, oder inaktiv) und einem Ablaufdatum, nach welchem das Projekt automatisch inaktiv gesetzt
wird. Die Cameras- und DeepLink-Tabelle wird an anderer Stelle verknüpft, da einem Projekt mehrere Kameras und DeepLinks
zugewiesen werden können.

|**Name**           |**Beschreibung** (* bedeutet verpflichtend)    |
|:------------------|-----------------------------------------------|
|Projektnummer *    |Kurze Bezeichnung des Projekts (z.B. P0147)    |
|Name *             |Name des Projekts                              |
|Inaktiv *          |Sichtbarkeit des Projekts für Kunden           |
|Ablaufdatum        |Zeitpunkt, ab welchem das Projekt inaktiv wird |

### Features-Tabelle

Die Features-Tabelle verlinkt zu Benutzern und Projekten und beinhaltet Informationen über gewählte Zusatzpakete im Zuge
eines Projekts.

|**Name**           |**Beschreibung** (* bedeutet verpflichtend)            |
|:------------------|-------------------------------------------------------|
|Benutzer-ID *      |Verweis auf einen Kunden                               |
|Projekt-ID *       |Verweis auf das zugehörige Projekt                     |
|Archiv *           |Archivfunktion mit Einsicht in alle gespeicherten Fotos|
|DeepLink *         |Einbetten von Bildern auf externen Webseiten           |
|Datenträger *      |Zusendung eines Datenträgers mit allen Daten           |

### Cameras-Tabelle

In der Cameras Tabelle werden jegliche Kamerabezogene Daten hinterlegt. Diese bestehen aus der VPN-IP-Adresse, der
Telefonnummer des Routers und den Standortkoordinaten.

|**Name**           |**Beschreibung** (* bedeutet verpflichtend)        |
|:------------------|---------------------------------------------------|
|VPN-IP-Adresse *   |Adresse des VPN-Tunnels direkt auf die Kamera      |
|Telefonnummer *    |Telefonnummer des installierten Routers            |
|Koordinaten *      |longitude und latitude zur Lokalisierung der Kamera|

### (DeepLinks-Tabelle)

**Diese Tabelle wird nur für spätere Zwecke definiert, jedoch für die ersten Schritte nicht benötigt**

Die DeepLink-Tabelle speichert Daten zu verschiedenen, bei Projekten hinterlegten DeepLinks, bestehend aus dem Token,
einem Ablaufdatum, und einem Zeitpunkt (bezogen auf das gewählte Bild), bestehend aus Jahr, Monat, Wochentag, Tag,
Stunde und Minute.

|**Name**           |**Beschreibung** (* bedeutet verpflichtend)  |
|:------------------|---------------------------------------------|
|Token *            |JSON-Web-Token für direkten externen Zugriff |
|Ablaufdatum        |Maximale Gültigkeitsdauer des Links          |
|Zeitpunkt          |Zeitpunkt des verlinkten Bildes              |

## Creator

Der Creator beinhaltet jegliche Funktionen zum Erstellen von Kunden und Projekten und kann lediglich von Benutzern mit
der Berechtigung 'Manager' oder 'Administrator' genutzt werden.

### Benutzer erstellen

Beim Erstellen eines Benutzers müssen jegliche unter Users-Tabelle als angegebene Daten angegeben werden. Bereits in
diesem Schritt kann der Benutzer einer bestehenden Firma hinzugefügt werden. Weiters ist es möglich, den Benutzer
inaktiv zu setzen, um diesem erst zu einem späteren Zeitpunkt vollen Zugriff auf den Account zu gewähren. Sollte eine
Firma hinzugefügt werden, so kann der neue Benutzer im nächsten Schritt direkt für Projekte der Firma freigeschaltet
werden. Nur Administratoren können Benutzerrechte festlegen. Standardmäßig werden neue Benutzer als Kunden angelegt.

### Firma anlegen

Eine Firma kann mit Firmenname und zugehöriger Adresse erstellt werden, wobei Firmen mit gleichem Namen und
verschiedener Anschrift erstellt werden können.

### Projekt anlegen

Projekte werden mit einem Projektnamen, einer Adresse für den Projektstandort und einem optionalen Projektablaufdatum
angelegt. Weiters kann bereits in diesem Schritt eine Kamera bzw. Firmen und deren zugehörige Kundenkonten dem Projekt
zugeordnet werden. Es ist möglich ein Projekt zuerst inaktiv zu setzen, um den Zugriff erst ab einem späteren Zeitpunkt
zu gewähren.

## Manager

Der Manager bietet jegliche Verwaltungsmöglichkeiten für Benutzer, Firmen und Projekte. Diese können sowohl bearbeitet,
als auch vollständig entfernt werden. Jegliche angelegte Informationen können explizit nach zweifacher Bestätigung
(Textfeld ausfüllen und bestätigen) vollständig entfernt werden.

### Benutzer verwalten

Es lassen sich alle Benutzerdaten, als auch die zugeteilten Projekte bearbeiten. Es können Zusatzfeatures als aktiviert
oder deaktiviert markiert werden. Weiters ist es möglich den Benutzer inaktiv zu setzen und somit den Zugriff auf den
Account zu verweigern. Es können nur inaktive Nutzer gelöscht werden.

### Firmen verwalten

Bei Firmen lassen sich sowohl Name und Adresse bearbeiten, als auch Firmendaten zusammenführen, wobei Kundendaten und
Projekte der zusammengeführten Firma übergeben werden.

### Projekte verwalten

Für Projekte lassen sich alle oben definierten Projektdaten anpassen und zusätzlich zugewiesene Kameras verändern.
Projekte können als inaktiv markiert werden, um jeglichen Kundenzugriff zu unterbinden. Um ein Projekt zu löschen,
muss es inaktiv sein.

## Projektübersicht

Die Projektübersicht zeigt angemeldeten Kunden alle zugeordneten Projekte an, sodass diese aufgerufen werden können.
Je nach angeforderten Features, sind unterschiedliche Ansichten verfügbar:

- Standard
  - Es werden nur maximal 30 Sekunden Zeitraffervideo angezeigt
  - Das letzte geschossene Bild kann angesehen werden

- Archiv
  - Link mit Zugriff auf das Archiv mit allen erstellten Bildern

- DeepLink
  - Link zu eigener Erstellungsseite
    - Auswahl des gewünschten Bildes anhand der Uhrzeit
    - Alternativ aktueller Zeitraffer
  - Angelegte DeepLinks werden angezeigt

## Einstellungen für Kunden

Kunden können Titel, Anrede, Vorname und Name anpassen, als auch das eigene Passwort verändern. Weitere Eingriffe sind
nicht erlaubt und erfordern die Kontaktaufnahme mit einem Administrator oder Manager.

--------------------------------------------------------------

*Author: Markus Fruhmann, markus@fruhmann.dev; Copyright 2020*
