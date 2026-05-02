@component('mail::message')
# New Application Received

**{{ $applicantName }}** has applied for the position of **{{ $jobTitle }}**.

@component('mail::button', ['url' => $url])
View Application
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
