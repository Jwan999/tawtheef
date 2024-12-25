@component('mail::message')
    # Reset Password Request

    You are receiving this email because we received a password reset request for your account.

    @component('mail::button', ['url' => url("/reset-password/{$token}?email={$email}")])
        Reset Password
    @endcomponent

    If you did not request a password reset, no further action is required.

    Thanks,<br>
    {{ config('app.name') }}
@endcomponent
