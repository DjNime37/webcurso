document.addEventListener('DOMContentLoaded', () => {  // Espera a que TODO el HTML de la página haya terminado de cargarse.
    // 1. GESTIÓN DE BANNERS DE PUBLICIDAD
    const banners = [   //por ejemplo
        { id: 'banner-nike', url: 'https://www.nike.com/es/' },  // "id" tiene que coincidir con el atributo "<div id=banner-nike"  de un elemento HTML.
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
                banner.classList.add('plegado'); // Pliega el banner    
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
    // Ocultación/muestra de las dos flechas.
    const btnSubir = document.getElementById('btn-subir');  // Busca el elemento cuyo id sea "btn-subir".
    const btnBajar = document.getElementById('btn-bajar');
    function actualizarVisibilidadFlechas() {    // Declara una función para actualizar la visibilidad de las dos flechas.
        const scrollActual = window.scrollY; // window.scrollY indica cuánto hemos bajado verticalmente desde la parte superior de la página.
        const altoVentana = window.innerHeight; // Si la zona visible del navegador mide 800 píxeles de alto: altoVentana = 800
        const altoTotal = document.documentElement.scrollHeight; // scrollHeight indica la altura total de todo el documento.
        // Comprueba si existe el botón subir.
        if (btnSubir) {
            if (scrollActual > 150) {   // Si hemos bajado más de 150 píxeles...
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
    // Función de subir/bajar.
    actualizarVisibilidadFlechas(); // Ejecuta la función una vez al cargar la página (función arriba ya definida).
    // Cada vez que hacemos scroll se vuelve a ejecutar.
    window.addEventListener('scroll', actualizarVisibilidadFlechas);
    if (btnSubir) { // Comprueba que existe el botón subir.        
        btnSubir.addEventListener('click', (event) => { // Detecta un clic sobre el botón.           
            event.preventDefault();  // Evita el comportamiento normal del enlace.      
            // Desplaza la página hasta arriba.      
            window.scrollTo({
                top: 0,  // Posición vertical 0 = parte superior.                
                behavior: 'smooth'// Hace que el desplazamiento sea progresivo.
            });
        });
    }
    if (btnBajar) {
        // Detecta un clic.
        btnBajar.addEventListener('click', (event) => {
            event.preventDefault(); // Evita el comportamiento normal del enlace.
            // Desplaza la página hasta el final.
            window.scrollTo({
                top: document.documentElement.scrollHeight, // document.documentElement.scrollHeight representa la altura total de la página.
                // “Quiero desplazarme hasta la altura total de la página.”                
                behavior: 'smooth' // Desplazamiento progresivo.
            });
        });
    }
    // 4. CANTIDAD DE PRODUCTOS 
    document.querySelectorAll('.btn-sumar').forEach(boton => { // Busca todos los botones con la clase .btn-sumar y recorre cada uno (+ 1 en cantidad)
        // Ejecuta este código cuando se hace clic en el botón.
        boton.addEventListener('click', () => {
            const input = boton.parentElement.querySelector('.cantidad-producto'); // class="form-control text-center cantidad-producto" value="1" min="1".            
            if (input) {// Comprueba que el campo de cantidad existe.                
                input.value = parseInt(input.value) + 1; // Convierte el valor actual a número entero y le suma 1.
                // Sigue siendo necesario convertirlo a número porque input.value normalmente se obtiene como texto (string: "5").                
                input.dispatchEvent(new Event('input')); // Lanza manualmente el evento "input" para avisar de que la cantidad ha cambiado.                
            }
        });
    });
    // Busca todos los botones con la clase .btn-restar y recorre cada uno (- 1 en cantidad)
    document.querySelectorAll('.btn-restar').forEach(boton => {
        boton.addEventListener('click', () => {
            // Ejecuta este código cuando se hace clic en el botón.
            const input = boton.parentElement.querySelector('.cantidad-producto');// // class="form-control text-center cantidad-producto" value="1" min="1". 
            if (input && parseInt(input.value) > 1) { // Comprueba que el campo existe y que la cantidad actual es mayor que 1.                
                input.value = parseInt(input.value) - 1; // Convierte el valor actual a número entero y le resta 1.                
                input.dispatchEvent(new Event('input')); // Lanza manualmente el evento "input" para avisar de que la cantidad ha cambiado.                
            }
        });
    });
    // 5. VALIDACIÓN DEL PRODUCTO (todos los botones de comprar)
    // Busca todos los elementos HTML que tengan la clase .btn-comprar y recorre cada uno de ellos.
    document.querySelectorAll('.btn-comprar').forEach(boton => {
        const tarjeta = boton.closest('.card'); // Busca el elemento padre más cercano que tenga la clase .card. Así obtenemos la tarjeta del producto al que pertenece este botón.
        if (!tarjeta) return; // Si no encuentra ninguna tarjeta .card, termina esta iteración.        
        const talla = tarjeta.querySelector('select'); // Busca dentro de la tarjeta un elemento <select>. En este caso corresponde al selector de talla.Busca dentro de la tarjeta el campo que contiene la cantidad.        
        const cantidad = tarjeta.querySelector('.cantidad-producto'); // Función que comprueba si los datos necesarios del producto son válidos (arriba explicada)       
        function validarProducto() { // Al principio suponemos que el producto es válido.            
            let valido = true; // Comprueba si existe un selector de talla y si está vacío.
            if (talla && talla.value.trim() === '') { // Si la talla está vacía, el producto deja de ser válido.                
                valido = false;
            }
            if (cantidad) { // Comprueba si existe el campo de cantidad.                
                const valorCantidad = parseInt(cantidad.value); // Obtiene la cantidad y la convierte de texto a número entero.                
                if (isNaN(valorCantidad) || valorCantidad < 1) { // Comprueba si el valor no es un número o si es menor que 1.
                    // Si ocurre cualquiera de esas condiciones, el producto no es válido.
                    valido = false;
                }
            }
            // El botón estará deshabilitado cuando valido sea false y habilitado cuando valido sea true.
            boton.disabled = !valido; // El botón está deshabilitado cuando el producto NO es válido.
        }
        // El botón comienza deshabilitado.
        boton.disabled = true;
        // Comprueba si existe un selector de talla.
        if (talla) {
            talla.addEventListener('change', validarProducto); // Cuando el usuario cambia la talla, vuelve a comprobar los datos.
        }
        // Comprueba si existe un campo de cantidad.
        if (cantidad) {
            cantidad.addEventListener('input', validarProducto); // Cuando cambia el contenido de la cantidad, vuelve a validar.            
            cantidad.addEventListener('change', validarProducto); // Cuando termina el cambio de la cantidad, vuelve a validar.
        }
        // Ejecuta la validación una primera vez al cargar la página.
        validarProducto();
    });
});