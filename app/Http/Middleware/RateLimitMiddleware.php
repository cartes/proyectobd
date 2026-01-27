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

        // Obtener límite configurado
        $rateLimit = $this->getRateLimit($limit);

        if (!$rateLimit) {
            return $next($request);
        }

        [$requests, $minutes] = explode(',', $rateLimit);

        // Verificar si excedió límite
        if ($this->limiter->tooManyAttempts($key, $requests)) {
            return $this->buildResponse(
                $request,
                $this->limiter->availableIn($key)
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
        if ($limit) {
            return $limit;
        }

        $route = request()->route();
        $routeName = $route?->getName();
        $path = request()->path();

        // 1. Intentar por nombre de ruta
        if ($routeName) {
            $configuredLimit = config("app.rate_limits.{$routeName}");
            if (is_string($configuredLimit)) {
                return $configuredLimit;
            }
        }

        // 2. Intentar por path (limpio)
        $configuredLimit = config("app.rate_limits.{$path}");
        if (is_string($configuredLimit)) {
            return $configuredLimit;
        }

        // Fallback para API si no hay específico
        if (request()->is('api/*')) {
            return config('app.rate_limits.api.default');
        }

        return null;
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
