<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <form action="{{ route('transit.upload') }}" method="POST" enctype="multipart/form-data">
        @csrf
        {{-- <input type="text" value="test" name="test"> --}}
        <input type="file" name="file_upload">
        <button type="submit">Upload</button>
    </form>

    <a href="{{ route('daily-journal.insert-journals') }}">copy journal</a>

    {{-- <form action="{{ route('daily-balance.store') }}" method="POST">
        @csrf
        <label for="date">Date</label>
        <input type="date" name="date">
        
        <button type="submit">Submit</button>
    </form> --}}
</body>
</html>