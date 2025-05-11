
@component('mail::message')
# Your Login Details

Dear {{ $client->title }} {{ $client->first_name }} {{ $client->last_name }},

Your account has been created. You can login with the following details:

**Email:** {{ $client->email }}  
**Password:** {{ $password }}

Please change your password after your first login for security reasons.

@component('mail::button', ['url' => $url])
Login Now
@endcomponent

Thank you,<br>
{{ config('app.name') }}
@endcomponent