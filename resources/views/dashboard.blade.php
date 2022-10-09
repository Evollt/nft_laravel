<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    Добро пожаловать

    <form action="{{ route('createWarning') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="text" name="title" placeholder="Название">
        @error('title')
            {{ $message }}
        @enderror
        <input type="text" name="text" placeholder="Текст">
        <input type="file" name="images[]" multiple accept="image/jpeg,image/png">
        <p>Выберите категорию обращения</p>
        <select name="category">
            <option disabled selected>Выбрать категорию</option>
            @foreach($data as $value)
                <option value="{{ $value->title }}">{{ $value->title }}</option>
            @endforeach
        </select>
        <input type="submit" value="Отправить">
    </form>
    @if(session('success'))
        <h1>{{ session('success') }}</h1>
    @endif

    <form action="{{ route('subscribe') }}" method="GET">
        @if(Auth::user()->sending_status)
            <button>Отписаться</button>
        @else
            <button>Подписаться</button>
        @endif
    </form>
    <img src="{{ asset('/storage/' . 'warnings/c6Jzre7kruMLgpYeamvDM3rFbeHT2P9pYKfwDoJl.jpg') }}" alt="dkjdskfljdsflj">
    {{ $data }}
</body>
</html>