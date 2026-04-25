<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: Arial;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid #000;
            padding: 6px;
            font-size: 12px;
            text-align: center;
        }

        th {
            background: #f0f0f0;
        }
    </style>
</head>
<body>

<h2>
    Historial de PPA ({{ $desde }} - {{ $hasta }})
</h2>

<table>
    <thead>
        <tr>
            <th>Profesor</th>
            <th>Cat Docente</th>
            <th>Cat Científica</th>
            <th>Departamento</th>
            <th>Carrera</th>
            <th>Año</th>
        </tr>
    </thead>

    <tbody>
        @foreach($data as $item)
            <tr>
                <td>{{ $item['nombre'] }} {{ $item['apellidos'] }}</td>
                <td>{{ $item['catDocente'] }}</td>
                <td>{{ $item['catCientifica'] }}</td>
                <td>{{ $item['departamento'] }}</td>
                <td>{{ $item['carrera'] }}</td>
                <td>{{ $item['anio'] }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

</body>
</html>
