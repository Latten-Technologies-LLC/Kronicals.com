@component('mail::message')

Hello, {{ ucwords($fullName) }}

Someone replied to your message!

@component('mail::button', ['url' => $url])
View Message
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
