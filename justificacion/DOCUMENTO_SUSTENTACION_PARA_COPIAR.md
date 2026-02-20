# DOCUMENTO COMPLETO DE SUSTENTACIÓN: PROYECTO DESTELLO DE ORO 18K

**INDICACIONES:** Si el archivo Word (.doc) tiene problemas de apertura, copia todo el contenido de este archivo (del principio al fin) y pégalo en un documento nuevo de Word. Luego aplica los formatos de títulos y negritas.

---

## PORTADA
**SISTEMA INTEGRAL DE GESTIÓN DE INVENTARIO Y CONTROL FINANCIERO "DESTELLO DE ORO 18K"**

**MODALIDAD:** Informe Final de Práctica Profesional - Emprendimiento  
**AUTOR:** [Tu Nombre Aquí]  
**FECHA:** 20 de Febrero de 2026  

---

## 1. INTRODUCCIÓN (Explicación Extendida)
El presente proyecto nace de una necesidad latente en el sector comercial de la joyería fina: la transición de métodos de gestión analógicos (libretas, facturas manuales) a un ecosistema digital centralizado. "Destello de Oro 18K" no es simplemente una base de datos; es una herramienta de software diseñada para blindar la operación del negocio contra errores humanos y falta de información estadística.

En el mercado actual, un emprendimiento de joyería enfrenta retos únicos, como la fluctuación de costos de materia prima, la necesidad de un servicio post-venta riguroso (garantías) y la gestión de diferentes canales de precio (mayorista y detal). Este sistema aborda integralmente estas variables, permitiendo que la administración se centre en la estrategia de ventas mientras el software garantiza que cada peso invertido y cada gramo de oro vendido estén debidamente registrados.

---

## 2. JUSTIFICACIÓN DEL PROYECTO (Análisis Profundo)
La relevancia de este proyecto se fundamenta en los siguientes pilares de eficiencia empresarial:

### A. Trazabilidad Operativa
Sin un sistema digital, rastrear el origen de una venta o el estado de una garantía de hace seis meses es casi imposible. El software permite una auditoría inmediata: quién vendió, a qué precio, qué productos exactos salieron del stock y si el cliente tiene pagos pendientes.

### B. Análisis de Ganancia Real (Algoritmo Financiero)
Muchos emprendedores cometen el error de considerar el "Total Vendido" como ganancia. Este proyecto implementa una lógica avanzada de **Costo de Productos Vendidos (CPV)**. El sistema sabe cuánto costó cada joya al ser adquirida y resta ese valor, junto con los gastos de administración y los fletes, para entregar una cifra de **Utilidad Neta**. Esto permite que el dueño del negocio reinvierta con seguridad, sabiendo exactamente qué porcentaje de su dinero es capital y qué porcentaje es ganancia.

### C. Digitalización de la Marca
La factura generada por el sistema no solo sirve para el control interno; es una pieza de marketing. Incluye términos de garantía, datos de contacto profesional y una estructura limpia que eleva la percepción de calidad del cliente hacia la empresa joyera.

---

## 3. MARCO TÉCNICO Y TECNOLÓGICO (Definiciones Senior)

*   **HTML5 & CSS3:** Se utiliza la última especificación de la web para la arquitectura visual. El CSS no es solo para "pintar"; se implementa un sistema de diseño responsivo basado en Grid y Flexbox que permite que el sistema funcione en cualquier dispositivo.
*   **JavaScript (ES6):** El lenguaje del lado del cliente. Se ha programado bajo el paradigma de **Programación Orientada a Eventos**. Esto permite que el carrito de compras se actualice al instante sin que la pantalla parpadee o se recargue.
*   **PHP (Versión 8.x):** El motor del servidor. Se utiliza PHP por su robustez en el manejo de sesiones y su capacidad nativa para comunicarse con bases de datos MySQL.
*   **MySQL & PDO:** Se implementa **PDO (PHP Data Objects)**, que es una capa de seguridad que previene inyecciones SQL, asegurando que los datos de los clientes estén protegidos contra ataques externos.

---

## 4. DESCRIPCIÓ DE ARCHIVOS Y FUNCIONALIDADES (Línea por Línea)

### 4.1. Análisis del index.php (Núcleo de la Aplicación)
Este archivo es una maravilla de la ingeniería de software compacta. Contiene tres capas fundamentales:

1.  **Capa CSS (Líneas 22 - 1,980):** Define la estética "Premium Deep Gold". Se incluyen animaciones personalizadas como `@keyframes planeFly` para publicidad dinámica y efectos de desenfoque (`backdrop-filter`) para los modales de seguridad.
2.  **Capa de Estructura (Líneas 2,182 - 3,497):** Contiene los contenedores dinámicos del sistema. Aquí se encuentra la lógica de los "modales", que son ventanas emergentes que no requieren abrir nuevas pestañas, manteniendo al usuario enfocado en su tarea.
3.  **Capa JavaScript (Líneas 3,498 - 10,781):** Aquí reside la inteligencia:
    *   `processCompleteSale()`: Valida que el dinero recibido coincida con el total, resta las unidades del inventario y dispara la creación de la factura digital.
    *   `loadMonthlySummary()`: La función más importante para el administrador. Realiza peticiones asíncronas para sumar ventas y restar gastos, entregando el balance general del mes en segundos.
    *   `triggerGoldSparkles()`: Una función diseñada para generar feedback emocional positivo en el trabajador tras realizar una venta, motivando el cierre de negocios.

### 4.2. Análisis de Microservicios (Carpeta /api)
*   `api/sales.php`: Gestiona el flujo económico. No permite registros incompletos, asegurando la integridad de la base de datos.
*   `api/warranties.php`: Controla las reclamaciones. Si un cliente trae una joya por garantía, el sistema registra el motivo (rayon, oxidación, etc.) y permite al administrador ver cuántas garantías está perdiendo el negocio al mes.
*   `api/products.php`: Centraliza el inventario. Permite actualizar fotos y precios de forma masiva.

---

## 5. CONCLUSIÓN
El sistema de gestión "Destello de Oro 18K" es una pieza de software de nivel profesional que cumple con los objetivos de eficiencia, seguridad y digitalización requeridos para una sustantación de práctica profesional. Es un proyecto escalable, seguro y estéticamente superior, diseñado para el éxito del emprendimiento.
