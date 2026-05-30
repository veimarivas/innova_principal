<?php

namespace App\Http\Controllers;

use App\Models\Ciudade;
use App\Models\Departamento;
use App\Models\Estudio;
use App\Models\GradoAcademico;
use App\Models\Persona;
use App\Models\Profesione;
use App\Models\Universidade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Encoders\JpegEncoder;

class PersonaController extends Controller
{
    public function index()
    {
        return view('admin.personas.index');
    }

    public function ver($id)
    {
        $persona = Persona::with([
            'ciudad.departamento',
            'estudios.grado_academico',
            'estudios.profesion',
            'estudios.universidad'
        ])->findOrFail($id);

        return view('admin.personas.show', compact('persona'));
    }

    public function listar()
    {
        $personas = Persona::with(['ciudad', 'estudios.grado_academico', 'estudios.profesion', 'estudios.universidad'])
            ->orderBy('apellido_paterno', 'asc')
            ->get();
        return response()->json(['data' => $personas]);
    }

    public function listarDepartamentos()
    {
        $departamentos = Departamento::orderBy('nombre', 'asc')->get(['id', 'nombre']);
        return response()->json(['data' => $departamentos]);
    }

    public function listarCiudades()
    {
        $ciudades = Ciudade::orderBy('nombre', 'asc')->get(['id', 'nombre', 'departamento_id']);
        return response()->json(['data' => $ciudades]);
    }

    public function listarGrados()
    {
        $grados = GradoAcademico::orderBy('nombre', 'asc')->get(['id', 'nombre']);
        return response()->json(['data' => $grados]);
    }

    public function listarProfesiones()
    {
        $profesiones = Profesione::orderBy('nombre', 'asc')->get(['id', 'nombre']);
        return response()->json(['data' => $profesiones]);
    }

    public function listarUniversidades()
    {
        $universidades = Universidade::orderBy('nombre', 'asc')->get(['id', 'nombre', 'sigla']);
        return response()->json(['data' => $universidades]);
    }

    // ─── Verificaciones en tiempo real ────────────────────────────────────────

    public function verificarCarnet(Request $request)
    {
        $carnet = strtoupper(trim($request->carnet));
        $query  = Persona::where('carnet', $carnet);
        if ($request->filled('id')) {
            $query->where('id', '!=', $request->id);
        }
        return response()->json(['existe' => $query->exists()]);
    }

    public function verificarCorreo(Request $request)
    {
        $correo = strtolower(trim($request->correo));
        $query  = Persona::where('correo', $correo);
        if ($request->filled('id')) {
            $query->where('id', '!=', $request->id);
        }
        return response()->json(['existe' => $query->exists()]);
    }

    // ─── CRUD ─────────────────────────────────────────────────────────────────

    public function guardar(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'carnet'           => 'required|string|max:20|unique:personas,carnet',
            'expedido'         => 'nullable|string|max:10',
            'nombres'          => 'required|string|max:100',
            'apellido_paterno' => 'nullable|string|max:80',
            'apellido_materno' => 'nullable|string|max:80',
            'sexo'             => 'nullable|in:M,F',
            'estado_civil'     => 'nullable|in:Soltero/a,Casado/a,Divorciado/a,Viudo/a,Unión Libre',
            'fecha_nacimiento' => 'nullable|date',
            'correo'           => 'nullable|email|max:150|unique:personas,correo',
            'direccion'        => 'nullable|string|max:200',
            'celular'          => 'nullable|string|max:20',
            'telefono'         => 'nullable|string|max:20',
            'ciudade_id'       => 'nullable|exists:ciudades,id',
            'fotografia'       => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'carnet.required'  => 'El número de carnet es obligatorio.',
            'carnet.unique'    => 'Este carnet ya está registrado.',
            'nombres.required' => 'El nombre es obligatorio.',
            'correo.email'     => 'El correo no tiene un formato válido.',
            'correo.unique'    => 'Este correo ya está registrado.',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        // Al menos un apellido
        if (empty(trim($request->apellido_paterno ?? '')) && empty(trim($request->apellido_materno ?? ''))) {
            return response()->json([
                'success' => false,
                'errors'  => ['apellidos' => ['Debe registrar al menos un apellido (paterno o materno).']],
            ], 422);
        }

        $persona = Persona::create([
            'carnet'           => strtoupper($request->carnet),
            'expedido'         => $request->expedido ? strtoupper($request->expedido) : null,
            'nombres'          => strtoupper($request->nombres),
            'apellido_paterno' => $request->apellido_paterno ? strtoupper($request->apellido_paterno) : null,
            'apellido_materno' => $request->apellido_materno ? strtoupper($request->apellido_materno) : null,
            'sexo'             => $request->sexo ?: null,
            'estado_civil'     => $request->estado_civil ?: null,
            'fecha_nacimiento' => $request->fecha_nacimiento ?: null,
            'correo'           => $request->correo ? strtolower($request->correo) : null,
            'direccion'        => $request->direccion ? strtoupper($request->direccion) : null,
            'celular'          => $request->celular ?: null,
            'telefono'         => $request->telefono ?: null,
            'ciudade_id'       => $request->ciudade_id ?: null,
        ]);

        if ($request->hasFile('fotografia')) {
            $imagen = $request->file('fotografia');
            $nombreArchivo = 'persona_' . $persona->id . '_' . time() . '.jpg';
            $ruta = public_path('images/personas/' . $nombreArchivo);
            
            $manager = new ImageManager(new Driver());
            $img = $manager->decode($imagen);
            $img->scaleDown(800, 800);
            $img->encode(new JpegEncoder(80))->save($ruta);

            $persona->update(['fotografia' => $nombreArchivo]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Persona registrada correctamente.',
            'data'    => $persona->load(['ciudad', 'estudios']),
        ]);
    }

