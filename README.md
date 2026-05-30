# SistemaPruebas Backend

Este repositorio contiene el backend de **SistemaPruebas** desarrollado con **Laravel**, orientado como API RESTful para ser consumido por aplicaciones frontend (por ejemplo, Angular).

---

## Requisitos

- **PHP** >= 8.2
- **Composer** (https://getcomposer.org/)
- **MySQL** o MariaDB (u otro motor compatible)
- **Extensiones PHP**: OpenSSL, PDO, Mbstring, Tokenizer, XML, Ctype, JSON, BCMath, Fileinfo
- Opcional (¡pero recomendado para desarrollo!):  
  - **Laravel Installer** (`laravel -v` para verificar)
  - **Git**  
  - Gestor local como XAMPP, WAMP, Laragon, Valet, etc.

---

## Instalación

1. **Clona el repositorio**
    ```bash
    git clone https://github.com/TU_USUARIO/TU_REPO.git
    cd TU_REPO
    ```

2. **Instala dependencias Composer**
    ```bash
    composer install
    ```

3. **Copia y configura el archivo .env**
    ```bash
    cp .env.example .env
    ```
    - Abre y configura tus variables de conexión a base de datos y tus llaves (APP_KEY, etc).

4. **Genera la clave de aplicación**
    ```bash
    php artisan key:generate
    ```

5. **Configura base de datos**
    - Crea tu base de datos en MySQL.
    - Edita `.env` con el nombre, usuario y contraseña de la base de datos.

6. **Ejecuta migraciones**
    ```bash
    php artisan migrate
    ```

7. **(Opcional) Seeders**
    ```bash
    php artisan db:seed
    ```

8. **Levanta el servidor local**
    ```bash
    php artisan serve
    ```
    El API estará disponible por defecto en `http://localhost:8000`

---

## Endpoints de la API

- Autenticación (`/api/login`, `/api/register`, `/api/logout`)
- Gestión de productos (`/api/products`)
- Gestión de clientes, ventas, reportes, inventario
- Todas las rutas importantes están documentadas en `routes/api.php`

> **NOTA:**  
> Todos los endpoints (excepto login y register) requieren autenticación vía token Bearer (Laravel Sanctum).

---

## CORS

La API permite peticiones CORS para ser consumida desde tu frontend (ej: Angular en `http://localhost:4200`).  
Para ambiente de producción, *restringe los orígenes permitidos* editando `config/cors.php`.

---

## Herramientas útiles

- **phpMyAdmin** o **MySQL Workbench** para administración visual de la base de datos.
- Variables de entorno en `.env` para configurar logins, puertos, credenciales de servicios externos, etc.

---

## Comandos Laravel útiles

- `php artisan serve`      → Servidor local
- `php artisan migrate`    → Ejecutar migraciones
- `php artisan db:seed`    → Poblar base de datos dummy
- `php artisan tinker`     → Consola interactiva

---

## Recursos

- [Documentación oficial de Laravel](https://laravel.com/docs)
- [Documentación de Composer](https://getcomposer.org/doc/)
- Si necesitas regenerar clases autoload:
    ```bash
    composer dump-autoload
    ```

---

## Créditos

Desarrollado por [TUS DATOS/EQUIPO].

---