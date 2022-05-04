@extends('master')

@section('content')
    <div class="row justify-content-center">
        <div class="col-12 col-lg-8">
            <div class="card mb-3">

                <div class="card-body">

                    <div class="pt-4 pb-2">
                        <p class="text-center small">Az adatok mentésre kerülnek ha a lap alján lévő gommbal el mented!</p>
                    </div>
                    @if ($errors->any())
                        @foreach ($errors->all() as $error)
                            <div class="col-12 text-danger text-center">{{ $error }}</div>
                        @endforeach
                    @endif
                    <form class="row g-3 needs-validation" novalidate method="POST"
                        action="/worksheets/update/{{ $worksheet->id }}">
                        @csrf
                        <h5 class="ms-2 card-title">Munkalap adatok</h5>

                        <div class="col-6 mb-3">
                            <label for="loginid" class="form-label">Belépési azonosító</label>
                            <div class="input-group">
                                <input type="text" name="login_id" class="form-control" id="loginid" disabled
                                    value={{ $worksheet->creator->login_id }}>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <label for="mechanicname" class="form-label">Létrehozó neve</label>
                            <div class="input-group">
                                <input type="text" name="name" class="form-control" id="creatorname" disabled
                                    value="{{ $worksheet->creator->name }}">
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <label for="mechanic_id" class="form-label">Szerelő:</label>
                            <div class="col-12">
                                @if (auth()->user()->role_id === 1)
                                    <select name="mechanic_id" class="form-select" id="mechanic_id"
                                        {{ $worksheet->closed === 0 ? '' : 'disabled' }}>
                                        <option {{ $worksheet->mechanic === null ? 'selected' : '' }} value="-1">
                                        </option>
                                        @foreach ($mechanics as $m)
                                            <option
                                                {{ $worksheet->mechanic !== null && $worksheet->mechanic->id === $m->id ? 'selected' : '' }}
                                                value="{{ $m->id }}">{{ $m->name }}</option>
                                        @endforeach
                                    </select>
                                @else
                                    <select name="mechanic_id" class="form-select" id="mechanic_id" disabled>
                                        <option selected value="-1">
                                            {{ $worksheet->mechanic !== null ? $worksheet->mechanic->name : '' }}</option>
                                    </select>
                                @endif
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <label for="created_at" class="form-label">Létrehozás időpontja:</label>
                            <div class="input-group">
                                <input type="datetime-local" name="created_at" class="form-control" id="created_at"
                                    value={{ $worksheet->created_at_html }} disabled>
                            </div>
                        </div>

                        <hr />
                        <h5 class="ms-2 card-title">Tulajdonos adatai</h5>
                        <div class="col-12 col-md-4 mb-3">
                            <label for="customer_name" class="form-label">Tulajdonos neve:</label>
                            <div class="input-group">
                                <input type="text" name="customer_name" class="form-control" id="customer_name"
                                    value="{{ $worksheet->customer_name }}"
                                    {{ auth()->user()->role_id === 1 && $worksheet->closed === 0 ? '' : 'disabled' }}>
                            </div>
                        </div>
                        <div class="col-12 col-md-8 mb-3">
                            <label for="customer_addr" class="form-label">Lakcím:</label>
                            <div class="input-group">
                                <input type="text" name="customer_addr" class="form-control" id="customer_addr"
                                    value="{{ $worksheet->customer_addr }}"
                                    {{ auth()->user()->role_id === 1 && $worksheet->closed === 0 ? '' : 'disabled' }}>
                            </div>
                        </div>
                        <div class="col-12 col-md-4 mb-3">
                            <label for="vehicle_brand" class="form-label">Gyártmány:</label>
                            <div class="input-group">
                                <input type="text" name="vehicle_brand" class="form-control" id="vehicle_brand"
                                    value="{{ $worksheet->vehicle_brand }}"
                                    {{ auth()->user()->role_id === 1 && $worksheet->closed === 0 ? '' : 'disabled' }}>
                            </div>
                        </div>
                        <div class="col-12 col-md-4 mb-3">
                            <label for="vehicle_model" class="form-label">Típus:</label>
                            <div class="input-group">
                                <input type="text" name="vehicle_model" class="form-control" id="vehicle_model"
                                    value="{{ $worksheet->vehicle_model }}"
                                    {{ auth()->user()->role_id === 1 && $worksheet->closed === 0 ? '' : 'disabled' }}>
                            </div>
                        </div>
                        <div class="col-12 col-md-4 mb-3">
                            <label for="vehicle_license" class="form-label">Rendszám:</label>
                            <div class="input-group">
                                <input type="text" name="vehicle_license" class="form-control" id="vehicle_license"
                                    value="{{ $worksheet->vehicle_license }}"
                                    {{ auth()->user()->role_id === 1 && $worksheet->closed === 0 ? '' : 'disabled' }}>
                            </div>
                        </div>
                        <hr />
                        <h5 class=" ms-2 card-title">Felvett munkfolyamatok</h5>
                        <div class="col-12">
                            Általános szervíz: <span class="badge bg-primary rounded-pill">&nbsp;</span> |
                            Alkatrész: <span class="badge bg-warning rounded-pill">&nbsp;</span> |
                            Anyag: <span class="badge bg-secondary rounded-pill">&nbsp;</span> |
                            Egyedi: <span class="badge bg-info rounded-pill">&nbsp;</span>
                        </div>
                        @if (count($labour_processes) !== 0)
                            @foreach ($labour_processes as $process)
                                @switch($process['type'])
                                    @case(1)
                                        @if ($process['maintenance_id'] != null)
                                            <div class="list-group-item-primary list-group-item border-1 d-flex flex-row">
                                                <div class="d-flex w-100 justify-content-center flex-column">
                                                    <h5 class="mb-1">{{ $process['name'] }}</h5>
                                                    <small>Időtartam: {{ $process['time_span'] }} óra</small>
                                                </div>
                                                <div class="d-flex w-100 justify-content-end align-items-center">
                                                    <div class="d-flex flex-column align-items-end">
                                                        <small>{{ $process['created_at'] }}</small>
                                                        <small class="text-danger fw-bold price">{{ $process['price'] }}
                                                            ft</small>
                                                    </div>
                                                    <div class="d-flex ms-4 me-2 pointer justify-content-center align-items-center">
                                                        <a class="delete-process"
                                                            id="delete-process-{{ $worksheet->id }}-{{ $process['type'] }}-{{ $process['id'] }}"
                                                            href="#" onclick="event.preventDefault();">
                                                            <i class="bi bi-trash"></i>
                                                        </a>
                                                    </div>

                                                </div>
                                            </div>
                                        @else
                                            <div class="list-group-item-info list-group-item border-1 d-flex flex-row">
                                                <div class="d-flex w-100 justify-content-center flex-column">
                                                    <h5 class="mb-1">{{ $process['name'] }}</h5>
                                                    <p>{{ $process['info'] }}</p>
                                                    <small>Időtartam: {{ $process['time_span'] }} óra</small>
                                                </div>
                                                <div class="d-flex w-100 justify-content-end align-items-center">
                                                    <div class="d-flex flex-column align-items-end">
                                                        <small>{{ $process['created_at'] }}</small>
                                                        <small class="text-danger fw-bold price">{{ $process['price'] }}
                                                            ft</small>
                                                    </div>
                                                    <div class="d-flex ms-4 me-2 pointer justify-content-center align-items-center">
                                                        <a class="delete-process"
                                                            id="delete-process-{{ $worksheet->id }}-{{ $process['type'] }}-{{ $process['id'] }}"
                                                            href="#" onclick="event.preventDefault();">
                                                            <i class="bi bi-trash"></i>
                                                        </a>
                                                    </div>

                                                </div>
                                            </div>
                                        @endif
                                    @break

                                    @case(2)
                                        <div class="list-group-item-warning list-group-item border-1 d-flex flex-row">
                                            <div class="d-flex w-100 justify-content-center flex-column">
                                                <h5 class="mb-1">{{ $process['name'] }}</h5>
                                                <small>Darab: {{ $process['amount'] }} db</small>
                                            </div>
                                            <div class="d-flex w-100 justify-content-end align-items-center">
                                                <div class="d-flex flex-column align-items-end">
                                                    <small>{{ $process['created_at'] }}</small>
                                                    <small class="text-danger fw-bold price">{{ $process['price'] }}
                                                        ft</small>
                                                </div>
                                                <div class="d-flex ms-4 me-2 pointer justify-content-center align-items-center">
                                                    <a class="delete-process"
                                                        id="delete-process-{{ $worksheet->id }}-{{ $process['type'] }}-{{ $process['id'] }}"
                                                        href="#" onclick="event.preventDefault();">
                                                        <i class="bi bi-trash"></i>
                                                    </a>
                                                </div>

                                            </div>
                                        </div>
                                    @break

                                    @case(3)
                                        <div class="list-group-item-secondary list-group-item border-1 d-flex flex-row">
                                            <div class="d-flex w-100 justify-content-center flex-column">
                                                <h5 class="mb-1">{{ $process['name'] }}</h5>
                                                <small>Darab: {{ $process['amount'] }} db</small>
                                            </div>
                                            <div class="d-flex w-100 justify-content-end align-items-center">
                                                <div class="d-flex flex-column align-items-end">
                                                    <small>{{ $process['created_at'] }}</small>
                                                    <small class="text-danger fw-bold price">{{ $process['price'] }}
                                                        ft</small>
                                                </div>
                                                <div class="d-flex ms-4 me-2 pointer justify-content-center align-items-center">
                                                    <a class="delete-process"
                                                        id="delete-process-{{ $worksheet->id }}-{{ $process['type'] }}-{{ $process['id'] }}"
                                                        href="#" onclick="event.preventDefault();">
                                                        <i class="bi bi-trash"></i>
                                                    </a>
                                                </div>

                                            </div>
                                        </div>
                                    @break

                                    @default
                                @endswitch
                            @endforeach
                            <hr class="bg-danger m-0 fw-bold mt-4" />
                            <div class='col-12 d-flex align-items-start justify-content-end text-danger fw-bold'
                                id="priceoflabours">Összesen: xy
                                ft</div>
                        @else
                            <div class="col-12 d-flex text-danger justify-content-center">
                                Nincs megjelenthető munkafolyamat!
                            </div>
                        @endif
                        @if ($worksheet['closed'] == 0)
                            <div class="col-12">
                                <button class="btn btn-primary w-100" id="addProcess">Munkafolyamat felvétele</button>
                            </div>
                        @endif
                        <ul id="processHolder" class="list-group list-group"></ul>
                        @if (auth()->user()->role_id === 1)
                            <hr />
                            <div class="col-6">
                                <div class="form-check form-switch mb-3">
                                    <label class="form-check-label" for="flexSwitchCheckDefault">Munkalap zárolása</label>
                                    <input class="form-check-input" type="checkbox" id="flexSwitchCheckDefault"
                                        name="closed" {{ $worksheet['closed'] === 1 ? 'checked' : '' }}>
                                </div>
                            </div>
                            <div class="col-6">
                                <select name="payment" class="form-select mb-3" id="payment">
                                    <option {{ !isset($worksheet['payment']) ? 'selected' : '' }} value="-1">Még nincs
                                        fizetve</option>
                                    <option {{ $worksheet['payment'] === 0 ? 'selected' : '' }} value="0">Készpénz
                                    </option>
                                    <option {{ $worksheet['payment'] === 1 ? 'selected' : '' }} value="1">Bankkártya
                                    </option>
                                </select>
                            </div>
                            <hr />
                        @endif
                        <div class="col-12">
                            <button class="btn btn-success w-100" type="submit">Mentés</button>
                            @if ($worksheet['payment'] != -1 && $worksheet['closed'] == 1)
                                <button class="btn btn-secondary w-100 mt-2"
                                    onclick="event.preventDefault(); window.open('/worksheets/{{ $worksheet['id'] }}/pdf', '_blank');">Nyomtatás</button>
                            @endif
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
@endsection
