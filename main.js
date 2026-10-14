document.addEventListener('DOMContentLoaded', () => {  // Espera a que TODO el HTML de la página haya terminado de cargarse.
    // 1. GESTIÓN DE BANNERS DE PUBLICIDAD
    const banners = [   // Array que contiene la información de cada banner.
        { id: 'banner-nike', url: 'https://www.nike.com/es/' },  // "id" tiene que coincidir con el atributo id del elemento HTML.
        { id: 'banner-adidas', url: 'https://www.adidas.es/' }   // "url" es la página que se abrirá al pulsar el banner.
    ];
    banners.forEach(item => {    // Recorre cada objeto del array banners; "item" representa cada banner individual.
        // Busca en el HTML un elemento cuyo id coincida con item.id.
        // Por ejemplo: <div id="banner-nike">
        const banner = document.getElementById(item.id);
        // Si el banner no existe en ESTA página,
        // termina esta vuelta del forEach y continúa con el siguiente.
        if (!banner) return;
        // Busca dentro del banner el botón que tenga la clase "cerrar-banner".
        // Por ejemplo: <button class="cerrar-banner">X</button>
        const botonCerrar = banner.querySelector('.cerrar-banner');
        // Comprueba si se ha encontrado el botón cerrar.
        if (botonCerrar) {
            // Detecta un clic sobre el botón cerrar.
            botonCerrar.addEventListener('click', (event) => {
                // Evita el comportamiento normal del elemento.
                // Solo ejecuta nuestro JavaScript.
                event.preventDefault();
                // Evita que el clic continúe propagándose al banner.
                // Esto es importante porque el banner completo también tiene un evento click.
                event.stopPropagation();
                // Añade la clase CSS "plegado" al banner.
                // "plegado" tiene que estar definido en el CSS.
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
                // item.url procede del array banners de arriba.
                window.open(item.url, '_blank');
            }
        });
    });
    // 2. VALIDACIÓN DEL FORMULARIO DE CONTACTO
    // Busca en el HTML un elemento cuyo id sea "email".
    // Por ejemplo: <input id="email">
    const emailInput = document.getElementById('email');
    // Busca el campo cuyo id sea "telefono".
    const phoneInput = document.getElementById('telefono');
    // Busca el campo cuyo id sea "mensaje".
    const messageText = document.getElementById('mensaje');
    // Busca el botón cuyo id sea "submitButton".
    const submitButton = document.getElementById('submitButton');
    // Comprueba que los CUATRO elementos anteriores existen.
    if (
        emailInput &&
        phoneInput &&
        messageText &&
        submitButton
    ) {
        // Detecta cuándo el usuario entra en el campo email.
        emailInput.addEventListener('focus', () => {
            // Quita disabled del campo teléfono.
            phoneInput.disabled = false;
            // Quita disabled del campo mensaje.
            messageText.disabled = false;
        });
        // Detecta cada vez que el usuario escribe algo en email.
        emailInput.addEventListener('input', () => {
            // value contiene el texto escrito dentro del input.
            // trim() elimina espacios al principio y al final.
            // Comprueba si el usuario ha escrito algo.
            if (emailInput.value.trim() !== '') {
                // Habilita el botón enviar.
                submitButton.disabled = false;
            } else {
                // Deshabilita el botón enviar.
                submitButton.disabled = true;
            }
        });
    }
    // 3. FLECHAS DE NAVEGACIÓN
    // Busca el elemento cuyo id sea "btn-subir".
    const btnSubir = document.getElementById('btn-subir');
    // Busca el elemento cuyo id sea "btn-bajar".
    const btnBajar = document.getElementById('btn-bajar');
    // Función que actualiza la visibilidad de las dos flechas.
    function actualizarVisibilidadFlechas() {
        // window.scrollY indica cuánto hemos bajado verticalmente
        // desde la parte superior de la página.
        const scrollActual = window.scrollY;
        // innerHeight indica la altura visible de la ventana.
        const altoVentana = window.innerHeight;
        // scrollHeight indica la altura total de todo el documento.
        const altoTotal = document.documentElement.scrollHeight;
        // Comprueba si existe el botón subir.
        if (btnSubir) {
            // Si hemos bajado más de 150 píxeles...
            if (scrollActual > 150) {
                // El botón vuelve a ser visible.
                btnSubir.classList.remove('d-none');
            } else {
                // Oculta el botón.
                btnSubir.classList.add('d-none');
            }
        }
        // Comprueba si existe el botón bajar.
        if (btnBajar) {
            // Comprueba si hemos llegado prácticamente al final de la página.
            if (
                scrollActual + altoVentana >=
                altoTotal - 10
            ) {
                // Oculta el botón bajar.
                btnBajar.classList.add('d-none');
            } else {
                // Muestra el botón bajar.
                btnBajar.classList.remove('d-none');
            }
        }
    }
    // Ejecuta la función una vez al cargar la página.
    actualizarVisibilidadFlechas();
    // Cada vez que hacemos scroll se vuelve a ejecutar.
    window.addEventListener(
        'scroll',
        actualizarVisibilidadFlechas
    );
    // Comprueba que existe el botón subir.
    if (btnSubir) {
        // Detecta un clic sobre el botón.
        btnSubir.addEventListener('click', (event) => {
            // Evita el comportamiento normal del enlace.
            event.preventDefault();
            // Desplaza la página hasta arriba.
            window.scrollTo({
                top: 0,             // Posición vertical 0 = parte superior.
                behavior: 'smooth'    // Hace que el desplazamiento sea progresivo.
            });
        });
    }
    // Comprueba que existe el botón bajar.
    if (btnBajar) {
        // Detecta un clic sobre el botón.
        btnBajar.addEventListener('click', (event) => {
            // Evita el comportamiento normal del enlace.
            event.preventDefault();
            // Desplaza la página hasta el final.
            window.scrollTo({
                top: document.documentElement.scrollHeight,  // Altura total de la página.
                behavior: 'smooth'                            // Desplazamiento progresivo.
            });
        });
    }
    // 4. CANTIDAD DE PRODUCTOS
    // Busca todos los botones con la clase .btn-sumar
    // y recorre cada uno.
    document.querySelectorAll('.btn-sumar').forEach(boton => {
        // Ejecuta este código cuando se hace clic en el botón.
        boton.addEventListener('click', () => {
            // Busca dentro del mismo elemento padre el campo
            // que contiene la cantidad del producto.
            const input =
                boton.parentElement.querySelector(
                    '.cantidad-producto'
                );
            // Comprueba que el campo de cantidad existe.
            if (input) {
                // Convierte el valor actual a número entero
                // y le suma 1.
                input.value =
                    parseInt(input.value) + 1;
                // Lanza manualmente el evento "input"
                // para avisar de que la cantidad ha cambiado.
                input.dispatchEvent(
                    new Event('input')
                );
            }
        });
    });
    // Busca todos los botones con la clase .btn-restar
    // y recorre cada uno.
    document.querySelectorAll('.btn-restar').forEach(boton => {
        // Ejecuta este código cuando se hace clic en el botón.
        boton.addEventListener('click', () => {
            // Busca el campo que contiene la cantidad.
            const input =
                boton.parentElement.querySelector(
                    '.cantidad-producto'
                );
            // Comprueba que el campo existe y que la cantidad actual es mayor que 1.
            if (
                input &&
                parseInt(input.value) > 1
            ) {
                // Convierte el valor actual a número entero
                // y le resta 1.
                input.value =
                    parseInt(input.value) - 1;
                // Lanza manualmente el evento "input"
                // para avisar de que la cantidad ha cambiado.
                input.dispatchEvent(
                    new Event('input')
                );
            }
        });
    });
    // 5. VALIDACIÓN DEL PRODUCTO (todos los botones de comprar)
    // Busca todos los elementos HTML que tengan la clase .btn-comprar
    // y recorre cada uno de ellos.
    document.querySelectorAll('.btn-comprar').forEach(boton => {
        // Busca el elemento padre más cercano que tenga la clase .card.
        // Así obtenemos la tarjeta del producto al que pertenece este botón.
        const tarjeta = boton.closest('.card');
        // Si no encuentra ninguna tarjeta .card,
        // termina esta iteración.
        if (!tarjeta) return;
        // Busca dentro de la tarjeta un elemento <select>.
        // En este caso corresponde al selector de talla.
        const talla = tarjeta.querySelector('select');
        // Busca dentro de la tarjeta el campo que contiene la cantidad.
        const cantidad =
            tarjeta.querySelector('.cantidad-producto');
        // Función que comprueba si los datos necesarios
        // del producto son válidos.
        function validarProducto() {
            // Al principio suponemos que el producto es válido.
            let valido = true;
            // Comprueba si existe un selector de talla
            // y si está vacío.
            if (
                talla &&
                talla.value.trim() === ''
            ) {
                // Si la talla está vacía,
                // el producto deja de ser válido.
                valido = false;
            }
            // Comprueba si existe el campo de cantidad.
            if (cantidad) {
                // Obtiene la cantidad y la convierte de texto
                // a número entero.
                const valorCantidad =
                    parseInt(cantidad.value);
                // Comprueba si el valor no es un número
                // o si es menor que 1.
                if (
                    isNaN(valorCantidad) ||
                    valorCantidad < 1
                ) {
                    // El producto deja de ser válido.
                    valido = false;
                }
            }
            // El botón estará deshabilitado cuando valido sea false
            // y habilitado cuando valido sea true.
            boton.disabled = !valido;
        }
        // El botón comienza deshabilitado.
        boton.disabled = true;
        // Comprueba si existe un selector de talla.
        if (talla) {
            // Cuando el usuario cambia la talla,
            // vuelve a comprobar los datos.
            talla.addEventListener(
                'change',
                validarProducto
            );
        }
        // Comprueba si existe un campo de cantidad.
        if (cantidad) {
            // Cuando cambia el contenido de la cantidad,
            // vuelve a validar.
            cantidad.addEventListener(
                'input',
                validarProducto
            );
            // Cuando termina el cambio de la cantidad,
            // vuelve a validar.
            cantidad.addEventListener(
                'change',
                validarProducto
            );
        }
        // Ejecuta la validación una primera vez al cargar la página.
        validarProducto();
    });
    // 6. MENSAJE DE BIENVENIDA
    // Busca el elemento cuyo id sea "mensaje-bienvenida".
    const mensajeBienvenida =
        document.getElementById('mensaje-bienvenida');
    // Comprueba si existe el mensaje de bienvenida.
    if (mensajeBienvenida) {
        setTimeout(function () {
            mensajeBienvenida.classList.add('ocultar');
        }, 1500);
        setTimeout(function () {
            mensajeBienvenida.remove();
        }, 2000);
    }
    // 7. MENSAJES Y AVISOS
    // Función que hace desaparecer un aviso después de 2 segundos.
    function ocultarAlerta(alerta) {
        // Evita iniciar el temporizador más de una vez
        // para el mismo aviso.
        if (alerta.dataset.temporizadorIniciado === 'true') {
            return;
        }
        // Marca el aviso como procesado.
        alerta.dataset.temporizadorIniciado = 'true';
        // Espera 2 segundos.
        setTimeout(function () {
            // Añade una transición para que desaparezca suavemente.
            alerta.style.transition = 'opacity 0.5s ease';
            // Hace el aviso transparente.
            alerta.style.opacity = '0';
            // Espera a que termine la animación.
            setTimeout(function () {
                // Elimina completamente el aviso del HTML.
                alerta.remove();
                // Espera 2.000 ms y después tarda otros 500 ms en eliminarlos.
            }, 500); // Los avisos desaparecen a los 2,5 segundos
        }, 3500);
    }
    // Busca todos los avisos que ya existen al cargar la página.
    document.querySelectorAll('.alert').forEach(function (alerta) {
        // Inicia el temporizador de cada aviso.
        ocultarAlerta(alerta);
    });
    // Observa cambios en el HTML de la página.
    // Esto permite detectar avisos que aparezcan después.
    const observadorAlertas = new MutationObserver(function (mutaciones) {
        mutaciones.forEach(function (mutacion) {
            // Recorre los elementos que se hayan añadido.
            mutacion.addedNodes.forEach(function (nodo) {
                // Comprueba que el elemento añadido sea un elemento HTML.
                if (nodo.nodeType !== 1) {
                    return;
                }
                // Si el propio elemento añadido es un .alert,
                // inicia su temporizador.
                if (nodo.classList.contains('alert')) {
                    ocultarAlerta(nodo);
                }
                // También busca .alert dentro del elemento añadido.
                nodo.querySelectorAll('.alert').forEach(function (alerta) {
                    ocultarAlerta(alerta);
                });
            });
        });
    });
    // Empieza a observar todo el contenido del documento.
    observadorAlertas.observe(document.body, {
        childList: true,
        subtree: true
    });
// 8. COMPROBACIÓN DEL DNI REGISTRADO Y BANNER DINÁMICO
// Obtiene el formulario de registro mediante su identificador HTML.
const formularioRegistroDNI = document.getElementById('registroForm');
// Comprueba que el formulario existe antes de añadirle el evento.
if (formularioRegistroDNI) {
    // Escucha el evento de envío del formulario y ejecuta una función asíncrona.
    formularioRegistroDNI.addEventListener('submit', async (event) => {
        // Comprueba si los campos del formulario cumplen las validaciones HTML.
        if (!formularioRegistroDNI.checkValidity()) {
            // Si hay algún campo no válido, permite que actúen las validaciones nativas del navegador.
            return;
        }
        // Evita el envío tradicional del formulario para procesarlo mediante fetch().
        event.preventDefault();
        // Recopila los datos del formulario, incluidos sus campos y valores.
        const datosRegistro = new FormData(formularioRegistroDNI);
        // Inicia el bloque de control de errores de la petición al servidor.
        try {
            // Envía los datos del formulario a registro.php mediante una petición POST.
            const respuestaRegistro = await fetch('registro.php', {
                method: 'POST',
                body: datosRegistro
            });
            // Obtiene el contenido de la respuesta del servidor como texto.
            const resultadoRegistro = await respuestaRegistro.text();
            // Elimina los espacios en blanco al principio y al final de la respuesta.
            const textoLimpio = resultadoRegistro.trim();
            // Comprueba si el servidor ha respondido exactamente con este mensaje.
            if (textoLimpio === 'Usuario registrado.') {
                // Muestra una alerta indicando que el usuario ya está registrado.
                alert('Usuario registrado.');
                // Detiene la ejecución para no continuar con el resto de comprobaciones.
                return;
            }
            // Comprueba si la respuesta contiene el mensaje de creación correcta de la cuenta.
            if (textoLimpio.includes('Cuenta creada correctamente')) {
                // 1. OCULTAR EL FORMULARIO
                // Oculta el formulario para limpiar la vista y evitar que siga mostrándose.
                formularioRegistroDNI.style.display = 'none';
                // 2. CREAR EL BANNER DE ÉXITO
                // Crea un nuevo elemento HTML de tipo div.
                const banner = document.createElement('div');
                // Asigna al banner su identificador HTML original.
                banner.id = 'banner-registro-exitoso';
                // Establece el mensaje que aparecerá dentro del banner.
                banner.textContent = 'Cuenta creada correctamente: redirigiendo a la página principal...';
                // 3. INSERTAR EL BANNER ANTES DEL FORMULARIO
                // Inserta el banner justo antes del formulario, que permanece oculto.
                formularioRegistroDNI.parentNode.insertBefore(banner, formularioRegistroDNI);
                // 4. REDIRIGIR A LA PÁGINA PRINCIPAL DESPUÉS DE 2,5 SEGUNDOS
                // Programa una función que se ejecutará después de 2500 milisegundos.
                setTimeout(() => {
                    // Sustituye la página actual por index.php sin conservar esta entrada
                    // como una página independiente en el historial de navegación.
                    window.location.replace('index.php');
                }, 2500);
                // Detiene la ejecución para no mostrar también una alerta con la respuesta.
                return;
            }
            // Si la respuesta no coincide con ninguno de los casos anteriores,
            // muestra al usuario el texto recibido del servidor.
            alert(resultadoRegistro);
        } catch (error) {
            // Registra en la consola los detalles técnicos del error producido.
            console.error("Error en el registro:", error);
            // Muestra un mensaje general para informar al usuario de que el registro ha fallado.
            alert('Se ha producido un error al realizar el registro.');
        }
    });
}
});