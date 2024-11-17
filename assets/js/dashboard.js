/**
 * Maneja la visibilidad y obtención de la información del stream
 */
function toggleStreamInfo() {
    const streamInfo = document.getElementById('streamInfo');
    const buttonText = document.getElementById('toggleButtonText');

    if (streamInfo.classList.contains('hidden')) {
        // Enviar solicitud POST para obtener la clave de stream
        fetch('dashboard.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'start_stream=1'
        })
        .then(response => {
            streamInfo.classList.remove('hidden');
            buttonText.textContent = 'Ocultar Información';
            
        })
        .catch(error => {
            console.error('Error:', error);
        });
    } else {
        streamInfo.classList.add('hidden');
        buttonText.textContent = 'Obtener Información de Stream';
    }
}

/**
 * Verifica el estado inicial del botón al cargar la página
 */
window.addEventListener('load', function() {
    const streamInfo = document.getElementById('streamInfo');
    const buttonText = document.getElementById('toggleButtonText');

    if (streamInfo.classList.contains('hidden')) {
        buttonText.textContent = 'Obtener Información de Stream';
    } else {
        buttonText.textContent = 'Ocultar Información';
    }
}); 