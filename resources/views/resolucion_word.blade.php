<h2 style="text-align:center;">UNIVERSIDAD CENTRAL “MARTA ABREU” DE LAS VILLAS</h2>
<h3 style="text-align:center;">FACULTAD DE MATEMÁTICA, FÍSICA Y COMPUTACIÓN</h3>

<p><strong>Resolución Decanal /2024</strong></p>

<p><strong>POR CUANTO:</strong> La Resolución Ministerial 47/2022 dictada por el Ministro de Educación Superior con fecha 27 de mayo de 2022 establece en su Capítulo IX las normas y procedimientos para el trabajo metodológico.
</p>

<p><strong>POR CUANTO:</strong> La Resolución Ministerial 47/2022 en su capítulo IX, artículo 153 establece que el colectivo de ano es el encargado de llevar a cabo el trabajo metodológico en este nivel organizativo. Constituye un nivel de dirección atípico en la estructura de las instituciones de educación superior, conducido por el profesor principal del ano académico. Agrupa a los profesores que desarrollan las asignaturas del ano, a los profesores guías de cada grupo, a los tutores y a los representantes de las organizaciones estudiantiles.

Este colectivo tiene como propósito lograr el cumplimiento con calidad de los objetivos de formación del ano académico, así como otros que se hayan concertado para responder a las características propias del grupo y del momento, mediante la implementación de la estrategia educativa del ano académico.</p>
<p><strong>POR CUANTO:</strong> La Resolución Ministerial 47/2022 en su capítulo IX, artículo 156 establece que la conducción del colectivo de ano debe estar a cargo de un profesor que posea una buena preparación pedagógica y científica, así como cualidades y actitudes que le permitan desempeñarse en esa responsabilidad. Es la principal autoridad académica del ano y se subordina directamente al decano de la facultad-carrera o al jefe del departamento-carrera, según corresponda.

Su trabajo es esencial para el cumplimiento de los objetivos de formación del ano. Su labor de dirección metodológica está basada en la coordinación, la asesoría y el control de los profesores guías, los tutores y el colectivo de profesores del ano.
</p>
<p><strong>POR CUANTO:</strong> La Resolución Ministerial 47/2022 en su capítulo IX, artículo 156 establece que la conducción del colectivo de ano debe estar a cargo de un profesor que posea una buena preparación pedagógica y científica, así como cualidades y actitudes que le permitan desempeñarse en esa responsabilidad. Es la principal autoridad académica del ano y se subordina directamente al decano de la facultad-carrera o al jefe del departamento-carrera, según corresponda.

Su trabajo es esencial para el cumplimiento de los objetivos de formación del ano. Su labor de dirección metodológica está basada en la coordinación, la asesoría y el control de los profesores guías, los tutores y el colectivo de profesores del ano.


</p>
<p><strong>POR TANTO:</strong> En uso de las facultades que, me están conferidas
</p>
<h3 style="text-align:center;">RESUELVO</h3>

<table border="1" width="100%">
<tr>
<th>Carrera</th>
<th>Año</th>
<th>Nombre</th>
<th>Cat Docente</th>
<th>Cat Científica</th>
</tr>

@foreach($data as $item)
<tr>
<td>{{ $item['carrera'] }}</td>
<td>{{ $item['anio'] }}</td>
<td>{{ $item['nombre'] }}</td>
<td>{{ $item['catDocente'] }}</td>
<td>{{ $item['catCientifica'] }}</td>
</tr>
@endforeach


</table>
