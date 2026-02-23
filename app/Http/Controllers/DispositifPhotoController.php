<?php

namespace App\Http\Controllers;

use App\Models\DispositifPhoto;
use Illuminate\Support\Facades\Storage;

class DispositifPhotoController extends Controller
{
    public function destroy(DispositifPhoto $photo)
    {
        // Sécurité : vérifier que la photo appartient à l'utilisateur
        abort_if($photo->dispositif->user_id !== auth()->id(), 403);

        if ($photo->dispositif->photos()->count() <= 1) {
            return back()->with('error', 'Impossible de supprimer la dernière photo');
        }

        // Supprimer le fichier physique
        Storage::disk('public')->delete($photo->path);

        // Supprimer l'entrée en base
        $photo->delete();
        if ($photo->is_cover) {
            $next = $photo->dispositif->photos()
                ->where('id', '!=', $photo->id)
                ->first();

            if ($next) {
                $next->update(['is_cover' => true]);
            }
        }

        return back()->with('success', 'Photo supprimée');
    }
}
