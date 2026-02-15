<!-- Header móvil: solo menú a la izquierda + usuario a la derecha (alineado con escritorio) -->
<div class="d-lg-none sticky top-0 z-50 border-bottom" style="background: var(--app-sidebar); border-color: rgba(148,163,184,0.2) !important;">
    <div class="px-3 py-3">
        <div class="d-flex align-items-center justify-content-between">
            <button type="button" onclick="toggleMenu()" class="btn btn-link p-2 rounded text-decoration-none d-flex align-items-center justify-content-center" style="color: var(--app-sidebar-text); min-width: 2.5rem;">
                <i class="fas fa-bars"></i>
            </button>
            <div class="d-flex align-items-center gap-2 min-w-0">
                <p class="mb-0 small fw-medium text-white text-truncate">{{ auth()->user()->nombre_completo }}</p>
                <div class="rounded-circle p-1 d-flex align-items-center justify-content-center flex-shrink-0" style="background: var(--app-sidebar-active); color: var(--app-sidebar-active-text); width: 2rem; height: 2rem;">
                    <i class="fas fa-user" style="font-size: 0.75rem;"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Header Desktop: solo usuario a la derecha (sin bloque izquierdo) -->
<div class="d-none d-lg-block sticky top-0 z-40 border-bottom" style="background: var(--app-sidebar); border-color: rgba(148,163,184,0.2) !important;">
    <div class="px-4 py-3">
        <div class="d-flex align-items-center justify-content-end">
            <div class="d-flex align-items-center gap-3">
                <div class="text-end d-none d-xl-block">
                    <p class="mb-0 small fw-medium text-white">{{ auth()->user()->nombre_completo }}</p>
                    <p class="mb-0 small" style="color: var(--app-sidebar-text);">{{ auth()->user()->nombre_perfil }} • RUN: {{ auth()->user()->run }}</p>
                </div>
                <div class="rounded-circle p-2 d-flex align-items-center justify-content-center" style="background: var(--app-sidebar-active); color: var(--app-sidebar-active-text);">
                    <i class="fas fa-user"></i>
                </div>
            </div>
        </div>
    </div>
</div>
<style>
/* Hover del botón menú en header */
[onclick="toggleMenu()"]:hover { background: rgba(51,65,85,0.5) !important; color: #f1f5f9 !important; }
</style>
