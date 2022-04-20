@extends('master')

@section('content')
    <div class="row">
        <div class="col-6 mb-4">
            <div class="input-group">
                <input type="text" name="search" class="form-control" id="search" placeholder="Munkalap keresése"
                    value="{{ isset($search) ? $search : '' }}">
                <button class="btn btn-primary" id="filter_search_btn"><i class="bi bi-search"></i></button>
            </div>
            <div class="row">
                <div class="col-6 d-flex mt-2 align-items-center">
                    <span class="me-2">Dátum:</span>
                    <select id="filter_date" class="form-select" aria-label="Default select example">
                        <option value="0" {{ $order == 'desc' ? 'selected' : '' }}>Csökkenő</option>
                        <option value="1" {{ $order == 'asc' ? 'selected' : '' }}>Növekvő</option>
                    </select>

                </div>
                <div class="col-6 d-flex mt-2 align-items-center">
                    <span class="me-2">Mutasd:</span>
                    <select id="filter_closed" class="form-select" aria-label="Default select example">
                        <option value="0" {{ !isset($closed) ? 'selected' : '' }}>Mind</option>
                        <option value="1" {{ $closed === 'false' ? 'selected' : '' }}>Nem zárolt</option>
                        <option value="2" {{ $closed === 'true' ? 'selected' : '' }}>Zárolt</option>

                    </select>
                </div>
            </div>
        </div>
        @if (auth()->user()->role_id === 1)
            <div class="col-6 mb-4">
                <button onclick="event.preventDefault(); window.location = '/worksheets/create'"
                    class="btn btn-primary w-100">Munkalap létrehozása</button>
            </div>
        @endif

        @if (count($worksheets) === 0)
            <div class="w-100 text-danger text-center">Nincs megjeleníthető munkalap!</div>
        @endif
        <div class="clear-fix"></div>
        @foreach ($worksheets as $ws)
            <div class="col-12 col-md-6">
                <div
                    class="card
                    {{ $ws->closed == 1 && $ws->payment == -1 ? 'border-danger border-1 border' : '' }}
                    {{ $ws->closed == 1 && $ws->payment != -1 ? 'border-success border-1 border' : '' }}
                ">
                    <div class="card-body">
                        <div class="card-title d-flex justify-content-between align-items-center m-0 p-0">
                            <h4 class="card-title">
                                {{ isset($ws->customer_name) ? $ws->customer_name . ' - ' . $ws->id : 'Munkalap - ' . $ws->id }}

                            </h4>
                            <span class="">{{ $ws->created_at }}</span>
                        </div>
                        <p><b>Lakcím:</b>
                            @if (isset($ws->customer_addr))
                                {{ $ws->customer_addr }}
                            @else
                                {!! "<span class='text-danger'>Nincs megadva!</span>" !!}
                            @endif
                        </p>
                        <hr />
                        <p><b>Létrehozta:</b> {{ $ws->creator->name }} (LoginID: {{ $ws->creator->login_id }})
                        </p>
                        <p><b>Szerelő:</b>
                            @if (isset($ws->mechanic->name))
                                {{ $ws->mechanic->name }}
                            @else
                                {!! "<span class='text-danger'>Nincs hozzárendelve!</span>" !!}
                            @endif
                        </p>
                        @if (isset($ws->vehicle_license) || isset($ws->vehicle_brand) || isset($ws->vehicle_model))
                            <hr />
                            <div class="row">
                                <p><b>Rendszám:</b>
                                    @if (isset($ws->vehicle_license))
                                        {{ $ws->vehicle_license }}
                                    @else
                                        {!! "<span class='text-danger'>Nincs megadva!</span>" !!}
                                    @endif
                                </p>
                                <p><b>Gyártmány:</b>
                                    @if (isset($ws->vehicle_brand))
                                        {{ $ws->vehicle_brand }}
                                    @else
                                        {!! "<span class='text-danger'>Nincs megadva!</span>" !!}
                                    @endif
                                </p>
                                <p><b>Típus:</b>
                                    @if (isset($ws->vehicle_model))
                                        {{ $ws->vehicle_model }}
                                    @else
                                        {!! "<span class='text-danger'>Nincs megadva!</span>" !!}
                                    @endif
                                </p>
                            </div>
                        @endif
                        @if ($ws->payment != -1)
                            <hr />
                            <p><b>Fizetve:</b>
                                @if ($ws->payment == 0)
                                    <span class='text-success'>Kézpénz</span>
                                @else
                                    <span class='text-success'>Bankkártya</span>
                                @endif
                            </p>
                        @endif
                        @if ($ws->payment == -1 && $ws->closed == 1)
                            <b>
                                <p class="text-danger w-100 text-center">A munkalap zárolva, de nincs fizetve!</p>
                            </b>
                        @elseif ($ws->payment != -1 && $ws->closed == 0)
                            <b>
                                <p class="text-danger w-100 text-center">A munkalap fizetve, de nincs zárolva!</p>
                            </b>
                        @elseif ($ws->payment != -1 && $ws->closed == 1)
                            <b>
                                <p class="text-success w-100 text-center">A munkalap fizetve, és zárolva!</p>
                                <p class="w-100 text-center">
                                    <i class="bi bi-printer fs-2 pointer"
                                        onclick="event.preventDefault(); window.location = '/worksheets/{{ $ws->id }}/pdf';"></i>
                                </p>
                            </b>
                        @endif
                    </div>
                    <div class="card-footer d-flex justify-content-between">
                        <div classs="d-flex">
                            Szerkesztve: {{ $ws->updated_at }} <br />
                            Zárolva:
                            @if (isset($ws->closed_at))
                                {{ $ws->closed_at }}
                            @else
                                {!! "<span class='text-danger'>-</span>" !!}
                            @endif
                        </div>
                        <a class="nav-link" href="worksheets/{{ $ws->id }}">
                            Megnyitás <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection
