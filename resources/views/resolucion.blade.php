<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">

<style>
body {
    font-family: Helvetica, Arial, sans-serif;
    font-size: 12pt;
    line-height: 1.4;
}
/* ENCABEZADO */
.header {
    text-align: center;
    font-weight: bold;
}

/* TITULO */
.titulo {
    margin-top: 15px;
    margin-bottom: 10px;
}

/* PARRAFOS JUSTIFICADOS */
.parrafo {
    text-align: justify;
    margin-bottom: 10px;
}

/* PARRAFO CON SANGRIA */
.parrafo-sangria {
    text-align: justify;
    text-indent: 30px;
    margin-bottom: 10px;
}

/* RESUELVO */
.resuelvo {
    text-align: center;
    margin: 20px 0;
    letter-spacing: 5px;
    font-weight: bold;
}

/* ARTICULOS */
.articulo {
    text-align: justify;
    margin-top: 10px;
}

/* TABLA */
.table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 10px;
}

.table th, .table td {
    border: 1px solid black;
    padding: 4px;
    font-size: 11px;
}

.table th {
    font-weight: bold;
    text-align: center;
}

.center {
    text-align: center;
}

/* FIRMA */
.firma {
    margin-top: 40px;
}
</style>

</head>

<body>

<!-- 🟣 ENCABEZADO -->
<div class="header">
    <div>UNIVERSIDAD CENTRAL “MARTA ABREU” DE LAS VILLAS</div>
    <div>FACULTAD DE MATEMÁTICA, FÍSICA Y COMPUTACIÓN</div>
    <div>EL DECANO</div>
</div>

<!-- 🟣 TITULO -->
<p class="titulo"><strong>Resolución Decanal /{{ $anio }}</strong></p>

<!-- 🟣 POR CUANTO -->
<p class="parrafo">
<strong>POR CUANTO:</strong> La Resolución Ministerial 47/2022 dictada por el Ministro de Educación Superior con fecha 27 de mayo de 2022 establece en su Capítulo IX las normas y procedimientos para el trabajo metodológico.
</p>

<p class="parrafo">
<strong>POR CUANTO:</strong> La Resolución Ministerial 47/2022 en <strong>su capítulo IX, artículo 153 </strong>establece que el colectivo de año es el encargado de llevar a cabo el trabajo metodológico en este nivel organizativo. Constituye un nivel de dirección atípico en la estructura de las instituciones de educación superior, conducido por el profesor principal del año académico. Agrupa a los profesores que desarrollan las asignaturas del año, a los profesores guías de cada grupo, a los tutores y a los representantes de las organizaciones estudiantiles.
</p>

<p class="parrafo-sangria">
Este colectivo tiene como propósito lograr el cumplimiento con calidad de los objetivos de formación del año académico, así como otros que se hayan concertado para responder a las características propias del grupo y del momento, mediante la implementación de la estrategia educativa del año académico.</p>

<p class="parrafo">
<strong>POR CUANTO:</strong> La Resolución Ministerial 47/2022 en <strong>su capítulo IX, artículo 156</strong> establece que la conducción del colectivo de año debe estar a cargo de un profesor que posea una buena preparación pedagógica y científica, así como cualidades y actitudes que le permitan desempeñarse en esa responsabilidad. Es la principal autoridad académica del año y se subordina directamente al decano de la facultad-carrera o al jefe del departamento-carrera, según corresponda. </p>

<p class="parrafo-sangria">
Su trabajo es esencial para el cumplimiento de los objetivos de formación del año. Su labor de dirección metodológica está basada en la coordinación, la asesoría y el control de los profesores guías, los tutores y el colectivo de profesores del año.</p>

<!-- 🟣 POR TANTO -->
<p class="parrafo">
<strong>POR TANTO:</strong> En uso de las facultades que, me están conferidas
</p>

<!-- 🟣 RESUELVO -->
<div class="resuelvo">R E S U E L V O</div>

