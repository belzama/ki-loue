@extends(auth()->user()->role == 'Admin'
    ? 'layouts.admin'
    : 'layouts.guest')

@section('content')

@if($errors->any())
<div class="alert alert-danger">
    <ul class="mb-0">
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

@php($devise = $user->pays->devise->symbol ?? '')

<div class="container">
    <form method="POST" action="{{ route('user.transactions.storeDeposit') }}">
        @csrf

        <div class="row">

            {{-- ================= LEFT SIDE ================= --}}
            <div class="col-md-5">

                <div class="card shadow-sm mb-3">
                    <div class="card-header fw-bold">
                        Dépôt
                    </div>

                    <div class="card-body">

                        {{-- Montant --}}
                        <div class="mb-4">
                            <label class="form-label fw-semibold">
                                Montant ({{ $devise }}) <span class="text-danger">*</span>
                            </label>

                            <div class="input-group">
                                <input type="number"
                                       name="montant"
                                       class="form-control"
                                        value="{{ old('montant', session('montant_a_recharger')) }}"
                                        min="0"
                                        step="0.01"
                                       required>
                                <span class="input-group-text">
                                    {{ $devise }}
                                </span>
                            </div>
                        </div>

                        {{-- Modes paiement --}}
                        <label class="form-label fw-semibold">
                            Mode de paiement <span class="text-danger">*</span>
                        </label>

                        @forelse($modes as $mode)
                            <div class="form-check mb-2">
                                <input class="form-check-input mode-radio"
                                       type="radio"
                                       name="mode_paiement_id"
                                       value="{{ $mode->id }}"
                                       data-type="{{ $mode->type }}">

                                <label class="form-check-label">
                                    {{ $mode->designation }}
                                    <small class="text-muted">
                                        ({{ $mode->type }})
                                    </small>
                                </label>
                            </div>
                        @empty
                            <div class="alert alert-warning">
                                Aucun mode de paiement disponible.
                            </div>
                        @endforelse

                    </div>
                </div>

            </div>

            {{-- ================= RIGHT SIDE ================= --}}
            <div class="col-md-7">

                <div class="card shadow-sm">
                    <div class="card-header fw-bold">
                        Informations de paiement
                    </div>

                    <div class="card-body">

                        {{-- MOBILE MONEY --}}
                        <div id="container-mobile-money"
                             class="payment-container d-none">

                            <h6 class="mb-3">Mobile Money</h6>

                            <div class="mb-3">
                                <label>N° Téléphone</label>
                                <input type="text"
                                       name="telephone"
                                       class="form-control">
                            </div>

                            <div class="mb-3">
                                <label>Code secret</label>
                                <input type="password"
                                       name="code_secret"
                                       class="form-control">
                            </div>
                        </div>

                        {{-- VISA --}}
                        <div id="container-visa"
                             class="payment-container d-none">

                            <h6 class="mb-3">Carte Visa</h6>

                            <div class="mb-3">
                                <label>N° Carte</label>
                                <input type="text"
                                       name="numero_carte"
                                       class="form-control">
                            </div>

                            <div class="row">
                                <div class="col">
                                    <label>Expire</label>
                                    <input type="text"
                                           name="expire"
                                           class="form-control">
                                </div>
                                <div class="col">
                                    <label>CVC</label>
                                    <input type="text"
                                           name="cvc"
                                           class="form-control">
                                </div>
                            </div>
                        </div>

                        {{-- AUTRES --}}
                        <div id="container-autres"
                             class="payment-container d-none">

                            <h6 class="mb-3">Référence</h6>

                            <div class="mb-3">
                                <label>Référence pièce</label>
                                <input type="text"
                                       name="reference_piece"
                                       class="form-control">
                            </div>
                        </div>

                    </div>
                </div>

            </div>

        </div>

        <button class="btn btn-success mt-3">
            Effectuer le dépôt
        </button>

    </form>
</div>

@endsection


@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    document.querySelectorAll('.mode-radio').forEach(radio => {

        radio.addEventListener('change', function () {

            document.querySelectorAll('.payment-container')
                .forEach(div => div.classList.add('d-none'));

            const type = this.dataset.type;

            if (type === 'Mobile Money') {
                document.getElementById('container-mobile-money')
                    .classList.remove('d-none');
            }
            else if (type === 'Visa Card') {
                document.getElementById('container-visa')
                    .classList.remove('d-none');
            }
            else {
                document.getElementById('container-autres')
                    .classList.remove('d-none');
            }
        });

    });

});
</script>
@endsection
