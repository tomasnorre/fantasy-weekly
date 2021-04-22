<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <title>Fantasy</title>
    <link rel="icon" href="{{ asset('favicon.png') }}">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body class="antialiased">
<div class="flex flex-col">
    <div class="-my-2 overflow- x-auto sm:-mx-6 lg:-mx-8">
        <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
            <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                    <tr>
                        <th scope="col"
                            class="px-2 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            #
                        </th>
                        <th scope="col"
                            class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        </th>
                        <th scope="col"
                            class="px-2 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Player
                        </th>
                        <th scope="col"
                            class="px-2 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Today
                        </th>
                        <th scope="col"
                            class="px-2 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Score
                        </th>
                        <th scope="col"
                            class="px-2 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Teams
                        </th>
                    </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr><td colspan="6" class="bg-gray-400 text-white whitespace-nowrap text-center text-m font-bold">Leading Score {{ $leadingScore }}</td></tr>
                    @foreach ($players as $player)
                        @if ($player['sortOrder']>=$cut && $first == true)
                            <tr><td colspan="6" class="bg-gray-400 text-white whitespace-nowrap text-center text-m font-bold">Projected Cut</td></tr>
                            @php
                                $first = false;
                            @endphp
                        @endif
                        <tr>
                            <td class="px-2 py-2 whitespace-nowrap">
                                {{ $player['position'] }}
                            </td>
                            <td class="whitespace-nowrap">
                                @if($player['moved']['direction']=='down')
                                    <span class="moved red">&#9660;</span><span
                                        class="moved">{{ $player['moved']['moved'] }}</span>
                                @endif
                                @if($player['moved']['direction']=='up')
                                    <span class="moved green">&#9650;</span><span
                                        class="moved">{{ $player['moved']['moved'] }}</span>
                                @endif
                            </td>
                            <td class="px-2 py-2 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $player['lastname'] }}</div>
                                <div class="text-sm text-gray-500">{{ $player['firstname'] }}</div>
                            </td>
                            <td class="px-2 py-2 whitespace-nowrap text-sm text-gray-500">
                                {{ $player['today'] }} {{ $player['played'] }}
                            </td>
                            <td class="px-2 py-2 whitespace-nowrap text-right text-sm font-medium score {{ $player['scoreColor'] }}">
                                {{ $player['score'] }}
                            </td>
                            <td class="px-2 py-2 whitespace-nowrap text-center text-sm font-medium teams">
                                <div class="flex -space-x-1 overflow-hidden">
                                    @foreach ( $player['teams'] as $team)
                                        <img
                                            class="inline-block h-6 w-6 rounded-full ring-2 ring-white"
                                            src="{{ asset("images/$team.png") }}"
                                            alt="">
                                    @endforeach
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</body>
</html>
