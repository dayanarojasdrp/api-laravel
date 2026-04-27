<?php

use App\Http\Controllers\API\asignaturaController;
use App\Http\Controllers\API\anoAcademicoController;
use App\Http\Controllers\API\cursoController;
use App\Http\Controllers\API\departamentoController;
use App\Http\Controllers\API\FacultadController;
use App\Http\Controllers\API\HistorialsC\asignaturaAgnoController;
use App\Http\Controllers\API\HistorialsC\historialDepProgFormController;
use App\Http\Controllers\API\HistorialsC\historialFacDepController;
use App\Http\Controllers\API\HistorialsC\historialProgFormModCarController;
use App\Http\Controllers\API\HistorialsC\historialUniversidadFacultadController;
use App\Http\Controllers\API\indicadores\indicadorController;
use App\Http\Controllers\API\indicadores\indicadorRegistroController;
use App\Http\Controllers\API\indicadores\tipoIndicadorController;
use App\Http\Controllers\API\modalidadCarreraController;
use App\Http\Controllers\API\municipioController;
use App\Http\Controllers\API\progFormController;
use App\Http\Controllers\API\provinciaController;
use App\Http\Controllers\API\universidadController;
use App\Http\Controllers\API\categoriaCientificaController;
use App\Http\Controllers\API\categoriaDocenteController;
use App\Http\Controllers\API\CurriculoController;
use App\Http\Controllers\API\DisciplinaController;
use App\Http\Controllers\API\HistorialsC\CurriculoDisciplinaController;
use App\Http\Controllers\API\HistorialsC\DisciplinaAsignaturaController;
use App\Http\Controllers\API\HistorialsC\PlanEstudioCurriculoController;
use App\Http\Controllers\API\ModificacionController;
use App\Http\Controllers\API\ProfesorController;
use App\Http\Controllers\API\PlanEstudioController;
use App\Http\Controllers\API\PlanEstudioProgFormController;
use App\Http\Controllers\API\VersionController;
use App\Http\Controllers\CohorteController;
use App\Models\Asignatura_Agno;
use App\Models\Cohorte;
use App\Models\Version;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PPAController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\EstudianteController;
use App\Http\Controllers\EdicionController;
use App\Http\Controllers\TipoController;
use App\Http\Controllers\ManifestacionController;
use App\Http\Controllers\EdicionCursoController;
use App\Http\Controllers\EstudianteManifestacionController;
use App\Http\Controllers\GrupoController;
use App\Http\Controllers\AnoGrupoController;
use App\Http\Controllers\EstudianteGrupoController;
use App\Http\Controllers\SectorEstrategicoController;
use App\Http\Controllers\TDPPController;
use App\Http\Controllers\EstudianteTDPPController;
use App\Http\Controllers\GradoTituloController;
use App\Http\Controllers\DecanoController;
use App\Http\Controllers\JefeDepartamentoController;
use App\Http\Controllers\MiembroDepartamentoController;
use App\Http\Controllers\CoordinadorCarreraController;
use App\Http\Controllers\ProfesorGuiaController;
use App\Http\Controllers\AlumnoAyudanteController;
use App\Http\Controllers\DocumentoController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

//Rutas provincia
Route::get('/provincia', [provinciaController::class, 'index']);
Route::post('/provincia', [provinciaController::class, 'store']);
Route::get('/provincia/{id}',[provinciaController::class, 'show']);
Route::put('/provincia/{id}',[provinciaController::class, 'update']);
Route::delete('/provincia/{id}', [provinciaController::class, 'destroy']);

//Rutas municipio
Route::get('/municipio', [municipioController::class, 'index']);
Route::post('/municipio', [municipioController::class, 'store']);
Route::get('/municipio/{id}',[municipioController::class, 'show']);
Route::put('/municipio/{id}',[municipioController::class, 'update']);
Route::delete('/municipio/{id}', [municipioController::class, 'destroy']);

