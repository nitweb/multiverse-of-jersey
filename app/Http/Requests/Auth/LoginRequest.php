<?php

namespace App\Http\Requests\Auth;

use App\Models\User;
use Closure;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /** The login field holds either a mobile number (01XXXXXXXXX) or an email address. */
    protected function prepareForValidation(): void
    {
        $login = trim((string) $this->input('login', $this->input('phone')));

        $this->merge(['login' => $this->isEmailLogin($login) ? strtolower($login) : User::normalizePhone($login)]);
    }

    public function isEmailLogin(?string $login = null): bool
    {
        return str_contains($login ?? (string) $this->input('login'), '@');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'login' => [
                'required',
                'string',
                'max:150',
                function (string $attribute, mixed $value, Closure $fail): void {
                    $valid = $this->isEmailLogin((string) $value)
                        ? filter_var($value, FILTER_VALIDATE_EMAIL) !== false
                        : preg_match('/^01[3-9][0-9]{8}$/', (string) $value) === 1;

                    if (! $valid) {
                        $fail('Enter a valid mobile number (01XXXXXXXXX) or email address.');
                    }
                },
            ],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'login.required' => 'Enter your mobile number or email address.',
        ];
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'login' => 'mobile number or email',
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        $field = $this->isEmailLogin() ? 'email' : 'phone';

        if (! Auth::attempt([$field => $this->string('login')->toString(), 'password' => $this->input('password')], $this->boolean('remember'))) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'login' => trans('auth.failed'),
            ]);
        }

        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'login' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->string('login')).'|'.$this->ip());
    }
}
