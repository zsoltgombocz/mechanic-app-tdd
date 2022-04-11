@extends('master')

@section('content')
    <div class="row">
        <div class="col-6 mb-4">
            <form action="worksheets/search" method="POST" id="searchWorksheets">
                @csrf
                <div class="input-group">
                    <input type="text" name="search" class="form-control" id="search" placeholder="Munkalap keresése"
                        value="{{ isset($search) ? $search : '' }}">
                    <button class="btn btn-primary"
                        onclick="event.preventDefault(); document.getElementById('searchWorksheets').submit()"><i
                            class="bi bi-search"></i></button>
                </div>
            </form>
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
                <div class="card">
                    <div class="card-body">
                        <div class="card-title d-flex justify-content-between align-items-center m-0 p-0">
                            <h4 class="card-title">
                                {{ isset($ws->customer_name) ? $ws->customer_name . ' - ' . $ws->id : 'Munkalap - ' . $ws->id }}

                            </h4>
                            <span class="">{{ $ws->created_at }}</span>
                        </div>
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
                                        {{ $ws->mechanic->vehicle_license }}
                                    @else
                                        {!! "<span class='text-danger'>Nincs megadva!</span>" !!}
                                    @endif
                                </p>
                                <p><b>Gyártmány:</b>
                                    @if (isset($ws->vehicle_brand))
                                        {{ $ws->mechanic->vehicle_brand }}
                                    @else
                                        {!! "<span class='text-danger'>Nincs megadva!</span>" !!}
                                    @endif
                                </p>
                                <p><b>Típus:</b>
                                    @if (isset($ws->vehicle_model))
                                        {{ $ws->mechanic->vehicle_model }}
                                    @else
                                        {!! "<span class='text-danger'>Nincs megadva!</span>" !!}
                                    @endif
                                </p>
                            </div>
                        @endif
                    </div>
                    <div class="card-footer d-flex justify-content-end">
                        <a class="nav-link" href="worksheets/{{ $ws->id }}">
                            Megnyitás <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection
