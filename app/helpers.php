<?php

use App\Models\User;

if (!function_exists('redirectToAllowedDashboard')) {
    function redirectToAllowedDashboard(User $user = null) {
        $user = $user ?: auth()->user();

        if (!$user) {
            return redirect()->route('login');
        }

        switch ($user->role) {
            case 5:
                return redirect()->route('ceo.dashboard');
            case 1:
                return redirect()->route('employee.dashboard');
            case 2:
                return redirect()->route('callcenter.index');
            default:
                abort(403, 'Unauthorized');
        }
    }
}
