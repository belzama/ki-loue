<?php

namespace App\Http\Controllers;

use App\Models\Publication;
use App\Models\Dispositif;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class UserDashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        $userId = auth()->id();
        
        return view('user.dashboard', [

            // 📊 Publications
            'totalPublications' => Publication::whereHas('dispositif', function ($q) use ($userId) {
                $q->where('user_id', $userId);
            })->count(),

            'activePublications' => Publication::whereHas('dispositif', function ($q) use ($userId) {
                    $q->where('user_id', $userId);
                })
                ->whereDate('date_debut', '<=', $today)
                ->where(function ($q) use ($today) {
                    $q->whereNull('date_fin')
                    ->orWhereDate('date_fin', '>=', $today);
                })
                ->count(),

            'expiredPublications' => Publication::whereHas('dispositif', function ($q) use ($userId) {
                    $q->where('user_id', $userId);
                })
                ->whereDate('date_fin', '<', $today)
                ->count(),

            'monthlyPublications' => Publication::whereHas('dispositif', function ($q) use ($userId) {
                    $q->where('user_id', $userId);
                })
                ->whereMonth('created_at', now()->month)
                ->count(),

            // 💰 Revenus
            'totalRevenue' => Publication::whereHas('dispositif', function ($q) use ($userId) {
                    $q->where('user_id', $userId);
                })
                ->sum(DB::raw('IFNULL(tarif_location,0)')),

            'monthlyRevenue' => Publication::whereHas('dispositif', function ($q) use ($userId) {
                    $q->where('user_id', $userId);
                })
                ->whereMonth('created_at', now()->month)
                ->sum(DB::raw('IFNULL(tarif_location,0)')),

            'revenueByCurrency' => Publication::whereHas('dispositif', function ($q) use ($userId) {
                    $q->where('user_id', $userId);
                })
                ->select('devise_id', DB::raw('SUM(IFNULL(tarif_location,0)) as total'))
                ->groupBy('devise_id')
                ->with('devise')
                ->get(),

            // 🔝 Dispositifs de l’utilisateur
            'topDispositifs' => Dispositif::where('user_id', $userId)
                ->withCount('publications')
                ->orderByDesc('publications_count')
                ->limit(5)
                ->get(),
        ]);

    }
}
