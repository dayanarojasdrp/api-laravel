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
    Listado de Alumnos Ayudantes
</h2>

<table>
    <thead>
        <tr>
            <th>Carnet</th>
            <th>Nombre del Estudiante</th>
            <th>Tutor</th>
            <th>Departamento</th>
            <th>Año</th>
        </tr>
    </thead>

    <tbody>
        @foreach($data as $item)
            <tr>
                <td>{{ $item['carnet'] }}</td>
                <td>{{ $item['nombre'] }}</td>
                <td>{{ $item['tutor'] }}</td>
                <td>{{ $item['departamento'] }}</td>
               <td>{{ $item['anio'] }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

</body>
</html>