//Rutas universidad
Route::get('/universidad', [universidadController::class, 'index']);
Route::post('/universidad', [universidadController::class, 'store']);
Route::get('/universidad/{id}',[universidadController::class, 'show']);
Route::put('/universidad/{id}',[universidadController::class, 'update']);
Route::delete('/universidad/{id}', [universidadController::class, 'destroy']);

//Rutas universidad-facultad
Route::get('/historial/unifacul', [historialUniversidadFacultadController::class, 'index']);
Route::delete('/historial/unifacul', [historialUniversidadFacultadController::class, 'destroy']);

//Rutas facultad
Route::get('/facultad', [FacultadController::class, 'index']);
Route::post('/facultad', [FacultadController::class, 'store']);
Route::get('/facultad/{id}',[FacultadController::class, 'show']);
Route::put('/facultad/{id}',[FacultadController::class, 'update']);
Route::delete('/facultad/{id}', [FacultadController::class, 'destroy']);

//Rutas facultad-departamento
Route::get('/historial/faculdep', [historialFacDepController::class, 'index']);
Route::delete('/historial/faculdep', [historialFacDepController::class, 'destroy']);

//Rutas departamento
Route::get('/departamento', [departamentoController::class, 'index']);
Route::post('/departamento', [departamentoController::class, 'store']);
Route::get('/departamento/{id}',[departamentoController::class, 'show']);
Route::put('/departamento/{id}',[departamentoController::class, 'update']);
Route::delete('/departamento/{id}', [departamentoController::class, 'destroy']);

//Rutas departamento-programaDeFormacion
Route::get('/historial/depProgForm', [historialDepProgFormController::class, 'index']);
Route::delete('/historial/depProgForm', [historialDepProgFormController::class, 'destroy']);

//Rutas Programa de Formacion
Route::get('/progForm', [progFormController::class, 'index']);
Route::post('/progForm', [progFormController::class, 'store']);
Route::get('/progForm/{id}',[progFormController::class, 'show']);
Route::put('/progForm/{id}',[progFormController::class, 'update']);
Route::delete('/progForm/{id}', [progFormController::class, 'destroy']);

//Rutas Ano Academico
Route::get('/a_academico', [anoAcademicoController::class, 'index']);
Route::post('/a_academico', [anoAcademicoController::class, 'store']);
Route::get('/a_academico/{id}',[anoAcademicoController::class, 'show']);
Route::put('/a_academico/{id}',[anoAcademicoController::class, 'update']);
Route::delete('/a_academico/{id}', [anoAcademicoController::class, 'destroy']);

//Rutas Asignatura Ano Academico
Route::get('/asignatura_a_academico', [asignaturaAgnoController::class, 'index']);
Route::delete('/asignatura_a_academico', [asignaturaAgnoController::class, 'destroy']);

//Rutas Modalidad de Carrera
Route::get('/modalidad', [modalidadCarreraController::class, 'index']);
Route::post('/modalidad', [modalidadCarreraController::class, 'store']);
Route::get('/modalidad/{id}',[modalidadCarreraController::class, 'show']);
Route::put('/modalidad/{id}',[modalidadCarreraController::class, 'update']);
Route::delete('/modalidad/{id}', [modalidadCarreraController::class, 'destroy']);

//Rutas programa_de_formacion-modalidad_de_carrera
Route::get('/progFormMod', [historialProgFormModCarController::class, 'index']);
Route::post('/progFormMod', [historialProgFormModCarController::class, 'store']);
Route::delete('/progFormMod/{idM}/{idP}', [historialProgFormModCarController::class, 'destroy']);

//Rutas de curso
Route::get('/cursos/{id_a_academico}', [CursoController::class, 'porAgno']);
Route::get('/curso', [cursoController::class, 'index']);
Route::post('/curso', [cursoController::class, 'store']);
Route::delete('/curso/{id}', [cursoController::class, 'destroy']);


//
// Rutas de indicadores
//

