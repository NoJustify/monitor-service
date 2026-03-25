<?php

namespace App\Http\Controllers;

use App\Models\Domain;
use App\Models\DomainCheck;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function __invoke(): Response
    {
        $user = Auth::user();

        $totalDomains = $user->domains()->count();
        $activeDomainsCount = $user->domains()->where('is_active', true)->count();
        $onlineCount = $user->domains()->where('last_status', true)->count();
        $offlineCount = $user->domains()->where('last_status', false)->count();

        $recentChecks = DomainCheck::whereIn('domain_id', $user->domains()->pluck('id'))
            ->with('domain:id,url,name')
            ->latest('created_at')
            ->limit(10)
            ->get();

        $offlineDomains = $user->domains()
            ->where('last_status', false)
            ->with('lastCheck')
            ->get();

        return Inertia::render('Dashboard', [
            'stats' => [
                'total' => $totalDomains,
                'active' => $activeDomainsCount,
                'online' => $onlineCount,
                'offline' => $offlineCount,
            ],
            'recentChecks' => $recentChecks,
            'offlineDomains' => $offlineDomains,
        ]);
    }
}