    public function actualizar(Request $request, $id)
    {
        $persona = Persona::find($id);
        if (!$persona) {
            return response()->json(['success' => false, 'message' => 'Persona no encontrada.'], 404);
        }

        $validator = Validator::make($request->all(), [
            'carnet'           => 'required|string|max:20|unique:personas,carnet,' . $id,
            'expedido'         => 'nullable|string|max:10',
            'nombres'          => 'required|string|max:100',
            'apellido_paterno' => 'nullable|string|max:80',
            'apellido_materno' => 'nullable|string|max:80',
            'sexo'             => 'nullable|in:M,F',
            'estado_civil'     => 'nullable|in:Soltero/a,Casado/a,Divorciado/a,Viudo/a,Unión Libre',
            'fecha_nacimiento' => 'nullable|date',
            'correo'           => 'nullable|email|max:150|unique:personas,correo,' . $id,
            'direccion'        => 'nullable|string|max:200',
            'celular'          => 'nullable|string|max:20',
            'telefono'         => 'nullable|string|max:20',
            'ciudade_id'       => 'nullable|exists:ciudades,id',
            'fotografia'       => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'carnet.required'  => 'El número de carnet es obligatorio.',
            'carnet.unique'    => 'Este carnet ya está registrado.',
            'nombres.required' => 'El nombre es obligatorio.',
            'correo.email'     => 'El correo no tiene un formato válido.',
            'correo.unique'    => 'Este correo ya está registrado.',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        // Al menos un apellido
        if (empty(trim($request->apellido_paterno ?? '')) && empty(trim($request->apellido_materno ?? ''))) {
            return response()->json([
                'success' => false,
                'errors'  => ['apellidos' => ['Debe registrar al menos un apellido (paterno o materno).']],
            ], 422);
        }

        $persona->update([
            'carnet'           => strtoupper($request->carnet),
            'expedido'         => $request->expedido ? strtoupper($request->expedido) : null,
            'nombres'          => strtoupper($request->nombres),
            'apellido_paterno' => $request->apellido_paterno ? strtoupper($request->apellido_paterno) : null,
            'apellido_materno' => $request->apellido_materno ? strtoupper($request->apellido_materno) : null,
            'sexo'             => $request->sexo ?: null,
            'estado_civil'     => $request->estado_civil ?: null,
            'fecha_nacimiento' => $request->fecha_nacimiento ?: null,
            'correo'           => $request->correo ? strtolower($request->correo) : null,
            'direccion'        => $request->direccion ? strtoupper($request->direccion) : null,
            'celular'          => $request->celular ?: null,
            'telefono'         => $request->telefono ?: null,
            'ciudade_id'       => $request->ciudade_id ?: null,
        ]);

        if ($request->hasFile('fotografia')) {
            if ($persona->fotografia && file_exists(public_path('images/personas/' . $persona->fotografia))) {
                unlink(public_path('images/personas/' . $persona->fotografia));
            }
            
            $imagen = $request->file('fotografia');
            $nombreArchivo = 'persona_' . $persona->id . '_' . time() . '.jpg';
            $ruta = public_path('images/personas/' . $nombreArchivo);
            
            $manager = new ImageManager(new Driver());
            $img = $manager->decode($imagen);
            $img->scaleDown(800, 800);
            $img->encode(new JpegEncoder(80))->save($ruta);
            
            $persona->update(['fotografia' => $nombreArchivo]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Persona actualizada correctamente.',
            'data'    => $persona->load(['ciudad', 'estudios.grado_academico', 'estudios.profesion', 'estudios.universidad']),
        ]);
    }

    public function eliminar($id)
    {
        $persona = Persona::find($id);
        if (!$persona) {
            return response()->json(['success' => false, 'message' => 'Persona no encontrada.'], 404);
        }

        if ($persona->estudios()->count() > 0) {
            return response()->json(['success' => false, 'message' => 'No se puede eliminar la persona porque tiene estudios asociados.'], 400);
        }

        $persona->delete();
        return response()->json(['success' => true, 'message' => 'Persona eliminada correctamente.']);
    }

    // ─── Estudios ─────────────────────────────────────────────────────────────

    public function agregarEstudio(Request $request, $id)
    {
        $persona = Persona::find($id);
        if (!$persona) {
            return response()->json(['success' => false, 'message' => 'Persona no encontrada.'], 404);
        }

        $validator = Validator::make($request->all(), [
            'grados_academico_id' => 'required|exists:grados_academicos,id',
            'universidade_id'     => 'nullable|exists:universidades,id',
            'profesione_id'       => 'nullable|exists:profesiones,id',
            'estado'              => 'required|in:En Desarrollo,Concluido',
            'principal'           => 'nullable|boolean',
            'documento_academico' => 'nullable|file|mimes:pdf,png,jpg,jpeg|max:2048',
            'documento_provision_nacional' => 'nullable|file|mimes:pdf,png,jpg,jpeg|max:2048',
        ], [
            'grados_academico_id.required' => 'El grado académico es obligatorio.',
            'grados_academico_id.exists'   => 'El grado académico seleccionado no es válido.',
            'estado.required'              => 'El estado es obligatorio.',
            'estado.in'                    => 'El estado debe ser "En Desarrollo" o "Concluido".',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $gradoId = $request->grados_academico_id;
        $profId = $request->profesione_id;
        $uniId = $request->universidade_id;

        $duplicado = $persona->estudios()->where('grados_academico_id', $gradoId)
            ->where('profesione_id', $profId)
            ->where('universidade_id', $uniId)
            ->exists();

        if ($duplicado) {
            return response()->json(['success' => false, 'message' => 'Ya existe un estudio registrado con el mismo grado académico, profesión y universidad.'], 422);
        }

        if ($request->boolean('principal')) {
            $persona->estudios()->update(['principal' => false]);
        }

        $estudioData = [
            'grados_academico_id' => $gradoId,
            'universidade_id'     => $uniId ?: null,
            'profesione_id'       => $profId ?: null,
            'estado'              => $request->estado,
            'principal'           => $request->boolean('principal'),
        ];

        // Manejar uploaded files
        if ($request->hasFile('documento_academico')) {
            $file = $request->file('documento_academico');
            $nombreArchivo = 'documento_academico_' . $persona->id . '_' . time() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('estudios', $nombreArchivo);
            $estudioData['documento_academico'] = $nombreArchivo;
        }

        if ($request->hasFile('documento_provision_nacional')) {
            $file = $request->file('documento_provision_nacional');
            $nombreArchivo = 'provision_nacional_' . $persona->id . '_' . time() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('estudios', $nombreArchivo);
            $estudioData['documento_provision_nacional'] = $nombreArchivo;
        }

        $estudio = $persona->estudios()->create($estudioData);

        return response()->json([
            'success' => true,
            'message' => 'Estudio agregado correctamente.',
            'data'    => $estudio->load(['grado_academico', 'profesion', 'universidad']),
        ]);
    }

    public function actualizarEstudio(Request $request, $id, $estudioId)
    {
        $persona = Persona::find($id);
        if (!$persona) {
            return response()->json(['success' => false, 'message' => 'Persona no encontrada.'], 404);
        }

        $estudio = $persona->estudios()->find($estudioId);
        if (!$estudio) {
            return response()->json(['success' => false, 'message' => 'Estudio no encontrado.'], 404);
        }

        $validator = Validator::make($request->all(), [
            'grados_academico_id' => 'required|exists:grados_academicos,id',
            'universidade_id'     => 'nullable|exists:universidades,id',
            'profesione_id'       => 'nullable|exists:profesiones,id',
            'estado'              => 'required|in:En Desarrollo,Concluido',
            'principal'           => 'nullable|boolean',
        ], [
            'grados_academico_id.required' => 'El grado académico es obligatorio.',
            'estado.required'              => 'El estado es obligatorio.',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $gradoId = $request->grados_academico_id;
        $profId = $request->profesione_id;
        $uniId = $request->universidade_id;

        $duplicado = $persona->estudios()->where('id', '!=', $estudioId)
            ->where('grados_academico_id', $gradoId)
            ->where('profesione_id', $profId)
            ->where('universidade_id', $uniId)
            ->exists();

        if ($duplicado) {
            return response()->json(['success' => false, 'message' => 'Ya existe otro estudio con el mismo grado académico, profesión y universidad.'], 422);
        }

        if ($request->boolean('principal')) {
            $persona->estudios()->where('id', '!=', $estudioId)->update(['principal' => false]);
        }

        $estudio->update([
            'grados_academico_id' => $gradoId,
            'universidade_id'     => $uniId ?: null,
            'profesione_id'       => $profId ?: null,
            'estado'              => $request->estado,
            'principal'           => $request->boolean('principal'),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Estudio actualizado correctamente.',
            'data'    => $estudio->load(['grado_academico', 'profesion', 'universidad']),
        ]);
    }

    public function eliminarEstudio($id, $estudioId)
    {
        $persona = Persona::find($id);
        if (!$persona) {
            return response()->json(['success' => false, 'message' => 'Persona no encontrada.'], 404);
        }

        $estudio = $persona->estudios()->find($estudioId);
        if (!$estudio) {
            return response()->json(['success' => false, 'message' => 'Estudio no encontrado.'], 404);
        }

        $estudio->delete();
        return response()->json(['success' => true, 'message' => 'Estudio eliminado correctamente.']);
    }
}
