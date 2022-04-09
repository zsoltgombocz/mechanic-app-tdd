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
                <a class="nav-link" data-bs-target="#mechanics-nav" data-bs-toggle="collapse" href="#">
                    <i class="bi bi-people"></i>
                    <span>Szerelők kezelése</span>
                    <i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul id="mechanics-nav"
                    class="nav-content collapse {{ Request::segment('1') == 'mechanics' ? 'show' : '' }}"
                    data-bs-parent="#sidebar-nav">
                    <li>
                        <a href="/mechanics" class="{{ Request::segment('1') == 'mechanics' ? 'active' : '' }}">
                            <i class="bi bi-circle"></i><span>Szerelők listázása</span>
                        </a>
                    </li>
                    <li>
                        <a href="/mechanics/create">
                            <i class="bi bi-circle"></i><span>Szerelők hozzáadása</span>
                        </a>
                    </li>
                </ul>
            </li>
        @endif

        <li class="nav-item">
            <a class="nav-link collapsed" data-bs-target="#workseehts-nav" data-bs-toggle="collapse" href="#">
                <i class="bi bi-clipboard"></i><span>Munkalapok</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="workseehts-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                @foreach (session()->get('assigned_worksheets') as $ws)
                    <li>
                        <a href="/worksheets/{{ $ws->id }}">
                            <i
                                class="bi bi-circle"></i><span>{{ isset($ws->customer_name) ? $ws->customer_name . ' - ' . ws->id : 'Munkalap - ' . $ws->id }}</span>
                        </a>
                    </li>
                @endforeach
                @if (auth()->user()->role_id == 1)
                    <hr class="dropdown-divider">
                    <li class="nav-heading">Általad létrehozott:</li>
                    @foreach (session()->get('created_worksheets') as $ws)
                        <li>
                            <a href="/worksheets/{{ $ws->id }}">
                                <i
                                    class="bi bi-circle"></i><span>{{ isset($ws->customer_name) ? $ws->customer_name . ' - ' . ws->id : 'Munkalap - ' . $ws->id }}</span>
                            </a>
                        </li>
                    @endforeach
                @endif
            </ul>
        </li>
    </ul>
</aside>
