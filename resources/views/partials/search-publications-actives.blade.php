<div class="container">
    <div class="section-header">
            <div class="section-title">Notre Catalogue</div>
            <div class="section-sub">({{ $publications->total() }}) matériels disponibles en location</div>      
    </div>
    {{-- LISTE --}}
    <div class="row g-4 mt-0">
        @forelse($publications as $publication)
            <div class="col-sm-6 col-md-4 col-lg-3">
                <div class="card h-100 shadow-sm hover-lift transition-all publication-card">

                    {{-- Carousel --}}
                    <div id="carousel{{ $publication->id }}" class="carousel slide card-img-top" data-bs-ride="carousel">
                        <div class="carousel-inner rounded-top">
                            @forelse($publication->dispositif->photos as $index => $photo)
                                <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                    <img src="{{ asset('storage/'.$photo->path) }}" class="d-block w-100" style="height:200px; object-fit:cover;" alt="photo">
                                </div>
                            @empty
                                <div class="carousel-item active">
                                    <img src="{{ asset('images/no-image.png') }}" class="d-block w-100" style="height:200px; object-fit:cover;">
                                </div>
                            @endforelse
                        </div>
                        @if($publication->dispositif->photos->count() > 1)
                            <button class="carousel-control-prev" type="button" data-bs-target="#carousel{{ $publication->id }}" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" style="width: 1.2rem;"></span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#carousel{{ $publication->id }}" data-bs-slide="next">
                                <span class="carousel-control-next-icon" style="width: 1.2rem;"></span>
                            </button>
                        @endif
                    </div>

                    {{-- Corps de la carte --}}
                    <div class="card-body p-3 d-flex flex-column">
                        <h6 class="fw-bold text-dark mb-1" title="{{ $publication->dispositif->designation }}">
                            {{ $publication->dispositif->designation ?? '-' }}
                        </h6>

                        <div class="d-flex align-items-start gap-1 mb-3 text-muted" style="font-size: 0.85rem; min-height: 40px;">
                            <i class="bi bi-geo-alt-fill text-danger"></i>
                            <span class="text-danger">
                                {{ $publication->departement->nom ?? '' }},
                                {{ $publication->departement->region->pays->libelle_division ?? '' }}
                                {{ $publication->departement->region->nom ?? '' }},
                                {{ $publication->departement->region->pays->nom ?? '' }}
                            </span>
                        </div>

                        {{-- Prix --}}
                        <div class="mt-auto pt-2 border-top">
                            <div class="d-flex align-items-baseline gap-1">
                                <span class="text-success fw-bold fs-4">{{ number_format($publication->tarif_location,0,',',' ') }}</span>
                                <span class="text-success fw-semibold small">{{ $publication->devise->symbol ?? 'FCFA' }}</span>
                                <span class="text-success" style="font-size: 0.7rem;">/ JOUR</span>
                            </div>
                        </div>
                    </div>

                    {{-- Footer (Actions) --}}
                    <div class="card-footer border-0 p-3 pt-0 d-flex gap-2" style="background-color: rgba(var(--bs-white-rgb),var(--bs-bg-opacity))!important">
                        <a href="{{ route('publications.show', $publication) }}" class="btn btn-outline-dark flex-grow-1 btn-sm fw-medium">
                            Détails
                        </a>
                        <!-- <button type="button" class="btn btn-success contact-btn btn-sm px-3 shadow-sm" data-url="{{ route('reservations.store', $publication->id) }}">
                            <i class="bi bi-chat-dots-fill"></i>
                            Contacter
                        </button> -->

                        <a href="https://wa.me/{{ config('services.infobip.whatsapp_sender') }}?text={{ urlencode('Bonjour, je suis intéressé par cette annonce : ' . route('publications.show', $publication)) }}"
                        target="_blank"
                        class="btn btn-success contact-btn btn-sm px-3 shadow-sm">
                            <i class="bi bi-whatsapp"></i>
                            Contacter
                        </a>
                    </div>
                </div>
            </div>

            @include('partials.contact-modal')
        @empty
            {{-- Ton bloc vide --}}
        @endforelse
    </div>

<div class="d-flex justify-content-center mt-4">
    {{ $publications->links() }}
</div>

    <div class="mt-4">
        {{ $publications->links() }}
    </div>
</div>

@push('scripts')
    <script src="{{ asset('js/dependent-select.js') }}"></script>
    <script src="{{ asset('js/contact-modal.js') }}"></script>
@endpush
