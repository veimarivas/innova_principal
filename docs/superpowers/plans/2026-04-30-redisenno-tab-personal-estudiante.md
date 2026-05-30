# Tab Personal - Rediseño Estilo Carnet de Identificación

> **For agentic workers:** NOT REQUIRED SUB-SKILL: This is a simple UI task - direct implementation is more efficient.

**Goal:** Rediseñar el tab "Personal" de la vista admin.estudiantes.detalle para usar el diseño "Carnet de Identificación" similar a admin.profile.index

**Architecture:** Reemplazar la estructura actual de 2 columnas (tarjetas simples) por un diseño de 3 columnas con estilo carnet: izquierda (foto + datos rápidos), centro (nombre + contacto), derecha (datos académicos)

**Tech Stack:** Blade templates (Laravel), CSS personalizado, Bootstrap icons (Remix Icon)

---

## Files Overview

| File | Action |
|------|--------|
| `resources/views/admin/estudiantes/partials/tab-personal.blade.php` | Modify - reemplazar estructura HTML |
| `resources/views/admin/estudiantes/detalle.blade.php` | Reference - revisar CSS existente |
| `resources/views/admin/profile/tabs/personal.blade.php` | Reference - diseño de referencia |
| `resources/views/admin/profile/styles.blade.php` | Reference - CSS de referencia (líneas 651-989) |

---

## Implementation Steps

- [ ] **Step 1: Revisar estructura actual del tab-personal.blade.php**

  Leer el archivo actual para recordar la estructura y variables disponibles:
  ```
  $persona = $estudiante->persona
  ```

