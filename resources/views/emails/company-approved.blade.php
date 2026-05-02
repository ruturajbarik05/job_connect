@component('mail::message')
# Congratulations! Your Company Has Been Approved

Your company **{{ $companyName }}** has been approved on our platform. You can now start posting jobs and finding the best talent.

@component('mail::button', ['url' => $url])
Go to Dashboard
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
