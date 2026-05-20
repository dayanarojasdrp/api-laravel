<style>
    body { font-family: Arial, sans-serif; }
    h2 { font-size: 14pt; }
    table { width: 100%; border-collapse: collapse; table-layout: fixed; font-size: 9.5pt; }
    th, td { border: 1px solid #000; padding: 4px; vertical-align: middle; word-wrap: break-word; }
    th { text-align: center; font-weight: bold; }
    .profesor { width: 26%; }
    .cat { width: 11%; text-align: center; }
    .departamento { width: 20%; }
    .carrera { width: 24%; }
    .ano { width: 8%; text-align: center; }
</style>

<h2>Listado de PPA</h2>

<table>
    <thead>
        <tr>
            <th class="profesor">Profesor</th>
            <th class="cat">Cat Docente</th>
            <th class="cat">Cat Científica</th>
            <th class="departamento">Departamento</th>
            <th class="carrera">Carrera</th>
            <th class="ano">Año</th>
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
            <td class="ano">{{ $item['anio'] }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
