window.onload = function() {
    const urlParams = new URLSearchParams(window.location.search);
    const id = urlParams.get('id');
    if (id) {
        const mensajeDiv = document.getElementById('mensaje-id');
        const textoId = document.getElementById('texto-id');
        if (mensajeDiv) mensajeDiv.style.display = 'block';
        if (textoId) textoId.innerText = 'Tu ID de seguimiento es: #' + id;
    }
};

window.addEventListener('pageshow', function (event) {
    const loader = document.getElementById('loader-modal');
    if (loader) loader.style.display = 'none';
});

document.addEventListener('DOMContentLoaded', function () {
    const loader = document.getElementById('loader-modal');
    const form = document.querySelector('form');

    function iniciarNavegacion(url, mensaje = "Procesando...", tiempo = 1500) {
        if (loader) {
            loader.style.display = 'flex';
            const loaderText = loader.querySelector('.loader-text');
            if (loaderText) loaderText.innerText = mensaje;
        }
        setTimeout(() => { window.location.href = url; }, tiempo);
    }

    document.querySelectorAll('.link-loader').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            iniciarNavegacion(this.getAttribute('href'));
        });
    });

    const btnCerrarVolver = document.querySelector('.btn-cerrar-volver');
    if (btnCerrarVolver) {
        btnCerrarVolver.addEventListener('click', (e) => {
            e.preventDefault();
            iniciarNavegacion('index.html', "Regresando al inicio...", 1800);
        });
    }

    const btnAccesoEmpresa = document.querySelector('a[href="/backend/login.php"]');
    if (btnAccesoEmpresa) {
        btnAccesoEmpresa.addEventListener('click', (e) => {
            e.preventDefault();
            iniciarNavegacion("/backend/login.php", "Redirigiendo...");
        });
    }

    if (form && loader) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            loader.style.display = 'flex';
            const formData = new FormData(this);
            fetch('/backend/procesar_reporte.php', { method: 'POST', body: formData })
                .then(r => r.text())
                .then(data => { window.location.href = '/frontend/reporte.html?id=' + data.trim(); })
                .catch(() => { loader.style.display = 'none'; alert("Error al enviar."); });
        });
    }

    const menuBtn = document.getElementById("menuBtn");
    const navLinks = document.getElementById("navLinks");
    if (menuBtn && navLinks) {
        menuBtn.addEventListener("click", () => {
            const active = navLinks.classList.toggle("active");
            menuBtn.innerHTML = active ? "×" : "☰";
        });
    }

    if (typeof Swiper !== 'undefined') {
        new Swiper('.card-wrapper', { loop: true, pagination: { el: '.swiper-pagination' }, navigation: { nextEl: '.swiper-button-next', prevEl: '.swiper-button-prev' }, breakpoints: { 0: { slidesPerView: 1 }, 768: { slidesPerView: 2 }, 1024: { slidesPerView: 3 } } });
    }

    const mapElement = document.getElementById('map');
    if (mapElement && typeof L !== 'undefined') {
        const map = L.map('map').setView([19.5391, -99.1995], 15);
        L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png').addTo(map);
        let marker = L.marker([19.5391, -99.1995], { draggable: true }).addTo(map);
        marker.on('dragend', () => {
            const pos = marker.getLatLng();
            if(document.getElementById('lat')) document.getElementById('lat').value = pos.lat.toFixed(6);
            if(document.getElementById('lng')) document.getElementById('lng').value = pos.lng.toFixed(6);
        });
    }
});

function abrirModal() { document.getElementById('modal-consulta').classList.add('modal-visible'); }
function cerrarModal() { document.getElementById('modal-consulta').classList.remove('modal-visible'); }
function buscarReporte() {
    const id = document.getElementById('id-busqueda').value;
    const nombre = document.getElementById('nombre-busqueda').value;
    fetch(`/backend/consultar_ajax.php?id=${encodeURIComponent(id)}&nombre=${encodeURIComponent(nombre)}`)
        .then(r => r.text()).then(d => { document.getElementById('resultado-busqueda').innerHTML = d; });
}