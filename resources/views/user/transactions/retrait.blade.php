@extends(auth()->user()->role == 'Admin'
    ? 'layouts.admin'
    : 'layouts.guest')

@section('content')
<div class="container">

    <h3>Retrait</h3>

    <form method="POST" action="{{ route('transactions.storeRetrait') }}">
        @csrf

        <div class="mb-3">
            <label>Montant</label>
            <input type="number" step="0.01" name="montant"
                   class="form-control" required>
        </div>

        <button class="btn btn-danger">
            Valider le retrait
        </button>
    </form>

</div>
@endsection
