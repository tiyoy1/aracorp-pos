<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>Laravel + Python trial</h1>

    @if(session('result')) 
        <div style="background:green; color: white; padding: 10px;">
            Python returned : {{ session('result') }}
        </div>
    @endif

    <form action="/process" method="POST">
        @csrf
        <input type="text" placeholder="isi apa kek" name="text">
        <button type="submit">Send to PyUpper</button>
    </form>

    @if(session('result')) 
        <div style="background:black; color: white; padding: 10px;">
            Python returned : {{ session('result') }}
        </div>
    @endif

    <form action="/reverse" method="POST">
        @csrf
        <input type="text" placeholder="reversal" name="text">
        <button type="submit">Send to PyReverse</button>
    </form>

</body>
</html>