# API — Sistema de Asistencias
### Contrato backend ↔ frontend

**Convenciones generales**
- Fecha/hora en formato ISO 8601: `2026-09-17T08:05:23`
- Zona horaria del servidor: `America/Lima`
- Auth admin: Laravel Sanctum — header `Authorization: Bearer <token>`
- Los endpoints de marcado (`/entrada`, `/salida`, `/remota`) **no requieren login**
- Los errores 404 son intencionalmente genéricos (no distinguen "no existe" de "desactivado")

---

## 1. Marcar entrada
`POST /api/asistencia/entrada`

**Request**
```json
{ "codigo": "string" }
```

**Response 200 (éxito)**
```json
{
  "ok": true,
  "nombre": "Juan Pérez",
  "carrera": "Ing. Software",
  "hora": "08:05:23"
}
```

**Response 404 (error)**
```json
{ "ok": false, "error": "Código no válido" }
```

---

## 2. Marcar salida
`POST /api/asistencia/salida`

**Request**
```json
{ "codigo": "string" }
```

**Response**: mismo formato que `/entrada` (con la hora de salida), o el mismo error genérico si no existe una entrada previa ese día.

---

## 3. Generar sesión remota (solo admin)
`POST /api/sesion-remota`

**Request**: sin body (el admin se identifica por el token)

**Response 200**
```json
{
  "ok": true,
  "codigo_temporal": "ABC123",
  "expira_en": "2026-09-17T08:15:00"
}
```

---

## 4. Marcar asistencia remota
`POST /api/asistencia/remota`

**Request**
```json
{ "codigo_temporal": "string", "codigo_usuario": "string" }
```

**Response**: mismo formato de éxito/error que `/entrada`. Valida además que el código temporal no esté expirado ni ya usado.

---

## 5. Reportes (solo admin)
`GET /api/reportes?desde&hasta&modalidad&estado`

**Query params** (todos opcionales)
| Param | Valores |
|---|---|
| `desde` | fecha |
| `hasta` | fecha |
| `modalidad` | `presencial` / `remoto` |
| `estado` | `a_tiempo` / `tardanza` |

**Response 200**
```json
{
  "ok": true,
  "data": [
    {
      "usuario": "Juan Pérez",
      "carrera": "Ing. Software",
      "fecha": "2026-09-17",
      "hora_entrada": "08:05:23",
      "hora_salida": "17:02:10",
      "modalidad": "presencial",
      "estado": "a_tiempo",
      "justificacion": null
    }
  ]
}
```

---

## 6. Login (solo admin)
`POST /api/login`

**Request**
```json
{ "email": "string", "password": "string" }
```

**Response 200**
```json
{ "ok": true, "token": "1|abcdef123456..." }
```

---

## 7. Gestión de usuarios (CRUD, solo admin)
`GET|POST|PUT|PATCH|DELETE /api/usuarios`

| Método | Ruta | Acción |
|---|---|---|
| GET | `/api/usuarios` | Listar todos |
| GET | `/api/usuarios/{id}` | Ver uno |
| POST | `/api/usuarios` | Crear |
| PUT/PATCH | `/api/usuarios/{id}` | Editar |
| DELETE | `/api/usuarios/{id}` | Eliminar / desactivar |

**Campos**: `codigo`, `nombre`, `carrera_id`, `rol` (admin/practicante), `modalidad` (presencial/remoto, nullable para admin), `activo`
