@component('mail::message')
**Dies ist eine automatisch generierte Nachricht! Bitte antworten Sie nicht auf diese E-Mail!**

Liebe Kundin/lieber Kunde!

Sie haben das Zurücksetzen Ihres Passworts bei uns angefordert.<br>
Sie können Ihr Passwort daher über folgenden Link zurücksetzen:
@component('mail::button', ['url' => $link])
Passwort zurücksetzen
@endcomponent
Falls Sie das Zurücksetzen Ihres Passworts nicht angefordert haben sollten, ignorieren Sie diese E-Mail und
informieren Sie uns bitte über unseren Kundenservice.

Herzliche Grüße

Ihr Timelapse Systems Team

*Bitte beachten Sie, dass jeder Aktivierungslink nur 18 Stunden ab Aussendung gültig ist.*
@endcomponent

