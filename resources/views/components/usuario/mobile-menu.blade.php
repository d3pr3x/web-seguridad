@php
    use App\Services\MenuBuilder;
    $menuBuilder = app(MenuBuilder::class);
    $user = $menuBuilder->user();
    $homeRoute = $menuBuilder->getHomeRoute();
    $isHomeActive = request()->routeIs($homeRoute);
    $sections = $menuBuilder->buildMenu();
    $acordeon = $menuBuilder->getItemsAcordeon();
    $acordeonLabel = $menuBuilder->tierSecundario() === 'supervisor' ? 'Supervisión' : ($menuBuilder->tierSecundario() === 'usuario' ? 'Usuario' : '');
    $activeColor = 'color: #5eead4;';
    $inactiveColor = 'color: #e2e8f0;';
    $sublinkColor = 'color: #cbd5e1;';
@endphp
<!-- Menú Móvil (solo visible en móvil) - Abre por la derecha -->
<div id="sideMenu" class="d-lg-none position-fixed top-0 end-0 h-100 z-50 overflow-hidden transition-all duration-300 shadow side-menu-mobile" style="width: 280px; max-width: 85vw; transform: translateX(100%);">
    <div class="p-3 border-bottom d-flex align-items-center justify-content-between side-menu-header" style="border-color: rgba(226,232,240,0.25) !important;">
        <h2 class="mb-0 fw-bold" style="color: #f8fafc; font-size: 1.1rem;">Menú</h2>
        <button type="button" onclick="toggleMenu()" class="btn btn-link p-2 text-decoration-none rounded" style="color: #f1f5f9;">
            <i class="fas fa-times" style="font-size: 1.25rem;"></i>
        </button>
    </div>
    <div class="flex-grow-1 overflow-auto py-2 px-2 side-menu-body">
        <ul class="nav flex-column side-menu-nav">
            @foreach($sections as $section)
                @if($section['label'] !== '')
                    <li class="nav-item"><hr class="my-2" style="border-color: rgba(226,232,240,0.25);"></li>
                @endif
                @foreach($section['items'] as $item)
                    @if($item['type'] === 'link' && ($item['route'] ?? null))
                        @php $linkActive = collect($item['routes_active'] ?? [])->contains(fn ($r) => request()->routeIs($r)); @endphp
                        <li class="nav-item">
                            <a href="{{ route($item['route']) }}" class="nav-link side-menu-link d-flex align-items-center py-2 rounded {{ $linkActive ? 'active' : '' }}" style="{{ $linkActive ? $activeColor : $inactiveColor }}" onclick="toggleMenu()">
                                <i class="fas {{ $item['icon'] }} me-2" style="width: 1.25rem;"></i><span>{{ $item['label'] }}</span>
                            </a>
                        </li>
                    @elseif($item['type'] === 'collapse' && !empty($item['children'] ?? []))
                        @php
                            $collapseId = 'mobile-' . $item['key'];
                            $isActive = collect($item['routes_active'] ?? [])->contains(fn ($r) => request()->routeIs($r));
                            if (($item['key'] ?? '') === 'gestion') { $isActive = $isActive && !request()->routeIs('admin.rondas.reporte'); }
                        @endphp
                        <li class="nav-item">
                            <a class="nav-link side-menu-link side-menu-toggle d-flex align-items-center py-2 rounded {{ $isActive ? '' : 'collapsed' }}" style="{{ $inactiveColor }}" data-bs-toggle="collapse" data-bs-target="#{{ $collapseId }}" aria-expanded="{{ $isActive ? 'true' : 'false' }}">
                                <i class="fas fa-chevron-right me-2 mobile-chevron" style="width: 1.25rem; transition: transform 0.2s;"></i>
                                <i class="fas {{ $item['icon'] }} me-2" style="width: 1.25rem;"></i><span>{{ $item['label'] }}</span>
                            </a>
                            <div class="collapse {{ $isActive ? 'show' : '' }}" id="{{ $collapseId }}">
                                <ul class="nav flex-column ms-4 ps-2 border-start" style="border-color: rgba(226,232,240,0.2) !important;">
                                    @foreach($item['children'] as $child)
                                        @php $childActive = collect($child['routes_active'] ?? [])->contains(fn ($r) => request()->routeIs($r)); @endphp
                                        <li class="nav-item">
                                            <a href="{{ $child['route'] ? route($child['route']) : '#' }}" class="nav-link side-menu-sublink py-2 small rounded {{ $childActive ? 'active' : '' }}" style="{{ $childActive ? $activeColor : $sublinkColor }}" onclick="toggleMenu()">{{ $child['label'] }}</a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </li>
                    @endif
                @endforeach
            @endforeach

            @if(count($acordeon) > 0 && $acordeonLabel)
                <li class="nav-item"><hr class="my-2" style="border-color: rgba(226,232,240,0.25);"></li>
                <li class="nav-item">
                    @php
                        $acordeonId = 'mobile-acordeon-secundario';
                        $acordeonActive = collect($acordeon)->contains(fn ($i) => collect($i['routes_active'] ?? [])->contains(fn ($r) => request()->routeIs($r)));
                    @endphp
                    <a class="nav-link side-menu-link side-menu-toggle d-flex align-items-center py-2 rounded {{ $acordeonActive ? '' : 'collapsed' }}" style="{{ $inactiveColor }}" data-bs-toggle="collapse" data-bs-target="#{{ $acordeonId }}" aria-expanded="{{ $acordeonActive ? 'true' : 'false' }}">
                        <i class="fas fa-chevron-right me-2 mobile-chevron" style="width: 1.25rem; transition: transform 0.2s;"></i>
                        <i class="fas fa-layer-group me-2" style="width: 1.25rem;"></i><span>{{ $acordeonLabel }}</span>
                    </a>
                    <div class="collapse {{ $acordeonActive ? 'show' : '' }}" id="{{ $acordeonId }}">
                        <ul class="nav flex-column ms-4 ps-2 border-start" style="border-color: rgba(226,232,240,0.2) !important;">
                            @foreach($acordeon as $item)
                                @if($item['type'] === 'link' && ($item['route'] ?? null))
                                    @php $aActive = collect($item['routes_active'] ?? [])->contains(fn ($r) => request()->routeIs($r)); @endphp
                                    <li class="nav-item">
                                        <a href="{{ route($item['route']) }}" class="nav-link side-menu-sublink py-2 small rounded {{ $aActive ? 'active' : '' }}" style="{{ $aActive ? $activeColor : $sublinkColor }}" onclick="toggleMenu()">{{ $item['label'] }}</a>
                                    </li>
                                @elseif($item['type'] === 'collapse' && !empty($item['children'] ?? []))
                                    @foreach($item['children'] as $child)
                                        @php $cActive = collect($child['routes_active'] ?? [])->contains(fn ($r) => request()->routeIs($r)); @endphp
                                        <li class="nav-item">
                                            <a href="{{ $child['route'] ? route($child['route']) : '#' }}" class="nav-link side-menu-sublink py-2 small rounded {{ $cActive ? 'active' : '' }}" style="{{ $cActive ? $activeColor : $sublinkColor }}" onclick="toggleMenu()">{{ $child['label'] }}</a>
                                        </li>
                                    @endforeach
                                @endif
                            @endforeach
                        </ul>
                    </div>
                </li>
            @endif

            <li class="nav-item" id="menu-item-instalar-app">
                <button type="button" class="nav-link side-menu-link d-flex align-items-center py-2 rounded w-100 text-start border-0 bg-transparent" style="{{ $inactiveColor }}" id="btn-instalar-app" onclick="toggleMenu(); if (typeof triggerPwaInstall === 'function') triggerPwaInstall();">
                    <i class="fas fa-download me-2" style="width: 1.25rem;"></i><span>Instalar aplicación</span>
                </button>
            </li>
            <li class="nav-item"><hr class="my-2" style="border-color: rgba(226,232,240,0.25);"></li>
            <li class="nav-item">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="nav-link side-menu-link d-flex align-items-center py-2 rounded w-100 text-start border-0 bg-transparent" style="color: #cbd5e1;">
                        <i class="fas fa-sign-out-alt me-2" style="width: 1.25rem;"></i><span>Cerrar sesión</span>
                    </button>
                </form>
            </li>
        </ul>
    </div>
