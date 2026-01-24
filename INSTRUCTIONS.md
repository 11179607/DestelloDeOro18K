# Guía de Instalación y Conexión

Para poner en marcha el sistema después de la migración, sigue estos pasos:

### 1. Preparar el Servidor Local
Necesitas un servidor que soporte PHP y MySQL. Lo más común es usar **XAMPP**.
- Descarga e instala XAMPP si no lo tienes.
- Abre el **XAMPP Control Panel** e inicia los servicios de **Apache** y **MySQL**.

### 2. Crear y Configurar la Base de Datos
- Abre tu navegador y ve a `http://localhost/phpmyadmin`.
- Haz clic en **"Nueva"** en el menú de la izquierda.
- Escribe `destello_oro` como nombre de la base de datos y haz clic en **"Crear"**.
- Una vez creada, ve a la pestaña **"Importar"**.
- Selecciona el archivo `setup.sql` que se encuentra en la carpeta raíz del proyecto.
- Baja hasta el final y haz clic en **"Importar"** (o "Continuar").

### 3. Verificar Credenciales de Conexión
Abre el archivo `config/db.php` y asegúrate de que los datos coincidan con tu configuración de MySQL. Por defecto en XAMPP son:
- **Host**: `localhost`
- **DB Name**: `destello_oro`
- **User**: `root`
- **Password**: ` ` (vacío)

### 4. Acceder al Sistema
- Asegúrate de que la carpeta del proyecto esté dentro de `C:\xampp\htdocs\DestellodeOro18K`.
- Abre tu navegador y ve a: `http://localhost/DestellodeOro18K/index.php`.

### Credenciales por Defecto
- **Administrador**: usuario: `admin` / contraseña: `admin123`
- **Vendedor**: usuario: `trabajador` / contraseña: `trabajador123`
