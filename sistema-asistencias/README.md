# Sistema de Asistencias

Sistema web centralizado para el control de asistencias **presenciales y remotas**, desarrollado para digitalizar el registro, facilitar la administración y generar información útil para el seguimiento de asistencias.

---

## 📌 Problemática

Actualmente, el registro de asistencia mediante listas físicas presenta diferentes dificultades:

* El registro manual es propenso a errores.
* Es difícil consultar registros históricos.
* La pérdida de listas físicas puede provocar pérdida de información.
* Existe falta de orden al momento de marcar la asistencia.
* Puede existir diferencia entre la hora real de llegada y la hora registrada manualmente.
* Se puede generar aglomeración al momento de registrar o entregar la información al responsable.
* El control de asistencia de las personas que trabajan de manera remota resulta menos eficiente.

---

## 🎯 Objetivos

### Objetivo general

Desarrollar un sistema web que permita digitalizar, centralizar y facilitar la gestión del registro de asistencias presenciales y remotas.

### Objetivos específicos

* Registrar automáticamente la fecha y hora de asistencia.
* Identificar rápidamente a cada persona mediante un código único.
* Evitar errores asociados al registro manual.
* Diferenciar las asistencias presenciales y remotas.
* Facilitar la consulta de registros.
* Permitir al administrador gestionar usuarios.
* Generar reportes de asistencia.
* Registrar tardanzas y permitir asociar justificaciones.

---

# 💡 Propuesta de solución

Desarrollo e implementación de un **Sistema Web Centralizado de Control de Asistencias Multimodal (Presencial y Remoto)**.

El sistema busca automatizar el registro de asistencia, garantizar que la fecha y hora sean obtenidas desde el servidor y facilitar la administración de los registros.

La solución contempla tres componentes principales:

1. Módulo presencial.
2. Módulo remoto.
3. Panel administrativo y reportes.

---

# 🧩 Módulos del sistema

## 1. Módulo presencial

El registro presencial se realiza desde una terminal o computadora ubicada dentro de las instalaciones.

### Flujo de entrada

1. La persona ingresa su código.
2. El sistema busca sus datos.
3. Se verifica que el usuario esté activo.
4. Se muestran sus datos.
5. El sistema obtiene automáticamente la fecha y hora.
6. Se determina si está a tiempo o presenta tardanza.
7. Se registra la entrada.

### Flujo de salida

1. La persona ingresa su código.
2. El sistema busca la asistencia correspondiente al día.
3. Se obtiene la hora actual del servidor.
4. Se registra la salida.

### Características

* Identificación mediante código único.
* Fecha y hora proporcionadas por el servidor.
* Validación de usuario activo.
* Prevención de registros duplicados.
* Registro de modalidad presencial.
* Detección de tardanza.

---

## 2. Módulo remoto

El módulo remoto permitirá registrar asistencia desde fuera de las instalaciones.

### Flujo previsto

1. El administrador crea una sesión de asistencia.
2. El sistema genera un código temporal.
3. El código tiene un tiempo limitado de validez.
4. El participante introduce el código temporal junto con su código personal.
5. El sistema valida la sesión.
6. Se registra la asistencia como remota.

### Características previstas

* Código temporal.
* Tiempo de expiración.
* Control de uso del código.
* Identificación del participante.
* Registro de modalidad remota.

> Este módulo se encuentra pendiente de implementación en el backend.

---

## 3. Panel administrativo

El administrador podrá visualizar y gestionar la información del sistema.

### Gestión de usuarios

Se contempla permitir al administrador:

* Registrar usuarios.
* Editar usuarios.
* Buscar usuarios.
* Activar o desactivar usuarios.
* Asignar una carrera.
* Consultar el código del usuario.
* Definir la modalidad.
* Gestionar roles.

### Información de un usuario

Cada persona tendrá un identificador único.

Ejemplo:

```text
Código:     PRES001
Nombre:     Juan Pérez
Carrera:    Ingeniería de Software con Inteligencia Artificial
Estado:     Activo
Modalidad:  Presencial
```

---

# 📊 Reportes

El sistema contempla reportes administrativos por:

* Día.
* Semana.
* Mes.
* Persona.
* Carrera.
* Modalidad.
* Llegadas tardías.
* Personas sin salida registrada.
* Asistencia presencial.
* Asistencia remota.

También se contempla la exportación de reportes, preferentemente en formato PDF.

> El módulo de reportes se encuentra pendiente de implementación.

---

# 🗄️ Base de datos

La base de datos utiliza **MySQL**.

Las principales tablas del sistema son:

| Tabla            | Descripción                                |
| ---------------- | ------------------------------------------ |
| `carreras`       | Carreras profesionales                     |
| `usuarios`       | Administradores y practicantes             |
| `asistencias`    | Registros de entrada y salida              |
| `sesion_remotas` | Sesiones temporales para asistencia remota |

