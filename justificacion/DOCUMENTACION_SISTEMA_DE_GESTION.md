# PROYECTO: SISTEMA DE GESTIÓN PROFESIONAL "DESTELLO DE ORO 18K"

**MODALIDAD:** Emprendimiento / Práctica Profesional  
**FECHA:** Febrero 20, 2026  
**ESTADO:** Versión Final de Implementación  

---

## 1. INTRODUCCIÓN

El presente documento detalla la justificación técnica y funcional del sistema de gestión desarrollado para la empresa **Destello de Oro 18K**. Este software ha sido diseñado como una Single Page Application (SPA) robusta, orientada a optimizar el control de inventarios, la gestión de ventas, el seguimiento de garantías y el análisis financiero en tiempo real.

El objetivo principal es proporcionar una herramienta tecnológica que permita al administrador y a sus trabajadores llevar un registro exacto de cada movimiento económico, asegurando la trazabilidad de los productos y la rentabilidad del negocio bajo un entorno seguro y visualmente atractivo.

---

## 2. ARQUITECTURA DEL SISTEMA

El sistema utiliza una arquitectura basada en tecnologías web modernas y el patrón de diseño Cliente-Servidor:

*   **Frontend:** Desarrollado en **HTML5, CSS3 (Vanilla) y JavaScript (ES6+)**. Implementa una interfaz dinámica con animaciones personalizadas (SVG) y una experiencia de usuario fluida sin recargas de página.
*   **Backend:** Construido con **PHP 8.x**, sirviendo como una capa de APIs REST que procesan las solicitudes del cliente y se comunican con el servidor de datos.
*   **Base de Datos:** Motor **MySQL/MariaDB**, encargado del almacenamiento persistente de productos, usuarios, ventas, gastos y registros de auditoría.
*   **Seguridad:** Implementación de sesiones seguras, validación de roles de usuario (Administrador/Trabajador) y protección de endpoints mediante validaciones en el servidor.

---

## 3. ESTRUCTURA DE ARCHIVOS Y FUNCIONALIDADES

### 3.1. Archivo Principal: `index.php`
Es el núcleo del sistema. Contiene tanto la estructura visual como la lógica de interacción (JavaScript) y el diseño (CSS).
*   **Gestión de Estados:** Maneja la navegación entre secciones (Inventario, Ventas, Gastos, Historial, etc.).
*   **Lógica de Negocio:** Cálculos de precios, descuentos, comisiones y gestión del carrito de compras.
*   **Interfaz Dinámica:** Incluye el reloj analógico/digital y la animación del avión publicitario.

### 3.2. Configuración y Base de Datos
*   **`config/db.php`**: Gestiona la conexión PDO segura con la base de datos centralizada.
*   **`setup.sql`**: Contiene la definición estructural de todas las tablas e índices necesarios para el funcionamiento inicial del sistema.

### 3.3. Capa de API (Directorio `/api`)
Cada archivo PHP en esta carpeta representa una funcionalidad específica del backend:
*   **`login.php` / `logout.php`**: Control de acceso y cierre de sesión seguro.
*   **`sales.php`**: Procesa la creación, modificación y eliminación de ventas. Maneja la lógica de pagos pendientes y facturación.
*   **`products.php`**: Gestión completa del inventario (CRUD: Crear, Leer, Actualizar, Borrar).
*   **`expenses.php`**: Registro de egresos administrativos y operativos.
*   **`restocks.php`**: Control de nuevos surtidos de mercancía para aumentar el stock.
*   **`warranties.php`**: Uno de los módulos más complejos; gestiona el proceso de cambio de productos, costos de envío asumidos y ajustes de valor adicional.
*   **`pending_sales.php`**: Filtro especializado para el seguimiento de ventas que aún no han sido pagadas totalmente.
*   **`forgot_password.php` / `reset_password.php`**: Sistema de recuperación de cuentas mediante correo electrónico (PHPMailer).

---

## 4. FUNCIONALIDADES DESTACADAS PARA EMPRENDIMIENTO

### A. Control Financiero Inteligente
El sistema genera automáticamente un **Dashboard de Ganancias Reales**, calculando:
`Ganancias = (Ventas Totales - Costo de Inventario - Gastos Operativos - Envíos Gratis)`.
Esto permite al emprendedor conocer su liquidez real en cualquier momento del mes.

### B. Gestión de Garantías y Fidelización
A diferencia de sistemas genéricos, este software incluye un flujo de garantías que permite registrar si el cliente paga un excedente o si la empresa asume el costo de envío, reflejándolo automáticamente en las estadísticas financieras.

### C. Experiencia de Usuario Premium
Se han implementado micro-animaciones (efecto de destellos, avión publicitario realista) que elevan la percepción de marca del sistema, haciéndolo sentir como un software de alta gama.

### D. Seguridad por Roles
El sistema distingue entre el **Administrador** (acceso total, eliminación de registros, reportes de inversión) y el **Trabajador** (ventas y consulta de stock), protegiendo la información sensible del negocio.

---

## 5. ESQUEMA DE BASE DE DATOS (TABLAS PRINCIPALES)

1.  **`users`**: Almacena credenciales, roles y datos personales.
2.  **`products`**: Registro maestro de inventario con precios de compra, detal y mayorista.
3.  **`sales`**: Cabecera de facturación con totales, métodos de pago y datos del cliente.
4.  **`sale_items`**: Detalle de productos vendidos en cada factura.
5.  **`expenses`**: Bitácora de gastos realizados.
6.  **`warranties`**: Historial de reclamaciones y soluciones técnicas.
7.  **`restocks`**: Registro de abastecimiento de mercancía.

---

## 6. CONCLUSIÓN

El sistema desarrollado para **Destello de Oro 18K** representa una solución tecnológica integral que cumple con los requerimientos de un entorno de emprendimiento real. Su arquitectura escalable permite futuras expansiones, mientras que su enfoque en el control detallado de costos asegura una gestión financiera saludable para el negocio.

---
*Este documento constituye la base documental técnica para la sustentación del proyecto.*
