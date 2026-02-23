@extends(auth()->user()->role == 'Admin'
    ? 'layouts.admin'
    : 'layouts.guest')

@section('content')

<div class="container">
    <h4 class="mb-3">Liste des réservations</h4>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered table-hover">
        <thead class="table-light">
            <tr>
                <th>Publication</th>
                <th>Nom</th>
                <th>Téléphone</th>
                <th>Date demandée</th>
                <th>Durée</th>
                <th>Statut</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($reservations as $reservation)
            <tr>
                <td>
                    {{ $reservation->publication->dispositif->designation ?? '-' }} 
                    <span class="badge bg-primary"> 
                        {{ $reservation->publication->dispositif->numero_immatriculation ?? '-' }}                    
                    </span>
                </td>
                <td>{{ $reservation->nom_prenom }}</td>
                <td>{{ $reservation->telephone }}</td>
                <td>{{ $reservation->date_demandee }}</td>
                <td>{{ $reservation->duree_demandee }} jours</td>
                <td>
                    
                    @if($reservation->statut == 'Demandée')
                        <a href="{{ route('user.reservations.approve.form',$reservation->id) }}"
                           class="btn btn-success btn-sm">
                            Accepter
                        </a>

                        <a href="{{ route('user.reservations.reject.form',$reservation->id) }}"
                           class="btn btn-danger btn-sm">
                            Rejeter
                        </a>
                    @else                 
                        <span class="badge
                            @if($reservation->statut=='Demandée') bg-warning
                            @elseif($reservation->statut=='Accordée') bg-success
                            @else bg-danger @endif">
                            {{ $reservation->statut }}
                        </span>
                    @endif
                </td>
                <td>
                    <a href="{{ route('user.reservations.show', $reservation) }}" class="btn btn-sm btn-outline-primary" title="Voir plus">
                        <i class="bi bi-eye"></i>
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{ $reservations->links() }}
</div>

@endsection
