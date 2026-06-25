import './bootstrap';

function adjustCatalogueLayout() {
    const navbar = document.querySelector('.bg-blue-custom');
    if (navbar) {
        const height = navbar.getBoundingClientRect().height + 10;
        document.documentElement.style.setProperty('--navbar-height', height + 'px');
    }
}

adjustCatalogueLayout();
window.addEventListener('resize', adjustCatalogueLayout);