//Rutas de tipo de indicador
Route::get('/tipoIndicador', [tipoIndicadorController::class, 'index']);
Route::post('/tipoIndicador', [tipoIndicadorController::class, 'store']);
Route::get('/tipoIndicador/{id}',[tipoIndicadorController::class, 'show']);
Route::put('/tipoIndicador/{id}',[tipoIndicadorController::class, 'update']);
Route::delete('/tipoIndicador/{id}', [tipoIndicadorController::class, 'destroy']);

//Rutas de indicador
Route::get('/indicador', [indicadorController::class, 'index']);
Route::post('/indicador', [indicadorController::class, 'store']);
Route::get('/indicador/{id}',[indicadorController::class, 'show']);
Route::put('/indicador/{id}',[indicadorController::class, 'update']);
Route::delete('/indicador/{id}', [indicadorController::class, 'destroy']);


//Rutas asignatura
Route::get('/asignatura', [asignaturaController::class, 'index']);
Route::post('/asignatura', [asignaturaController::class, 'store']);
Route::get('/asignatura/{id}', [asignaturaController::class, 'show']);
Route::put('/asignatura/{id}', [asignaturaController::class, 'update']);
Route::delete('/asignatura/{id}', [asignaturaController::class, 'destroy']);

//Rutas de Disciplina Asignatura
Route::get('/disciplina_asignatura', [DisciplinaAsignaturaController::class, 'index']);
Route::delete('/disciplina_asignatura', [DisciplinaAsignaturaController::class, 'destroy']);

//Rutas asignatura_agno
Route::get('/asignaturaAgno', [asignaturaAgnoController::class, 'index']);
//Rutas de registros de indicadores
/*
Estas son las rutas para los registros de los indicadores osea para intruducirle
los valores, no tienen nada de el otro mundo, salvo que para el put y el delete
se dividen entre los indicadores de ano y los indicadores de asignatura:
    los de asignatura son los que tienen 4 parametros y los de ano son los que tienen 3

    ademas las siglas:
    idC = idCurso
    idI = idIndicador osea el indicador al cual pertenecen
    idAA = idAnoAcademico
    idA = idAsignatura
*/
// Rutas existentes
Route::get('/indicadorReg', [indicadorRegistroController::class, 'index']);
Route::post('/indicadorReg', [indicadorRegistroController::class, 'store']);
Route::put('/indicadorReg/{idC}/{idI}/{idAA}',[indicadorRegistroController::class, 'updateAA']);
Route::put('/indicadorReg/{idC}/{idI}/{idAA}/{idA}',[indicadorRegistroController::class, 'updateA']);
Route::delete('/indicadorReg/{idC}/{idI}/{idAA}',[indicadorRegistroController::class, 'destroyAA']);
Route::delete('/indicadorReg/{idC}/{idI}/{idAA}/{idA}',[indicadorRegistroController::class, 'destroyA']);

// Nuevas rutas para departamento
Route::put('/indicadorReg/departamento/{idD}/{idI}/{idC}', [indicadorRegistroController::class, 'updateD']);
Route::delete('/indicadorReg/departamento/{idD}/{idI}/{idC}', [indicadorRegistroController::class, 'destroyD']);



// Rutas Profesor
Route::get('/profesor', [ProfesorController::class, 'index']);
Route::post('/profesor', [ProfesorController::class, 'store']);
Route::get('/profesor/{id}', [ProfesorController::class, 'show']);
Route::put('/profesor/{id}', [ProfesorController::class, 'update']);
Route::delete('/profesor/{id}', [ProfesorController::class, 'destroy']);

// Rutas categorias
Route::get('/categoria_docente', [categoriaDocenteController::class, 'index']);
Route::get('/categoria_cientifica', [categoriaCientificaController::class, 'index']);

// Indicadores
Route::get('/indicador/{id}/valores', [IndicadorController::class, 'getValores']);
Route::get('/indicador/{id}/calculado', [IndicadorController::class, 'getValoresCalculados']);

// Registro de valores
Route::post('/indicadorReg', [indicadorRegistroController::class, 'store']);

