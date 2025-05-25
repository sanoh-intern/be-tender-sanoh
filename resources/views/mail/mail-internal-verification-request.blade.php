<x-mail::header :url="$url" />
<x-mail::message>
{{-- header --}}
<h2>Dear Respective In-Charge,</h2>

<p>A new verification request has been submitted by a user. Please review the details below and proceed with the necessary verification steps.</p>

{{-- Content --}}
<x-mail::panel>
    <p>Company Name : {{ $companyName }}</p>
</x-mail::panel>

<x-mail::button :url="$url">
Log In
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
