<?php

namespace App\Http\Middleware;

use App\Services\Auth\TwoFactorAuthenticationService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RequireTwoFactorForPrivilegedRoles
{
    private const PRIVILEGED_ROLES = ['super_admin', 'admin', 'moderateur'];

    public function __construct(private readonly TwoFactorAuthenticationService $twoFactor) {}

    /**
     * Impose l'activation de la double authentification pour les rôles
     * d'administration avant d'accéder au back-office.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && $user->hasAnyRole(self::PRIVILEGED_ROLES) && ! $this->twoFactor->isEnabled($user)) {
            return redirect()->route('profile')
                ->with('status', '2fa-required');
        }

        return $next($request);
    }
}
