# ESPECIFICACIÓN TÉCNICA Y ARQUITECTURA DE SOFTWARE: PROYECTO "DESTELLO DE ORO 18K"

**MODALIDAD:** Sustentación de Práctica / Emprendimiento Tecnológico  
**NIVEL:** Desarrollo Senior / Arquitectura Web  
**FECHA:** 20 de Febrero de 2026  

---

## 1. RESUMEN EJECUTIVO (ABSTRACT)
El sistema **Destello de Oro 18K** no es solo un software de ventas; es un ecosistema digital diseñado bajo el paradigma de **Alta Disponibilidad** y **Experiencia de Usuario Inmersiva**. Este documento expone la ingeniería detrás de la aplicación, detallando la lógica de negocio distribuida, la optimización de recursos en el cliente y la integridad relacional de la persistencia de datos.

---

## 2. ARQUITECTURA DEL SISTEMA (STACK TECNOLÓGICO)

### 2.1. Frontend: High-Performance Single Page Application (SPA)
Se optó por una arquitectura **Vanilla JavaScript (ES6+)** para maximizar el rendimiento al eliminar la sobrecarga (overhead) de frameworks externos.  
- **Motores de Renderizado:** Manipulación directa del DOM para una actualización reactiva de las tablas de inventario y balances financieros.
- **Ecosistema Visual:** Implementación de **SVG Dinámico** para animaciones de hélice en tiempo real y **Canvas/Particles** para efectos de celebración (Sparkles).

### 2.2. Backend: Capa de Servicios RESTful (PHP 8.x)
El backend actúa como un **Middleware de Seguridad** y persistencia:
- **Protocolo de Comunicación:** JSON sobre HTTP FETCH API.
- **Seguridad:** Sanitización de inputs mediante filtrado por expresiones regulares y sentencias preparadas (PDO) para mitigar ataques de SQL Injection.

---

## 3. INGENIERÍA DE ARCHIVOS: ANÁLISIS COMPONENTE POR COMPONENTE

### 3.1. NÚCLEO CENTRAL: `index.php` (El Corazón del Sistema)
Este archivo de más de 10,000 líneas representa la consola central. Su diseño sigue un patrón de **Módulos JS Autocontenidos**.

#### A. Segmento de Estilos y UI (Líneas 22 - 1980)
- **Design System:** Implementación de un sistema de diseño basado en variables CSS (`:root`) para escalabilidad de marca.
- **Keyframes Architecture:** Definición de complejas secuencias de animación para el avión publicitario, diseñadas para evitar el "Layout Thrashing" y mantener los 60 FPS en el navegador.

#### B. Componentes HTML y Esqueleto Estructural (Líneas 2182 - 3497)
- **Modal Engine:** Sistema de capas (Z-index management) para la visualización de facturas, detalles de garantías y auditoría de ventas.
- **Semantic HTML5:** Uso de etiquetas semánticas para asegurar la accesibilidad y el rendimiento del parser del navegador.

#### C. Lógica de Negocio y Controladores JS (Líneas 3498 - 10781)
Aquí es donde ocurre la "magia" del programador estrella:
- **`processCompleteSale()` (Líneas 4070-4196):** Un algoritmo transaccional que valida la integridad del carrito, procesa descuentos, calcula fletes y sincroniza con el servidor.
- **`loadHistoryCards()` (Líneas 4271-4371):** Implementación de **Promesa Concurrente (`Promise.all`)** para traer datos de múltiples APIs simultáneamente, reduciendo el tiempo de carga percibido.
- **`createProfitHistoryCard()` (Líneas 4478-4660):** Lógica actuarial que desglosa la rentabilidad por canal de venta, separando costos operativos de utilidades brutas.
- **`triggerGoldSparkles()` (Líneas 9600-9683):** Sistema de partículas basado en física simple para feedback positivo al usuario.

### 3.2. INFRAESTRUCTURA DE DATOS: `setup.sql`
Diseño de base de datos en **Tercera Forma Normal (3NF)** para garantizar la no redundancia:
- **`warranties`**: Tabla lógica que permite el rastreo histórico de cada pieza de joyería.
- **Triggers y Procedimientos Logicos:** Configurados para mantener la consistencia entre el stock global y las ventas procesadas.

### 3.3. MICROSERVICIOS API (Directorio `/api`)
- **`sales.php`**: Gestiona el flujo de caja. No solo registra la venta, sino que audita quién la hizo y con qué método de pago.
- **`warranties.php`**: Lógica de "Post-Venta Proactiva". Permite cambios de producto calculando automáticamente diferenciales de precio.
- **`pending_sales.php`**: Un controlador de riesgos para ventas que requieren confirmación administrativa antes de impactar el flujo de caja real.

---

## 4. FUNCIONALIDADES AVANZADAS (EL TOQUE "STAR DEVELOPER")

### 4.1. Análisis Financiero en Tiempo Real
El sistema no solo suma y resta; realiza un **análisis de márgenes**. A través de la función `loadMonthlySummary()`, el software computa el CPV (Costo de Productos Vendidos) dinámicamente, permitiendo al dueño del negocio ver su utilidad neta real, libre de gastos operativos y fletes promocionales.

### 4.2. Algoritmo de Animación del Avión
A diferencia de un GIF simple, el avión publicitario está construido con **SVG y CSS Transform**, lo que garantiza que la marca se vea nítida en cualquier resolución (pantallas 4K o móviles) sin consumir memoria excesiva.

### 4.3. Sistemas de Seguridad por Roles
Implementación de un sistema de **Control de Acceso Basado en Roles (RBAC)** que bloquea funciones críticas (como borrar ventas o ver inversión) a nivel de interfaz y a nivel de API, asegurando que un trabajador no pueda acceder a datos de administración.

---

## 5. CONCLUSIÓN TÉCNICA
El desarrollo de **Destello de Oro 18K** se posiciona como una solución de software de nivel empresarial adaptada a un emprendimiento. La combinación de un frontend ligero, un backend seguro y una lógica de negocio financiera precisa, convierte a este proyecto en un referente técnico de cómo la tecnología puede transformar un negocio tradicional en una empresa digitalmente optimizada.

---
**INGENIERÍA DE SOFTWARE | DESTELLO DE ORO 18K**
