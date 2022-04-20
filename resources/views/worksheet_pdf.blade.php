<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="{{ asset('/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
    <script src="{{ asset('/js/main.js') }}" defer></script>

    <title>Munkalap nyomtatása</title>
</head>

<body class="p-5">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 text-center">
                <h2>Munkalap</h2>
            </div>
        </div>
        <div class="row mt-2">
            <div class="col-12 p-2 border-bottom-0 border-dark border text-center">
                <b>Tulajdonos adatai</b>
            </div>
        </div>
        <div class="row">
            <div class="col-6 p-2 border-1 border-dark border d-flex align-items-center justify-content-evenly">
                <b>Tulajdonos neve: </b> {{ $worksheet->customer_name }}
            </div>
            <div class="col-6 p-2 border-dark border border-start-0 d-flex align-items-center justify-content-evenly">
                <b>Tulajdonos lakcíme: </b> {{ $worksheet->customer_addr }}
            </div>
        </div>
        <div class="row mt-5">
            <div class="col-12 p-2 border-bottom-0 border-dark border text-center">
                <b>Jármű adatai</b>
            </div>
        </div>
        <div class="row">
            <div class="col-4 p-2 border-1 border-dark border text-center">
                <b>Rendszám:</b> {{ $worksheet->vehicle_license }}
            </div>
            <div class="col-4 p-2 border-start-0 border-dark border text-center">
                <b>Gyártmány:</b> {{ $worksheet->vehicle_brand }}
            </div>
            <div class="col-4 p-2 border-start-0 border-dark border text-center">
                <b>Típus:</b> {{ $worksheet->vehicle_model }}
            </div>
        </div>
        <div class="row mt-5">
            <div class="col-12 p-2 border-bottom-0 border-dark border text-center">
                <b>Elvégzett munkafolyamatok / anyaghasználat</b>
            </div>
        </div>
        <div class="row align-items-center border-1 border-dark border">
            @foreach ($labours as $l)
                @if ($l['type'] != 4)
                    <div class="col-1 p-2">
                        {{ $l['created_at'] }}
                    </div>
                    <div class="col-4 p-2 text-center">
                        <b>{{ $l['name'] }} {!! isset($l['serial']) ? '(Ssz:' . $l['serial'] . ')' : '' !!}</b>
                    </div>
                    <div class="col-4 p-2 text-center">
                        @if (isset($l['time_span']))
                            {{ $l['time_span'] }} óra
                        @else
                            {{ $l['amount'] }} db
                        @endif
                    </div>
                    <div class="col-3 p-2 text-end pe-4">
                        <b>{{ $l['price'] }} Ft</b>
                    </div>
                @else
                    <div class="col-1 p-2">
                        {{ $l['created_at'] }}
                    </div>
                    <div class="col-3 p-2 text-center">
                        <b>{{ $l['name'] }} {!! isset($l['serial']) ? '(Ssz:' . $l['serial'] . ')' : '' !!}</b>
                    </div>
                    <div class="col-4 p-2 text-center">
                        {{ $l['info'] }}
                    </div>
                    <div class="col-1 p-2 text-center">
                        @if (isset($l['time_span']))
                            {{ $l['time_span'] }} óra
                        @else
                            {{ $l['amount'] }} db
                        @endif
                    </div>
                    <div class="col-3 p-2 text-end pe-4">
                        <b>{{ $l['price'] }} Ft</b>
                    </div>
                @endif
            @endforeach
        </div>
        <div class="row mt-1">
            <div class="col-12 text-end">
                <h4>Összesen: {{ $price }} Ft</h4>
            </div>
        </div>
        <div class="row mt-5">
            <div class="col-12 p-2 border-bottom-0 border-dark border text-center">
                <b>További adatok</b>
            </div>
        </div>
        <div class="row text-center" style="height: 150px">
            <div class="col-6 p-2 border-1 border-dark border">
                Cég béjegzője
            </div>
            <div class="col-6 p-2 border-start-0 border-dark border">
                Tulajdonos aláírása
            </div>
        </div>
        <div class="row mt-5">
            <div class="col-12 p-2">
                <b>Kiállítás dátuma: {{ \Carbon\Carbon::now() }}</b>
            </div>
        </div>
    </div>
</body>

</html>
