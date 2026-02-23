@extends(auth()->user()->role == 'Admin'
    ? 'layouts.admin'
    : 'layouts.guest')

@section('content')

<div class="container">
    <h4>Accorder la réservation</h4>

    <form method="POST"
          action="{{ route('user.reservations.approve',$reservation->id) }}">
        @csrf

        <div class="mb-3">
            <label>Date accordée <span class="text-danger">*</span></label>
            <input type="date" name="date_accordee"
                   class="form-control" 
                   value="{{ old('date_accordee', $reservation->date_demandee) }}" required>
        </div>

        <div class="mb-3">
            <label>Durée accordée (jours) <span class="text-danger">*</span></label>
            <input type="number" name="duree_accordee"
                   class="form-control"
                   value="{{ old('duree_accordee', $reservation->duree_demandee) }}"
                   required>
        </div>

        <div class="mb-3">
            <label>Motif / remarque</label>
            <textarea name="motif_apporbation"
                      class="form-control"></textarea>
        </div>

        <button class="btn btn-success">Valider</button>
        <a href="{{ route('user.reservations.index') }}"
           class="btn btn-secondary">Retour</a>
    </form>
</div>

@endsection
