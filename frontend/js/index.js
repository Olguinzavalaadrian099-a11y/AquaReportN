window.addEventListener('pageshow', function (event) {
    const loader = document.getElementById('loader-modal');
    if (loader) {
        loader.style.display = 'none';
    }
});

document.addEventListener('DOMContentLoaded', function () {
    const form = document.querySelector('form');
    const loader = document.getElementById('loader-modal');
    
    const linksConLoader = document.querySelectorAll('.link-loader');
    linksConLoader.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const url = this.getAttribute('href');
            if (loader) loader.style.display = 'flex';
            setTimeout(() => { window.location.href = url; }, 1500);
        });
    });

    const btnCerrarVolver = document.querySelector('button[onclick="cerrarYVolver()"]');
    if (btnCerrarVolver) {
        btnCerrarVolver.addEventListener('click', function(e) {
            e.preventDefault(); 
            
            if (loader) {
                loader.style.display = 'flex';
                const loaderText = loader.querySelector('.loader-text');
                if (loaderText) loaderText.innerText = "Regresando al inicio...";
            }

            setTimeout(() => {
                window.location.href = 'index.html';
            }, 1800);
        });
    }

    if (form && loader) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            loader.style.display = 'flex'; 

            const formData = new FormData(this);
            fetch('/backend/procesar_reporte.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                window.location.href = '/frontend/reporte.html?id=' + data.trim(); 
            })
            .catch(error => {
                loader.style.display = 'none'; 
                alert("Ocurrió un error al enviar el reporte.");
            });
        });
    }

    const menuBtn = document.getElementById("menuBtn");
    const navLinks = document.getElementById("navLinks");
    if (menuBtn && navLinks) {
        const links = navLinks.querySelectorAll("a");
        menuBtn.addEventListener("click", () => {
            navLinks.classList.toggle("active");
            const isActive = navLinks.classList.contains("active");
            menuBtn.innerHTML = isActive ? "×" : "☰"; 
        });
        links.forEach(link => {
            link.addEventListener("click", () => {
                navLinks.classList.remove("active");
                menuBtn.innerHTML = "☰";
            });
        });
    }

    if (typeof Swiper !== 'undefined') {
        new Swiper('.card-wrapper', {
            loop: true,
            spaceBetween: 30,
            pagination: { el: '.swiper-pagination', clickable: true },
            navigation: { nextEl: '.swiper-button-next', prevEl: '.swiper-button-prev' },
            breakpoints: { 0: { slidesPerView: 1 }, 768: { slidesPerView: 2 }, 1024: { slidesPerView: 3 } }
        });
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

function cerrarYVolver() { window.location.href = 'index.html'; }
function abrirModal() {
    const modal = document.getElementById('modal-consulta');
    if(modal) { modal.classList.add('modal-visible'); modal.classList.remove('modal-fade'); }
}
function cerrarModal() {
    const modal = document.getElementById('modal-consulta');
    if(modal) { modal.classList.remove('modal-visible'); modal.classList.add('modal-fade'); }
}
function buscarReporte() {
    const id = document.getElementById('id-busqueda').value;
    const nombre = document.getElementById('nombre-busqueda').value;
    fetch('/backend/consultar_ajax.php?id=' + encodeURIComponent(id) + '&nombre=' + encodeURIComponent(nombre))
        .then(res => res.text())
        .then(data => { if(document.getElementById('resultado-busqueda')) document.getElementById('resultado-busqueda').innerHTML = data; });
}

window.onload = function() {
    const id = new URLSearchParams(window.location.search).get('id');
    if (id) {
        document.getElementById('mensaje-id').style.display = 'block';
        document.getElementById('texto-id').innerText = 'Tu ID de seguimiento es: #' + id;
    }
};