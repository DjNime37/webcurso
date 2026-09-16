document.addEventListener('DOMContentLoaded', () => {  // Espera a que TODO el HTML de la página haya terminado de cargarse.

    // 1. GESTIÓN DE BANNERS DE PUBLICIDAD

    const banners = [   //por ejemplo
        { id: 'banner-nike', url: 'https://www.nike.com/es/' },  // "id" tiene que coincidir con el atributo id="" de un elemento HTML.
        { id: 'banner-adidas', url: 'https://www.adidas.es/' }
    ];

    banners.forEach(item => {    // Recorre cada objeto del array de arriba banners; "item" representa cada banner individual.

        // Busca en el HTML un elemento cuyo id coincida con item.id; Por ejemplo: <div id="banner-nike" (arriba)>, ("banner-nike" tiene que existir en el HTML)
        const banner = document.getElementById(item.id);

        // Si el banner no existe en ESTA página, termina esta vuelta del forEach y continúa con el siguiente.
        if (!banner) return;

        const botonCerrar = banner.querySelector('.cerrar-banner');  // Si queremos que funcione el botón cerrar, <button class="cerrar-banner">X</button> en index.html

        // Comprueba si se ha encontrado el botón cerrar=X
        if (botonCerrar) {

            botonCerrar.addEventListener('click', (event) => {    // Al hacer click

                // Evita el comportamiento normal del elemento; como tienes una flecha para arriba o abjo: "Solo ejecuta mi JavaScript."; event representa ese click
                event.preventDefault();

                // Evita que el clic continúe propagándose al banner; Esto es importante porque el banner completo también tiene un evento click.
                event.stopPropagation();

                // Añade la clase CSS "plegado" al banner; "plegado" tiene que estar definido en el CSS    
                banner.classList.add('plegado');
            });
        }

        // Detecta un clic sobre TODO el banner.
        banner.addEventListener('click', () => {

            // Comprueba si el banner tiene actualmente la clase "plegado".
            if (banner.classList.contains('plegado')) {

                // Si estaba plegado, vuelve a mostrarlo.
                banner.classList.remove('plegado');

            } else {

                // Si no estaba plegado, abre la URL del banner en una nueva pestaña.               
                window.open(item.url, '_blank');  // item.url procede del array banners de arriba.
            }

        });

    });

    // 2. VALIDACIÓN DEL FORMULARIO DE CONTACTO 

    const emailInput = document.getElementById('email'); // Busca en el HTML un elemento cuyo id sea "email" (definido <input id="email">).
    const phoneInput = document.getElementById('telefono');
    const messageText = document.getElementById('mensaje');
    const submitButton = document.getElementById('submitButton');

    if (emailInput && phoneInput && messageText && submitButton) {  // Comprueba que los CUATRO elementos anteriores existen. (a lo mejor porque nos hemos equivocado
        // aquí o en el html

        // Detecta cuándo el usuario entra = click en el campo email.
        emailInput.addEventListener('focus', () => {

            // Quita disabled del campo teléfono.
            phoneInput.disabled = false;

            // Quita disabled del campo mensaje.
            messageText.disabled = false;
        });

        // Detecta cada vez que el usuario escribe algo en email.
        emailInput.addEventListener('input', () => {

            if (emailInput.value.trim() !== '') {  // value contiene el texto escrito dentro del input email; trim() elimina espacios al principio y al final.
                // !== '' → comprueba que no sea una cadena vacía= “si el usuario ha escrito algo en el campo email”...

                // Habilita el botón enviar.
                submitButton.disabled = false;

            } else {

                // Deshabilita el botón enviar.
                submitButton.disabled = true;
            }

        });

    }

    // 3. FLECHAS DE NAVEGACIÓN

    const btnSubir = document.getElementById('btn-subir');  // Busca el elemento cuyo id sea "btn-subir".
    const btnBajar = document.getElementById('btn-bajar');

    function actualizarVisibilidadFlechas() {    // Declara una función para actualizar la visibilidad de las dos flechas.

        const scrollActual = window.scrollY; // window.scrollY indica cuánto hemos bajado verticalmente desde la parte superior de la página.

        const altoVentana = window.innerHeight; // Si la zona visible del navegador mide 800 píxeles de alto: altoVentana = 800

        const altoTotal = document.documentElement.scrollHeight; // scrollHeight indica la altura total de todo el documento.

        // Comprueba si existe el botón subir.
        if (btnSubir) {

            // Si hemos bajado más de 150 píxeles...
            if (scrollActual > 150) {

                btnSubir.classList.remove('d-none'); // El botón vuelve a ser visible.

            } else {

                btnSubir.classList.add('d-none'); // oculta el botón.
            }

        }

        if (btnBajar) { // Comprueba si existe el botón bajar.

            if (scrollActual + altoVentana >= altoTotal - 10) { // Comprueba si hemos llegado prácticamente al final de la página.
                // altoTotal → cuánto mide toda la página, incluida la parte que todavía no ves.

                btnBajar.classList.add('d-none'); // Oculta el botón bajar.

            } else {

                btnBajar.classList.remove('d-none'); // Lo muestra.
            }

        }

    }

    actualizarVisibilidadFlechas(); // Ejecuta la función una vez al cargar la página.

    // Cada vez que hacemos scroll se vuelve a ejecutar.
    window.addEventListener('scroll', actualizarVisibilidadFlechas);

    if (btnSubir) { // Comprueba que existe el botón subir.

        // Detecta un clic sobre el botón.
        btnSubir.addEventListener('click', (event) => {

            // Evita el comportamiento normal del enlace.
            event.preventDefault();

            // Desplaza la página hasta arriba.
            window.scrollTo({

                // Posición vertical 0 = parte superior.
                top: 0,

                // Hace que el desplazamiento sea progresivo.
                behavior: 'smooth'
            });

        });

    }

    // Comprueba que existe el botón bajar.
    if (btnBajar) {

        // Detecta un clic.
        btnBajar.addEventListener('click', (event) => {

            // Evita el comportamiento normal del enlace.
            event.preventDefault();

            // Desplaza la página hasta el final.
            window.scrollTo({

                top: document.documentElement.scrollHeight, // document.documentElement.scrollHeight representa la altura total de la página.
                // “Quiero desplazarme hasta la altura total de la página.”

                // Desplazamiento progresivo.
                behavior: 'smooth'
            });

        });

    }

    // 4. CANTIDAD DE PRODUCTOS
    document.querySelectorAll('.btn-sumar').forEach(boton => {

        boton.addEventListener('click', () => {

            const input = boton.parentElement.querySelector('.cantidad-producto');

            if (input) {
                input.value = parseInt(input.value) + 1;
                input.dispatchEvent(new Event('input'));
            }

        });

    });

    document.querySelectorAll('.btn-restar').forEach(boton => {

        boton.addEventListener('click', () => {

            const input = boton.parentElement.querySelector('.cantidad-producto');

            if (input && parseInt(input.value) > 1) {
                input.value = parseInt(input.value) - 1;
                input.dispatchEvent(new Event('input'));
            }

        });

    });

});