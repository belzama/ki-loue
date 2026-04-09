<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

use App\Models\Publication;
use App\Models\Dispositif;
use App\Models\Transaction;

class UserDashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        $user = auth()->user();
        $userId = $user->id;

        return view('user.dashboard', [
            'user' => $user,

            // 📊 Dispositifs
            'totalMateriels' => Dispositif::where('user_id', $userId)->count(),
            'totalEnLigne' => Dispositif::where('user_id', $userId)
                ->whereHas('publications', function ($query) {
                    $query->where('active', 1)
                        ->where('date_debut', '<=', now())
                        ->where('date_fin', '>=', now());
                })
                ->count(),

            // 📊 Publications
            'totalPublications' => Publication::whereHas('dispositif', function ($q) use ($userId) {
                $q->where('user_id', $userId);
                })
                ->distinct('dispositif_id')
                ->count('dispositif_id'),

            'activePublications' => Publication::whereHas('dispositif', function ($q) use ($userId) {
                    $q->where('user_id', $userId);
                })
                ->whereDate('date_debut', '<=', $today)
                ->where(function ($q) use ($today) {
                    $q->whereNull('date_fin')
                    ->orWhereDate('date_fin', '>=', $today);
                })
                ->distinct('dispositif_id')
                ->count('dispositif_id'),

            'expiredPublications' => Publication::whereHas('dispositif', function ($q) use ($userId) {
                    $q->where('user_id', $userId);
                })
                ->whereDate('date_fin', '<', $today)
                ->distinct('dispositif_id')
                ->count('dispositif_id'),

            'monthlyPublications' => Publication::whereHas('dispositif', function ($q) use ($userId) {
                    $q->where('user_id', $userId);
                })
                ->whereMonth('created_at', now()->month)
                ->distinct('dispositif_id')
                ->count('dispositif_id'),

            // Récupération des totaux par catégorie pour le mois actuel
            'statsTransactMoisEncours' => Transaction::where('user_id', $userId)
                ->where('statut', 'effectuee')
                ->whereBetween('created_at', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()])
                ->groupBy('categorie')
                ->select('categorie', DB::raw('SUM(montant) as total'))
                ->pluck('total', 'categorie'), // Retourne un objet Collection

            // 🔝 Dispositifs de l’utilisateur
            'topDispositifs' => Dispositif::where('user_id', $userId)
                ->withCount('reservations')
                ->withSum(['publications as total_jours_publication' => function($query) {
                    // COALESCE retourne 0 si la somme est NULL
                    $query->select(DB::raw('COALESCE(SUM(DATEDIFF(LEAST(date_fin, CURRENT_DATE), date_debut)), 0)'))
                        ->where('date_debut', '<=', now());
                }], 'id')
                ->orderByDesc('reservations_count')
                ->limit(5)
                ->get(),
        ]);

    }
}
