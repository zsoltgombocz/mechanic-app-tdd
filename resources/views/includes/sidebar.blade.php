<!-- ======= Sidebar ======= -->
<aside id="sidebar" class="sidebar">
    <ul class="sidebar-nav" id="sidebar-nav">
        @if (Request::path() != '/')
            <li class="nav-item">
                <a class="nav-link" href="/">
                    <i class="bi bi-house"></i>
                    <span>Kezdőlap</span>
                </a>
            </li>
        @endif
        @if (auth()->user()->role_id == 1)
            <li class="nav-item">
                <a class="nav-link" href="#">
                    <i class="bi bi-clipboard-plus"></i>
                    <span>Munkalap létrehozás</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Request::path() == 'mechanics' ? 'active' : '' }}" href="/mechanics">
                    <i class="bi bi-people"></i>
                    <span>Szerelők kezelése</span>
                </a>
            </li>
        @endif

        <li class="nav-item">
            <a class="nav-link collapsed" data-bs-target="#components-nav" data-bs-toggle="collapse" href="#">
                <i class="bi bi-clipboard"></i><span>Munkalapok</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="components-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                <li>
                    <a href="components-alerts.html">
                        <i class="bi bi-circle"></i><span>Munkalap 1</span>
                    </a>
                </li>
                <li>
                    <a href="components-accordion.html">
                        <i class="bi bi-circle"></i><span>Munkalap 2</span>
                    </a>
                </li>
                <li>
                    <a href="components-badges.html">
                        <i class="bi bi-circle"></i><span>Munkalap 3</span>
                    </a>
                </li>
            </ul>
        </li>
    </ul>
</aside>
