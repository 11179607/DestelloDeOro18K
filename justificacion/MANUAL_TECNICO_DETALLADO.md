# MANUAL TÉCNICO Y FUNCIONAL: SISTEMA DE GESTIÓN "DESTELLO DE ORO 18K"

**AUTOR:** Equipo de Desarrollo - Modalidad Emprendimiento  
**PROYECTO:** Sistema Integral de Inventario, Ventas y Finanzas  
**VERSIÓN:** 2.0 (Premium)  

---

## 1. INTRODUCCIÓN
El sistema "Destello de Oro 18K" es una solución tecnológica diseñada específicamente para el sector de la joyería de alta gama. Su objetivo es centralizar la operación diaria, desde la llegada de nueva mercancía hasta el balance financiero mensual, pasando por la gestión de clientes y garantías.

---

## 2. ARQUITECTURA GENERAL DEL SOFTWARE
El software se basa en el modelo **Single Page Application (SPA)**, lo que significa que la experiencia del usuario es fluida y sin recargas. La comunicación entre el navegador y el servidor se realiza a través de peticiones asíncronas (AJAX/Fetch) hacia una capa de servicios en PHP.

---

## 3. ANÁLISIS DETALLADO DEL NÚCLEO: `index.php`
Este es el archivo más extenso y crítico del sistema. Se divide en tres grandes capas:

### 3.1. CAPA DE PRESENTACIÓN (CSS/ESTILOS)
Ubicada entre las líneas **22 y aproximadamente 1980**.  
Define la identidad visual del proyecto:
- **Variables de Diseño (Root):** Configuración de la paleta de colores dorados (#D4AF37) y tipografía Poppins.
- **Animaciones Premium:** Incluye el sistema de "Destellos Dorados" (Línea 353), las animaciones de carga y el sistema de vuelo del avión publicitario (Línea 1901).
- **Reloj Analógico:** Estilización completa del reloj de la marca que aparece en el encabezado.

### 3.2. ESTRUCTURA DE LA INTERFAZ (HTML)
Ubicada entre las líneas **2182 y 3497**.
- **Pantalla de Login:** Sistema de acceso con selección automática de rol y formularios de datos personales.
- **Header:** Contiene el reloj interactivo, el nombre de la empresa y el sistema de animación del avión.
- **Main Content:** Secciones dinámicas que se muestran/ocultan según la navegación:
    - `inventory`: Tabla de productos y control de stock.
    - `sales`: Interfaz de carrito de compras y POS.
    - `expenses`: Registro de egresos.
    - `history`: Panel de tarjetas con métricas financieras.

### 3.3. LÓGICA DE PROGRAMACIÓN (JAVASCRIPT)
Ubicada desde la línea **3498 hasta el final**. A continuación, el detalle de las funciones principales:

| Función | Línea Aprox. | Descripción |
| :--- | :--- | :--- |
| `setupLoginEvents()` | 7952 - 8100 | Gestiona el flujo de entrada, selección de rol y autenticación del usuario. |
| `loadInventoryTable()` | 3600 - 3800 | Petición a la API para traer el stock actual y renderizarlo en la tabla con colores de alerta. |
| `addToCart()` | 4004 - 4058 | Lógica para agregar productos al carrito, verificando stock y aplicando precios (Mayorista o Detal). |
| `processCompleteSale()`| 4070 - 4196 | Corazón del sistema de ventas. Valida datos, aplica fletes, genera número de factura y guarda en BD. |
| `updateSaleSummary()` | 4199 - 4233 | Calcula en tiempo real el subtotal, descuento y flete mientras se arma el carrito. |
| `loadHistoryCards()` | 4271 - 4371 | Procesa toda la data del servidor para generar las tarjetas de resumen financiero. |
| `createProfitHistoryCard()`| 4478 - 4660 | Realiza el análisis de rentabilidad dividiendo ganancias por tipo de canal (Detal/Mayorista). |
| `loadMonthlySummary()` | 5593 - 5676 | Genera las estadísticas del Dashboard principal: Ventas, Gastos, Costos y Utilidad Real. |
| `showInvoice()` | 9457 - 9531 | Genera visualmente la factura profesional para el cliente. |
| `triggerGoldSparkles()`| 9600 - 9683 | Efecto visual de celebración al concretar una venta exitosa. |

---

## 4. COMPONENTES DEL BACKEND Y BASE DE DATOS

### 4.1. BASE DE DATOS: `setup.sql`
Define la inteligencia de datos:
- **Tablas:** `users` (seguridad), `products` (almacén), `sales` (ingresos), `expenses` (egresos), `restocks` (compras), `warranties` (servicio post-venta).
- **Relaciones:** Sistema relacional para que cada ítem vendido esté vinculado a una factura única.

### 4.2. DIRECTORIO `/api` (MICROSERVICIOS)
- **`config/db.php`**: Parametrización del servidor de base de datos.
- **`api/login.php`**: Valida credenciales contra la tabla de usuarios.
- **`api/sales.php`**: El motor más complejo; gestiona transacciones SQL, deducción de inventario y generación de facturas.
- **`api/products.php`**: CRUD de joyería (referencias, imágenes y precios).
- **`api/warranties.php`**: Procesa reclamaciones, manejando la lógica de si se cambia el producto o si hay cobros adicionales.
- **`api/expenses.php`**: Control de flujo de caja para gastos operativos.

---

## 5. JUSTIFICACIÓN DE EMPRENDIMIENTO
Este sistema fue creado bajo la premisa de **Transformación Digital**. Permite a un negocio tradicional de joyería:
1.  **Reducir Errores Humanos:** Mediante cálculos automáticos de utilidad.
2.  **Controlar Pérdidas:** Gracias al seguimiento estricto del Costo de lo Vendido (COGS).
3.  **Mejorar el Servicio:** Con facturación digital inmediata y seguimiento de garantías profesional.

---
*Este documento es propiedad intelectual del proyecto de modalidad emprendimiento "Destello de Oro 18K".*
