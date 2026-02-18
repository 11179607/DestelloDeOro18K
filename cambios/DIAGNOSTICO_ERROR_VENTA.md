# Diagnóstico y Solución: Error al Procesar Venta

## Problema
Al intentar realizar una venta, aparece el mensaje:
```
Error
Error al procesar venta
```

## Posibles Causas

### 1. **Producto no existe en la base de datos**
- El sistema intenta vender un producto cuya referencia no existe en la tabla `products`
- **Solución**: Verificar que todos los productos en el inventario existan antes de vender

### 2. **Stock insuficiente**
- Aunque el sistema debería permitir la venta, podría haber un problema con la actualización del stock
- **Solución**: Verificar que hay suficiente cantidad del producto

### 3. **Datos del cliente incompletos**
- Faltan campos requeridos como nombre, cédula, teléfono, dirección o ciudad
- **Solución**: Asegurarse de que todos los campos del cliente estén completos

### 4. **ID de factura duplicado**
- El sistema ya tiene una venta con el mismo número de factura
- **Solución**: El sistema debería generar un ID único automáticamente

### 5. **Problema de conexión a la base de datos**
- La conexión a la base de datos remota (InfinityFree) puede estar fallando
- **Solución**: Verificar credenciales y conexión

### 6. **Campos requeridos faltantes en la solicitud**
- La solicitud no incluye todos los campos necesarios
- **Solución**: Verificar que se envíen: id, customerInfo, paymentMethod, deliveryType, total, products

## Pasos para Diagnosticar

### Opción 1: Usar el archivo de diagnóstico
He creado un archivo `api/sales_debug.php` que te ayudará a identificar el problema exacto.

**Cómo usarlo:**
1. Abre el navegador y ve a tu aplicación
2. Abre las Herramientas de Desarrollador (F12)
3. Ve a la pestaña "Console"
4. Antes de hacer una venta, ejecuta este código en la consola:

```javascript
// Interceptar la llamada a sales.php y redirigirla a sales_debug.php
const originalFetch = window.fetch;
window.fetch = function(...args) {
    if (args[0] && args[0].includes('api/sales.php')) {
        args[0] = args[0].replace('api/sales.php', 'api/sales_debug.php');
    }
    return originalFetch.apply(this, args);
};
console.log('Diagnóstico activado - la próxima venta mostrará información detallada');
```

5. Intenta hacer una venta
6. Revisa la respuesta en la consola - te mostrará exactamente qué está fallando

### Opción 2: Revisar los logs del servidor
Si tienes acceso a los logs de InfinityFree, revisa los errores PHP recientes.

### Opción 3: Verificar manualmente

1. **Verificar productos en inventario:**
   - Ve a la sección de Inventario
   - Asegúrate de que los productos que intentas vender existen
   - Verifica que tengan stock disponible

2. **Verificar datos del cliente:**
   - Asegúrate de llenar TODOS los campos del cliente:
     - Nombre completo
     - Cédula
     - Teléfono
     - Dirección
     - Ciudad

3. **Verificar conexión a la base de datos:**
   - Abre `config/db.php`
   - Verifica que las credenciales sean correctas:
     - Host: sql308.infinityfree.com
     - Database: if0_40983741_destellodeoro18k1
     - Username: if0_40983741
     - Password: (verificar que sea correcto)

## Soluciones Rápidas

### Si el problema es stock insuficiente:
```sql
-- Actualizar stock de un producto
UPDATE products SET quantity = quantity + 10 WHERE reference = 'REF_PRODUCTO';
```

### Si el problema es un producto faltante:
- Ve a Inventario → Agregar Producto
- Crea el producto con todos sus datos

### Si el problema es ID duplicado:
El sistema debería manejar esto automáticamente. Si persiste, verifica que el campo `id` en la venta sea único.

## Mejora Recomendada

Para obtener mensajes de error más descriptivos, puedo modificar el código para que muestre exactamente qué está fallando. ¿Quieres que implemente esto?

## Próximos Pasos

1. Ejecuta el diagnóstico usando `sales_debug.php`
2. Comparte conmigo el resultado que aparece en la consola
3. Con esa información podré darte una solución exacta

---

**Nota**: El archivo `sales_debug.php` es solo para diagnóstico. Una vez resuelto el problema, podemos eliminarlo.
