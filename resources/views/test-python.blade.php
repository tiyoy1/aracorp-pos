<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Pythonism</title>
</head>
<body class="bg-gray-950 min-h-screen text-white flex items-center justify-center p-6">
    <div class="w-full max-w-2xl space-y-6">
        {{--Header--}}
        <div class="text-center mb-8">
            <h1 class="text-4xl font-bold text-white">🐍Python Tools</h1>
            <p class="text-gray-400 mt-2">Powered by Laravel & Python</p>
        </div>

        {{-- TEXT PROCESSSOR --}}
        <div class="bg-gray-900 rounded-2xl p-6 shadow-lg border border-gray-800">
            <h2 class="text-xl font-semibold text-white mb-1">✏️Text Processor</h2>
            <p class="text-gray-400 text-sm mb-4">Uppercase or reverse your text</p>

            {{-- Uppercase Result --}}
            @if(session('result_upper')) 
                <div class="bg-green-900 border border-green-600 text-green-300 rounded-xl px-4 py-3 mb-3 text-sm">
                    ⬆️Uppercased: <span class="font-bold">{{ session('result_upper') }}</span>
                </div>
            @endif

            {{-- Reverse Result --}}
            @if(session('result_reverse')) 
                <div class="bg-blue-900 border border-blue-600 text-blue-300 rounded-xl px-4 py-3 mb-3 text-sm">
                    🔁Reversed: <span class="font-bold">{{ session('result_reverse') }}</span>
                </div>
            @endif

            {{-- Uppercase Form --}}
            <form action="/process" method="POST" class="flex gap-2 mb-3">
                @csrf
                <input type="text" placeholder="Type something to uppercase..." name="upper_text" class="flex-1 bg-gray-800 border border-gray-700 rounded-xl px-4 py-2 text-sm text-white placeholder-gray-500 focus:outline-none focus:border-green-500">
                <button type="submit" class="bg-green-600 hover:bg-green-500 text-white px-4 py-2 rounded-xl text-sm font-medium transition">Uppercase</button>
            </form>

            {{-- Reverse Form --}}
            <form action="/reverse" method="POST" class="flex gap-2">
                @csrf
                <input type="text" placeholder="Type something to reverse..." name="reverse_text" class="flex-1 bg-gray-800 border border-gray-700 rounded-xl px-4 py-2 text-sm text-white placeholder-gray-500 focus:out;ine-none focus:border-blue-500">
                <button type="submit" class="bg-blue-600 hover:bg-blue-500 text-white px-4 py-2 rounded-xl text-sm font-medium transition">Reverse</button>
            </form>
        </div>

        {{-- LANGUAGE DETECTOR CARD --}}
        <div class="bg-gray-900 rounded-2xl p-6 shadow-lg border border-gray-800">
            <h2 class="text-xl font-semibold text-white mb-1">🌍 Language Detector</h2>
            <p class="text-gray-400 text-sm mb-4">Detects what language your text is in</p>

            {{-- Error --}}
            @if(session('error'))
                <div class="bg-red-900 border border-red-600 text-red-300 rounded-xl px-4 py-3 mb-3 text-sm">
                    ⚠️ {{ session('error') }}
                </div>
            @endif

            {{-- Result --}}
            @if(session('lang_result'))
                @php $result = session('lang_result') @endphp 
                <div class="bg-purple-900 border border-purple-600 text-purple-200 rounded-xl px-4 py-4 mb-4">
                    <p class="text-lg font-bold mb-2">🗣️ Detected: 
                        <span class="text-purple-300">
                            {{ strtoupper($result['result_detection']) }}
                        </span>
                    </p>
                    <p class="text-xs text-purple-400 mb-1">Confidence breakdown: </p>
                    @foreach($result['lang_probability'] as $prob)
                        <p class="text-sm text-purple-300">{{ $prob }}</p>
                    @endforeach
                </div>
            @endif

            {{-- Form --}}
            <form action="/lang_detect" method="POST" class="space-y-3">
                @csrf
                <input type="text" placeholder="Paste text in any language..." name="predetect_text" class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-3 text-sm text-white placeholder-gray-500 focus:outline-none focus:border-purple-500 resize-none">
                <button type="submit" class="w-full bg-purple-600 hover:bg-purple-500 text-white py-2 rounded-xl text-sm font-medium transition">Detect Language</button>
            </form>
        </div>
        {{-- Footer --}}
        <p class="text-center text-gray-600 text-xs">
            Built with Laravel + Python Flask
        </p>
    </div>
</body>
</html>