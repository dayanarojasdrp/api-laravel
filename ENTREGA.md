# Entrega del Trabajo de Diploma

## Datos del estudiante

- Nombre y apellidos: PENDIENTE_COMPLETAR
- Carrera: PENDIENTE_COMPLETAR
- Correo institucional: PENDIENTE_COMPLETAR

## Titulo del Trabajo de Diploma

PENDIENTE_COMPLETAR

## Proyecto entregado

Backend API desarrollado con Laravel para la gestion academica de nombramientos, ratificaciones, desnombramientos, documentos, roles, estudiantes, profesores, PPA, alumnos ayudantes, planes de estudio, indicadores y trazabilidad de acciones.

## Tecnologias utilizadas

- PHP 8.2 o superior
- Laravel 11
- Composer
- MySQL o MariaDB
- Laravel Eloquent ORM
- Laravel Sanctum
- DomPDF
- PHPWord
- PhpSpreadsheet

## Requisitos previos

- PHP 8.2 o superior
- Composer 2.x
- MySQL o MariaDB
- XAMPP recomendado en Windows
- Git
- Postman, Insomnia o curl para probar la API

## Instalacion

Desde la raiz del proyecto:

```bash
composer install
cp .env.example .env
php artisan key:generate
```

En Windows PowerShell:

```powershell
composer install
Copy-Item .env.example .env
php artisan key:generate
```

Configurar la base de datos en `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=api_academica
DB_USERNAME=root
DB_PASSWORD=
```

En Windows con XAMPP, crear la base de datos `api_academica` desde phpMyAdmin antes de ejecutar las migraciones.

## Preparacion de la base de datos

Ejecutar:

```bash
php artisan optimize:clear
php artisan migrate:fresh --seed
php artisan storage:link
```

Si no se desea borrar la base de datos:

```bash
php artisan migrate
php artisan db:seed
php artisan storage:link
```

## Ejecucion

Levantar la API:

```bash
php artisan serve
```

Por defecto queda disponible en:

```txt
http://127.0.0.1:8000
```

Endpoint de prueba:

```txt
GET http://127.0.0.1:8000/api/provincia
```

## Credenciales de prueba

El login de esta API depende de una API externa de usuarios configurada en:

```env
USERS_API_URL=http://127.0.0.1:8001/api
```

Si se va a probar autenticacion, debe estar levantada esa API externa y deben usarse usuarios validos de ese servicio.

Si no se incluye o no se levanta la API externa de usuarios, los endpoints que no dependan del login pueden probarse normalmente, pero `POST /api/login` puede responder `503`.

## Observaciones relevantes

- No se incluyen dependencias descargadas automaticamente como `vendor/`.
- No se debe subir el archivo `.env`; se incluye `.env.example` como plantilla.
- No se deben subir carpetas de IDE como `.idea/` ni archivos temporales.
- El manual detallado de instalacion esta en `MANUAL_INSTALACION.md`.
- Los archivos de dependencias PHP requeridos para reconstruir el entorno son `composer.json` y `composer.lock`.

