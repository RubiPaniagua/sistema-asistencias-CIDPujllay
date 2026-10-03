# API — Sistema de Asistencias
## Contrato Backend ↔ Frontend

> Documento de referencia para integrar frontend y backend.  
> Este contrato refleja el comportamiento actual del backend y debe actualizarse cuando cambie una ruta, un campo o una respuesta.
  
---

## 0. Convenciones generales

- Base URL local: `http://127.0.0.1:8000`
- Prefijo API: `/api`
- Zona horaria: `America/Lima`
- Fechas/horas: formato ISO 8601 cuando corresponda.
- Autenticación admin: Laravel Sanctum.
- Header para rutas protegidas:

```http
Authorization: Bearer <token>
Accept: application/json
```

- Para requests JSON:

```http
Content-Type: application/json
Accept: application/json
```

- Las rutas de marcación de asistencia son públicas:
  - `POST /api/asistencia/entrada`
  - `POST /api/asistencia/salida`
  - `POST /api/asistencia/remoto`

- Las rutas de administración, usuarios, reportes y generación de sesión remota requieren token.
- El identificador funcional del practicante es `dni`.
- El campo interno `id` de Laravel se mantiene para relaciones y claves foráneas.

### Formatos de intercambio

| Caso | Formato |
|---|---|
| API normal | JSON |
| API protegida | JSON + Bearer Token |
| Subida de archivos | `multipart/form-data` |
| Descarga Excel/PDF | respuesta binaria |
| Formularios Blade tradicionales | formulario HTML |

---

# 1. Autenticación de administrador

## 1.1 Login

`POST /api/login`

### Request

```json
{
  "email": "admin@sistema.com",
  "password": "password"
}
```

### Response 200

```json
{
  "ok": true,
  "token": "1|abcdef123456...",
  "nombre": "Admin General"
}
```

### Errores posibles

- `401`: credenciales inválidas.
- `422`: campos inválidos o faltantes.

---

## 1.2 Validar token / obtener admin autenticado

`GET /api/me`

### Headers

```http
Authorization: Bearer <token>
Accept: application/json
```

### Response 200

```json
{
  "ok": true,
  "nombre": "Admin General",
  "email": "admin@sistema.com"
}
```

### Error

- `401`: token ausente o inválido.

---

## 1.3 Cerrar sesión

`POST /api/logout`

### Headers

```http
Authorization: Bearer <token>
Accept: application/json
```

El backend elimina el token de acceso actual.

---

# 2. Buscar practicante por DNI

`GET /api/practicante/buscar?dni=74827384`

### Response 200

```json
{
  "encontrado": true,
  "nombres": "Jorge Quenta Oliva",
  "especialidad": "Ingeniería de Software con Inteligencia Artificial",
  "institucion": "SENATI"
}
```

Frontend puede usar este endpoint para autocompletar los datos del practicante.

---

# 3. Asistencia presencial

## 3.1 Marcar entrada

`POST /api/asistencia/entrada`

### Request JSON

```json
{
  "dni": "74827384",
  "actividad": "Avance del proyecto"
}
```

> Importante: el backend espera `dni`, no `codigo`.

### Comportamiento

- Busca al usuario por DNI.
- Verifica que esté activo.
- Evita registros duplicados del mismo día.
- Registra fecha y hora automáticamente.
- Calcula el estado según la hora:
  - `a_tiempo`
  - `tardanza`

### Response de éxito

```json
{
  "ok": true,
  "nombre": "Jorge Quenta Oliva",
  "hora": "08:05:23"
}
```

---

## 3.2 Marcar salida

`POST /api/asistencia/salida`

### Request JSON

```json
{
  "dni": "74827384"
}
```

### Comportamiento

- Busca la asistencia del usuario correspondiente al día.
- Completa `hora_salida`.
- No crea una segunda asistencia.

### Response

Devuelve una respuesta JSON de éxito o error según exista una entrada válida del día.

---

# 4. Asistencia remota

## 4.1 Generar sesión remota

`POST /api/sesion-remota`

Ruta protegida con Sanctum.

### Headers

```http
Authorization: Bearer <token>
Accept: application/json
```

