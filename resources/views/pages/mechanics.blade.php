@extends('master')

@section('content')
    <div class="row">
        <div class="col-12">

            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Szerelők listája</h5>
                    <!-- Default Table -->
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">ID</th>
                                <th scope="col">Név</th>
                                <th scope="col">Azonosító</th>
                                <th scope="col">Engedélyek</th>
                                <th scope="col" class="text-center">#</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr>
                                    <th scope="row">{{ $user->id }}</th>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->login_id }}</td>
                                    <td>{{ $user->roles->name }}</td>
                                    <td class="d-flex justify-content-evenly">
                                        <a href="/mechanics/{{ $user->id }}">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        @if (auth()->user()->id !== $user->id)
                                            <form action="/mechanics/delete/{{ $user['id'] }}" method="POST" id="delete">
                                                @csrf
                                            </form>
                                            <a href="#"
                                                onclick="event.preventDefault(); document.getElementById('delete').submit()">
                                                <i class="bi bi-trash"></i>
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <!-- End Default Table Example -->
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-md-4">
                    <button onclick="event.preventDefault(); window.location = '/mechanics/create'"
                        class="btn btn-primary w-100" data-bs-toggle="modal"
                        data-bs-target="#mechanicadd">Hozzáadás</button>
                </div>
            </div>
        @endsection