Además, Laravel utiliza sus propias tablas de soporte para funcionalidades del framework y autenticación.

---

# 🏗️ Arquitectura tecnológica

## Frontend

* HTML
* Tailwind CSS
* JavaScript

> El frontend se encuentra en desarrollo.

## Backend

* Laravel
* PHP

## Base de datos

* MySQL
* XAMPP para el entorno local

## Autenticación

* Laravel Sanctum

## Control de versiones

* Git
* GitHub

## Entorno de desarrollo

* Visual Studio Code

---

# 🚀 Instalación del proyecto

## Requisitos

Antes de comenzar se recomienda tener instalado:

* PHP 8.5 o superior
* Composer
* XAMPP
* Git
* Visual Studio Code

---

## 1. Clonar el repositorio

```bash
git clone https://github.com/RubiPaniagua/sistema-asistencias-CIDPujllay.git
```

Ingresar al proyecto:

```bash
cd sistema-asistencias-CIDPujllay/sistema-asistencias
```

---

## 2. Elegir la rama de trabajo

### Backend

```bash
git checkout feature/backend
```

### Frontend

La rama correspondiente al frontend es:

```bash
git checkout feature/frontend
```

> Si se necesita trabajar con funcionalidades del backend, se debe utilizar la rama `feature/backend` o integrar los cambios correspondientes según el flujo de trabajo del equipo.

---

## 3. Instalar dependencias

Ejecutar:

```bash
composer install
```

---

## 4. Crear el archivo `.env`

Copiar el archivo de ejemplo:

### PowerShell

```powershell
Copy-Item .env.example .env
```

O en CMD:

```cmd
copy .env.example .env
```

---

## 5. Configurar la base de datos

Abrir **XAMPP** y activar:

* Apache
* MySQL

Crear una base de datos llamada:

```text
sistema_asistencias
```