### Request

Sin body obligatorio.

### Response 200

```json
{
  "ok": true,
  "codigo_temporal": "M2GKFC",
  "expira_en": "2026-09-29T22:46:51-05:00"
}
```

### Comportamiento actual del backend

- El código tiene una fecha/hora de expiración.
- El mismo código puede ser utilizado por varios practicantes mientras siga vigente.
- El código no se consume al ser usado por una sola persona.

> Pendiente futuro: automatizar la aparición del código remoto en pantalla pública.

---

## 4.2 Marcar asistencia remota

`POST /api/asistencia/remoto`

### Request JSON

```json
{
  "dni": "74827384",
  "codigo_sesion": "M2GKFC",
  "actividad": "Trabajo remoto"
}
```

### Validaciones

- código de sesión válido;
- usuario existente y activo;
- evita asistencia duplicada del mismo usuario en el día;
- calcula automáticamente `a_tiempo` o `tardanza`.

### Response de éxito

```json
{
  "ok": true,
  "nombre": "Jorge Quenta Oliva",
  "hora": "08:07:10"
}
```

### Estado de tardanza

La hora límite se configura en `.env` mediante:

```env
HORA_LIMITE_TARDANZA=08:10:00
```

Después de la hora límite el registro sigue permitido, pero se guarda con:

```text
estado = tardanza
```

---

# 5. Gestión de usuarios — Admin

Todas estas rutas requieren Bearer Token.

## 5.1 Listar usuarios

`GET /api/usuarios`

### Response 200

El backend devuelve un **array directo**, no un objeto envuelto.

```json
[
  {
    "id": 23,
    "dni": "12345678",
    "nombres": "Prueba",
    "apellidos": "Backend",
    "carrera_id": 1,
    "institucion_id": 1,
    "rol": "practicante",
    "modalidad": "presencial",
    "activo": true,
    "email": null,
    "carrera": {
      "id": 1,
      "nombre": "Ingeniería de Software con Inteligencia Artificial"
    },
    "institucion": {
      "id": 1,
      "nombre": "SENATI"
    }
  }
]
```

Frontend puede usar directamente:

```js
data.forEach(...)
```

---

## 5.2 Crear usuario

`POST /api/usuarios`

### Request JSON de practicante

```json
{
  "dni": "12345678",
  "nombres": "Prueba",
  "apellidos": "Usuario",
  "rol": "practicante",
  "carrera_id": 1,
  "institucion_id": 1,
  "modalidad": "presencial"
}
```

### Response 201

```json
{
  "ok": true,
  "usuario": {
    "id": 23,
    "dni": "12345678",
    "nombres": "Prueba",
    "apellidos": "Usuario",
    "rol": "practicante",
    "activo": true
  }
}
```

---

## 5.3 Editar usuario

`PUT /api/usuarios/{id}`

### Estado actual

Actualmente el endpoint permite editar principalmente:

```text
nombres
apellidos
carrera_id
institucion_id
modalidad
```

Ejemplo:

```json
{
  "nombres": "Prueba Editado",
  "apellidos": "Backend Actualizado",
  "carrera_id": 1,
  "institucion_id": 1,
  "modalidad": "remoto"
}
```

### Pendiente

Se quiere ampliar el CRUD administrativo para permitir también:

```text
dni
rol
email
activo
contraseña opcional de administradores
```

---

## 5.4 Desactivar usuario

`DELETE /api/usuarios/{id}`

### Comportamiento

No elimina físicamente al usuario.

Realiza baja lógica:

```text
activo = false
```

Esto permite conservar su historial de asistencias.

### Response

```json
{
  "ok": true
}
```

---

# 6. Reportes — Admin

## 6.1 Consultar reportes JSON

`GET /api/reportes`

### Filtros opcionales

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

### Response 200

