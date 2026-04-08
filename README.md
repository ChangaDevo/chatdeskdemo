# ChatDesk — CRM de Calificación de Prospectos con Chat en Vivo

Sistema de atención al cliente con chat en tiempo real, bot de calificación automática y panel de gestión para asesoras. Especializado en trámites de inmigración (USCIS, CRBA, Residencias, Perdones, Ciudadanías, Visa K-1, Visa por Viudez, Ajustes de Estatus).

---

## Requisitos

| Herramienta | Versión mínima |
|-------------|---------------|
| PHP | 8.2+ |
| Composer | 2.x |
| Node.js | 18+ |
| npm | 9+ |
| MySQL | 5.7+ / 8.x |
| XAMPP (u otro servidor local) | Cualquier versión reciente |

---

## Instalación

### 1. Clonar o copiar el proyecto

Coloca la carpeta del proyecto dentro de `htdocs` de XAMPP:

```
C:\xampp\htdocs\chatdesk\
```

### 2. Instalar dependencias

Abre una terminal dentro de la carpeta del proyecto y ejecuta:

```bash
composer install
npm install
```

### 3. Configurar el archivo `.env`

Copia el archivo de ejemplo:

```bash
cp .env.example .env
```

Edita `.env` con los valores de tu entorno:

```env
APP_NAME=ChatDesk
APP_URL=http://localhost/chatdesk/public

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=chatdesk
DB_USERNAME=root
DB_PASSWORD=

BROADCAST_CONNECTION=reverb

REVERB_APP_ID=chatdesk_app
REVERB_APP_KEY=chatdesk_key
REVERB_APP_SECRET=chatdesk_secret
REVERB_HOST=127.0.0.1
REVERB_PORT=8080
REVERB_SCHEME=http

VITE_REVERB_APP_KEY="${REVERB_APP_KEY}"
VITE_REVERB_HOST="${REVERB_HOST}"
VITE_REVERB_PORT="${REVERB_PORT}"
VITE_REVERB_SCHEME="${REVERB_SCHEME}"
```

> **Nota:** Si tu MySQL tiene contraseña, agrégala en `DB_PASSWORD`.

### 4. Generar clave de la aplicación

```bash
php artisan key:generate
```

### 5. Crear la base de datos

Abre phpMyAdmin o tu cliente MySQL y crea la base de datos:

```sql
CREATE DATABASE chatdesk CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 6. Ejecutar migraciones y datos de prueba

```bash
php artisan migrate --seed
```

Esto crea todas las tablas y carga **8 prospectos de demo** con conversaciones de ejemplo.

### 7. Compilar los assets

```bash
npm run build
```

---

## Iniciar la aplicación

Necesitas tener **dos procesos corriendo** al mismo tiempo. Abre dos terminales dentro de la carpeta del proyecto:

**Terminal 1 — Servidor WebSocket (chat en tiempo real):**

```bash
php artisan reverb:start
```

> Mantén esta terminal abierta mientras usas el sistema.

**Terminal 2 — Solo si NO usas XAMPP Apache:**

```bash
php artisan serve
```

> Si ya tienes XAMPP Apache corriendo, no necesitas este comando. Accede directamente por el navegador.

---

## Acceso

| URL | Descripción |
|-----|-------------|
| `http://localhost/chatdesk/public` | Panel de administración |
| `http://localhost/chatdesk/public/widget-demo` | Demo del chat widget embebible |

### Credenciales de prueba

| Email | Contraseña | Rol |
|-------|-----------|-----|
| `admin@themesbrand.com` | `12345678` | Administrador |
| `ana@chatdesk.com` | `password` | Asesora |
| `maria@chatdesk.com` | `password` | Asesora |

---

## Flujo del demo (para presentaciones)

1. Abre **Widget Demo** → el chat aparece automáticamente a los 3 segundos
2. Ingresa nombre y teléfono → el bot de bienvenida responde
3. Escribe un trámite (ej: *"necesito mi residencia"*, *"quiero ajuste de estatus"*, *"visa de prometido"*)
4. El bot califica el caso en 2-3 mensajes automáticamente
5. Abre el **Dashboard** → verás el nuevo prospecto con score de IA
6. Ve a **Chat en Vivo** → selecciona el prospecto → responde como asesora
7. Ve a **Prospectos** → cambia el estado o presiona **Convertir a Cliente**

> **↺ Nueva sesión:** En la barra de navegación del Widget Demo hay un botón para reiniciar el chat y hacer una nueva prueba sin abrir el inspector del navegador.

---

## Trámites que califica el bot

El sistema detecta automáticamente si el caso corresponde a alguno de estos trámites:

- USCIS (trámites generales)
- CRBA (Ciudadanía por Nacimiento en el Extranjero)
- Residencia Permanente / Green Card
- Perdones de Inadmisibilidad (Waivers / I-601A)
- Ciudadanía / Naturalización
- Visa de Prometido/a (K-1)
- Visa por Viudez
- Ajuste de Estatus (I-485)

Si el caso **no corresponde** a ninguno de estos trámites, el bot lo descarta cortésmente y el prospecto se marca como **Descartado** en el panel.

---

## Estructura del proyecto

```
chatdesk/
├── app/
│   ├── Http/Controllers/
│   │   ├── DashboardController.php    # Stats y actividad reciente
│   │   ├── ProspectController.php     # Gestión de prospectos
│   │   └── ChatController.php         # Chat en vivo + Widget API + Bot
│   ├── Events/
│   │   ├── NewMessage.php             # Broadcast de nuevos mensajes
│   │   └── ProspectStatusChanged.php  # Broadcast de cambios de estado
│   └── Models/
│       ├── Prospect.php
│       └── Message.php
├── resources/views/
│   ├── dashboard.blade.php            # Panel principal con gráficas
│   ├── prospects/index.blade.php      # Lista y gestión de prospectos
│   ├── chat/index.blade.php           # Panel de chat en tiempo real
│   └── widget/demo.blade.php          # Página demo con chat flotante
├── routes/web.php                     # Rutas web y API del widget
└── database/
    ├── migrations/                    # Tablas: users, prospects, messages
    └── seeders/DemoSeeder.php         # Datos de demostración
```

---

## Stack tecnológico

- **Backend:** Laravel 11 + PHP 8.2
- **Base de datos:** MySQL con Eloquent ORM
- **WebSockets:** Laravel Reverb (tiempo real)
- **Frontend:** Bootstrap 5.3 (WebAdmin template) + Vanilla JS
- **Iconos:** Boxicons CDN + Material Design Icons CDN
- **Bot:** Lógica de calificación integrada (preparado para conectar n8n / OpenAI)

---

## Solución de problemas comunes

**Los iconos no se muestran**
→ Verifica que tienes conexión a internet (se cargan desde CDN).

**Error 419 al enviar mensajes**
→ Asegúrate de que `SESSION_DRIVER=file` en `.env` y que la carpeta `storage/` tiene permisos de escritura.

**El chat en tiempo real no funciona**
→ Verifica que `php artisan reverb:start` esté corriendo en una terminal separada.

**La página muestra error 500**
→ Ejecuta `php artisan optimize:clear` y revisa el archivo `storage/logs/laravel.log`.

**Quiero reiniciar los datos de demo**

```bash
php artisan migrate:fresh --seed
```
