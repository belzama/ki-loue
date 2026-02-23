@extends(auth()->user()->role == 'Admin'
    ? 'layouts.admin'
    : 'layouts.guest')

@section('content')

<div class="container">
    <h4>Refuser la réservation</h4>

    <form method="POST"
          action="{{ route('user.reservations.reject',$reservation->id) }}">
        @csrf

        <div class="mb-3">
            <label>Motif de rejet</label>
            <textarea name="motif_apporbation"
                      class="form-control" required></textarea>
        </div>

        <button class="btn btn-success">Valider</button>
        <a href="{{ route('user.reservations.index') }}"
           class="btn btn-secondary">Retour</a>
    </form>
</div>

@endsection