```json
{
  "ok": true,
  "data": [
    {
      "id": 1,
      "usuario_id": 2,
      "carrera_id": 1,
      "fecha": "2026-09-29T05:00:00.000000Z",
      "hora_entrada": "2026-09-29T16:25:38.000000Z",
      "hora_salida": null,
      "modalidad": "presencial",
      "estado": "tardanza",
      "justificacion": null,
      "actividad": "Prueba de reporte",
      "usuario": {
        "dni": "74827384",
        "nombres": "Jorge",
        "apellidos": "Quenta Oliva",
        "carrera": {
          "nombre": "Ingeniería de Software con Inteligencia Artificial"
        },
        "institucion": {
          "nombre": "SENATI"
        }
      }
    }
  ]
}
```

### Lectura desde frontend

```js
item.usuario.carrera.nombre
item.usuario.institucion.nombre
```

---

## 6.2 Exportar Excel

`GET /api/reportes/excel`

Ruta protegida.

Devuelve un archivo binario:

```text
reporte-asistencias.xlsx
```

Frontend no debe tratar esta respuesta con:

```js
response.json()
```

Debe manejarla como archivo/binario (`blob`).

---

## 6.3 Exportar PDF

`GET /api/reportes/pdf`

Ruta protegida.

Devuelve un archivo PDF binario.

Frontend debe manejar la respuesta como archivo/binario (`blob`).

---

# 7. Justificaciones

## 7.1 Formulario Blade actual

Rutas web existentes:

```text
GET  /justificaciones/crear
POST /justificaciones
```

### Campos

```text
identificacion
tipo
fecha
motivo
evidencia
```

### Tipos permitidos

```text
tardanza
inasistencia
```

### Evidencia

Archivos permitidos:

```text
jpg
jpeg
png
pdf
```

Máximo:

```text
5 MB
```

Para archivos se utiliza:

```text
multipart/form-data
```

### Backend actual

- busca usuario por DNI;
- busca asistencia por usuario y fecha;
- guarda el texto en `asistencias.justificacion`;
- almacena la ruta del archivo en `asistencias.evidencia_path`.

### Importante para frontend

Si se usa JavaScript con `FormData`, no establecer manualmente:

```http
Content-Type: multipart/form-data
```

El navegador genera automáticamente el `boundary`.

---

# 8. Códigos HTTP que frontend debe manejar

| Código | Significado general |
|---|---|
| `200` | operación correcta |
| `201` | recurso creado correctamente |
| `400` | operación inválida o regla de negocio incumplida |
| `401` | token inválido o usuario no autenticado |
| `404` | recurso/usuario no encontrado |
| `422` | error de validación |
| `500` | error interno del servidor |

Frontend debe mostrar el mensaje devuelto por el backend cuando exista un campo:

```json
{
  "error": "..."
}
```

o:

```json
{
  "message": "..."
}
```

---

# 9. Estado de implementación

## Implementado y probado

- ✅ Login de administrador.
- ✅ Validación de token con `/api/me`.
- ✅ Logout.
- ✅ Búsqueda de practicante por DNI.
- ✅ Entrada presencial.
- ✅ Salida presencial.
- ✅ Asistencia remota.
- ✅ Código remoto compartido por varios practicantes.
- ✅ Cálculo de `a_tiempo` / `tardanza`.
- ✅ Hora límite configurable.
- ✅ Listado de usuarios.
- ✅ Creación de usuarios.
- ✅ Edición básica de usuarios.
- ✅ Baja lógica de usuarios.
- ✅ Reportes JSON.
- ✅ Filtros backend de reportes.
- ✅ Exportación Excel.
- ✅ Exportación PDF.
- ✅ Justificación en texto.
- ✅ Campo `evidencia_path`.
- ✅ Seeders y factories principales.

## Pendiente / por completar

- 🟡 Ampliar edición de usuarios: DNI, rol, email, estado y contraseña opcional.
- 🟡 Historial de asistencias por usuario.
- 🟡 Gestión administrativa más completa de justificaciones/evidencias.
- 🟡 Salida remota.
- 🟡 Código remoto automático en pantalla pública.
- 🟡 Pruebas finales de integración frontend ↔ backend.
- 🟡 Limpieza de archivos y código de prueba antes de entrega.

-----------
-----------
-----------

## ultimo estado de backend

## 1. Rutas públicas

### Login
**POST** `/api/login`

Uso: autenticar administrador y obtener token Bearer.

