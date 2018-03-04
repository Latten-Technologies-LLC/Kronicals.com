@component('mail::message')

Hello, {{ ucwords($name) }}

Someone replied to your message!

@component('mail::button', ['url' => $url])
View Message
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