//Rutas de Plan de Estudio
Route::get('/plan_estudio',[PlanEstudioController::class, 'index']);
Route::post('/plan_estudio', [PlanEstudioController::class, 'store']);
Route::get('/plan_estudio/{id}', [PlanEstudioController::class, 'show']);
Route::put('/plan_estudio/{id}', [PlanEstudioController::class, 'update']);
Route::delete('/plan_estudio/{id}', [PlanEstudioController::class, 'destroy']);

//Rutas de Plan de Estudio Programa de Formacion
Route::get('/plan_estudio_prog_form', [PlanEstudioProgFormController::class, 'index']);
Route::delete('/plan_estudio_prog_form', [PlanEstudioProgFormController::class, 'destroy']);

//Rutas de Curriculo
Route::get('/curriculo', [CurriculoController::class, 'index']);
Route::post('/curriculo', [CurriculoController::class, 'store']);
Route::get('/curriculo/{id}', [CurriculoController::class, 'show']);
Route::put('/curriculo/{id}', [CurriculoController::class, 'update']);
Route::delete('/curriculo/{id}', [CurriculoController::class, 'destroy']);

//Rutas de Plan de Estudio Curriculo
Route::get('/plan_estudio_curriculo', [PlanEstudioCurriculoController::class, 'index']);
Route::delete('/plan_estudio_curriculo', [PlanEstudioCurriculoController::class, 'destroy']);

//Rutas de Disciplina
Route::get('/disciplina', [DisciplinaController::class, 'index']);
Route::post('/disciplina', [DisciplinaController::class, 'store']);
Route::get('/disciplina/{id}', [DisciplinaController::class, 'show']);
Route::put('/disciplina/{id}', [DisciplinaController::class, 'update']);
Route::delete('/disciplina/{id}', [DisciplinaController::class, 'destroy']);

//Rutas de CurriculoDisciplina
Route::get('/curriculo_disciplina', [CurriculoDisciplinaController::class, 'index']);
Route::delete('/curriculo_disciplina', [CurriculoDisciplinaController::class, 'destroy']);

//Rutas de version
Route::get('/version', [VersionController::class, 'index']);
Route::post('/version', [VersionController::class, 'store']);
Route::get('/version/{id}', [VersionController::class, 'show']);
Route::put('/version/{id}', [VersionController::class, 'update']);
Route::delete('/version/{id}', [VersionController::class, 'destroy']);

//Rutas de modificacion
Route::get('/modificacion', [ModificacionController::class, 'index']);
Route::post('/modificacion', [ModificacionController::class, 'store']);
Route::get('/modificacion/{id}', [ModificacionController::class, 'show']);
Route::put('/modificacion/{id}', [ModificacionController::class, 'update']);
Route::delete('/modificacion/{id}', [ModificacionController::class, 'destroy']);

//Rutas de Cohorte
Route::get('/cohorte', [CohorteController::class, 'index']);
Route::post('/cohorte', [CohorteController::class, 'store']);
Route::delete('/cohorte/{id}', [CohorteController::class, 'destroy']);


Route::post('/ppa/designar', [PPAController::class, 'designar']);
Route::post('/ppa/ratificar', [PPAController::class, 'ratificar']);
Route::post('/ppa/desnombrar', [PPAController::class, 'desnombrar']);
Route::get('/ppa', [PPAController::class, 'index']);

Route::get('/facultad/{id}/departamentos', [historialFacDepController::class, 'departamentosPorFacultad']);
Route::get('/departamento/{id}/carreras', [historialDepProgFormController::class, 'carrerasPorDepartamento']);
Route::get('/programa/{id}/anios', [anoAcademicoController::class, 'aniosPorPrograma']);
Route::get('/anio/{id}/curso', [anoAcademicoController::class, 'cursoPorAnio']);


Route::get('/logs', [LogController::class, 'index']);

Route::get('/export/ppa/pdf', [PPAController::class, 'exportPDF']);
Route::get('/export/ppa/word', [PPAController::class, 'exportWord']);
Route::get('/export/resolucion/ppa/pdf', [PPAController::class, 'exportResolucionPDF']);
Route::get('/export/resolucion/ppa/word', [PPAController::class, 'exportResolucionWord']);