### Buscar practicante por DNI
**GET** `/api/practicante/buscar`

Uso: consultar practicante activo antes del marcado.

### Marcar entrada presencial
**POST** `/api/asistencia/entrada`

Validaciones principales:
- DNI obligatorio y de 8 dígitos.
- Actividad opcional.
- Usuario debe existir y estar activo.
- No se permite duplicar asistencia del mismo usuario en la misma fecha.

### Marcar salida
**POST** `/api/asistencia/salida`

Validaciones principales:
- DNI obligatorio y de 8 dígitos.
- Busca la asistencia del día y registra salida.

### Marcar asistencia remota
**POST** `/api/asistencia/remoto`

Campos principales:
- `dni`
- `codigo_sesion`
- `actividad` opcional

Validaciones principales:
- DNI obligatorio y de 8 dígitos.
- Código de sesión obligatorio.
- Sesión remota debe existir y no estar vencida.
- Un mismo código puede ser utilizado por varios practicantes mientras siga vigente.
- No se permite duplicar asistencia del mismo usuario en la misma fecha.

> Importante para frontend: la ruta oficial es `/api/asistencia/remoto`, no `/api/asistencia/remota`.

---

## 2. Rutas autenticadas

Estas rutas requieren `auth:sanctum`.

### Usuario autenticado
**GET** `/api/me`

Uso: validar token y obtener información del usuario autenticado.

### Logout
**POST** `/api/logout`

Uso: revocar el token actual.

---

## 3. Rutas exclusivas de administrador

Todas las rutas de esta sección requieren:
- `auth:sanctum`
- `AdminMiddleware`

### Sesión remota
**POST** `/api/sesion-remota`

Comportamiento:
- Genera código temporal de 6 caracteres.
- Vigencia: 15 minutos.
- Si ya existe una sesión vigente, reutiliza el mismo código.
- El código no se consume con el primer uso.

---

## 4. Usuarios

### Listar
**GET** `/api/usuarios`

### Crear
**POST** `/api/usuarios`

Reglas importantes:
- DNI único.
- Rol: `admin` o `practicante`.
- Si el rol es `admin`, email y contraseña son obligatorios.
- Modalidad del admin queda en `null`.
- Contraseña se guarda hasheada.

### Actualizar
**PUT** `/api/usuarios/{usuario}`

Regla importante:
- No se puede convertir un practicante en admin sin que disponga de email y contraseña.

### Desactivar
**DELETE** `/api/usuarios/{usuario}`

Comportamiento:
- Baja lógica.
- Establece `activo = false`.

### Historial de asistencias
**GET** `/api/usuarios/{usuario}/asistencias`

---

## 5. Carreras

### Listar
**GET** `/api/carreras`

### Crear
**POST** `/api/carreras`

### Mostrar
**GET** `/api/carreras/{carrera}`

### Actualizar
**PUT** `/api/carreras/{carrera}`

### Eliminar
**DELETE** `/api/carreras/{carrera}`

Regla:
- Si la carrera tiene usuarios asociados, responde `409 Conflict`.
- Si no tiene usuarios asociados, puede eliminarse normalmente.

---

## 6. Instituciones

### Listar
**GET** `/api/instituciones`

### Crear
**POST** `/api/instituciones`

### Mostrar
**GET** `/api/instituciones/{institucion}`

### Actualizar
**PUT** `/api/instituciones/{institucion}`

### Eliminar
**DELETE** `/api/instituciones/{institucion}`

Regla:
- Si la institución tiene usuarios asociados, responde `409 Conflict`.
- Si no tiene usuarios asociados, puede eliminarse normalmente.

---

## 7. Justificaciones

### Envío desde formulario web
**POST** `/justificaciones`

No es endpoint API. Usa formulario Blade y protección CSRF.

Campos:
- `identificacion`
- `tipo`: `tardanza` o `inasistencia`
- `fecha`
- `motivo`
- `evidencia` opcional