</div>
<div id="menuOverlay" class="d-lg-none position-fixed top-0 start-0 w-100 h-100 z-40 bg-black bg-opacity-50" style="display: none; left: 0;" onclick="toggleMenu()"></div>
<style>
#sideMenu.side-menu-mobile,
#sideMenu.side-menu-mobile .side-menu-header,
#sideMenu.side-menu-mobile .side-menu-body,
#sideMenu.side-menu-mobile .side-menu-nav,
#sideMenu.side-menu-mobile ul.nav { background: #0f172a !important; }
#sideMenu.side-menu-mobile .nav-item { background: transparent !important; }
.side-menu-mobile .nav-link { text-decoration: none !important; }
.side-menu-mobile .side-menu-link,
.side-menu-mobile .side-menu-link span,
.side-menu-mobile .side-menu-link i.fas { color: #e2e8f0 !important; }
.side-menu-mobile .side-menu-link:hover,
.side-menu-mobile .side-menu-link:hover span,
.side-menu-mobile .side-menu-link:hover i.fas { background: transparent !important; color: #f8fafc !important; }
.side-menu-mobile .side-menu-link.active,
.side-menu-mobile .side-menu-link.active span,
.side-menu-mobile .side-menu-link.active i.fas { color: #5eead4 !important; background: transparent !important; }
.side-menu-mobile .side-menu-sublink,
.side-menu-mobile .side-menu-sublink span { color: #cbd5e1 !important; font-size: 0.9rem !important; }
.side-menu-mobile .side-menu-sublink:hover { color: #f1f5f9 !important; background: transparent !important; }
.side-menu-mobile .side-menu-sublink.active { color: #5eead4 !important; background: transparent !important; }
.side-menu-mobile .side-menu-toggle,
.side-menu-mobile .side-menu-toggle span,
.side-menu-mobile .side-menu-toggle i.fas { color: #e2e8f0 !important; }
.side-menu-mobile .side-menu-toggle:hover,
.side-menu-mobile .side-menu-toggle[aria-expanded="true"] { color: #f8fafc !important; background: transparent !important; }
.side-menu-mobile .side-menu-toggle[data-bs-toggle="collapse"].collapsed .mobile-chevron { transform: none; }
.side-menu-mobile .side-menu-toggle[data-bs-toggle="collapse"]:not(.collapsed) .mobile-chevron { transform: rotate(90deg); }
#sideMenu.show { transform: translateX(0) !important; }
.side-menu-mobile .collapse.show { visibility: visible !important; }
.side-menu-mobile .collapse .nav-link { opacity: 1 !important; visibility: visible !important; }
.side-menu-mobile .nav-link:active,
.side-menu-mobile .nav-link:focus,
.side-menu-mobile .nav-link:hover { background: transparent !important; box-shadow: none !important; outline: none !important; }
.side-menu-mobile .nav-link { -webkit-tap-highlight-color: transparent; }
.side-menu-mobile .side-menu-link.active,
.side-menu-mobile .side-menu-sublink.active { -webkit-tap-highlight-color: transparent; }
</style>
<script>
function toggleMenu() {
    var menu = document.getElementById('sideMenu');
    var overlay = document.getElementById('menuOverlay');
    if (menu.classList.contains('show')) {
        menu.classList.remove('show');
        overlay.style.display = 'none';
    } else {
        menu.classList.add('show');
        overlay.style.display = 'block';
    }
}
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('#sideMenu [data-bs-toggle="collapse"]').forEach(function(btn) {
        btn.addEventListener('click', function() { this.classList.toggle('collapsed', this.getAttribute('aria-expanded') !== 'true'); });
        if (btn.getAttribute('aria-expanded') === 'true') btn.classList.remove('collapsed');
    });
});
</script>
