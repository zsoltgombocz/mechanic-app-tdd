@extends('master');

@section('content')
    <div class="row justify-content-center">
        <div class="col-12 col-md-6">
            <div class="card mb-3">

                <div class="card-body">

                    <div class="pt-4 pb-2">
                        <p class="text-center small">A szerkesztés csakis a mentés után lesz érvényben! Nem megfelelő adatok
                            bevitele esetén az eredeti adatok fognak újra itt megjelenni!</p>
                    </div>
                    @if ($errors->any())
                        @foreach ($errors->all() as $error)
                            <div class="col-12 text-danger text-center">{{ $error }}</div>
                        @endforeach
                    @endif
                    <form class="row g-3 needs-validation" novalidate method="POST"
                        action="/mechanics/update/{{ $user['id'] }}">
                        @csrf
                        <div class="col-12 mb-3">
                            <label for="loginid" class="form-label">Belépési azonosító</label>
                            <div class="input-group has-validation">
                                <input type="text" name="login_id" class="form-control" id="loginid" required
                                    value={{ $user['login_id'] }}>
                                <div class="invalid-feedback">A mező kitöltése kötelező!</div>
                            </div>
                        </div>

                        <div class="col-12 mb-3">
                            <label for="mechanicname" class="form-label">Szerelő neve</label>
                            <div class="input-group has-validation">
                                <input type="text" name="name" class="form-control" id="mechanicname" required
                                    value="{{ $user['name'] }}">
                                <div class=" invalid-feedback">A mező kitöltése kötelező!
                                </div>
                            </div>
                        </div>

                        <div class="col-12 mb-3">
                            <label for="rang" class="form-label">Szerelő jogosultsága</label>
                            <div class="col-12">
                                <select name="role_id" class="form-select" id="rang">
                                    <option {{ $user['role_id'] == 2 ? 'selected' : '' }} value="2">Szerelő</option>
                                    <option {{ $user['role_id'] == 1 ? 'selected' : '' }} value="1">Műhelyfőnök</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-12 text-center">
                            <button class="btn btn-success w-100 mb-3" type="submit">Mentés</button>
                            @if ($user->id !== auth()->user()->id)
                                <button type="button" class="btn btn-danger w-50"
                                    onclick="event.preventDefault(); document.getElementById('delete').submit();">Szerelő
                                    törlése</button>
                            @else
                                <button type="button" class="btn btn-danger w-50"">Nem
                                            törölhető! Ez te vagy!</button>
     @endif
                        </div>
                    </form>
                    @if ($user->id !== auth()->user()->id)
                        <form action="/mechanics/delete/{{ $user['id'] }}" method="POST" id="delete">
                            @csrf
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