- [ ] **Step 2: Crear nueva estructura HTML con diseño de carnet**

  Reemplazar todo el contenido del archivo `tab-personal.blade.php` con:
  
  ```blade
  @php
      $persona = $estudiante->persona;
      
      // Foto
      $tieneFoto = $persona && $persona->fotografia && file_exists(public_path('images/personas/' . $persona->fotografia));
      $avatarUrl = $tieneFoto ? asset('images/personas/' . $persona->fotografia) : null;
      
      // Nombre completo
      $nombreCompleto = $persona 
          ? trim(($persona->nombres ?? '') . ' ' . ($persona->apellido_paterno ?? '') . ' ' . ($persona->apellido_materno ?? ''))
          : 'Estudiante';
      
      // Iniciales para fallback
      $iniciales = collect(explode(' ', $nombreCompleto))
          ->filter()->take(2)->map(fn($p) => strtoupper($p[0]))->implode('');
      
      // Edad
      $edad = ($persona && $persona->fecha_nacimiento)
          ? \Carbon\Carbon::parse($persona->fecha_nacimiento)->age
          : null;
      
      // Ubicación
      $ubicacion = ($persona && $persona->ciudad)
          ? optional($persona->ciudad)->nombre . ', ' . (optional(optional($persona->ciudad)->departamento)->nombre ?? '')
          : null;
      
      // Estudio principal (para datos académicos)
      $estudio = $persona?->estudios?->first();
  @endphp
  
  <div class="est-ci-wrap">
      {{-- Franja superior --}}
      <div class="est-ci-stripe"></div>
  
      <div class="est-ci-body">
          {{-- Columna izquierda: foto + datos rápidos --}}
          <div class="est-ci-left">
              <div class="est-ci-foto-label">
                  <i class="ri-building-2-line"></i>
                  <span>INNOVA CIENCIA</span>
              </div>
  
              <div class="est-ci-foto" id="est-ci-foto-container">
                  <img src="{{ $avatarUrl ?? '' }}" alt="Foto"
                       id="est-ci-foto-img"
                       style="{{ $tieneFoto ? '' : 'display:none;' }}"
                       onerror="this.style.display='none';document.getElementById('est-ci-initials').style.display='flex';">
                  <div id="est-ci-initials" class="est-ci-initials"
                       style="{{ $tieneFoto ? 'display:none;' : '' }}">
                      {{ $iniciales ?: '?' }}
                  </div>
              </div>
  
              <div class="est-ci-quick-data">
                  @if($persona?->carnet)
                  <div class="est-ci-qd-item">
                      <i class="ri-shield-check-line"></i>
                      <span class="est-ci-qd-label">CI</span>
                      <span class="est-ci-qd-val">{{ $persona->carnet }}{{ $persona->expedido ? ' '.$persona->expedido : '' }}</span>
                  </div>
                  @endif
                  @if($persona?->fecha_nacimiento)
                  <div class="est-ci-qd-item">
                      <i class="ri-cake-line"></i>
                      <span class="est-ci-qd-label">Nacimiento</span>
                      <span class="est-ci-qd-val">{{ \Carbon\Carbon::parse($persona->fecha_nacimiento)->format('d/m/Y') }}</span>
                  </div>
                  @endif
                  @if($edad)
                  <div class="est-ci-qd-item">
                      <i class="ri-user-line"></i>
                      <span class="est-ci-qd-label">Edad</span>
                      <span class="est-ci-qd-val">{{ $edad }} años</span>
                  </div>
                  @endif
                  @if($persona?->sexo)
                  <div class="est-ci-qd-item">
                      <i class="ri-genderless-line"></i>
                      <span class="est-ci-qd-label">Sexo</span>
                      <span class="est-ci-qd-val">{{ $persona->sexo == 'M' ? 'Masculino' : ($persona->sexo == 'F' ? 'Femenino' : '—') }}</span>
                  </div>
                  @endif
              </div>
          </div>
  
          {{-- Columna central: nombre + datos de contacto --}}
          <div class="est-ci-center">
              <div class="est-ci-nombre-wrap">
                  <div>
                      <div class="est-ci-nombre">{{ $nombreCompleto }}</div>
                      <div class="est-ci-estado-label">Estudiante</div>
                  </div>
                  <span class="est-ci-estado-badge est-ci-badge-{{ ($estudiante->estado ?? 'Activo') === 'Activo' ? 'activo' : 'inactivo' }}">
                      <i class="ri-checkbox-circle-line"></i>
                      {{ $estudiante->estado ?? 'Activo' }}
                  </span>
              </div>
  
              <div class="est-ci-section-title">
                  <i class="ri-contacts-line"></i> Datos de Contacto
              </div>
  
              <div class="est-ci-datos-grid">
                  <div class="est-ci-dato">
                      <span class="est-ci-label">Correo</span>
                      <span class="est-ci-value">{{ $persona?->correo ?? '—' }}</span>
                  </div>
                  <div class="est-ci-dato">
                      <span class="est-ci-label">Celular</span>
                      <span class="est-ci-value">{{ $persona?->celular ?? '—' }}</span>
                  </div>
                  <div class="est-ci-dato">
                      <span class="est-ci-label">Teléfono</span>
                      <span class="est-ci-value">{{ $persona?->telefono ?? '—' }}</span>
                  </div>
                  <div class="est-ci-dato">
                      <span class="est-ci-label">Estado Civil</span>
                      <span class="est-ci-value">{{ $persona?->estado_civil ?? '—' }}</span>
                  </div>
                  <div class="est-ci-dato est-ci-full">
                      <span class="est-ci-label">Ciudad / Departamento</span>
                      <span class="est-ci-value">{{ $ubicacion ?? '—' }}</span>
                  </div>
                  <div class="est-ci-dato est-ci-full">
                      <span class="est-ci-label">Dirección</span>
                      <span class="est-ci-value">{{ $persona?->direccion ?? '—' }}</span>
                  </div>
              </div>
          </div>
  
          {{-- Columna derecha: datos académicos --}}
          <div class="est-ci-right">
              <div class="est-ci-right-header">
                  <i class="ri-graduation-cap-line"></i>
                  <span>Datos del Estudiante</span>
              </div>
  
              <div class="est-ci-account-list">
                  @php
                      $estudio = $persona?->estudios?->first();
                  @endphp
                  @if($estudio?->universidad)
                  <div class="est-ci-acc-item">
                      <div class="est-ci-acc-icon"><i class="ri-building-4-line"></i></div>
                      <div>
                          <div class="est-ci-acc-label">Universidad</div>
                          <div class="est-ci-acc-value">{{ $estudio->universidad->nombre ?? '—' }}</div>
                      </div>
                  </div>
                  @endif
                  @if($estudio?->profesion)
                  <div class="est-ci-acc-item">
                      <div class="est-ci-acc-icon"><i class="ri-graduation-cap-line"></i></div>
                      <div>
                          <div class="est-ci-acc-label">Carrera / Programa</div>
                          <div class="est-ci-acc-value">{{ $estudio->profesion->nombre ?? '—' }}</div>
                      </div>
                  </div>
                  @endif
                  <div class="est-ci-acc-item">
                      <div class="est-ci-acc-icon"><i class="ri-calendar-check-line"></i></div>
                      <div>
                          <div class="est-ci-acc-label">Inscripción</div>
                          <div class="est-ci-acc-value">{{ $estudiante->created_at->format('d/m/Y') }}</div>
                      </div>
                  </div>
                  <div class="est-ci-acc-item">
                      <div class="est-ci-acc-icon"><i class="ri-vip-diamond-line"></i></div>
                      <div>
                          <div class="est-ci-acc-label">Estado</div>
                          <div class="est-ci-acc-value">{{ $estudiante->estado ?? 'Activo' }}</div>
                      </div>
                  </div>
                  @if($persona?->estudios?->count() > 1)
                  <div class="est-ci-acc-item">
                      <div class="est-ci-acc-icon"><i class="ri-book-line"></i></div>
                      <div>
                          <div class="est-ci-acc-label">Total Estudios</div>
                          <div class="est-ci-acc-value">{{ $persona->estudios->count() }} registrado(s)</div>
                      </div>
                  </div>
                  @endif
              </div>
          </div>
      </div>
  
      {{-- Franja inferior --}}
      <div class="est-ci-bottom-bar">
          <span><i class="ri-id-card-line"></i> Carnet de Identificación</span>
          <span>{{ now()->format('Y') }}</span>
      </div>
  </div>
  ```

