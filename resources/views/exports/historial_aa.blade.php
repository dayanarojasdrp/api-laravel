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

        .carnet { width: 18%; text-align: center; }
        .nombre { width: 32%; }
        .tutor { width: 24%; }
        .departamento { width: 18%; }
        .ano { width: 8%; text-align: center; }
    </style>
</head>
<body>

<h2>
    Listado de Alumnos Ayudantes
</h2>

<table>
    <thead>
        <tr>
            <th class="carnet">Carnet</th>
            <th class="nombre">Nombre del Estudiante</th>
            <th class="tutor">Tutor</th>
            <th class="departamento">Departamento</th>
            <th class="ano">Año</th>
        </tr>
    </thead>

    <tbody>
        @foreach($data as $item)
            <tr>
                <td class="carnet">{{ $item['carnet'] }}</td>
                <td class="nombre">{{ $item['nombre'] }}</td>
                <td class="tutor">{{ $item['tutor'] }}</td>
                <td class="departamento">{{ $item['departamento'] }}</td>
               <td class="ano">{{ $item['anio'] }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

</body>
</html>
