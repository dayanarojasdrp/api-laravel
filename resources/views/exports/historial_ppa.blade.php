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
            table-layout: fixed;
        }

        th, td {
            border: 1px solid #000;
            padding: 4px;
            font-size: 10px;
            vertical-align: middle;
            word-wrap: break-word;
        }

        th {
            background: #f0f0f0;
            text-align: center;
        }

        .profesor { width: 22%; }
        .cat { width: 10%; text-align: center; }
        .departamento { width: 18%; }
        .carrera { width: 22%; }
        .ano { width: 9%; text-align: center; }
        .academico { width: 9%; text-align: center; }
    </style>
</head>
<body>

<h2>
    Historial de PPA ({{ $desde }} - {{ $hasta }})
</h2>

<table>
    <thead>
        <tr>
            <th class="profesor">Profesor</th>
            <th class="cat">Cat Docente</th>
            <th class="cat">Cat Científica</th>
            <th class="departamento">Departamento</th>
            <th class="carrera">Carrera</th>
            <th class="ano">Año PPA</th>
<th class="academico">Año Académico</th>
        </tr>
    </thead>

    <tbody>
        @foreach($data as $item)
            <tr>
                <td class="profesor">{{ $item['nombre'] }} {{ $item['apellidos'] }}</td>
                <td class="cat">{{ $item['catDocente'] }}</td>
                <td class="cat">{{ $item['catCientifica'] }}</td>
                <td class="departamento">{{ $item['departamento'] }}</td>
                <td class="carrera">{{ $item['carrera'] }}</td>
                <td class="ano">{{ $item['anio_calendario'] }}</td>
<td class="academico">{{ $item['anio_academico'] }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

</body>
</html>
