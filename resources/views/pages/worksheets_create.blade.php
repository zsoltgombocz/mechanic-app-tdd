@extends('master')

@section('content')
    <div class="row justify-content-center">
        <div class="col-12 col-md-6">
            <div class="card mb-3">

                <div class="card-body">

                    <div class="pt-4 pb-2">
                        <p class="text-center small">Adatok megadása nélkül egy üres munkalap kerül létrehozásra! Ez a
                            késöbbiekben is kitölthető!</p>
                    </div>
                    @if ($errors->any())
                        @foreach ($errors->all() as $error)
                            <div class="col-12 text-danger text-center">{{ $error }}</div>
                        @endforeach
                    @endif
                    <form class="row g-3 needs-validation" novalidate method="POST" action="/worksheets/create">
                        @csrf
                        <div class="col-6 mb-3">
                            <label for="loginid" class="form-label">Belépési azonosító</label>
                            <div class="input-group">
                                <input type="text" name="login_id" class="form-control" id="loginid" disabled
                                    value={{ auth()->user()->login_id }}>
                            </div>
                        </div>

                        <div class="col-6 mb-3">
                            <label for="mechanicname" class="form-label">Létrehozó neve</label>
                            <div class="input-group">
                                <input type="text" name="name" class="form-control" id="creatorname" disabled
                                    value="{{ auth()->user()->name }}">
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <label for="mechanic_id" class="form-label">Szerelő:</label>
                            <div class="col-12">
                                <select name="mechanic_id" class="form-select" id="mechanic_id">
                                    <option selected value="-1"></option>
                                    @foreach ($mechanics as $m)
                                        <option value="{{ $m->id }}">{{ $m->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <label for="created_at" class="form-label">Létrehozás időpontja:</label>
                            <div class="input-group">
                                <input type="datetime-local" name="created_at" class="form-control" id="created_at"
                                    value={{ $datetime }} disabled>
                            </div>
                        </div>

                        <hr />
                        <div class="col-12 col-md-4 mb-3">
                            <label for="customer_name" class="form-label">Tulajdonos neve:</label>
                            <div class="input-group">
                                <input type="text" name="customer_name" class="form-control" id="customer_name"
                                    value="{{ old('customer_name') }}">
                            </div>
                        </div>
                        <div class="col-12 col-md-8 mb-3">
                            <label for="customer_addr" class="form-label">Lakcím:</label>
                            <div class="input-group">
                                <input type="text" name="customer_addr" class="form-control" id="customer_addr"
                                    value="{{ old('customer_addr') }}">
                            </div>
                        </div>
                        <div class="col-12 col-md-4 mb-3">
                            <label for="vehicle_brand" class="form-label">Gyártmány:</label>
                            <div class="input-group">
                                <input type="text" name="vehicle_brand" class="form-control" id="vehicle_brand"
                                    value="{{ old('vehicle_brand') }}">
                            </div>
                        </div>
                        <div class="col-12 col-md-4 mb-3">
                            <label for="vehicle_model" class="form-label">Típus:</label>
                            <div class="input-group">
                                <input type="text" name="vehicle_model" class="form-control" id="vehicle_model"
                                    value="{{ old('vehicle_model') }}">
                            </div>
                        </div>
                        <div class="col-12 col-md-4 mb-3">
                            <label for="vehicle_license" class="form-label">Rendszám:</label>
                            <div class="input-group">
                                <input type="text" name="vehicle_license" class="form-control" id="vehicle_license"
                                    value="{{ old('vehicle_license') }}">
                            </div>
                        </div>
                        <hr />

                        <div class="col-12">
                            <button class="btn btn-success w-100" type="submit">Létrehozás</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
@endsection