<!-- 🟣 PRIMERO -->
<p class="articulo">
<strong>PRIMERO:</strong> Ratificar a los compañeros que a continuación se presentan como Profesores Principales de Año (PPA) de la Facultad Matemática, Física y Computación para el curso {{ $anio }} en el Curso Diurno.
</p>

<!-- 🟣 TABLA -->
<table class="table">
<thead>
<tr>
<th>Carrera</th>
<th>Año</th>
<th>Nombre</th>
<th>Categoría Docente</th>
<th>Categoría Científica</th>
</tr>
</thead>

<tbody>
@foreach($data as $item)
<tr>
<td>{{ $item['carrera'] }}</td>
<td class="center">{{ $item['anio'] }}</td>
<td>{{ $item['nombre'] }}</td>
<td>{{ $item['catDocente'] }}</td>
<td>{{ $item['catCientifica'] }}</td>
</tr>
@endforeach
</tbody>
</table>


<!-- 🟣 Segunda -->
<p class="articulo">
<strong>SEGUNDO:</strong> Desnombrar a los compañeros que a continuación se presentan como Profesores Principales de Año (PPA) de la Facultad Matemática, Física y Computación para el curso {{ $anio }} en el curso diurno.
</p>

<!-- 🟣 TABLA -->
<table class="table">
<thead>
<tr>
<th>Carrera</th>
<th>Año</th>
<th>Nombre</th>
<th>Categoría Docente</th>
<th>Categoría Científica</th>
</tr>
</thead>

<tbody>
@foreach($data as $item)
<tr>
<td>{{ $item['carrera'] }}</td>
<td class="center">{{ $item['anio'] }}</td>
<td>{{ $item['nombre'] }}</td>
<td>{{ $item['catDocente'] }}</td>
<td>{{ $item['catCientifica'] }}</td>
</tr>
@endforeach
</tbody>
</table>

<!-- 🟣 Tercero -->
<p class="articulo">
<strong>TERCERO:</strong> Designar a los compañeros que a continuación se presentan como Profesores Principales de Año (PPA) de la Facultad Matemática, Física y Computación para el curso {{ $anio }} en el Curso Diurno.
</p>

<!-- 🟣 TABLA -->
<table class="table">
<thead>
<tr>
<th>Carrera</th>
<th>Año</th>
<th>Nombre</th>
<th>Categoría Docente</th>
<th>Categoría Científica</th>
</tr>
</thead>

<tbody>
@foreach($data as $item)
<tr>
<td>{{ $item['carrera'] }}</td>
<td class="center">{{ $item['anio'] }}</td>
<td>{{ $item['nombre'] }}</td>
<td>{{ $item['catDocente'] }}</td>
<td>{{ $item['catCientifica'] }}</td>
</tr>
@endforeach
</tbody>
</table>

<!-- 🟣 FINAL -->
<p class="parrafo-sangria">
Esta Resolución entra en vigor a partir de su firma.
</p>

<p class="parrafo">
<strong>NOTIFÍQUESE</strong> la presente resolución a los profesores designados, al Vice Decano Docente, Vicedecano de Investigación y Postgrado, Jefes de Departamentos Docentes y a cuantas personas más deban conocer el contenido de la presente.
</p>

<p class="parrafo">
<strong>ARCHÍVESE</strong> el original en el protocolo de Disposiciones de la Facultad de Matemática, Física y Computación
</p>

<p class="parrafo">
<strong>ARCHÍVESE</strong> en la Universidad Central “Marta Abreu” de Las Villas, a los {{ $dia }} días del mes de {{ $mes }} de {{ $anio }}. “AÑO {{ $revolucion }} DE LA REVOLUCION”.
</p>

<!-- 🟣 FIRMA -->
<div class="firma">
    <p>__________________________</p>
    <p>Dr. C. {{ $nombreDecano }}</p>
</div>

</body>
</html>
