<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class ValidateReCaptcha
{
    public function handle(Request $request, Closure $next)
    {
        Log::info('ReCAPTCHA validation started', [
            'environment' => app()->environment(),
            'skip_local' => config('services.recaptcha.skip_on_local'),
            'secret_key_exists' => !empty(config('services.recaptcha.secret_key')),
            'token_exists' => !empty($request->input('recaptcha_token'))
        ]);

        if (app()->environment('local') && config('services.recaptcha.skip_on_local', false)) {
            Log::info('ReCAPTCHA skipped due to local environment');
            return $next($request);
        }

        $token = $request->input('recaptcha_token');

        if (!$token) {
            Log::warning('No reCAPTCHA token provided');
            return response()->json(['message' => 'Please complete the reCAPTCHA verification'], 422);
        }

        try {
            $client = new Client();
            $response = $client->post('https://www.google.com/recaptcha/api/siteverify', [
                'form_params' => [
                    'secret' => config('services.recaptcha.secret_key'),
                    'response' => $token,
                    'remoteip' => $request->ip()
                ]
            ]);

            $body = json_decode((string)$response->getBody());
            Log::info('ReCAPTCHA API response', ['response' => $body]);

            if (!$body->success) {
                Log::warning('ReCAPTCHA verification failed', [
                    'ip' => $request->ip(),
                    'errors' => $body->{'error-codes'} ?? []
                ]);
                return response()->json(['message' => 'Security verification failed'], 422);
            }

            return $next($request);
        } catch (\Exception $e) {
            Log::error('ReCAPTCHA verification error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['message' => 'Security verification failed'], 500);
        }
    }}
