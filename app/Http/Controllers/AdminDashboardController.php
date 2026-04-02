<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

use App\Models\User;
use App\Models\Publication;
use App\Models\Dispositif;
use App\Models\Transaction;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        $userId = auth()->id();

        return view('admin.dashboard', [
            
            // 📊 Dispositifs
            'totalUsers' => User::where('role', 'User')->count(),

            // 📊 Dispositifs
            'totalMateriels' => Dispositif::count(),
            
            // 📊 Publications
            'totalPublications' => Publication::distinct('dispositif_id')
                ->count('dispositif_id'),

            'activePublications' => Publication::whereDate('date_debut','<=',$today)
                ->where(function ($q) use ($today) {
                    $q->whereNull('date_fin')
                      ->orWhereDate('date_fin','>=',$today);
                })
                ->distinct('dispositif_id')
                ->count('dispositif_id'),

            'expiredPublications' => Publication::whereDate('date_fin','<',$today)
                ->distinct('dispositif_id')
                ->count('dispositif_id'),

            'monthlyPublications' => Publication::whereMonth('created_at', now()->month)
                ->distinct('dispositif_id')
                ->count('dispositif_id'),

            // 💰 Revenus (basé sur coût journalier)
            'totalRevenue' => Publication::sum(DB::raw('IFNULL(cout_publication,0)')),

            'monthlyRevenue' => Publication::whereMonth('created_at', now()->month)
                ->sum(DB::raw('IFNULL(cout_publication,0)')),

            'revenueByCurrency' => Publication::select('devise_id',
                    DB::raw('SUM(IFNULL(cout_publication,0)) as total'))
                ->groupBy('devise_id')
                ->with('devise')
                ->get(),
            
                // Récupération des totaux par catégorie pour le mois actuel
            'statsTransactMoisEncours' => Transaction::where('statut', 'effectuee')
                ->whereBetween('created_at', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()])
                ->groupBy('categorie')
                ->select('categorie', DB::raw('SUM(montant) as total'))
                ->pluck('total', 'categorie'), // Retourne un objet Collection

            'topDispositifs' => Dispositif::withCount('publications')
                ->withCount('reservations') // Utilise la relation hasManyThrough
                ->orderByDesc('publications_count')
                ->limit(5)
                ->get(),

            'topUsers' => User::where('role', 'User')
                ->withSum(['publications as total_genere' => function($query) {
                    // On passe par la relation publications (via dispositifs)
                    // Note : Laravel gérera la jointure si vos relations sont bien définies
                }], 'cout_publication')
                ->orderByDesc('total_genere')
                ->limit(5)
                ->get(),

            'flopUsers' => User::where('role', 'User')
                ->withSum('publications as total_genere', 'cout_publication')
                ->orderBy('total_genere') // Ordre croissant pour les moins rentables
                ->limit(5)
                ->get(),
        ]);
    }
}
