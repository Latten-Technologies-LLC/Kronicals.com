@component('mail::message')

Hello, {{ ucwords($name) }}

Someone replied to your post!

@component('mail::button', ['url' => $url])
View Timeline
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
