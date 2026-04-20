<?php

namespace App\Http\Controllers\API\HistorialsC;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HistorialFacultadDepartamento;
use App\Models\Departamento;
use App\Models\Facultad;
use Illuminate\Support\Facades\Validator;
class historialFacDepController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json([
            'res'=> true,
            'data'=> HistorialFacultadDepartamento::all()
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $val = Validator::make($request->all(), [
            'id_departamento' => 'required',
            'id_facultad' => 'required',
            'id_curso' => 'required'
        ]);
        if ($val->fails()) {
            return response()->json([
                'res'=> false,
                'message'=> $val->errors(),
                'status'=> 400
            ], 400);
        }
        $hist =HistorialFacultadDepartamento::
            where('id_departamento', $request->id_departamento)
            ->where('id_facultad', $request->id_facultad)
            ->where('id_curso', $request->id_curso)->first();
        if(!$hist){
            return response()->json([
                'res'=> false,
                'message'=> 'No se encontro el registro'
            ], 400);
        }
        $hist->delete();
        return response()->json([
            'res' => true,
            'message' => 'Se elimino el registro'
        ], 200);
    }
  public function departamentosPorFacultad($id)
{
    $departamentos = DB::table('facultad_departamento')
        ->join(
            'departamento',
            'facultad_departamento.id_departamento',
            '=',
            'departamento.id'
        )
        ->where('facultad_departamento.id_facultad', $id)
        ->select(
            'departamento.id',
            'departamento.nombre'
        )
        ->get();

    return response()->json($departamentos);
}
}
