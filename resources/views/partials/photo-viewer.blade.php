<div class="modal fade" id="lightboxModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content bg-dark border-0">
            <div class="modal-body p-0 position-relative">

                <button type="button"
                        class="btn-close btn-close-white position-absolute top-0 end-0 m-3"
                        data-bs-dismiss="modal"></button>

                <img id="lightboxImage"
                     class="w-100"
                     style="max-height:85vh; object-fit:contain;">

                <button id="lightboxPrev"
                        class="btn btn-dark position-absolute top-50 start-0 translate-middle-y">
                    <i class="bi bi-chevron-left fs-2"></i>
                </button>

                <button id="lightboxNext"
                        class="btn btn-dark position-absolute top-50 end-0 translate-middle-y">
                    <i class="bi bi-chevron-right fs-2"></i>
                </button>

            </div>
        </div>
    </div>
</div>


@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    let currentIndex = 0;
    let images = [];

    const modalElement = document.getElementById('lightboxModal');
    if (!modalElement) return;

    const modal = new bootstrap.Modal(modalElement);
    const modalImage = document.getElementById('lightboxImage');

    const updateImage = (index) => {
        currentIndex = index;
        modalImage.src = images[currentIndex];
    };

    document.querySelectorAll('.lightbox-gallery').forEach(gallery => {

        const items = gallery.querySelectorAll('.lightbox-item');

        items.forEach((img, index) => {
            img.addEventListener('click', function () {

                images = Array.from(items).map(i => i.src);
                updateImage(index);
                modal.show();
            });
        });
    });

    document.getElementById('lightboxPrev').onclick = () => {
        const index = (currentIndex - 1 + images.length) % images.length;
        updateImage(index);
    };

    document.getElementById('lightboxNext').onclick = () => {
        const index = (currentIndex + 1) % images.length;
        updateImage(index);
    };

});
</script>
@endpush
