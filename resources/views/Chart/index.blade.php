<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <title>Fantasy - Chart</title>
    <link rel="icon" href="{{ asset('favicon.png') }}">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
@include('_partials.navigation')
<div id="chart">
</div>

<script type="text/javascript" src='https://cdn.plot.ly/plotly-latest.min.js'></script>
<script type="text/javascript">
    @foreach ($data as $team => $value)
        var {{ $team }} = {
            x: [1,2,3,4,5,6,7,8,9,10,11,12,13,14],
            y: [
                @foreach ($value as $val)
                    {{ $val }},
                @endforeach
            ],
            name: '{{ $team }}',
            mode: 'lines+markers',
            line: {
                width: 2
            }
        }
    @endforeach

    var config = {responsive: true}
    var data = [Mathias, Kasper, Morten, Tomas];

    var layout = {
        title: 'Points Development'
    };

    Plotly.newPlot('chart', data, layout, config);
</script>
<script src="{{ asset('js/app.js')}}"></script>
</body>
</html>
