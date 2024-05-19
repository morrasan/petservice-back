<?php

namespace App\Http\Middleware;

use App\Models\Pet;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPetExists
{
    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $petId = $request->route('petId');

        if (!Pet::find($petId)) {

            return response()->json(['error' => 'Pet not found'], 404);
        }

        return $next($request);
    }
}
