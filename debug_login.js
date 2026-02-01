// Script de debugging para el login
// Copia y pega este código en la consola del navegador (F12)

console.log('=== DIAGNÓSTICO DEL LOGIN ===');

// 1. Verificar que los elementos existan
console.log('\n1. Verificando elementos del DOM:');
const adminRole = document.getElementById('adminRole');
const workerRole = document.getElementById('workerRole');
const nextToUserInfo = document.getElementById('nextToUserInfo');
const roleSelection = document.getElementById('roleSelection');
const userInfoForm = document.getElementById('userInfoForm');
const loginCredentials = document.getElementById('loginCredentials');

console.log('adminRole:', adminRole);
console.log('workerRole:', workerRole);
console.log('nextToUserInfo:', nextToUserInfo);
console.log('roleSelection:', roleSelection);
console.log('userInfoForm:', userInfoForm);
console.log('loginCredentials:', loginCredentials);

// 2. Verificar visibilidad
console.log('\n2. Verificando visibilidad:');
console.log('roleSelection display:', roleSelection ? roleSelection.style.display : 'N/A');
console.log('userInfoForm display:', userInfoForm ? userInfoForm.style.display : 'N/A');
console.log('loginCredentials display:', loginCredentials ? loginCredentials.style.display : 'N/A');

// 3. Verificar eventos
console.log('\n3. Verificando eventos del botón:');
if (nextToUserInfo) {
    console.log('Botón existe:', true);
    console.log('Tipo de botón:', nextToUserInfo.type);
    console.log('Eventos onclick:', nextToUserInfo.onclick);

    // Agregar evento de prueba
    console.log('\n4. Agregando evento de prueba...');
    nextToUserInfo.addEventListener('click', function (e) {
        console.log('¡CLICK DETECTADO EN EL BOTÓN!');
        console.log('Event:', e);
        console.log('Target:', e.target);
    });

    console.log('Evento de prueba agregado. Ahora haz clic en "Continuar"');
} else {
    console.error('ERROR: El botón nextToUserInfo no existe en el DOM');
}

// 5. Función manual para avanzar
console.log('\n5. Creando función manual para avanzar...');
window.manualAdvance = function () {
    console.log('Ejecutando avance manual...');
    if (roleSelection) roleSelection.style.display = 'none';
    if (userInfoForm) userInfoForm.style.display = 'block';
    console.log('Avance completado. Verifica la pantalla.');
};

console.log('\n=== FIN DEL DIAGNÓSTICO ===');
console.log('Si el botón no funciona, ejecuta: manualAdvance()');
