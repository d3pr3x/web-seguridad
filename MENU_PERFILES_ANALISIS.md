# Análisis de menú por perfil

**Instrucciones:** En la matriz cambia `—` por `✓` donde ese perfil deba tener la opción (o `✓` por `—` para quitar). La columna **Depende de** indica el módulo padre: si tiene `—` es una opción principal; si tiene un nombre, es una subopción de ese módulo. Cuando termines, lo aplicamos al código.

**Columnas:** Depende de | Guardia | 2º jefe | Jefe turno | Jefe contrato | Admin

---

## Módulos del menú (resumen)

| Módulo (sección) | Subopciones |
|------------------|-------------|
| **Inicio** | — |
| **Control de acceso** | — |
| **Mi perfil** | — |
| **Mis reportes** | ↳ Ver mis reportes |
| **Mis documentos** | ↳ Ver mis documentos |
| **Rondas QR** | — |
| **Reportes y estadísticas** | ↳ Todos los reportes, ↳ Reporte escaneos QR, ↳ Reporte por sucursal, ↳ Reportes diarios |
| **Supervisión** | ↳ Usuarios, ↳ Aprobar documentos, ↳ Novedades, ↳ Todos los reportes |
| **Gestión** | ↳ Dispositivos, ↳ Ubicaciones, ↳ Sectores, ↳ Puntos de ronda (QR) |
| **Cerrar sesión** | — |

---

## Matriz para editar (con flecha ↳ = depende del módulo de la fila anterior)

```
Opción del menú                    Depende de              Guardia  2º jefe  Jefe turno  Jefe contrato  Admin
────────────────────────────────────────────────────────────────────────────────────────────────────────────
Inicio                             —                        ✓        ✓       ✓          ✓              ✓
Control de acceso                  —                        ✓        ✓       ✓          —              —
Mi perfil                          —                        ✓        ✓       ✓          ✓              ✓
Mis reportes                       —                        ✓        ✓       ✓          —              —
  ↳ Ver mis reportes               Mis reportes             ✓        ✓       ✓          —              —
Mis documentos                     —                        ✓        ✓       ✓          —              —
  ↳ Ver mis documentos             Mis documentos           ✓        ✓       ✓          —              —
Rondas QR                          —                        ✓        ✓       ✓          —              —
Reportes y estadísticas            —                        —        —       ✓          ✓              ✓
  ↳ Todos los reportes             Reportes y estadísticas  —        —       ✓          ✓              ✓
  ↳ Reporte escaneos QR            Reportes y estadísticas  —        —       ✓          ✓              ✓
  ↳ Reporte por sucursal           Reportes y estadísticas  —        ✓       ✓          ✓              ✓
  ↳ Reportes diarios               Reportes y estadísticas  —        —       —          —              ✓
Supervisión                        —                        —        —       ✓          ✓              ✓
  ↳ Usuarios                       Supervisión             —        —       —          —              ✓
  ↳ Aprobar documentos             Supervisión             —        —       ✓          ✓              ✓
  ↳ Novedades                      Supervisión             —        —       ✓          ✓              ✓
  ↳ Todos los reportes             Supervisión             —        —       ✓          ✓              ✓
Gestión                            —                        —        —       —          —              ✓
  ↳ Dispositivos                   Gestión                 —        —       —          —              ✓
  ↳ Ubicaciones                    Gestión                 —        —       —          —              ✓
  ↳ Sectores                       Gestión                 —        —       —          —              ✓
  ↳ Puntos de ronda (QR)           Gestión                 —        —       —          —              ✓
Cerrar sesión                      —                        ✓        ✓       ✓          ✓              ✓
```

**Leyenda:** `✓` = tiene acceso · `—` = no tiene acceso · **↳** = subopción del módulo indicado en "Depende de"

---

## Tabla Markdown (con Depende de y flecha ↳)

| Opción del menú | Depende de | Guardia | 2º jefe | Jefe turno | Jefe contrato | Admin |
|-----------------|------------|:-------:|:-------:|:----------:|:-------------:|:-----:|
| Inicio | — | ✓ | ✓ | ✓ | ✓ | ✓ |
| Control de acceso | — | ✓ | ✓ | ✓ | — | — |
| Mi perfil | — | ✓ | ✓ | ✓ | ✓ | ✓ |
| Mis reportes | — | ✓ | ✓ | ✓ | — | — |
| ↳ Ver mis reportes | Mis reportes | ✓ | ✓ | ✓ | — | — |
| Mis documentos | — | ✓ | ✓ | ✓ | — | — |
| ↳ Ver mis documentos | Mis documentos | ✓ | ✓ | ✓ | — | — |
| Rondas QR | — | ✓ | ✓ | ✓ | — | — |
| Reportes y estadísticas | — | — | — | ✓ | ✓ | ✓ |
| ↳ Todos los reportes | Reportes y estadísticas | — | — | ✓ | ✓ | ✓ |
| ↳ Reporte escaneos QR | Reportes y estadísticas | — | — | ✓ | ✓ | ✓ |
| ↳ Reporte por sucursal | Reportes y estadísticas | — | ✓ | ✓ | ✓ | ✓ |
| ↳ Reportes diarios | Reportes y estadísticas | — | — | — | — | ✓ |
| Supervisión | — | — | — | ✓ | ✓ | ✓ |
| ↳ Usuarios | Supervisión | — | — | — | — | ✓ |
| ↳ Aprobar documentos | Supervisión | — | — | ✓ | ✓ | ✓ |
| ↳ Novedades | Supervisión | — | — | ✓ | ✓ | ✓ |
| ↳ Todos los reportes | Supervisión | — | — | ✓ | ✓ | ✓ |
| Gestión | — | — | — | — | — | ✓ |
| ↳ Dispositivos | Gestión | — | — | — | — | ✓ |
| ↳ Ubicaciones | Gestión | — | — | — | — | ✓ |
| ↳ Sectores | Gestión | — | — | — | — | ✓ |
| ↳ Puntos de ronda (QR) | Gestión | — | — | — | — | ✓ |
| Cerrar sesión | — | ✓ | ✓ | ✓ | ✓ | ✓ |

---

## Archivo TSV (Excel / Google Sheets)

Abre **`MENU_PERFILES_ANALISIS.tsv`**: incluye la columna **Depende de** y la flecha **↳** en el nombre de las subopciones. Así ves qué es módulo principal y qué depende de quién.

---

Cuando termines de marcar, indica que ya está y aplicamos los cambios en el código.
