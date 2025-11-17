---

## 🚗 Sistema de Gestión de Flotas  

`````markdown
<div align="center">

### Control inteligente de activos vehiculares para organizaciones modernas

![Laravel](https://img.shields.io/badge/Laravel-11-red?logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-8.3-blue?logo=php)
![Filament](https://img.shields.io/badge/Filament-3.x-purple)
![Docker](https://img.shields.io/badge/Docker-Compose-blue?logo=docker)
![PostgreSQL](https://img.shields.io/badge/PostgreSQL-15-blue?logo=postgresql)
![Internal](https://img.shields.io/badge/License-Internal-yellow)

**Levanta TODO el entorno completo con un solo comando:**

> 🟢 **Inicio rápido: Ejecuta en una sola línea**
```bash
./init.sh


WSL2 + Docker + Laravel + Filament = máxima velocidad y experiencia de desarrollo.

</div>

---

## ✨ Características principales

✔ Solicitudes de vehículos
✔ Aprobaciones por roles y departamentos
✔ Asignaciones y devoluciones con control
✔ Mantenimiento preventivo y correctivo
✔ Panel de administración con **UI profesional Filament**
✔ Soporte para posiciones y mapas (PostGIS)

---

## 🚀 Arranque express

Ejecuta este comando en WSL Ubuntu:

```bash
./init.sh
```

Incluye automáticamente:

| Tarea                  | Estado |
| ---------------------- | :----: |
| Build Docker completo  |    ✔   |
| Composer + NPM install |    ✔   |
| Migraciones + Seeders  |    ✔   |
| Permisos y links       |    ✔   |
| Servicios levantados   |   🚀   |

---

## 🌐 Acceso

| Módulo          | URL                                                        |
| --------------- | ---------------------------------------------------------- |
| Sitio principal | [http://localhost:8000](http://localhost:8000)             |
| Filament Admin  | [http://localhost:8000/admin](http://localhost:8000/admin) |

Ejemplo de credenciales (según seed):

```bash
admin@example.com / password
```

---

## 🧱 Tecnologías

| Área          | Stack                    |
| ------------- | ------------------------ |
| Backend       | Laravel 11 + PHP-FPM 8.3 |
| UI Admin      | Filament V3              |
| Frontend      | Vite + TailwindCSS       |
| Base de datos | PostgreSQL 15 + PostGIS  |
| Webserver     | Nginx                    |
| Contenedores  | Docker Compose           |
| SO óptimo     | WSL2 Ubuntu              |

---

## 🧩 Arquitectura del sistema

```
┌───────────┐     FastCGI      ┌─────────────┐        ┌────────────────┐
│   Nginx   │ → (app:9000) →  │ PHP-FPM 8.3 │  ⇆ DB  │ PostGIS 15     │
│:8000      │                  │ Laravel     │        │ Geodata Ready  │
└───────────┘                  └─────────────┘        └────────────────┘
```

---

## 📦 Estructura del repositorio

```
gestion_flotas/
├─ app/
├─ routes/
├─ public/
├─ resources/
├─ docker-compose.yml
├─ Dockerfile
├─ init.sh
└─ docker/nginx/default.conf
```

---

## 🗄 Modelo de datos (versión inicial)

```
Usuarios (roles/permisos)
    │ 1:N
    ▼
Solicitudes ── 1:1 ── Asignaciones ── 1:1 ── Devoluciones
    │
    │ N:1
    ▼
Vehículos ── 1:N ── Mantenimientos
```

---

## 🔧 Variables de entorno .env

```dotenv
APP_URL=http://localhost:8000

DB_CONNECTION=pgsql
DB_HOST=db
DB_PORT=5432
DB_DATABASE=gestion_flotas
DB_USERNAME=postgres
DB_PASSWORD=postgres
```

---

## 🧪 Troubleshooting

| Problema                    | Solución                                                 |
| --------------------------- | -------------------------------------------------------- |
| vendor/autoload.php missing | `composer install` dentro del contenedor                 |
| Git ownership warning       | `git config --global --add safe.directory /var/www/html` |
| Nginx 502                   | `docker compose restart web`                             |
| Cambios no reflejados       | `docker compose down -v && ./init.sh`                    |

---

## 🛠 Comandos útiles

| Acción               | Comando                        |
| -------------------- | ------------------------------ |
| Levantar servicios   | `docker compose up -d`         |
| Detener servicios    | `docker compose down`          |
| Ingresar a la app    | `docker compose exec app bash` |
| Logs en tiempo real  | `docker compose logs -f app`   |
| Frontend en caliente | `npm run dev`                  |

---

## 🔮 Roadmap

* [ ] GPS Tracking en tiempo real
* [ ] Dashboard KPIs para vehículos
* [ ] API Mobile con tokenización segura
* [ ] Alertas de mantenimiento
* [ ] Integración IoT

---

## 🤝 Cómo contribuir

```bash
git checkout -b feature/nueva-funcionalidad
```

* Código limpio estilo PSR-12
* Documentar cambios funcionales
* Pull Request hacia `main`

---

## 📄 Licencia

Uso interno. No distribuir sin autorización.

---

<div align="center">

Hecho con ❤️ usando Laravel + Docker
Optimizando la gestión vehicular institucional

</div>