Route::get('/estudiante', [EstudianteController::class, 'index']);
Route::post('/estudiante', [EstudianteController::class, 'store']);

Route::apiResource('tipos', TipoController::class);

Route::apiResource('ediciones', EdicionController::class);

Route::apiResource('manifestaciones', ManifestacionController::class);

Route::apiResource('edicion-curso', EdicionCursoController::class);

Route::apiResource('estudiante-manifestacion', EstudianteManifestacionController::class);

Route::apiResource('grupos', GrupoController::class);

Route::apiResource('ano-grupo', AnoGrupoController::class);


Route::apiResource('estudiante-grupo', EstudianteGrupoController::class);
Route::apiResource('sector-estrategico', SectorEstrategicoController::class);
Route::apiResource('td-pp', TDPPController::class);
Route::apiResource('estudiante-tdpp', EstudianteTDPPController::class);
Route::apiResource('grado-titulo', GradoTituloController::class);


Route::apiResource('decano', DecanoController::class);

// 🔥 extras
Route::get('decano/actual/{facultad}', [DecanoController::class, 'actual']);
Route::get('decano/historial/{facultad}', [DecanoController::class, 'historial']);

Route::apiResource('jefe-departamento', JefeDepartamentoController::class);

// 🔥 extras
Route::get('jefe-departamento/actual/{departamento}', [JefeDepartamentoController::class, 'actual']);
Route::get('jefe-departamento/historial/{departamento}', [JefeDepartamentoController::class, 'historial']);


Route::apiResource('miembro-departamento', MiembroDepartamentoController::class);

// extras
Route::get('miembro-departamento/activos/{departamento}', [MiembroDepartamentoController::class, 'activos']);
Route::get('miembro-departamento/historial/{profesor}', [MiembroDepartamentoController::class, 'historialProfesor']);


Route::apiResource('coordinador-carrera', CoordinadorCarreraController::class);

Route::get('coordinador-carrera/actual/{programa}', [CoordinadorCarreraController::class, 'actual']);
Route::get('coordinador-carrera/historial/{programa}', [CoordinadorCarreraController::class, 'historial']);

Route::apiResource('profesor-guia', ProfesorGuiaController::class);

Route::get('profesor-guia/actual/{grupo}', [ProfesorGuiaController::class, 'actual']);
Route::get('profesor-guia/historial/{grupo}', [ProfesorGuiaController::class, 'historial']);



Route::get('alumno-ayudante', [AlumnoAyudanteController::class, 'index']);

Route::post('alumno-ayudante/designar', [AlumnoAyudanteController::class, 'designar']);
Route::post('alumno-ayudante/ratificar/{id}', [AlumnoAyudanteController::class, 'ratificar']);
Route::post('alumno-ayudante/desnombrar/{id}', [AlumnoAyudanteController::class, 'desnombrar']);

Route::get('alumno-ayudante/actual/{estudiante}', [AlumnoAyudanteController::class, 'actual']);
Route::get('alumno-ayudante/historial/{estudiante}', [AlumnoAyudanteController::class, 'historial']);
Route::get('alumno-ayudante/activos', [AlumnoAyudanteController::class, 'activos']);


Route::get('/export/resolucion/aa/pdf', [AlumnoAyudanteController::class, 'aaPdf']);
Route::get('/export/resolucion/aa/word', [AlumnoAyudanteController::class, 'aaWord']);
Route::get('/export/aa/pdf', [AlumnoAyudanteController::class, 'exportPDF']);
Route::get('/export/aa/word', [AlumnoAyudanteController::class, 'exportWord']);


Route::get('/documentos', [DocumentoController::class, 'index']);
Route::get('/cursos', [cursoController::class, 'index']);

Route::post('/documentos/historial', [PPAController::class, 'historial']);
Route::post('/documentos/historial-aa', [AlumnoAyudanteController::class, 'historialAA']);

