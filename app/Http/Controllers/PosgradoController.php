<?php

namespace App\Http\Controllers;

use App\Models\Posgrado;
use App\Models\Convenio;
use App\Models\Area;
use App\Models\Tipo;
use App\Models\Programa;
use App\Models\Fase;
use App\Models\Modalidade;
use App\Models\Sucursale;
use App\Models\TrabajadoresCargo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PosgradoController extends Controller
{
    public function index()
    {
        $convenios = Convenio::orderBy('nombre', 'asc')->get();
        $areas = Area::orderBy('nombre', 'asc')->get();
        $tipos = Tipo::orderBy('nombre', 'asc')->get();
        $programas = Programa::orderBy('nombre', 'asc')->get();
        $fases = Fase::orderBy('nombre', 'asc')->get();
        $modalidades = Modalidade::orderBy('nombre', 'asc')->get();
        $sucursales = Sucursale::orderBy('nombre', 'asc')->get();
        $trabajadores = TrabajadoresCargo::with(['trabajador.persona', 'cargo'])->get();
        $trabajadoresAcademicos = $trabajadores->filter(function($t) {
            return $t->cargo && stripos($t->cargo->nombre, 'academico') !== false;
        })->values();
        $trabajadoresMarketing = $trabajadores->filter(function($t) {
            return $t->cargo && stripos($t->cargo->nombre, 'marketing') !== false;
        })->values();
        $formatTrabajador = function($t) {
            $nombre = '';
            if ($t->trabajador && $t->trabajador->persona) {
                $p = $t->trabajador->persona;
                $nombre = trim(($p->nombres ?? '') . ' ' . ($p->apellido_paterno ?? '') . ' ' . ($p->apellido_materno ?? ''));
            }
            return [
                'id' => $t->id,
                'nombre' => $nombre ?: 'Sin nombre',
                'cargo' => ($t->cargo && $t->cargo->nombre) ? $t->cargo->nombre : '',
            ];
        };
        $trabajadoresAcademicosData = $trabajadoresAcademicos->map($formatTrabajador)->values();
        $trabajadoresMarketingData = $trabajadoresMarketing->map($formatTrabajador)->values();
        return view('admin.posgrados.index', compact('convenios', 'areas', 'tipos', 'programas', 'fases', 'modalidades', 'sucursales', 'trabajadoresAcademicosData', 'trabajadoresMarketingData'));
    }

    public function listar()
    {
        try {
            $posgrados = Posgrado::with(['convenio', 'area', 'tipo'])->orderBy('nombre', 'asc')->get();
            return response()->json(['data' => $posgrados]);
        } catch (\Exception $e) {
            // Devuelve error detallado para depurar
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function verificarNombre(Request $request)
    {
        $nombre = strtoupper($request->nombre);
        $query = Posgrado::where('nombre', $nombre);
        if ($request->has('id')) {
            $query->where('id', '!=', $request->id);
        }
        $existe = $query->exists();
        return response()->json(['existe' => $existe]);
    }

    public function guardar(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre'          => 'required|string|max:200|unique:posgrados,nombre', // Cambia 'posgrados' por 'posgios' si tu tabla se llama así
            'creditaje'       => 'nullable|integer|min:0',
            'carga_horaria'   => 'nullable|integer|min:0',
            'duracion_numero' => 'nullable|integer|min:0',
            'duracion_unidad' => 'nullable|in:Horas,Días,Semanas,Meses',
            'dirigido'        => 'nullable|string',
            'objetivo'        => 'nullable|string',
            'convenio_id'     => 'required|exists:convenios,id',
            'area_id'         => 'required|exists:areas,id',
            'tipo_id'         => 'required|exists:tipos,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $posgrado = Posgrado::create([
            'nombre'          => strtoupper($request->nombre),
            'creditaje'       => $request->creditaje ?? 0,
            'carga_horaria'   => $request->carga_horaria ?? 0,
            'duracion_numero' => $request->duracion_numero ?? 0,
            'duracion_unidad' => $request->duracion_unidad ?? 'Horas',
            'dirigido'        => $request->dirigido ? strtoupper($request->dirigido) : null,
            'objetivo'        => $request->objetivo ? strtoupper($request->objetivo) : null,
            'estado'          => true,
            'convenio_id'     => $request->convenio_id,
            'area_id'         => $request->area_id,
            'tipo_id'         => $request->tipo_id,
        ]);

        return response()->json(['success' => true, 'message' => 'Posgrado registrado correctamente.', 'data' => $posgrado]);
    }

    public function actualizar(Request $request, $id)
    {
        $posgrado = Posgrado::find($id);
        if (!$posgrado) {
            return response()->json(['success' => false, 'message' => 'Posgrado no encontrado.'], 404);
        }

        $validator = Validator::make($request->all(), [
            'nombre'          => 'required|string|max:200|unique:posgrados,nombre,' . $id,
            'creditaje'       => 'nullable|integer|min:0',
            'carga_horaria'   => 'nullable|integer|min:0',
            'duracion_numero' => 'nullable|integer|min:0',
            'duracion_unidad' => 'nullable|in:Horas,Días,Semanas,Meses',
            'dirigido'        => 'nullable|string',
            'objetivo'        => 'nullable|string',
            'convenio_id'     => 'required|exists:convenios,id',
            'area_id'         => 'required|exists:areas,id',
            'tipo_id'         => 'required|exists:tipos,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $posgrado->update([
            'nombre'          => strtoupper($request->nombre),
            'creditaje'       => $request->creditaje ?? 0,
            'carga_horaria'   => $request->carga_horaria ?? 0,
            'duracion_numero' => $request->duracion_numero ?? 0,
            'duracion_unidad' => $request->duracion_unidad ?? 'Horas',
            'dirigido'        => $request->dirigido ? strtoupper($request->dirigido) : null,
            'objetivo'        => $request->objetivo ? strtoupper($request->objetivo) : null,
            'convenio_id'     => $request->convenio_id,
            'area_id'         => $request->area_id,
            'tipo_id'         => $request->tipo_id,
        ]);

        return response()->json(['success' => true, 'message' => 'Posgrado actualizado correctamente.', 'data' => $posgrado]);
    }

    public function eliminar($id)
    {
        $posgrado = Posgrado::find($id);
        if (!$posgrado) {
            return response()->json(['success' => false, 'message' => 'Posgrado no encontrado.'], 404);
        }
        $posgrado->delete();
        return response()->json(['success' => true, 'message' => 'Posgrado eliminado correctamente.']);
    }

    public function cambiarEstado($id)
    {
        $posgrado = Posgrado::find($id);
        if (!$posgrado) {
            return response()->json(['success' => false, 'message' => 'Posgrado no encontrado.'], 404);
        }
        $posgrado->estado = !$posgrado->estado;
        $posgrado->save();
        $estadoTexto = $posgrado->estado ? 'Activado' : 'Desactivado';
        return response()->json(['success' => true, 'message' => "Posgrado {$estadoTexto} correctamente.", 'estado' => $posgrado->estado]);
    }
}
