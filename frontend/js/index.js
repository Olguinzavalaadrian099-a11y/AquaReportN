document.addEventListener('DOMContentLoaded', function () {
    const menuBtn = document.getElementById("menuBtn");
    const navLinks = document.getElementById("navLinks");

    if (menuBtn && navLinks) {
        const links = navLinks.querySelectorAll("a");
        menuBtn.addEventListener("click", () => {
            navLinks.classList.toggle("active");
            const isActive = navLinks.classList.contains("active");
            menuBtn.innerHTML = isActive ? "×" : "☰"; 
            menuBtn.setAttribute("aria-expanded", isActive ? "true" : "false");
        });
        
        links.forEach(link => {
            link.addEventListener("click", () => {
                navLinks.classList.remove("active");
                menuBtn.innerHTML = "☰";
                menuBtn.setAttribute("aria-expanded", "false");
            });
        });
    }

if (typeof Swiper !== 'undefined') {
    const miSwiper = new Swiper('.card-wrapper', {
        loop: true,
        spaceBetween: 30,
        grabCursor: true,
        slideToClickedSlide: true, 
        pagination: { el: '.swiper-pagination', clickable: true },
        navigation: { nextEl: '.swiper-button-next', prevEl: '.swiper-button-prev' },
        breakpoints: { 
            0: { slidesPerView: 1 }, 
            768: { slidesPerView: 2 }, 
            1024: { slidesPerView: 3 } 
        }
    });
    document.querySelectorAll('.card-link').forEach(link => {
        link.addEventListener('click', (e) => {
            if (link.getAttribute('href') === '#') {
                e.preventDefault();
            }
        });
    });
}

    const mapElement = document.getElementById('map');
    if (mapElement && typeof L !== 'undefined') {
        const map = L.map('map').setView([19.5391, -99.1995], 15);
        L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png').addTo(map);
        let marker = L.marker([19.5391, -99.1995], { draggable: true }).addTo(map);

        function updateCoords(lat, lng) {
            const latInput = document.getElementById('lat');
            const lngInput = document.getElementById('lng');
            if(latInput) latInput.value = lat.toFixed(6);
            if(lngInput) lngInput.value = lng.toFixed(6);
        }

        marker.on('dragend', () => {
            const pos = marker.getLatLng();
            updateCoords(pos.lat, pos.lng);
        });

        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition((position) => {
                const pos = [position.coords.latitude, position.coords.longitude];
                map.setView(pos, 15);
                marker.setLatLng(pos);
                updateCoords(pos[0], pos[1]);
            });
        }
    }

    document.querySelectorAll('input[type="file"]').forEach(input => {
        input.addEventListener('change', function () {
            const label = document.querySelector(`label[for="${this.id}"] span`);
            if (label && this.files && this.files.length > 0) {
                label.textContent = this.files[0].name;
            }
        });
    });
});

function cerrarYVolver() {
    window.location.href = 'aquareport.html'; 
}

function abrirModal() {
    const modal = document.getElementById('modal-consulta');
    if(modal) {
        modal.classList.add('modal-visible');
        modal.classList.remove('modal-fade');
    }
}

function cerrarModal() {
    const modal = document.getElementById('modal-consulta');
    if(modal) {
        modal.classList.remove('modal-visible');
        modal.classList.add('modal-fade');
    }
}

function buscarReporte() {
    const id = document.getElementById('id-busqueda').value;
    const nombre = document.getElementById('nombre-busqueda').value;
    const resultadoDiv = document.getElementById('resultado-busqueda');

    if (!id || !nombre) {
        if(resultadoDiv) resultadoDiv.innerHTML = "Por favor completa ambos campos.";
        return;
    }
    fetch('consultar_ajax.php?id=' + encodeURIComponent(id) + '&nombre=' + encodeURIComponent(nombre))
        .then(response => response.text())
        .then(data => { if(resultadoDiv) resultadoDiv.innerHTML = data; })
        .catch(error => { if(resultadoDiv) resultadoDiv.innerHTML = "Error de conexión."; });
}

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