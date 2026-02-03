<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;

class HandleAppearance
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $appearance = $request->cookie('appearance') ?? 'system';
        $primaryColor = $request->cookie('primary_color') ?? 'indigo';

        if (! in_array($appearance, ['light', 'dark', 'system'], true)) {
            $appearance = 'system';
        }

        if (! in_array($primaryColor, ['indigo', 'emerald', 'rose', 'amber', 'sky', 'slate'], true)) {
            $primaryColor = 'indigo';
        }

        View::share('appearance', $appearance);
        View::share('primaryColor', $primaryColor);

        return $next($request);
    }
}
