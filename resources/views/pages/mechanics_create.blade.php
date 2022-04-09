@extends('master')

@section('content')
    <div class="row justify-content-center">
        <div class="col-12 col-md-6">
            <div class="card mb-3">

                <div class="card-body">

                    <div class="pt-4 pb-2">
                        <p class="text-center small">Az itt megadott adatok megtekinthetőek a késöbbiekben.</p>
                    </div>
                    @if ($errors->any())
                        @foreach ($errors->all() as $error)
                            <div class="col-12 text-danger text-center">{{ $error }}</div>
                        @endforeach
                    @endif
                    <form class="row g-3 needs-validation" novalidate method="POST" action="/mechanics/create">
                        @csrf
                        <div class="col-12 mb-3">
                            <label for="loginid" class="form-label">Belépési azonosító</label>
                            <div class="input-group has-validation">
                                <input type="text" name="login_id" class="form-control" id="loginid" required
                                    value={{ old('login_id') }}>
                                <div class="invalid-feedback">A mező kitöltése kötelező!</div>
                            </div>
                        </div>

                        <div class="col-12 mb-3">
                            <label for="mechanicname" class="form-label">Szerelő neve</label>
                            <div class="input-group has-validation">
                                <input type="text" name="name" class="form-control" id="mechanicname" required
                                    value={{ old('name') }}>
                                <div class="invalid-feedback">A mező kitöltése kötelező!</div>
                            </div>
                        </div>

                        <div class="col-12 mb-3">
                            <label for="rang" class="form-label">Szerelő jogosultsága</label>
                            <div class="col-12">
                                <select name="role_id" class="form-select" id="rang">
                                    <option {{ old('role_id') == 2 ? 'selected' : '' }} value="2">Szerelő</option>
                                    <option {{ old('role_id') == 1 ? 'selected' : '' }} value="1">Műhelyfőnök</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-12 mb-3">
                            <label for="password" class="form-label">Jelszó</label>
                            <input type="input" name="password" class="form-control" id="password" required
                                value={{ old('password') }}>
                            <div class="invalid-feedback">A mező kitöltése kötelező!</div>
                        </div>

                        <div class="col-12">
                            <button class="btn btn-success w-100" type="submit">Mentés</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
@endsection
