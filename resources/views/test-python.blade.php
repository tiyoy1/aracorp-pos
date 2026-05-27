<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>Laravel + Python trial</h1>

    @if(session('result_upper')) 
        <div style="background:green; color: white; padding: 10px;">
            Python returned : {{ session('result_upper') }}
        </div>
    @endif

    <form action="/process" method="POST">
        @csrf
        <input type="text" placeholder="isi apa kek" name="upper_text">
        <button type="submit">Send to PyUpper</button>
    </form>

    @if(session('result_reverse')) 
        <div style="background:black; color: white; padding: 10px;">
            Python returned : {{ session('result_reverse') }}
        </div>
    @endif

    <form action="/reverse" method="POST">
        @csrf
        <input type="text" placeholder="reversal" name="reverse_text">
        <button type="submit">Send to PyReverse</button>
    </form>

</body>
</html>