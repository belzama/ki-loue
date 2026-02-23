<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Publication;
use App\Models\Dispositif;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();

        return view('admin.dashboard', [

            // 📊 Publications
            'totalPublications' => Publication::count(),

            'activePublications' => Publication::whereDate('date_debut','<=',$today)
                ->where(function ($q) use ($today) {
                    $q->whereNull('date_fin')
                      ->orWhereDate('date_fin','>=',$today);
                })->count(),

            'expiredPublications' => Publication::whereDate('date_fin','<',$today)->count(),

            'monthlyPublications' => Publication::whereMonth('created_at', now()->month)->count(),

            // 💰 Revenus (basé sur coût journalier)
            'totalRevenue' => Publication::sum(DB::raw('IFNULL(tarif_location,0)')),

            'monthlyRevenue' => Publication::whereMonth('created_at', now()->month)
                ->sum(DB::raw('IFNULL(tarif_location,0)')),

            'revenueByCurrency' => Publication::select('devise_id',
                    DB::raw('SUM(IFNULL(tarif_location,0)) as total'))
                ->groupBy('devise_id')
                ->with('devise')
                ->get(),

            'topDispositifs' => Dispositif::withCount('publications')
                ->orderByDesc('publications_count')
                ->limit(5)
                ->get(),
        ]);
    }
}
