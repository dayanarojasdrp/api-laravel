<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Listado de PPA</title>
</head>
<body>

<h2 style="font-family: Arial; font-size: 14pt;">Listado de PPA</h2>

<table style="width:100%; border-collapse: collapse; font-family: Arial; font-size: 12pt;">
    <thead>
        <tr>
            <th style="border:1px solid black;">Profesor</th>
            <th style="border:1px solid black;">Cat Docente</th>
            <th style="border:1px solid black;">Cat Científica</th>
            <th style="border:1px solid black;">Departamento</th>
            <th style="border:1px solid black;">Carrera</th>
            <th style="border:1px solid black;">Año</th>
        </tr>
    </thead>

    <tbody>
        @foreach($data as $item)
        <tr>
            <td style="border:1px solid black;">
                {{ $item['nombre'] }} {{ $item['apellidos'] }}
            </td>
            <td style="border:1px solid black;">{{ $item['catDocente'] }}</td>
            <td style="border:1px solid black;">{{ $item['catCientifica'] }}</td>
            <td style="border:1px solid black;">{{ $item['departamento'] }}</td>
            <td style="border:1px solid black;">{{ $item['carrera'] }}</td>
            <td style="border:1px solid black;">{{ $item['anio'] }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

</body>
</html>
