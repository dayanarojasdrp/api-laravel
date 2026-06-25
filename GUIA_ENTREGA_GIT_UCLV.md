# Guia para preparar la entrega en el Git de la UCLV

Segun las instrucciones del repositorio de Trabajos de Diploma, para este proyecto debes entregar una carpeta personal con:

```txt
CARRERA/apellido1_apellido2_nombre/
├── informe.pdf
├── ENTREGA.md
└── backend/
```

Si tu carrera es Ingenieria Informatica, la carpeta probablemente debe ir dentro de:

```txt
II/apellido1_apellido2_nombre/
```

## Lo que falta fuera de este repo

No encontre el informe final en PDF dentro de este proyecto. Debes conseguir el documento de tesis ya exportado a PDF y ponerlo como:

```txt
informe.pdf
```

## Lo que ya prepare

En este proyecto ya quedaron estos documentos utiles para la entrega:

```txt
ENTREGA.md
MANUAL_INSTALACION.md
README.md
```

Antes de entregar, abre `ENTREGA.md` y completa:

- Nombre y apellidos.
- Carrera.
- Correo institucional.
- Titulo exacto del Trabajo de Diploma.

## Que copiar como codigo fuente

Copia el proyecto de la API dentro de la carpeta:

```txt
CARRERA/apellido1_apellido2_nombre/backend/
```

Incluye estos archivos y carpetas:

```txt
app/
bootstrap/
config/
database/
public/
resources/
routes/
storage/
tests/
.editorconfig
.env.example
.gitattributes
.gitignore
artisan
composer.json
composer.lock
ENTREGA.md
MANUAL_INSTALACION.md
phpunit.xml
README.md
```

## Que NO copiar

No copies:

```txt
.git/
.env
.idea/
.vscode/
vendor/
node_modules/
public/build/
public/hot
public/storage
.phpunit.result.cache
*.log
```

Tampoco copies `backend-api.sql` si sigue vacio, porque ahora mismo pesa 0 bytes y no aporta nada a la entrega.

## Sobre el archivo SQL

El proyecto ya tiene migraciones y seeders en `database/`, por lo que no es obligatorio entregar un `.sql` para reconstruir la base de datos.

Solo incluye un SQL si realmente contiene datos necesarios que no puedan generarse con:

```bash
php artisan migrate:fresh --seed
```

## Comandos para preparar la entrega

Dentro del repositorio de la UCLV:

```bash
git checkout main
git pull
git checkout -b apellido1_apellido2_nombre
mkdir -p CARRERA/apellido1_apellido2_nombre/backend
```

Luego copia:

```txt
informe.pdf
ENTREGA.md
backend/
```

Revisa antes de subir:

```bash
git status
```

Confirma que no aparezcan:

```txt
vendor/
.env
.idea/
node_modules/
```

Despues:

```bash
git add .
git commit -m "Entrega Trabajo de Diploma apellido1_apellido2_nombre"
git push -u origin apellido1_apellido2_nombre
```

Finalmente, crea el Merge Request en GitLab UCLV desde tu rama hacia `main`.

