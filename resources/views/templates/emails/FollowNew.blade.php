@component('mail::message')

Hello, {{ ucwords($name) }}

You have a new follower!

@component('mail::button', ['url' => $url])
View now
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
