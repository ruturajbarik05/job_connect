@component('mail::message')
# Application Status Updated

Your application for **{{ $jobTitle }}** has been updated to: **{{ ucfirst($status) }}**.

@component('mail::button', ['url' => $url])
View Application
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
