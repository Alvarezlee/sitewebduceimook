<x-mail::message>
# Nouveau message de contact

**Nom :** {{ $data['name'] }}
**Email :** {{ $data['email'] }}
**Téléphone :** {{ $data['phone'] ?? '—' }}
**Sujet :** {{ $data['subject'] }}

{{ $data['message'] }}

Merci,<br>
{{ config('app.name') }}
</x-mail::message>
