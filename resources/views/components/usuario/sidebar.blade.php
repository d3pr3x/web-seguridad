@php
    use App\Services\MenuBuilder;
    $menuBuilder = app(MenuBuilder::class);
    $user = $menuBuilder->user();
    $homeRoute = $menuBuilder->getHomeRoute();
    $isHomeActive = request()->routeIs($homeRoute);
    $sections = $menuBuilder->buildMenu();
    $acordeon = $menuBuilder->getItemsAcordeon();
    $acordeonLabel = $menuBuilder->tierSecundario() === 'supervisor' ? 'Supervisión' : ($menuBuilder->tierSecundario() === 'usuario' ? 'Usuario' : '');
@endphp
<aside class="d-none d-lg-flex flex-column position-fixed top-0 end-0 h-100 z-30 overflow-hidden" style="width: 256px; background: var(--app-sidebar);">
    <div class="p-3 border-bottom" style="border-color: rgba(148,163,184,0.2) !important;">
        <p class="mb-0 small fw-semibold text-white text-truncate" title="Portal {{ $user?->nombre_perfil }}">Portal {{ $user?->nombre_perfil ?? 'Usuario' }}</p>
    </div>
    <nav class="flex-grow-1 overflow-auto py-2">
        <ul class="nav flex-column px-2">
            @foreach($sections as $section)
                @if($section['label'] !== '')
                    <li class="nav-item"><hr class="my-2" style="border-color: rgba(148,163,184,0.3);"></li>
                @endif
                @foreach($section['items'] as $item)
                    @if($item['type'] === 'link' && ($item['route'] ?? null))
                        @php $linkActive = collect($item['routes_active'] ?? [])->contains(fn ($r) => request()->routeIs($r)); @endphp
                        <li class="nav-item">
                            <a href="{{ route($item['route']) }}" class="nav-link d-flex align-items-center py-2 rounded {{ $linkActive ? 'active' : '' }}" style="{{ $linkActive ? 'color: var(--app-sidebar-active-text);' : 'color: #cbd5e1;' }}">
                                <i class="fas {{ $item['icon'] }} me-2" style="width: 1.25rem;"></i>
                                <span>{{ $item['label'] }}</span>
                            </a>
                        </li>
                    @elseif($item['type'] === 'collapse' && !empty($item['children'] ?? []))
                        <li class="nav-item">
                            @php
                                $collapseId = 'sidebar-' . $item['key'];
                                $isActive = collect($item['routes_active'] ?? [])->contains(fn ($r) => request()->routeIs($r));
                                if (($item['key'] ?? '') === 'gestion') {
                                    $isActive = $isActive && !request()->routeIs('admin.rondas.reporte');
                                }
                            @endphp
                            <a class="nav-link d-flex align-items-center py-2 rounded {{ $isActive ? '' : 'collapsed' }}" style="color: #cbd5e1;" data-bs-toggle="collapse" data-bs-target="#{{ $collapseId }}" aria-expanded="{{ $isActive ? 'true' : 'false' }}">
                                <i class="fas fa-chevron-right me-2 sidebar-chevron" style="width: 1.25rem; transition: transform 0.2s;"></i>
                                <i class="fas {{ $item['icon'] }} me-2" style="width: 1.25rem;"></i>
                                <span>{{ $item['label'] }}</span>
                            </a>
                            <div class="collapse {{ $isActive ? 'show' : '' }}" id="{{ $collapseId }}">
                                <ul class="nav flex-column ms-4 ps-2 border-start border-secondary">
                                    @foreach($item['children'] as $child)
                                        @php $childActive = collect($child['routes_active'] ?? [])->contains(fn ($r) => request()->routeIs($r)); @endphp
                                        <li class="nav-item">
                                            <a href="{{ $child['route'] ? route($child['route']) : '#' }}" class="nav-link py-1 small rounded {{ $childActive ? 'active' : '' }}" style="{{ $childActive ? 'color: var(--app-sidebar-active-text);' : 'color: #94a3b8;' }}">{{ $child['label'] }}</a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </li>
                    @endif
                @endforeach
            @endforeach

            @if(count($acordeon) > 0 && $acordeonLabel)
                <li class="nav-item"><hr class="my-2" style="border-color: rgba(148,163,184,0.3);"></li>
                <li class="nav-item">
                    @php
                        $acordeonId = 'sidebar-acordeon-secundario';
                        $acordeonActive = collect($acordeon)->contains(fn ($i) => collect($i['routes_active'] ?? [])->contains(fn ($r) => request()->routeIs($r)));
                    @endphp
                    <a class="nav-link d-flex align-items-center py-2 rounded {{ $acordeonActive ? '' : 'collapsed' }}" style="color: #cbd5e1;" data-bs-toggle="collapse" data-bs-target="#{{ $acordeonId }}" aria-expanded="{{ $acordeonActive ? 'true' : 'false' }}">
                        <i class="fas fa-chevron-right me-2 sidebar-chevron" style="width: 1.25rem; transition: transform 0.2s;"></i>
                        <i class="fas fa-layer-group me-2" style="width: 1.25rem;"></i>
                        <span>{{ $acordeonLabel }}</span>
                    </a>
                    <div class="collapse {{ $acordeonActive ? 'show' : '' }}" id="{{ $acordeonId }}">
                        <ul class="nav flex-column ms-4 ps-2 border-start border-secondary">
                            @foreach($acordeon as $item)
                                @if($item['type'] === 'link' && ($item['route'] ?? null))
                                    @php $aActive = collect($item['routes_active'] ?? [])->contains(fn ($r) => request()->routeIs($r)); @endphp
                                    <li class="nav-item">
                                        <a href="{{ route($item['route']) }}" class="nav-link py-1 small rounded {{ $aActive ? 'active' : '' }}" style="{{ $aActive ? 'color: var(--app-sidebar-active-text);' : 'color: #94a3b8;' }}">{{ $item['label'] }}</a>
                                    </li>
                                @elseif($item['type'] === 'collapse' && !empty($item['children'] ?? []))
                                    @foreach($item['children'] as $child)
                                        @php $cActive = collect($child['routes_active'] ?? [])->contains(fn ($r) => request()->routeIs($r)); @endphp
                                        <li class="nav-item">
                                            <a href="{{ $child['route'] ? route($child['route']) : '#' }}" class="nav-link py-1 small rounded {{ $cActive ? 'active' : '' }}" style="{{ $cActive ? 'color: var(--app-sidebar-active-text);' : 'color: #94a3b8;' }}">{{ $child['label'] }}</a>
                                        </li>
                                    @endforeach
                                @endif
                            @endforeach
                        </ul>
                    </div>
                </li>
            @endif

            <li class="nav-item"><hr class="my-2" style="border-color: rgba(148,163,184,0.3);"></li>
            <li class="nav-item">
                <button type="button" class="nav-link d-flex align-items-center py-2 rounded w-100 text-start border-0 bg-transparent" style="color: #94a3b8;" onclick="if (typeof triggerPwaInstall === 'function') triggerPwaInstall();">
                    <i class="fas fa-download me-2" style="width: 1.25rem;"></i>
                    <span>Instalar aplicación</span>
                </button>
            </li>
            <li class="nav-item">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="nav-link d-flex align-items-center py-2 rounded w-100 text-start border-0 bg-transparent" style="color: #94a3b8;">
                        <i class="fas fa-sign-out-alt me-2" style="width: 1.25rem;"></i>
                        <span>Cerrar sesión</span>
                    </button>
                </form>
            </li>
        </ul>
    </nav>
