@component('mail::message')

Hello, {{ ucwords($fullName) }}

You have a new message!

@component('mail::button', ['url' => $url])
View messages
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
