<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" href="css/app.css">
</head>
<body>
    <div class="header">
        <div class="search-form">
            <a href="{{ route('index') }}" style="font-size: 30px; color: black; text-decoration: none;">Home</a>
            <form action="{{ route('search') }}" method="POST" style="margin-top: 20px;">
                @csrf
                <input type="text" class="search-view-input" name="title" placeholder="Search...">
                <input type="submit" class="search-view-btn" value="Search">
            </form>
        </div>
    </div>
    <div class="container">
        <div class="content">
            <div class="search-results">
                @foreach ($warnings as $warning)
                    <div class="search-result">
                            Title: {{ $warning->title }}
                            <br>
                            Text: {{ $warning->text }}
                            <br>
                            @if ($warning->warningImage != '[]')
                                Images:
                                <br>
                                @foreach ($warning->warningImage as $image)
                                    <img src="{{ $image->path }}" style="max-width: 500px;" alt="">
                                @endforeach
                                <br>
                            @endif
                            Category: {{ $warning->category->title }}
                            <br>
                            Created at: {{ $warning->created_at }}
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</body>
</html>