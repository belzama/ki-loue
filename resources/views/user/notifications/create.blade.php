@extends(auth()->user()->role == 'Admin'
    ? 'layouts.admin'
    : 'layouts.guest')

@section('content')

<div class="container">
    <h4 class="mb-4">Nouvelle notification</h4>

    <form action="{{ route('user.notifications.store') }}" method="POST">
        @csrf

        <div class="row g-3">

            {{-- Type --}}
            <div class="col-md-6">
                <label>Type</label>
                <select name="type" class="form-select" required>
                    <option value="">Sélectionner</option>
                    <option value="Authentification">Authentification</option>
                    <option value="Dépôt">Dépôt</option>
                    <option value="Retrait">Retrait</option>
                    <option value="Réservation">Réservation</option>
                </select>
            </div>

            {{-- Message --}}
            <div class="col-md-12">
                <label>Message</label>
                <textarea name="message" class="form-control" rows="3" required></textarea>
            </div>

            <hr>

            {{-- Email --}}
            <div class="col-md-6">
                <label>Email destinataire</label>
                <input type="email" name="email_address" class="form-control">
            </div>

            {{-- WhatsApp --}}
            <div class="col-md-6">
                <label>Numéro WhatsApp</label>
                <input type="text" name="whatsapp_number" class="form-control">
            </div>

            {{-- Options --}}
            <div class="col-md-6">
                <div class="form-check">
                    <input type="checkbox" name="send_email" value="1" class="form-check-input">
                    <label class="form-check-label">Envoyer par Email</label>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-check">
                    <input type="checkbox" name="send_whatsapp" value="1" class="form-check-input">
                    <label class="form-check-label">Envoyer par WhatsApp</label>
                </div>
            </div>

        </div>

        <div class="mt-4">
            <button class="btn btn-success">Enregistrer</button>
            <a href="{{ route('user.notifications.index') }}" class="btn btn-secondary">
                Annuler
            </a>
        </div>

    </form>
</div>

@endsection
