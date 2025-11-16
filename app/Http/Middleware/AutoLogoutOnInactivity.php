<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AutoLogoutOnInactivity
{
    // menit
    protected int $timeoutMinutes = 60;

    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $now = now()->timestamp;
            $last = session('last_activity_time');

            if ($last && ($now - $last) > ($this->timeoutMinutes * 60)) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()
                    ->route('login')
                    ->with('error', 'Sesi berakhir karena tidak ada aktivitas selama 60 menit.');
            }

            session(['last_activity_time' => $now]);
        }

        return $next($request);
    }
}
