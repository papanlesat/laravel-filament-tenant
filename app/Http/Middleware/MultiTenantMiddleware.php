<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Stancl\Tenancy\Middleware\IdentificationMiddleware;
use Illuminate\Support\Facades\Auth;

class MultiTenantMiddleware extends IdentificationMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $tenant = Auth::user()->tenant_id;

        return $this->initializeTenancy(
            $request, $next, $tenant
        );
    }
}
