<style>
    body { font-family: Arial, sans-serif; }
    h2 { font-size: 14pt; }
    table { width: 100%; border-collapse: collapse; table-layout: fixed; font-size: 9.5pt; }
    th, td { border: 1px solid #000; padding: 4px; vertical-align: middle; word-wrap: break-word; }
    th { text-align: center; font-weight: bold; }
    .carnet { width: 18%; text-align: center; }
    .nombre { width: 34%; }
    .ano { width: 12%; text-align: center; }
    .tutor { width: 26%; }
    .etapa { width: 10%; text-align: center; }
</style>

<h2>Listado de Alumnos Ayudantes</h2>

<table>
    <thead>
        <tr>
            <th class="carnet">Carnet</th>
            <th class="nombre">Nombre</th>
            <th class="ano">Año Académico</th>
            <th class="tutor">Tutor</th>
            <th class="etapa">Etapa</th>
        </tr>
    </thead>

    <tbody>
        @foreach($data as $item)
        <tr>
            <td class="carnet">{{ $item['carnet'] }}</td>
            <td class="nombre">{{ $item['nombre'] }}</td>
            <td class="ano">{{ $item['anio'] }}</td>
            <td class="tutor">{{ $item['tutor'] }}</td>
            <td class="etapa">{{ $item['etapa'] }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
