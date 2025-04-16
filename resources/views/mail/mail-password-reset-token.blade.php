<x-mail::header :url="$url" />
<x-mail::message>
{{-- header --}}
<h2>Dear Respective In-Charge,</h2>

<p>You requested to reset your password. Use the token below to proceed:</p>

{{-- Content --}}
<x-mail::panel>
    <p>Token : {{ $token }}</p>
</x-mail::panel>

<x-mail::button :url="$url">
Log In
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
