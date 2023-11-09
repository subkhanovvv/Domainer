<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <title>Domainer|search domains</title>
</head>

<body>
    <br>
     <div class="bg-light">Domains</div>
    <br>
    <textarea name="" id="" cols="120" rows="2"placeholder="search domain..." class="bg-light"></textarea>
    <br>
    <button class="btn btn-primary">search</button>
    <br>

    <table class="table">
        <thead class="table-primary">
            <tr>
                <th>Названия</th>
                <th>Статус</th>
                <th>Дата окончания</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($domain as $item)
            <tr>
                <td>{{$item->$name}}</td>
                <td>{{$item->$status}}</td>
                <td>{{$item->$name}}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
