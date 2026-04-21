<h2>Listado de PPA</h2>

<table border="1" width="100%" cellpadding="5" cellspacing="0">
    <thead>
        <tr>
            <th>Profesor</th>
            <th>Cat Docente</th>
            <th>Cat Científica</th>
            <th>Departamento</th>
            <th>Carrera</th>
            <th>Ano</th>
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