Reglas:
- El usuario debe existir y estar activo.
- Si el tipo es `tardanza`, debe existir una asistencia de esa fecha y su estado debe ser `tardanza`.
- Se evita duplicar una justificación del mismo tipo para el mismo usuario y fecha.
- Una inasistencia puede justificarse aunque no exista asistencia.
- Evidencia permitida: JPG, JPEG, PNG o PDF.
- Tamaño máximo: 5 MB.

Almacenamiento:
- Disco: `public`
- Carpeta: `storage/app/public/evidencias`
- URL pública esperada: `/storage/evidencias/...`

### Listar para administrador
**GET** `/api/justificaciones`

Incluye relaciones:
- usuario
- carrera
- institución
- asistencia

---

## 8. Reportes

### JSON
**GET** `/api/reportes`

### Excel
**GET** `/api/reportes/excel`

### PDF
**GET** `/api/reportes/pdf`

Filtros soportados:
- `desde`
- `hasta`
- `modalidad`
- `estado`

La información de justificaciones se obtiene desde la relación:

`$asistencia->justificaciones`

La tabla `justificaciones` es la fuente actual de esta información.

---

## 9. Modelos principales

### Usuario
Relaciones:
- `carrera()`
- `institucion()`
- `asistencias()`
- `justificaciones()`
- `sesionesGeneradas()`

### Asistencia
Relaciones:
- `usuario()`
- `justificaciones()`

### Justificacion
Relaciones:
- `usuario()`
- `asistencia()`

### Carrera
Relación:
- `usuarios()`

### Institucion
Relación:
- `usuarios()`

### SesionRemota
Relación:
- `creador()`

---

## 10. Campos antiguos conservados

Por compatibilidad e historial se decidió no eliminar por ahora:
- `asistencias.justificacion`
- `asistencias.evidencia_path`
- `sesion_remotas.usado`

Estos campos no forman parte de la lógica principal actual.

La fuente real de justificaciones es la tabla `justificaciones`.

---

## 11. Pendiente de integración frontend

Las APIs administrativas ya están protegidas correctamente.

Las vistas Blade:
- `/admin/dashboard`
- `/admin/reportes`
- `/admin/usuarios`
- `/admin/usuarios/crear`
- `/admin/usuarios/{usuario}/editar`

todavía pueden cargar HTML directamente.

Cuando se integre el frontend definitivo, cada vista administrativa debe:
1. Obtener el token desde `localStorage`.
2. Consultar `GET /api/me` con `Authorization: Bearer <token>`.
3. Si recibe `401` o `403`, redirigir a `/admin/login`.
4. Mostrar el contenido administrativo únicamente después de validar el token.

Este punto queda pendiente hasta integrar la rama frontend.

---

## 12. Pruebas realizadas

Se verificó:
- Login administrativo.
- Protección `auth:sanctum`.
- Protección `AdminMiddleware`.
- Acceso no-admin devuelve `403`.
- Acceso sin token devuelve `401`.
- CRUD de carreras.
- CRUD de instituciones.
- Eliminación de carrera/institución asociada devuelve `409`.
- Creación de admin sin email/password devuelve `422`.
- Conversión a admin sin credenciales devuelve `422`.
- Validaciones de asistencia devuelven `422` cuando corresponde.
- Sesión remota reutilizable durante su vigencia.
- Justificación de tardanza rechazada si la asistencia fue `a_tiempo`.
- Detección de justificación duplicada.
- Almacenamiento público de evidencias.
- Reportes con relación `justificaciones`.

---

## 13. Estado para cierre backend

Backend funcional y revisado.

Pendiente:
- Gestión administrativa más completa de justificaciones/evidencias hecho parcial
- Código remoto automático en pantalla pública
- Integración con frontend actualizado.
- Validación visual de vistas `/admin/*` usando `/api/me`.
- Revisión final después del merge frontend/backend.
- Commit y push final de la rama backend.




# 10. Regla de mantenimiento del contrato

Cuando backend cambie:

- nombre de una ruta;
- nombre de un campo;
- formato de request;
- formato de response;
- autenticación;
- código HTTP;

este archivo debe actualizarse en el mismo commit.

Ejemplo:

```bash
git add docs/API_CONTRATO.md
git add app/ routes/
git commit -m "docs: actualiza contrato API"
```
