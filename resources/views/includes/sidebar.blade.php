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
                <a class="nav-link collapsed" data-bs-target="#mechanics-nav" data-bs-toggle="collapse" href="#">
                    <i class="bi bi-people"></i>
                    <span>Szerelők kezelése</span>
                    <i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul id="mechanics-nav"
                    class="nav-content collapse {{ Request::segment('1') == 'mechanics' ? 'show' : '' }}"
                    data-bs-parent="#sidebar-nav">
                    <li>
                        <a href="/mechanics"
                            class="{{ Request::segment('1') == 'mechanics' && !Request::segment('2') ? 'active' : '' }}">
                            <i class="bi bi-circle"></i><span>Szerelők listázása</span>
                        </a>
                    </li>
                    <li>
                        <a href="/mechanics/create"
                            class="{{ Request::segment('1') == 'mechanics' && Request::segment('2') == 'create' ? 'active' : '' }}">
                            <i class="
                            bi bi-circle"></i><span>Szerelők hozzáadása</span>
                        </a>
                    </li>
                </ul>
            </li>
        @endif

        <li class="nav-item">
            <a class="nav-link collapsed" data-bs-target="#workseehts-nav" data-bs-toggle="collapse" href="#">
                <i class="bi bi-clipboard"></i><span>Munkalapok</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="workseehts-nav"
                class="nav-content collapse {{ Request::segment('1') == 'worksheets' ? 'show' : '' }} "
                data-bs-parent="#sidebar-nav">
                <li>
                    <a href="/worksheets"
                        class="{{ Request::segment('1') == 'worksheets' && !Request::segment('2') ? 'active' : '' }}">
                        <i class="bi bi-circle"></i><span>Összes munkalap</span>
                    </a>
                    @if (auth()->user()->role_id === 1)
                        <a href="/worksheets/create"
                            class="{{ Request::segment('1') == 'worksheets' && Request::segment('2') == 'create' ? 'active' : '' }}">
                            <i class="bi bi-circle"></i>
                            <span>Munkalap létrehozás</span>
                        </a>
                    @endif
                </li>
                <hr class="dropdown-divider">
                <li class="nav-heading">Hozzád rendelve:</li>
                @if (count(session()->get('assigned_worksheets')) === 0)
                    <div class="text-danger text-center w-100">Nincs munkalap!</div>
                @endif
                @foreach (session()->get('assigned_worksheets') as $ws)
                    <li>
                        <a href="/worksheets/{{ $ws->id }}">
                            <i
                                class="bi bi-circle"></i><span>{{ isset($ws->customer_name) ? $ws->customer_name . ' - ' . $ws->id : 'Munkalap - ' . $ws->id }}</span>
                        </a>
                    </li>
                @endforeach
                @if (auth()->user()->role_id == 1)
                    <hr class="dropdown-divider">
                    <li class="nav-heading">Általad létrehozott:</li>
                    @if (count(session()->get('created_worksheets')) === 0)
                        <div class="text-danger text-center w-100">Nincs munkalap!</div>
                    @endif
                    @foreach (session()->get('created_worksheets') as $ws)
                        <li>
                            <a href="/worksheets/{{ $ws->id }}">
                                <i class="bi bi-circle"></i>
                                <span
                                    class="d-flex justify-content-center align-items-center {{ $ws->closed == 1 && $ws->payment != -1 ? 'text-success' : '' }} {{ $ws->closed == 1 && $ws->payment == -1 ? 'text-danger' : '' }}">
                                    {{ isset($ws->customer_name) ? $ws->customer_name . ' - ' . $ws->id : 'Munkalap - ' . $ws->id }}
                                    {!! $ws->closed == 1 && $ws->payment == -1 ? ' - <i class="bi bi-x-lg fs-5"></i>' : '' !!}
                                    {!! $ws->closed == 1 && $ws->payment != -1 ? ' - <i class="bi bi-check-lg fs-5"></i>' : '' !!}
                                </span>
                            </a>
                        </li>
                    @endforeach
                @endif
            </ul>
        </li>
    </ul>
</aside>
