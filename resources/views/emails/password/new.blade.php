@component('mail::message')
**Dies ist eine automatisch generierte Nachricht! Bitte antworten Sie nicht auf diese E-Mail!**

Liebe Kundin/lieber Kunde!

Herzlich willkommen!

Sie sind soeben im Timelapse Systems Kundenportal angelegt worden.<br>
Mit Ihrem Login im Kundenportal behalten Sie Ihr Projekt im Auge!<br>
Bitte setzen Sie unter folgendem Link Ihr neues Passwort, mit welchem Sie ab sofort auf Ihre persönlichen Projektdaten (Fotos und Kurzzeit-Zeitraffer) zugreifen können.
@component('mail::button', ['url' => $link])
Passwort erstellen
@endcomponent
Diese Zugangsdaten sind vertraulich und nur für den Adressaten bestimmt. Sollten Sie diese Daten fälschliche Weise erhalten haben, bitten wir Sie mit uns Kontakt aufzunehmen.

Herzliche Grüße

Ihr Timelapse Systems Team

*Bitte beachten Sie, dass jeder Aktivierungslink nur 18 Stunden ab Aussendung gültig ist.*
@endcomponent

