<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cuota;
use App\Models\Inscripcione;
use App\Models\OfertasAcademica;
use App\Models\Fase;
use App\Models\Estudiante;
use App\Models\EnlacePreinscripcion;
use App\Models\PagoRespaldo;
use App\Models\PlanesConcepto;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Encoders\JpegEncoder;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show()
    {
        $user = auth()->user()->load(['persona.ciudad.departamento', 'persona.estudios']);

        $tieneMarketing = false;
        if ($user->persona && $user->persona->trabajador) {
            $tieneMarketing = $user->persona->trabajador->trabajadores_cargos()
                ->whereIn('cargo_id', [2, 3, 6])
                ->where('estado', 'Vigente')
                ->exists();
        }

        return view('admin.profile.index', compact('user', 'tieneMarketing'));
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password'     => 'required|min:8|confirmed',
        ]);

        if (!Hash::check($request->current_password, auth()->user()->password)) {
            return response()->json(['success' => false, 'message' => 'La contraseña actual es incorrecta.']);
        }

        auth()->user()->update(['password' => Hash::make($request->new_password)]);

        return response()->json(['success' => true, 'message' => 'Contraseña actualizada correctamente.']);
    }

    public function uploadFoto(Request $request)
    {
        $request->validate([
            'foto' => 'required|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);

        $user    = auth()->user();
        $persona = $user->persona;

        if (!$persona) {
            return response()->json(['success' => false, 'message' => 'No se encontró la persona asociada.']);
        }

        $dir = public_path('images/personas');
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        if ($persona->fotografia && file_exists($dir . '/' . $persona->fotografia)) {
            unlink($dir . '/' . $persona->fotografia);
        }

        $nombreArchivo = 'persona_' . $persona->id . '_' . time() . '.jpg';
        $destino       = $dir . '/' . $nombreArchivo;

        $manager = new ImageManager(new Driver());
        $img     = $manager->decode($request->file('foto')->getRealPath());
        $img->scaleDown(800, 800);
        $img->encode(new JpegEncoder(80))->save($destino);

        $persona->update(['fotografia' => $nombreArchivo]);

        return response()->json([
            'success' => true,
            'url'     => asset('images/personas/' . $nombreArchivo),
            'message' => 'Foto de perfil actualizada correctamente.',
        ]);
    }

    /* ── Marketing ── */

    public function getEstadisticasMarketing(Request $request)
    {
        $persona = auth()->user()->persona;

        if (!$persona || !$persona->trabajador) {
            return response()->json(['success' => false, 'error' => 'Sin acceso'], 403);
        }

        $cargosIds = $persona->trabajador->trabajadores_cargos()
            ->whereIn('cargo_id', [2, 3, 6])
            ->where('estado', 'Vigente')
            ->pluck('id');

        if ($cargosIds->isEmpty()) {
            return response()->json(['success' => false, 'error' => 'Sin cargos de marketing'], 403);
        }

        $year      = (int) $request->input('year', now()->year);
        $month     = $request->input('month');
        $programaId = $request->input('programa_id');

        // Estadísticas por mes (12 meses fijos)
        $porMes = Inscripcione::selectRaw("
            MONTH(fecha_registro) as month,
            SUM(CASE WHEN estado = 'Inscrito'     THEN 1 ELSE 0 END) as inscritos,
            SUM(CASE WHEN estado = 'Pre-Inscrito' THEN 1 ELSE 0 END) as pre_inscritos,
            COUNT(*) as total
        ")
            ->whereIn('trabajadores_cargo_id', $cargosIds)
            ->whereIn('estado', ['Inscrito', 'Pre-Inscrito'])
            ->whereYear('fecha_registro', $year)
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->keyBy('month');

        $mesesCortos = ['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'];
        $grafico = ['meses' => [], 'inscritos' => [], 'pre_inscritos' => [], 'totales' => []];

        for ($m = 1; $m <= 12; $m++) {
            $grafico['meses'][]       = $mesesCortos[$m - 1];
            $grafico['inscritos'][]   = isset($porMes[$m]) ? (int) $porMes[$m]->inscritos    : 0;
            $grafico['pre_inscritos'][]= isset($porMes[$m]) ? (int) $porMes[$m]->pre_inscritos : 0;
            $grafico['totales'][]     = isset($porMes[$m]) ? (int) $porMes[$m]->total          : 0;
        }

        // Top programas
        $qProgramas = Inscripcione::selectRaw("
            programas.id   as programa_id,
            programas.nombre as programa_nombre,
            COUNT(*)       as total,
            SUM(CASE WHEN inscripciones.estado = 'Inscrito'     THEN 1 ELSE 0 END) as inscritos,
            SUM(CASE WHEN inscripciones.estado = 'Pre-Inscrito' THEN 1 ELSE 0 END) as pre_inscritos
        ")
            ->join('ofertas_academicas', 'inscripciones.ofertas_academica_id', '=', 'ofertas_academicas.id')
            ->join('programas', 'ofertas_academicas.programa_id', '=', 'programas.id')
            ->whereIn('inscripciones.trabajadores_cargo_id', $cargosIds)
            ->whereIn('inscripciones.estado', ['Inscrito', 'Pre-Inscrito'])
            ->whereYear('inscripciones.fecha_registro', $year);

        if ($month && $month !== 'todos') {
            $qProgramas->whereMonth('inscripciones.fecha_registro', (int) $month);
        }
        if ($programaId) {
            $qProgramas->where('programas.id', $programaId);
        }

        $programas = $qProgramas->groupBy('programas.id', 'programas.nombre')
            ->orderByDesc('total')->limit(10)->get();

        // Resumen
        $qResumen = Inscripcione::whereIn('trabajadores_cargo_id', $cargosIds)
            ->whereIn('estado', ['Inscrito', 'Pre-Inscrito'])
            ->whereYear('fecha_registro', $year);

        if ($month && $month !== 'todos') {
            $qResumen->whereMonth('fecha_registro', (int) $month);
        }
        if ($programaId) {
            $qResumen->whereHas('ofertaAcademica', fn ($q) => $q->where('programa_id', $programaId));
        }

        $mesesLargos   = ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'];
        $mesSeleccionado = ($month && $month !== 'todos') ? $mesesLargos[(int)$month - 1] : 'Todos los meses';

        return response()->json([
            'success'  => true,
            'grafico'  => $grafico,
            'programas' => $programas,
            'resumen'  => [
                'total'            => $qResumen->count(),
                'inscritos'        => (clone $qResumen)->where('estado', 'Inscrito')->count(),
                'pre_inscritos'    => (clone $qResumen)->where('estado', 'Pre-Inscrito')->count(),
                'mes_seleccionado' => $mesSeleccionado,
                'anio_seleccionado'=> $year,
            ],
        ]);
    }

    public function getInscripcionesFiltradas(Request $request)
    {
        $persona = auth()->user()->persona;

        if (!$persona || !$persona->trabajador) {
            return response()->json(['success' => false, 'error' => 'Sin acceso'], 403);
        }

        $cargosIds = $persona->trabajador->trabajadores_cargos()
            ->whereIn('cargo_id', [2, 3, 6])
            ->where('estado', 'Vigente')
            ->pluck('id');

        if ($cargosIds->isEmpty()) {
            return response()->json(['success' => false, 'error' => 'Sin cargos de marketing'], 403);
        }

        $year      = $request->input('year', now()->year);
        $month     = $request->input('month');
        $programaId = $request->input('programa_id');
        $estado    = $request->input('estado');
        $search    = $request->input('search', '');

        $query = Inscripcione::whereIn('trabajadores_cargo_id', $cargosIds)
            ->whereIn('estado', ['Inscrito', 'Pre-Inscrito'])
            ->whereYear('fecha_registro', $year)
            ->with(['ofertaAcademica.programa', 'ofertaAcademica.sucursal', 'estudiante.persona', 'planesPago']);

        if ($month && $month !== 'todos') {
            $query->whereMonth('fecha_registro', $month);
        }
        if ($programaId) {
            $query->whereHas('ofertaAcademica', fn ($q) => $q->where('programa_id', $programaId));
        }
        if ($estado && in_array($estado, ['Inscrito', 'Pre-Inscrito'])) {
            $query->where('estado', $estado);
        }
        if ($search) {
            $query->whereHas('estudiante.persona', function ($q) use ($search) {
                $q->where('nombres', 'like', "%{$search}%")
                  ->orWhere('apellido_paterno', 'like', "%{$search}%")
                  ->orWhere('carnet', 'like', "%{$search}%");
            });
        }

        $inscripciones = $query->orderBy('fecha_registro', 'desc')->get();

        // Cargar la relación planesPago y estudios en cada inscripción
        $inscripciones->transform(function ($inscripcion) {
            $inscripcion->load(['planesPago', 'estudiante.persona', 'estudiante.persona.estudios' => function($q) {
                $q->where('principal', 1)->limit(1);
            }]);
            $inscripcion->plan_pago_nombre = $inscripcion->planesPago->nombre ?? null;
            $inscripcion->programa_nombre = $inscripcion->ofertaAcademica->programa->nombre ?? 'Sin programa';
            $inscripcion->sucursal_nombre = $inscripcion->ofertaAcademica->sucursal->nombre ?? 'Sin sucursal';
            
            // Agregar datos del estudio principal del estudiante
            $estudioPrincipal = null;
            if ($inscripcion->estudiante && $inscripcion->estudiante->persona && $inscripcion->estudiante->persona->estudios) {
                $estudioPrincipal = $inscripcion->estudiante->persona->estudios->first();
            }
            $inscripcion->estudio = $estudioPrincipal ? [
                'documento_academico' => $estudioPrincipal->documento_academico,
                'documento_provision_nacional' => $estudioPrincipal->documento_provision_nacional,
            ] : null;
            
            return $inscripcion;
        });

        // Agrupar por programa
        $agrupadas = $inscripciones->groupBy('programa_nombre')->map(function ($grupo) {
            return [
                'programa' => $grupo->first()->programa_nombre,
                'inscripciones' => $grupo->values()->toArray(),
                'total' => $grupo->count()
            ];
        })->values();

        $totalPages = ceil($agrupadas->count() / 10);
        $page = $request->input('page', 1);
        $paginated = $agrupadas->forPage($page, 10);

        return response()->json([
            'success'     => true,
            'agrupadas' => $paginated,
            'pagination'  => [
                'current_page' => (int) $page,
                'last_page'    => $totalPages,
                'total'        => $agrupadas->count(),
                'per_page'     => 10,
            ],
        ]);
    }

    public function getDocumentosEstudiante(Request $request, $estudianteId)
    {
        $persona = auth()->user()->persona;

        if (!$persona || !$persona->trabajador) {
            return response()->json(['success' => false, 'error' => 'Sin acceso'], 403);
        }

        $estudiante = Estudiante::with(['persona', 'estudios' => function ($q) {
            $q->where('principal', 1)->with('tipoEstudio');
        }])->find($estudianteId);

        if (!$estudiante) {
            return response()->json(['success' => false, 'error' => 'Estudiante no encontrado'], 404);
        }

        $documentos = [
            'fotografia_carnet' => [
                'nombre' => 'Fotografía Carnet',
                'campo' => 'fotografia_carnet',
                'valor' => $estudiante->persona->fotografia_carnet ?? null,
                'ruta' => $estudiante->persona->fotografia_carnet ? asset('storage/' . $estudiante->persona->fotografia_carnet) : null,
            ],
            'certificado_nacimiento' => [
                'nombre' => 'Certificado de Nacimiento',
                'campo' => 'fotografia_certificado_nacimiento',
                'valor' => $estudiante->persona->fotografia_certificado_nacimiento ?? null,
                'ruta' => $estudiante->persona->fotografia_certificado_nacimiento ? asset('storage/' . $estudiante->persona->fotografia_certificado_nacimiento) : null,
            ],
        ];

        $estudioPrincipal = $estudiante->persona ? $estudiante->persona->estudios->first() : null;

        if ($estudioPrincipal) {
            $documentos['documento_academico'] = [
                'nombre' => 'Documento Académico',
                'campo' => 'documento_academico',
                'valor' => $estudioPrincipal->documento_academico ?? null,
                'ruta' => $estudioPrincipal->documento_academico ? asset('storage/' . $estudioPrincipal->documento_academico) : null,
                'detalle' => $estudioPrincipal->tipoEstudio->nombre ?? 'Sin tipo' . ' - ' . ($estudioPrincipal->institucion ?? 'Sin institución'),
            ];
            $documentos['documento_provision_nacional'] = [
                'nombre' => 'Provisión Nacional',
                'campo' => 'documento_provision_nacional',
                'valor' => $estudioPrincipal->documento_provision_nacional ?? null,
                'ruta' => $estudioPrincipal->documento_provision_nacional ? asset('storage/' . $estudioPrincipal->documento_provision_nacional) : null,
                'detalle' => $estudioPrincipal->tipoEstudio->nombre ?? 'Sin tipo' . ' - ' . ($estudioPrincipal->institucion ?? 'Sin institución'),
            ];
        } else {
            $documentos['documento_academico'] = [
                'nombre' => 'Documento Académico',
                'campo' => 'documento_academico',
                'valor' => null,
                'ruta' => null,
                'detalle' => 'No se ha registrado estudio principal',
                'faltante' => true,
            ];
            $documentos['documento_provision_nacional'] = [
                'nombre' => 'Provisión Nacional',
                'campo' => 'documento_provision_nacional',
                'valor' => null,
                'ruta' => null,
                'detalle' => 'No se ha registrado estudio principal',
                'faltante' => true,
            ];
        }

        return response()->json([
            'success' => true,
            'estudiante' => [
                'id' => $estudiante->id,
                'nombre' => $estudiante->persona->nombres . ' ' . $estudiante->persona->apellido_paterno,
                'carnet' => $estudiante->persona->carnet,
            ],
            'documentos' => $documentos,
        ]);
    }

    public function getOfertasActivas(Request $request)
    {
        try {
            $persona = auth()->user()->persona;

            if (!$persona || !$persona->trabajador) {
                return response()->json(['success' => false, 'message' => 'Sin acceso'], 403);
            }

            $cargoPrincipal = $persona->trabajador->trabajadores_cargos()
                ->where('principal', 1)
                ->where('estado', 'Vigente')
                ->with(['cargo', 'sucursale'])
                ->first();

            $faseInscripciones = Fase::where('nombre', 'Inscripciones')->first();

            $query = OfertasAcademica::with([
                    'programa',
                    'sucursal.sede',
                    'modalidad',
                    'fase',
                    'trabajador_cargo_academico.trabajador.persona',
                    'trabajador_cargo_marketing.trabajador.persona',
                ])
                ->orderBy('fecha_inicio_inscripciones', 'desc');

            if ($faseInscripciones) {
                $query->where('fase_id', $faseInscripciones->id);
            }

            if ($request->filled('search')) {
                $s = $request->search;
                $query->where(function ($q) use ($s) {
                    $q->where('codigo', 'like', "%{$s}%")
                      ->orWhereHas('programa', fn ($q2) => $q2->where('nombre', 'like', "%{$s}%"));
                });
            }

            if ($request->filled('sucursal_id')) {
                $query->where('sucursale_id', $request->input('sucursal_id'));
            }

            $ofertas = $query->paginate($request->input('per_page', 10));

            $ofertas->getCollection()->transform(function ($oferta) {
                return [
                    'id'                      => $oferta->id,
                    'codigo'                  => $oferta->codigo,
                    'programa_nombre'         => optional($oferta->programa)->nombre ?? 'Sin programa',
                    'sucursal_nombre'         => optional($oferta->sucursal)->nombre ?? 'Sin sucursal',
                    'sede_nombre'             => optional(optional($oferta->sucursal)->sede)->nombre ?? '',
                    'modalidad_nombre'        => optional($oferta->modalidad)->nombre ?? 'Sin modalidad',
                    'fecha_inicio_formateada' => $oferta->fecha_inicio_inscripciones
                        ? Carbon::parse($oferta->fecha_inicio_inscripciones)->format('d/m/Y') : '—',
                    'fecha_fin_formateada'    => $oferta->fecha_fin_programa
                        ? Carbon::parse($oferta->fecha_fin_programa)->format('d/m/Y') : '—',
                ];
            });

            return response()->json([
                'success' => true,
                'ofertas' => $ofertas,
                'cargo_principal' => $cargoPrincipal ? [
                    'id'             => $cargoPrincipal->id,
                    'cargo_nombre'   => $cargoPrincipal->cargo->nombre ?? 'Sin cargo',
                    'sucursal_nombre'=> optional($cargoPrincipal->sucursale)->nombre ?? 'Sin sucursal',
                ] : null,
            ]);
        } catch (\Exception $e) {
            \Log::error('ProfileController@getOfertasActivas: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function generarEnlacePreinscripcion(Request $request)
    {
        $request->validate([
            'oferta_academica_id' => 'required|integer|exists:ofertas_academicas,id',
            'planes_pago_id'      => 'nullable|integer|exists:planes_pagos,id',
        ]);

        $persona = auth()->user()->persona;

        if (!$persona || !$persona->trabajador) {
            return response()->json(['success' => false, 'message' => 'Sin acceso'], 403);
        }

        $cargo = $persona->trabajador->trabajadores_cargos()
            ->whereIn('cargo_id', [2, 3, 6])
            ->where('estado', 'Vigente')
            ->first();

        if (!$cargo) {
            return response()->json(['success' => false, 'message' => 'Sin cargo de marketing vigente'], 403);
        }

        $ofertaId  = $request->oferta_academica_id;
        $planId    = $request->input('planes_pago_id');

        // Buscar enlace existente para esta combinación exacta (oferta + cargo + plan)
        $query = EnlacePreinscripcion::where('oferta_academica_id', $ofertaId)
            ->where('trabajadores_cargo_id', $cargo->id);

        if ($planId) {
            $query->where('planes_pago_id', $planId);
        } else {
            $query->whereNull('planes_pago_id');
        }

        $enlace = $query->first();

        if (!$enlace) {
            $enlace = EnlacePreinscripcion::create([
                'oferta_academica_id'  => $ofertaId,
                'trabajadores_cargo_id'=> $cargo->id,
                'planes_pago_id'       => $planId ?: null,
            ]);
        } elseif (!$enlace->activo) {
            $enlace->update(['activo' => true]);
        }

        $url = route('preinscripcion.show', ['token' => $enlace->token]);

        return response()->json([
            'success' => true,
            'url'     => $url,
            'token'   => $enlace->token,
        ]);
    }

    public function getPlanesPagoParaOferta(Request $request, int $ofertaId)
    {
        $persona = auth()->user()->persona;

        if (!$persona || !$persona->trabajador) {
            return response()->json(['success' => false, 'message' => 'Sin acceso'], 403);
        }

        $planes = PlanesConcepto::where('ofertas_academica_id', $ofertaId)
            ->with(['plan_pago', 'concepto'])
            ->get()
            ->groupBy('planes_pago_id');

        $resultado = $planes->map(function ($conceptos, $planId) {
            $plan = $conceptos->first()->plan_pago;
            return [
                'id'     => $planId,
                'nombre' => optional($plan)->nombre ?? 'Plan',
                'conceptos' => $conceptos->map(fn($pc) => [
                    'concepto' => optional($pc->concepto)->nombre,
                    'pago_bs'  => $pc->pago_bs,
                    'n_cuotas' => $pc->n_cuotas,
                ])->values(),
            ];
        })->values();

        return response()->json(['success' => true, 'planes' => $resultado]);
    }

    public function uploadDocument(Request $request)
    {
        $request->validate([
            'tipo' => 'required|in:carnet,certificado_nacimiento,documento_academico,provision_nacional',
            'documento' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        $user = auth()->user();
        $persona = $user->persona;

        if (!$persona) {
            return response()->json(['success' => false, 'message' => 'No se encontró la persona asociada.']);
        }

        $tipo = $request->input('tipo');
        $dir = public_path('storage/documentos');
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $campoArchivo = match ($tipo) {
            'carnet' => 'fotografia_carnet',
            'certificado_nacimiento' => 'fotografia_certificado_nacimiento',
            'documento_academico' => 'documento_academico',
            'provision_nacional' => 'documento_provision_nacional',
            default => null,
        };

        if (!$campoArchivo) {
            return response()->json(['success' => false, 'message' => 'Tipo de documento inválido.']);
        }

        if ($tipo === 'documento_academico' || $tipo === 'provision_nacional') {
            $estudio = $persona->estudios()->where('principal', 1)->first();
            if (!$estudio) {
                return response()->json(['success' => false, 'message' => 'No tienes un estudio principal registrado.']);
            }

            $oldFile = $estudio->$campoArchivo;
            if ($oldFile && file_exists($dir . '/' . $oldFile)) {
                unlink($dir . '/' . $oldFile);
            }

            $nombreArchivo = $tipo . '_' . $persona->id . '_' . time() . '.' . $request->file('documento')->getClientOriginalExtension();
            $request->file('documento')->move($dir, $nombreArchivo);

            $verificarCampo = $tipo === 'documento_academico' ? 'documento_academico_verified' : 'documento_provision_verified';
            $estudio->update([
                $campoArchivo => $nombreArchivo,
                $verificarCampo => false,
            ]);
        } else {
            $oldFile = $persona->$campoArchivo;
            if ($oldFile && file_exists($dir . '/' . $oldFile)) {
                unlink($dir . '/' . $oldFile);
            }

            $nombreArchivo = $tipo . '_' . $persona->id . '_' . time() . '.' . $request->file('documento')->getClientOriginalExtension();
            $request->file('documento')->move($dir, $nombreArchivo);

            $verificarCampo = $tipo === 'carnet' ? 'carnet_verificado' : 'certificado_nacimiento_verificado';
            $persona->update([
                $campoArchivo => $nombreArchivo,
                $verificarCampo => false,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Documento subido correctamente.',
            'url' => asset('storage/documentos/' . $nombreArchivo),
        ]);
    }

    /* ── Comprobantes de Pago ── */

    public function getInscritosParaComprobante()
    {
        $persona = auth()->user()->persona;

        if (!$persona || !$persona->trabajador) {
            return response()->json(['success' => false, 'error' => 'Sin acceso'], 403);
        }

        $cargosIds = $persona->trabajador->trabajadores_cargos()
            ->whereIn('cargo_id', [2, 3, 6])
            ->where('estado', 'Vigente')
            ->pluck('id');

        if ($cargosIds->isEmpty()) {
            return response()->json(['success' => false, 'error' => 'Sin cargos de marketing'], 403);
        }

        $inscritos = Inscripcione::whereIn('trabajadores_cargo_id', $cargosIds)
            ->where('estado', 'Inscrito')
            ->with(['estudiante.persona', 'ofertaAcademica.programa', 'planesPago', 'pagosRespaldos', 'cuotas'])
            ->get()
            ->map(function ($ins) {
                $p = $ins->estudiante?->persona;
                $tieneCuotasPendientes = $ins->cuotas->contains(
                    fn ($c) => (float) $c->pago_pendiente_bs > 0
                );
                return [
                    'inscripcion_id'          => $ins->id,
                    'estudiante_nombre'        => trim(($p?->nombres ?? '') . ' ' . ($p?->apellido_paterno ?? '') . ' ' . ($p?->apellido_materno ?? '')),
                    'estudiante_carnet'        => $p?->carnet ?? '',
                    'programa'                 => $ins->ofertaAcademica?->programa?->nombre ?? '—',
                    'plan_pago'                => $ins->planesPago?->nombre ?? 'Sin plan',
                    'tiene_cuotas_pendientes'  => $tieneCuotasPendientes,
                    'comprobantes'             => $ins->pagosRespaldos->map(fn ($c) => [
                        'id'         => $c->id,
                        'estado'     => $c->estado,
                        'fecha'      => $c->created_at->format('d/m/Y'),
                        'archivo'    => asset('storage/comprobantes/' . $c->archivo),
                    ])->values(),
                ];
            });

        return response()->json(['success' => true, 'inscritos' => $inscritos]);
    }

    public function getCuotasPorInscripcion(int $inscripcionId)
    {
        $persona = auth()->user()->persona;

        if (!$persona || !$persona->trabajador) {
            return response()->json(['success' => false, 'error' => 'Sin acceso'], 403);
        }

        $cargosIds = $persona->trabajador->trabajadores_cargos()
            ->whereIn('cargo_id', [2, 3, 6])
            ->where('estado', 'Vigente')
            ->pluck('id');

        $inscripcion = Inscripcione::whereIn('trabajadores_cargo_id', $cargosIds)
            ->where('id', $inscripcionId)
            ->with(['cuotas', 'planesPago'])
            ->first();

        if (!$inscripcion) {
            return response()->json(['success' => false, 'error' => 'Inscripción no encontrada'], 404);
        }

        $cuotasPendientes = $inscripcion->cuotas->filter(fn ($c) => (float) $c->pago_pendiente_bs > 0);

        $porPlan = [
            'plan_nombre' => $inscripcion->planesPago?->nombre ?? 'Plan de Pago',
            'cuotas'      => $cuotasPendientes->map(fn ($c) => [
                'id'                => $c->id,
                'nombre'            => $c->nombre,
                'n_cuota'           => $c->n_cuota,
                'monto_bs'          => number_format((float) $c->monto_bs, 2),
                'pago_pendiente_bs' => number_format((float) $c->pago_pendiente_bs, 2),
                'fecha_vencimiento' => $c->fecha_vencimiento?->format('d/m/Y'),
                'estado'            => $c->estado,
            ])->values(),
        ];

        return response()->json(['success' => true, 'grupo' => $porPlan]);
    }

    public function subirComprobante(Request $request)
    {
        $request->validate([
            'inscripcion_id' => 'required|integer|exists:inscripciones,id',
            'archivo'        => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'observaciones'  => 'nullable|string|max:500',
            'cuotas'         => 'required|array|min:1',
            'cuotas.*'       => 'integer|exists:cuotas,id',
        ]);

        $persona = auth()->user()->persona;

        if (!$persona || !$persona->trabajador) {
            return response()->json(['success' => false, 'message' => 'Sin acceso'], 403);
        }

        $cargosIds = $persona->trabajador->trabajadores_cargos()
            ->whereIn('cargo_id', [2, 3, 6])
            ->where('estado', 'Vigente')
            ->pluck('id');

        $inscripcion = Inscripcione::whereIn('trabajadores_cargo_id', $cargosIds)
            ->where('id', $request->inscripcion_id)
            ->where('estado', 'Inscrito')
            ->first();

        if (!$inscripcion) {
            return response()->json(['success' => false, 'message' => 'Inscripción no encontrada o sin acceso.'], 404);
        }

        $cuotasIds = Cuota::where('inscripcione_id', $inscripcion->id)
            ->whereIn('id', $request->cuotas)
            ->pluck('id');

        if ($cuotasIds->isEmpty()) {
            return response()->json(['success' => false, 'message' => 'Las cuotas seleccionadas no pertenecen a esta inscripción.'], 422);
        }

        $dir = public_path('storage/comprobantes');
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $ext          = $request->file('archivo')->getClientOriginalExtension();
        $nombreArchivo = 'comprobante_' . $inscripcion->id . '_' . time() . '.' . $ext;
        $request->file('archivo')->move($dir, $nombreArchivo);

        $comprobante = PagoRespaldo::create([
            'inscripcione_id' => $inscripcion->id,
            'archivo'         => $nombreArchivo,
            'observaciones'   => $request->observaciones,
            'estado'          => 'pendiente',
        ]);

        $comprobante->cuotas()->attach($cuotasIds);

        return response()->json(['success' => true, 'mensaje' => 'Comprobante subido correctamente. Pendiente de verificación.']);
    }

    public function verifyDocument(Request $request)
    {
        $request->validate([
            'tipo' => 'required|in:carnet,certificado_nacimiento,documento_academico,provision_nacional',
            'accion' => 'required|in:verificar,quitar',
        ]);

        $user = auth()->user();
        $persona = $user->persona;

        if (!$persona) {
            return response()->json(['success' => false, 'message' => 'No se encontró la persona asociada.']);
        }

        $tipo = $request->input('tipo');
        $accion = $request->input('accion');
        $verificado = $accion === 'verificar';

        if ($tipo === 'documento_academico' || $tipo === 'provision_nacional') {
            $estudio = $persona->estudios()->where('principal', 1)->first();
            if (!$estudio) {
                return response()->json(['success' => false, 'message' => 'No tienes un estudio principal registrado.']);
            }

            $verificarCampo = $tipo === 'documento_academico' ? 'documento_academico_verified' : 'documento_provision_verificado';
            $estudio->update([$verificarCampo => $verificado]);
        } else {
            $verificarCampo = $tipo === 'carnet' ? 'carnet_verificado' : 'certificado_nacimiento_verificado';
            $persona->update([$verificarCampo => $verificado]);
        }

        $texto = $verificado ? 'verificado' : 'verificación retirada';
        return response()->json([
            'success' => true,
            'message' => "Documento {$texto} correctamente.",
        ]);
    }
}
