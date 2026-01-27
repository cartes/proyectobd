<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Cache\RateLimiter;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class RateLimitMiddleware
{
    protected RateLimiter $limiter;

    public function __construct(RateLimiter $limiter)
    {
        $this->limiter = $limiter;
    }

    public function handle(Request $request, Closure $next, $limit = null): Response
    {
        // Obtener identificador único (IP o User ID)
        $key = $this->getKey($request);

        $violationKey = "{$key}:violations";
        $isBlocked = cache()->get("{$key}:blocked", false);

        if ($isBlocked) {
            return response()->json([
                'message' => 'Account temporarily blocked due to suspicious activity.',
                'retry_after' => cache()->get("{$key}:blocked_until", 0) - now()->timestamp,
            ], 403);
        }

        // Obtener límite configurado
        $rateLimit = $this->getRateLimit($limit);

        if (!$rateLimit) {
            return $next($request);
        }

        [$requests, $minutes] = explode(',', $rateLimit);

        // Verificar si excedió límite
        if ($this->limiter->tooManyAttempts($key, $requests)) {
            $expectedRetryAfter = $this->limiter->availableIn($key);

            $violations = cache()->get($violationKey, 0) + 1;
            cache()->put($violationKey, $violations, 60 * 60); // 1 hora

            if ($violations >= 3) {
                cache()->put("{$key}:blocked", true, 24 * 60 * 60);
                cache()->put("{$key}:blocked_until", now()->addDay()->timestamp, 24 * 60 * 60);

                Log::alert('User blocked for 24 hours due to rate limit violations', [
                    'key' => $key,
                    'violations' => $violations,
                ]);
            }

            return $this->buildResponse(
                $request,
                $expectedRetryAfter // Use normal retryAfter unless blocked? If blocked, next request handles.
            );
        }

        // Registrar intento
        $this->limiter->hit($key, $minutes * 60);

        $response = $next($request);

        // Agregar headers informativos
        return $response
            ->header('X-RateLimit-Limit', $requests)
            ->header('X-RateLimit-Remaining', $this->limiter->remaining($key, $requests))
            ->header('X-RateLimit-Reset', time() + ($minutes * 60));
    }

    protected function getKey(Request $request): string
    {
        // NUEVO: Checkear whitelist
        $whitelist = config('app.rate_limit_whitelist', []);
        if (in_array($request->ip(), $whitelist)) {
            return 'rate_limit:whitelist:' . $request->ip();
        }

        $path = $request->path();

        // Para auth, siempre usar IP (prevenir brute force antes de login)
        if ($path === 'login' || $path === 'register') {
            return 'rate_limit:ip:' . $request->ip() . ':' . $path;
        }

        // Usar User ID si está autenticado, sino IP
        return auth()->check()
            ? 'rate_limit:user:' . auth()->id() . ':' . $this->getRoute($request)
            : 'rate_limit:ip:' . $request->ip() . ':' . $this->getRoute($request);
    }

    protected function getRoute(Request $request): string
    {
        return $request->route()?->getName() ?? $request->path();
    }

    protected function getRateLimit(?string $limit): ?string
    {
        // NUEVO: Si es whitelist, sin límite
        if (in_array(request()->ip(), config('app.rate_limit_whitelist', []))) {
            return null;
        }

        if ($limit) {
            // Already set passed explicitly
        } else {
            $route = request()->route();
            $routeName = $route?->getName();
            $path = request()->path();

            // 1. Intentar por nombre de ruta
            if ($routeName) {
                $limit = config("app.rate_limits.{$routeName}");
            }

            // 2. Intentar por path si no hay por ruta
            if (!$limit || !is_string($limit)) {
                $limit = config("app.rate_limits.{$path}");
            }

            // 3. Fallback para API
            if ((!$limit || !is_string($limit)) && request()->is('api/*')) {
                $limit = config('app.rate_limits.api.default');
            }
        }

        // Si no hay límite configurado, retorna null (sin límite)
        if (!$limit || !is_string($limit)) {
            return null;
        }

        // Modificar límites según tipo de usuario
        $user = auth()->user();

        if ($user) {
            // Admin sin límite
            if ($user->is_admin) {
                return null;
            }

            // Premium users: 2x límite
            // Prioritize hasActiveSubscription method if available, else attribute?
            // User model logic confirms hasActiveSubscription() method exists.
            if ($user->hasActiveSubscription()) {
                [$requests, $minutes] = explode(',', $limit);
                return ($requests * 2) . ',' . $minutes;
            }
        }

        return $limit;
    }

    protected function buildResponse(Request $request, int $retryAfter)
    {
        Log::warning('Rate limit exceeded', [
            'user_id' => auth()->id(),
            'ip' => $request->ip(),
            'route' => $request->route()?->getName(),
            'path' => $request->path(),
            'retry_after' => $retryAfter,
            'timestamp' => now()->toIso8601String(),
        ]);

        if (
            str_contains($request->path(), 'payment') ||
            str_contains($request->path(), 'webhook') ||
            str_contains($request->path(), 'checkout')
        ) {
            Log::alert('Suspicious payment activity blocked', [
                'user_id' => auth()->id(),
                'ip' => $request->ip(),
                'endpoint' => $request->path(),
                'retry_after' => $retryAfter,
            ]);
        }

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Too many requests. Please try again later.',
                'retry_after' => $retryAfter,
            ], 429);
        }

        return response('Too Many Requests', 429)
            ->header('Retry-After', $retryAfter);
    }
}
