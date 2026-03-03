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
                <th>Date</th>
                <th>Dispositif</th>
                <th>Statut</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($reservations as $reservation)
            <tr>
                <td> {{ $reservation->created_at->format('d/m/Y H:i') }} 
                <td>                    
                    {{ $reservation->publication->dispositif->type_dispositif->nom ?? '' }} 
                    {{ $reservation->publication->dispositif->designation ?? '' }} 
                    {{ $reservation->publication->dispositif->numero_immatriculation ?? '' }}
                </td>
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
