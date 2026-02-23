@extends(auth()->user()->role == 'Admin'
    ? 'layouts.admin'
    : 'layouts.guest')

@section('content')

<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>Mes notifications</h4>

        <div>
            <a href="{{ route('user.notifications.create') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-circle me-1"></i> Nouvelle notification
            </a>

            <form action="{{ route('user.notifications.markAllAsRead') }}" 
                  method="POST" 
                  class="d-inline">
                @csrf
                <button type="submit" class="btn btn-secondary btn-sm">
                    Tout marquer comme lu
                </button>
            </form>
        </div>
    </div>

    @forelse($notifications as $notification)

        <div class="card mb-2 {{ $notification->read ? '' : 'border-primary' }}">
            <div class="card-body">

                <div class="d-flex justify-content-between">
                    <div>
                        <strong>{{ $notification->type }}</strong>
                        <p class="mb-1">{{ $notification->message }}</p>

                        <small class="text-muted">
                            {{ $notification->created_at->format('d/m/Y H:i') }}
                        </small>
                    </div>

                    <div class="text-end">

                        @if(!$notification->read)
                            <form action="{{ route('user.notifications.markAsRead', $notification) }}"
                                  method="POST"
                                  class="mb-1">
                                @csrf
                                @method('PATCH')
                                <button class="btn btn-outline-success btn-sm">
                                    Marquer comme lu
                                </button>
                            </form>
                        @endif

                        <form action="{{ route('user.notifications.destroy', $notification) }}"
                              method="POST">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-outline-danger btn-sm">
                                Supprimer
                            </button>
                        </form>

                    </div>
                </div>

            </div>
        </div>

    @empty
        <div class="alert alert-info">
            Aucune notification.
        </div>
    @endforelse

    <div class="mt-3">
        {{ $notifications->links() }}
    </div>
</div>

@endsection