</aside>
<style>
aside .nav-link { text-decoration: none; }
aside .nav-link:hover { background: rgba(51,65,85,0.5); color: #f1f5f9 !important; }
aside .nav-link[data-bs-toggle="collapse"]:not(.collapsed) .sidebar-chevron { transform: rotate(90deg); }
aside .nav-link[aria-expanded="true"] .sidebar-chevron { transform: rotate(90deg); }
aside [data-bs-toggle="collapse"].nav-link,
aside [data-bs-toggle="collapse"].nav-link span,
aside [data-bs-toggle="collapse"].nav-link i.fas { color: #cbd5e1 !important; }
aside [data-bs-toggle="collapse"].nav-link:hover,
aside [data-bs-toggle="collapse"].nav-link:hover span,
aside [data-bs-toggle="collapse"].nav-link:hover i.fas { color: #f1f5f9 !important; }
aside .collapse .nav-link { color: #94a3b8 !important; }
aside .collapse .nav-link.active { color: var(--app-sidebar-active-text) !important; }
aside .collapse .nav-link:hover { color: #f1f5f9 !important; }
aside .collapse.show { visibility: visible !important; }
aside .collapse .collapse .nav-link,
aside .collapse .nav-link span { opacity: 1 !important; visibility: visible !important; }
</style>
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('aside [data-bs-toggle="collapse"]').forEach(function(btn) {
        btn.addEventListener('click', function() {
            this.classList.toggle('collapsed', !this.getAttribute('aria-expanded') || this.getAttribute('aria-expanded') === 'false');
        });
        if (btn.getAttribute('aria-expanded') === 'true') btn.classList.remove('collapsed');
    });
});
</script>
