<x-mail::header :url="$url" />
<x-mail::message>
{{-- header --}}
<h2>Dear Respective In-Charge,</h2>

<p>Welcome! Your account has been successfully created. Here are your password:</p>

{{-- Content --}}
<x-mail::panel>
    <p>Password : {{ $password }}</p>
</x-mail::panel>

<x-mail::button :url="$url">
Log In
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
