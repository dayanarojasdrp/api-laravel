<h2 style="font-family: Arial; font-size: 14pt;">Listado de Alumnos Ayudantes</h2>

<table style="width:100%; border-collapse: collapse; font-family: Arial; font-size: 12pt;">
    <thead>
        <tr>
            <th style="border:1px solid black;">Carnet</th>
            <th style="border:1px solid black;">Nombre</th>
            <th style="border:1px solid black;">Año Académico</th>
            <th style="border:1px solid black;">Tutor</th>
            <th style="border:1px solid black;">Etapa</th>
        </tr>
    </thead>

    <tbody>
        @foreach($data as $item)
        <tr>
            <td style="border:1px solid black;">{{ $item['carnet'] }}</td>
            <td style="border:1px solid black;">{{ $item['nombre'] }}</td>
            <td style="border:1px solid black;">{{ $item['anio'] }}</td>
            <td style="border:1px solid black;">{{ $item['tutor'] }}</td>
            <td style="border:1px solid black;">{{ $item['etapa'] }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