- [ ] **Step 3: Agregar estilos CSS necesarios**

  Agregar los estilos CSS al archivo `detalle.blade.php` (dentro del bloque @section('css'), después de los estilos existentes). Los estilos deben usar el prefijo `est-ci-` para evitar conflictos:

  ```css
  /* ═══════════════════════════════════════
     Carnet de Identificación (tab Personal)
  ═══════════════════════════════════════ */
  
  .est-ci-wrap {
      background: #fff;
      border: 1.5px solid var(--est-border);
      border-radius: 18px;
      overflow: hidden;
      box-shadow: 0 6px 30px rgba(0,0,0,.07);
      position: relative;
  }
  
  .est-ci-stripe {
      height: 5px;
      background: linear-gradient(90deg, #391b04 0%, #9a4904 35%, #fc7b04 65%, #9a4904 100%);
  }
  
  .est-ci-body {
      display: grid;
      grid-template-columns: 220px 1fr 280px;
      gap: 0;
  }
  
  /* Columna izquierda */
  .est-ci-left {
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: .85rem;
      padding: 1.5rem 1rem 1.25rem;
      background: linear-gradient(180deg, #9a4904 0%, #5a2800 100%);
  }
  
  .est-ci-foto-label {
      display: flex;
      align-items: center;
      gap: 5px;
      font-size: .65rem;
      font-weight: 700;
      letter-spacing: .08em;
      text-transform: uppercase;
      color: rgba(255,255,255,.75);
  }
  
  .est-ci-foto {
      width: 140px;
      height: 175px;
      border-radius: 10px;
      border: 3px solid rgba(255,255,255,.45);
      background: rgba(255,255,255,.12);
      display: flex;
      align-items: center;
      justify-content: center;
      overflow: hidden;
      box-shadow: 0 6px 20px rgba(0,0,0,.3);
      flex-shrink: 0;
  }
  
  .est-ci-foto img { width: 100%; height: 100%; object-fit: cover; }
  
  .est-ci-initials {
      font-family: 'Outfit', sans-serif;
      font-size: 2.6rem;
      font-weight: 800;
      color: rgba(255,255,255,.7);
      display: flex;
      align-items: center;
      justify-content: center;
      width: 100%;
      height: 100%;
  }
  
  .est-ci-quick-data {
      width: 100%;
      display: flex;
      flex-direction: column;
      gap: .28rem;
  }
  
  .est-ci-qd-item {
      display: grid;
      grid-template-columns: 14px auto 1fr;
      align-items: center;
      gap: .35rem;
      font-size: .7rem;
      padding: .28rem .4rem;
      background: rgba(255,255,255,.11);
      border-radius: 6px;
      color: rgba(255,255,255,.9);
  }
  
  .est-ci-qd-item i    { color: rgba(255,255,255,.65); font-size: .82rem; }
  .est-ci-qd-label     { color: rgba(255,255,255,.58); font-size: .63rem; text-transform: uppercase; letter-spacing: .03em; }
  .est-ci-qd-val       { color: #fff; font-weight: 600; text-align: right; font-size: .72rem; }
  
  /* Columna central */
  .est-ci-center {
      display: flex;
      flex-direction: column;
      padding: 1.4rem 1.25rem 1.25rem;
      border-right: 1.5px solid var(--est-border);
  }
  
  .est-ci-nombre-wrap {
      display: flex;
      align-items: flex-start;
      justify-content: space-between;
      gap: .6rem;
      margin-bottom: 1rem;
      padding-bottom: .85rem;
      border-bottom: 1.5px solid var(--est-border);
      flex-wrap: wrap;
  }
  
  .est-ci-nombre {
      font-family: 'Outfit', sans-serif;
      font-size: 1.3rem;
      font-weight: 700;
      color: var(--est-text);
      line-height: 1.2;
  }
  
  .est-ci-estado-label {
      display: flex;
      align-items: center;
      gap: 4px;
      font-size: .78rem;
      color: var(--est-primary);
      font-weight: 600;
      margin-top: 4px;
  }
  
  .est-ci-estado-badge {
      display: inline-flex;
      align-items: center;
      gap: 4px;
      padding: .25rem .65rem;
      border-radius: 20px;
      font-size: .68rem;
      font-weight: 700;
      white-space: nowrap;
      align-self: flex-start;
  }
  
  .est-ci-badge-activo   { background: #dcfce7; color: #15803d; border: 1px solid #86efac; }
  .est-ci-badge-inactivo { background: #fee2e2; color: #b91c1c; border: 1px solid #fca5a5; }
  
  .est-ci-section-title {
      font-size: .72rem;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: .05em;
      color: var(--est-text-muted);
      margin-bottom: .65rem;
      display: flex;
      align-items: center;
      gap: 5px;
  }
  
  .est-ci-section-title i { color: var(--est-accent); }
  
  .est-ci-datos-grid {
      display: grid;
      grid-template-columns: repeat(2, 1fr);
      gap: .65rem;
  }
  
  .est-ci-dato         { display: flex; flex-direction: column; gap: .1rem; }
  .est-ci-dato.est-ci-full { grid-column: 1 / -1; }
  
  .est-ci-label {
      font-size: .62rem;
      color: var(--est-text-muted);
      text-transform: uppercase;
      letter-spacing: .05em;
      font-weight: 600;
  }
  
  .est-ci-value {
      font-size: .86rem;
      font-weight: 500;
      color: var(--est-text);
  }
  
  /* Columna derecha */
  .est-ci-right {
      display: flex;
      flex-direction: column;
      padding: 1.25rem 1rem 1.25rem;
      background: var(--est-surface);
      gap: .75rem;
  }
  
  .est-ci-right-header {
      display: flex;
      align-items: center;
      gap: 6px;
      font-size: .72rem;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: .05em;
      color: var(--est-text-muted);
      padding-bottom: .7rem;
      border-bottom: 1.5px solid var(--est-border);
  }
  
  .est-ci-right-header i { color: var(--est-accent); font-size: 1rem; }
  
  .est-ci-account-list {
      display: flex;
      flex-direction: column;
      gap: .5rem;
      flex: 1;
  }
  
  .est-ci-acc-item {
      display: flex;
      align-items: center;
      gap: .6rem;
      padding: .45rem .55rem;
      background: white;
      border: 1px solid var(--est-border);
      border-radius: 8px;
  }
  
  .est-ci-acc-icon {
      width: 28px; height: 28px;
      border-radius: 6px;
      background: var(--est-primary-light);
      color: var(--est-primary);
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: .85rem;
      flex-shrink: 0;
  }
  
  .est-ci-acc-label {
      font-size: .62rem;
      color: var(--est-text-muted);
      text-transform: uppercase;
      letter-spacing: .04em;
      font-weight: 600;
  }
  
  .est-ci-acc-value {
      font-size: .82rem;
      font-weight: 600;
      color: var(--est-text);
      word-break: break-all;
  }
  
  /* Franja inferior */
  .est-ci-bottom-bar {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: .5rem 1.25rem;
      background: linear-gradient(90deg, #391b04 0%, #9a4904 60%, #bc5404 100%);
      color: rgba(255,255,255,.8);
      font-size: .68rem;
      font-weight: 600;
      letter-spacing: .04em;
      text-transform: uppercase;
  }
  
  .est-ci-bottom-bar i { margin-right: 4px; }
  
  /* Responsive */
  @media (max-width: 991px) {
      .est-ci-body {
          grid-template-columns: 180px 1fr;
          grid-template-rows: auto auto;
      }
      .est-ci-right {
          grid-column: 1 / -1;
          border-top: 1.5px solid var(--est-border);
      }
  }
  
  @media (max-width: 575px) {
      .est-ci-body { grid-template-columns: 1fr; }
      .est-ci-left  { flex-direction: row; flex-wrap: wrap; align-items: flex-start; gap: .75rem; }
      .est-ci-foto  { width: 100px; height: 125px; }
      .est-ci-right { grid-column: 1; }
  }
  ```