En el archivo `.env` verificar la configuración:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sistema_asistencias
DB_USERNAME=root
DB_PASSWORD=
```

Si la instalación local de MySQL utiliza otra contraseña, modificar `DB_PASSWORD` según corresponda.

---

## 6. Generar la clave de Laravel

Ejecutar:

```bash
php artisan key:generate
```

---

## 7. Crear las tablas y datos de prueba

Ejecutar:

```bash
php artisan migrate --seed
```

Este comando ejecuta las migraciones y posteriormente los seeders.

No es necesario copiar la base de datos de otra computadora.

La estructura se reconstruye mediante las migraciones y los datos iniciales mediante los seeders.

---

## 8. Iniciar el servidor

Ejecutar:

```bash
php artisan serve
```

El proyecto estará disponible normalmente en:

```text
http://127.0.0.1:8000
```

---

# 👤 Datos de prueba

Los seeders crean actualmente los siguientes usuarios:

| Código     | Nombre                    | Rol         | Modalidad  |
| ---------- | ------------------------- | ----------- | ---------- |
| `ADMIN001` | Administrador del Sistema | admin       | —          |
| `PRES001`  | Juan Pérez                | practicante | presencial |
| `REM001`   | María López               | practicante | remoto     |

### Administrador

```text
Email:    admin@sistema.com
Password: password
```

> Estos datos son únicamente para desarrollo y pruebas locales.

---

# 🔌 API

## Convenciones generales

* Zona horaria: `America/Lima`
* Las fechas y horas son generadas por el servidor.
* Las respuestas utilizan JSON.
* Los endpoints se encuentran bajo `/api`.
* La autenticación administrativa utilizará Laravel Sanctum.

---

## 1. Registrar entrada presencial

### Endpoint

```http
POST /api/asistencia/entrada
```

### URL local

```text
http://127.0.0.1:8000/api/asistencia/entrada
```

### Request

```json
{
  "codigo": "PRES001"
}
```

### Respuesta exitosa

```json
{
  "ok": true,
  "nombre": "Juan Pérez",
  "carrera": "Ingeniería de Software con Inteligencia Artificial",
  "hora": "11:34:48"
}
```

### Código no válido

```json
{
  "ok": false,
  "error": "Código no válido"
}
```

---

## 2. Registrar salida presencial

### Endpoint

```http
POST /api/asistencia/salida
```

### URL local

```text
http://127.0.0.1:8000/api/asistencia/salida
```

### Request

```json
{
  "codigo": "PRES001"
}
```

### Respuesta exitosa

```json
{
  "ok": true,
  "nombre": "Juan Pérez",
  "hora": "11:46:05"
}
```

---

# 🧪 Pruebas realizadas

Actualmente se ha probado correctamente el flujo presencial.

### Entrada

```text
PRES001
↓
Usuario encontrado
↓
Fecha y hora obtenidas del servidor
↓
Asistencia registrada
```

### Salida

```text
PRES001
↓
Asistencia del día encontrada
↓
Hora de salida obtenida del servidor
↓
Salida registrada
```

También se comprobó directamente la información almacenada en MySQL mediante Laravel Tinker.

---

# 📋 API planificada

Las siguientes funcionalidades forman parte del contrato definido para el backend y serán implementadas progresivamente.

## Login administrativo

```http
POST /api/login
```

Request:

```json
{
  "email": "admin@sistema.com",
  "password": "password"
}
```

---

## Generar sesión remota

```http
POST /api/sesion-remota
```

Solo disponible para administradores autenticados.

Respuesta prevista:

```json
{
  "ok": true,
  "codigo_temporal": "ABC123",
  "expira_en": "2026-09-17T08:15:00"
}
```

---

## Registrar asistencia remota

```http
POST /api/asistencia/remota
```

Request:

```json
{
  "codigo_temporal": "ABC123",
  "codigo_usuario": "REM001"
}
```

El sistema deberá validar:

* Existencia del código temporal.
* Vigencia del código.
* Que el código no haya sido utilizado.
* Existencia del usuario.
* Que el usuario esté activo.
* Modalidad correspondiente.

---

## Reportes

```http
GET /api/reportes
```

Filtros previstos:

```text
desde
hasta
modalidad
estado
```

Ejemplo:

```text
/api/reportes?desde=2026-09-01&hasta=2026-09-30&modalidad=presencial&estado=tardanza
```

---

## Gestión de usuarios

### Listar

```http
GET /api/usuarios
```

### Ver usuario

```http
GET /api/usuarios/{id}
```

### Crear

```http
POST /api/usuarios
```

### Editar

```http
PUT /api/usuarios/{id}
```

### Eliminar o desactivar

```http
DELETE /api/usuarios/{id}
```

Campos principales:

```text
codigo
nombre
carrera_id
rol
modalidad
activo
email
password
```

> Estas funcionalidades administrativas todavía están pendientes de implementación.

---

# 🔐 Seguridad y configuración

El archivo `.env` contiene configuración local y **no debe subirse al repositorio**.

Cada integrante debe crear su propio archivo `.env`.

El proyecto utiliza `.env.example` como referencia para la configuración.

---

# ⚠️ Comando importante

El siguiente comando:

```bash
php artisan migrate:fresh --seed
```

**elimina todas las tablas existentes y las vuelve a crear.**

Debe utilizarse únicamente cuando se quiera reconstruir completamente la base de datos de desarrollo.

Para una instalación normal se recomienda:

```bash
php artisan migrate --seed
```

---

# 🔄 Comandos útiles

Ver las rutas disponibles:

```bash
php artisan route:list
```

Iniciar Laravel:

```bash
php artisan serve
```

Abrir Tinker:

```bash
php artisan tinker
```

Consultar asistencias:

```php
App\Models\Asistencia::all();
```

Consultar usuarios:

```php
App\Models\Usuario::all();
```

Consultar carreras:

```php
App\Models\Carrera::all();
```

---

# 🌿 Flujo de trabajo Git

Las ramas principales del desarrollo son:

```text
feature/backend
feature/frontend
```

Cada integrante debe trabajar principalmente en su propia rama.

Antes de comenzar a trabajar:

```bash
git pull
```

Después de realizar cambios:

```bash
git status
git add .
git commit -m "descripcion del cambio"
git push
```

Los cambios del backend y frontend deberán integrarse posteriormente mediante Git.

---

# 📌 Estado actual del proyecto

### Backend

* [x] Configuración inicial de Laravel
* [x] Conexión con MySQL
* [x] Migraciones
* [x] Seeders
* [x] Modelo `Usuario`
* [x] Modelo `Carrera`
* [x] Modelo `Asistencia`
* [x] Registro de entrada presencial
* [x] Registro de salida presencial
* [x] Registro automático de fecha y hora
* [x] Validación de usuario activo
* [x] Detección de tardanza
* [x] Persistencia en MySQL

### Pendiente

* [ ] Login administrativo
* [ ] Autenticación con Sanctum
* [ ] Generación de sesiones remotas
* [ ] Registro de asistencia remota
* [ ] CRUD de usuarios
* [ ] Panel administrativo
* [ ] Reportes
* [ ] Exportación PDF
* [ ] Integración completa con frontend

---

# 👥 Equipo

Proyecto desarrollado colaborativamente mediante Git y GitHub.

### Tecnologías principales

```text
Frontend  → HTML / Tailwind CSS / JavaScript
Backend   → Laravel / PHP
Base de datos → MySQL
Control de versiones → Git / GitHub
IDE → Visual Studio Code
```

---

## 📄 Nota

Este README funciona como guía técnica del proyecto y puede actualizarse conforme se implementen nuevos módulos.

#### Correr este comando para las migraciones y la creación de los seeders
php artisan migrate:fresh --seed