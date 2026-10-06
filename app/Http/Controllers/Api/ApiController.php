<?php
// app/Http/Controllers/Api/ApiController.php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PublicationResource;
use App\Services\CatalogueService;
use App\Models\Publication;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function __construct(protected CatalogueService $catalogue) {}

    /**
     * GET /api/v1/publications
     */
    public function publications(Request $request)
    {
        $publications = Publication::with([
            'devise',
            'departement.region.pays',
            'dispositif.type_dispositif.categorie',
            'dispositif.photos',
            'dispositif.params.typeParam',
        ])->paginate(15);

        return PublicationResource::collection($publications);
    }

    /**
     * GET /api/v1/categories
     */
    public function categories()
    {
        $categories = $this->catalogue->categoriesAvecPublicationsActives()
            ->map(fn ($cat) => [
                'id'                   => $cat->id,
                'nom'                  => $cat->nom,
                'image_link'           => $cat->image_link,
                'publications_actives' => (int) $cat->publications_actives_count,
            ]);

        return response()->json(['data' => $categories]);
    }

    /**
     * POST /api/v1/publications/{publication}/reservations
     */
    public function storeReservation(Request $request, Publication $publication)
    {
        $data = $request->validate([
            'nom_prenom'     => 'nullable|string|max:255',
            'email'          => 'nullable|email|max:255',
            'telephone'      => 'nullable|string|max:50',
            'date_demandee'  => 'nullable|date',
            'duree_demandee' => 'nullable|integer',
            'message'        => 'nullable|string',
        ]);

        try {
            $reservation = $this->catalogue->creerReservation($publication, $data);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error'   => 'Une erreur est survenue.',
            ], 500);
        }

        $owner = $publication->dispositif->user;

        return response()->json([
            'success'          => true,
            'reservation_id'   => $reservation->id,
            'owner'            => [
                'telephone' => $owner->telephone,
                'whatsapp'  => $owner->whatsapp,
                'email'     => $owner->email,
            ],
            'whatsapp_message' => $this->catalogue->messageWhatsappReservation($publication, $reservation, $data['message'] ?? null),
        ], 201);
    }
}