@component('mail::message')

Hello, {{ ucwords($name) }}

Someone confessed to one of your messages

@component('mail::button', ['url' => $url])
View it now!
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