- [ ] **Step 4: Verificar que las variables CSS existan**

  Asegurarse de que las variables CSS usadas estén definidas en el archivo `detalle.blade.php`. Las necesarias son:
  - `--est-border` (ya existe)
  - `--est-text` (ya existe)
  - `--est-text-muted` (ya existe)
  - `--est-surface` (ya existe)
  - `--est-primary` (ya existe)
  - `--est-primary-light` (ya existe)
  - `--est-accent` (ya existe)

- [ ] **Step 5: Verificar visualmente el resultado**

  Acceder a la vista de detalle de estudiante y verificar que el tab Personal se visualice correctamente con:
  - La faixa superior com gradiente
  - Foto do estudiante (ou iniciais)
  - Dados rápida (CI, nascimento, idade, sexo)
  - Nome completo com badge de estado
  - Grid de contacto
  - Dados académicos na coluna direita
  - Faixa inferior com marca d'água
  
  Verificar também o layout responsivo em diferentes tamanhos de tela.

---

## Notas Importantes

1. **Prefijo de clases**: Se usa el prefijo `est-ci-` (estudiante carnet identificación) para evitar conflictos con otros estilos en la página

2. **Fonts**: El diseño usa las fuentes `Outfit` y `Plus Jakarta Sans` que ya están importadas en el archivo `detalle.blade.php`

3. **Fallback de foto**: Si no hay foto, se muestran las iniciales del nombre del estudiante

4. **Campos vacíos**: Todos los campos muestran "—" cuando el valor es null

5. **Compatibilidad**: El diseño es compatible con el sistema de tabs existente de la vista

---

**Plan complete and saved to `docs/superpowers/plans/2026-04-30-redisenno-tab-personal-estudiante.md`**

**Two execution options:**

**1. Direct Implementation (recommended)** - This is a simple UI task, just modify the files directly

**2. Subagent-Driven** - Dispatch subagent to implement task-by-task

Which approach?