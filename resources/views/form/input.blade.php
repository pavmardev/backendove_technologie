<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="/example/result" method="POST">
        @csrf
        <label for="number">Zadajte číslo</label>
        <input type="text" id="number" name="n">
        <button type="submit">Odoslať</button>
    </form>
@if(isset($list))
    @foreach($list as $number)
        <p>{{ $number }}</p>
    @endforeach

@endif
</body>
</html>
