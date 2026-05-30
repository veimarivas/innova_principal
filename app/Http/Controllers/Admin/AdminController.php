<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Inscripcione;
use App\Models\Persona;
use App\Models\Sucursale;
use App\Models\Tipo;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function AdminLogout(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }

    public function dashboard(Request $request)
    {
        $user = Auth::user();


        $mes = $request->input('mes', Carbon::now()->month);
        $gestion = $request->input('gestion', Carbon::now()->year);
        $sucursalId = $request->input('sucursal');

        $mesesEs = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
        $nombreMes = $mesesEs[$mes - 1];

        // Obtener ranking con desglose
        $rankingGeneralTop3Result = $this->getRankingPorMesYGestion($mes, $gestion, 3, $sucursalId);
        $rankingGeneralResult = $this->getRankingPorMesYGestion($mes, $gestion, null, $sucursalId);
        $rankingPorSucursal = $this->getRankingPorSucursal($mes, $gestion, $sucursalId);
        $graficoPorTipo = $this->getTotalPorTipo($mes, $gestion, $sucursalId); // ✅
        $graficoBarrasData = $this->getDatosGraficoBarrasPorSucursal($mes, $gestion, $sucursalId);

        $sucursales = Sucursale::all();

        return view('admin.dashboard.index', compact('mes', 'gestion', 'nombreMes', 'sucursales', 'sucursalId'))
            ->with('rankingGeneralTop3', $rankingGeneralTop3Result['data'])
            ->with('rankingGeneralCompleto', $rankingGeneralResult['data'])
            ->with('rankingPorSucursal', $rankingPorSucursal)
            ->with('tipos', $rankingGeneralResult['tipos'])
            ->with('graficoPorTipo', $graficoPorTipo)
            ->with('graficoBarrasData', $graficoBarrasData);
    }


    public function dashboardData(Request $request)
    {
        $mes = $request->get('mes', now()->month);
        $gestion = $request->get('gestion', now()->year);
        $sucursalId = $request->get('sucursal');

        // Reutilizamos exactamente los mismos métodos que en dashboard()
        $rankingGeneralTop3Result = $this->getRankingPorMesYGestion($mes, $gestion, 3, $sucursalId);
        $rankingGeneralResult = $this->getRankingPorMesYGestion($mes, $gestion, null, $sucursalId);
        $rankingPorSucursal = $this->getRankingPorSucursal($mes, $gestion, $sucursalId);
        $graficoPorTipo = $this->getTotalPorTipo($mes, $gestion, $sucursalId);
        $graficoBarrasData = $this->getDatosGraficoBarrasPorSucursal($mes, $gestion, $sucursalId);

        $mesesEs = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
        return response()->json([
            'rankingGeneralTop3' => $rankingGeneralTop3Result['data'],
            'rankingGeneralCompleto' => $rankingGeneralResult['data'],
            'rankingPorSucursal' => $rankingPorSucursal,
            'graficoPorTipo' => $graficoPorTipo,
            'graficoBarrasData' => $graficoBarrasData,
            'tipos' => $rankingGeneralResult['tipos'], // ✅ Correcto: array asociativo [id => nombre]
            'nombreMes' => $mesesEs[$mes - 1],
            'gestion' => $gestion,
            'mes' => $mes,
            'sucursalId' => $sucursalId,
        ]);
    }

    private function getRankingPorMesYGestion($mes, $gestion, $limit = null, $sucursalId = null)
    {
        $tipos = Tipo::pluck('nombre', 'id')->toArray();

        $tipoSelects = [];
        foreach ($tipos as $tipoId => $tipoNombre) {
            $alias = 'tipo_' . $tipoId;
            $tipoSelects[] = "SUM(CASE WHEN posgrados.tipo_id = {$tipoId} THEN 1 ELSE 0 END) as {$alias}";
        }
        $tipoSelectString = implode(', ', $tipoSelects);

        $query = Inscripcione::selectRaw("
            personas.id,
            personas.nombres,
            personas.apellido_paterno,
            personas.apellido_materno,
            personas.sexo,
            personas.fotografia, 
            COUNT(inscripciones.id) as total_inscripciones,
            {$tipoSelectString}
        ")
            ->join('trabajadores_cargos', 'inscripciones.trabajadores_cargo_id', '=', 'trabajadores_cargos.id')
            ->join('trabajadores', 'trabajadores_cargos.trabajadore_id', '=', 'trabajadores.id')
            ->join('personas', 'trabajadores.persona_id', '=', 'personas.id')
            ->join('ofertas_academicas', 'inscripciones.ofertas_academica_id', '=', 'ofertas_academicas.id')
            ->join('posgrados', 'ofertas_academicas.posgrado_id', '=', 'posgrados.id')
            ->whereYear('inscripciones.fecha_registro', $gestion)
            ->whereMonth('inscripciones.fecha_registro', $mes)
            ->where('inscripciones.estado', 'Inscrito')
            ->groupBy('personas.id', 'personas.nombres', 'personas.apellido_paterno', 'personas.apellido_materno', 'personas.sexo', 'personas.fotografia');

        // ✅ Corrección: filtrar por sucursal de la oferta, no del vendedor
        if ($sucursalId) {
            $query->where('ofertas_academicas.sucursale_id', $sucursalId);
        }

        $collection = $query
            ->orderByDesc('total_inscripciones')
            ->when($limit, fn($q, $l) => $q->take($l))
            ->get()
            ->map(function ($item) use ($tipos) {
                $persona = new \stdClass();
                $persona->id = $item->id;
                $persona->nombres = $item->nombres;
                $persona->apellido_paterno = $item->apellido_paterno;
                $persona->apellido_materno = $item->apellido_materno;
                $persona->sexo = $item->sexo;
                $persona->fotografia = $item->fotografia;
                $persona->total_inscripciones = $item->total_inscripciones;
                $persona->nombre_completo = trim("{$item->nombres} {$item->apellido_paterno} {$item->apellido_materno}");

                if ($item->fotografia && file_exists(public_path($item->fotografia))) {
                    $persona->avatar = asset($item->fotografia);
                } else {
                    $persona->avatar = strpos(strtolower($item->sexo), 'hombre') !== false
                        ? asset('backend/assets/images/hombre.png')
                        : asset('backend/assets/images/mujer.png');
                }
                $persona->desglose = [];
                foreach ($tipos as $tipoId => $tipoNombre) {
                    $alias = 'tipo_' . $tipoId;
                    $persona->desglose[$tipoNombre] = isset($item->$alias) ? (int) $item->$alias : 0;
                }
                return $persona;
            });

        return [
            'data' => $collection,
            'tipos' => $tipos
        ];
    }

    /**
     * Obtiene el ranking por sucursal con desglose por tipo de posgrado
     */
    private function getRankingPorSucursal($mes, $gestion, $sucursalId = null)
    {
        $tipos = Tipo::pluck('nombre', 'id')->toArray();

        $tipoSelects = [];
        foreach ($tipos as $tipoId => $tipoNombre) {
            $alias = 'tipo_' . $tipoId;
            $tipoSelects[] = "SUM(CASE WHEN posgrados.tipo_id = {$tipoId} THEN 1 ELSE 0 END) as {$alias}";
        }
        $tipoSelectString = implode(', ', $tipoSelects);

        $query = Inscripcione::selectRaw("
            sucursales_oa.id as sucursal_id,
            sucursales_oa.nombre as sucursal_nombre,
            personas.id,
            personas.nombres,
            personas.apellido_paterno,
            personas.apellido_materno,
            personas.sexo,
            personas.fotografia,
            COUNT(inscripciones.id) as total_inscripciones,
            {$tipoSelectString}
        ")
            ->join('ofertas_academicas', 'inscripciones.ofertas_academica_id', '=', 'ofertas_academicas.id')
            ->join('sucursales as sucursales_oa', 'ofertas_academicas.sucursale_id', '=', 'sucursales_oa.id') // ✅ Sucursal de la oferta
            ->join('trabajadores_cargos', 'inscripciones.trabajadores_cargo_id', '=', 'trabajadores_cargos.id')
            ->join('trabajadores', 'trabajadores_cargos.trabajadore_id', '=', 'trabajadores.id')
            ->join('personas', 'trabajadores.persona_id', '=', 'personas.id')
            ->join('posgrados', 'ofertas_academicas.posgrado_id', '=', 'posgrados.id')
            ->whereYear('inscripciones.fecha_registro', $gestion)
            ->whereMonth('inscripciones.fecha_registro', $mes)
            ->where('inscripciones.estado', 'Inscrito')
            ->groupBy(
                'sucursales_oa.id',
                'sucursales_oa.nombre',
                'personas.id',
                'personas.nombres',
                'personas.apellido_paterno',
                'personas.apellido_materno',
                'personas.sexo',
                'personas.fotografia'
            );

        if ($sucursalId) {
            $query->where('sucursales_oa.id', $sucursalId);
        }

        return $query
            ->orderBy('sucursales_oa.nombre')
            ->orderByDesc('total_inscripciones')
            ->get()
            ->map(function ($item) use ($tipos) {
                $persona = new \stdClass();
                $persona->sucursal_id = $item->sucursal_id;
                $persona->sucursal_nombre = $item->sucursal_nombre;
                $persona->id = $item->id;
                $persona->nombres = $item->nombres;
                $persona->apellido_paterno = $item->apellido_paterno;
                $persona->apellido_materno = $item->apellido_materno;
                $persona->sexo = $item->sexo;
                $persona->fotografia = $item->fotografia;

                if ($item->fotografia && file_exists(public_path($item->fotografia))) {
                    $persona->avatar = asset($item->fotografia);
                } else {
                    $persona->avatar = strpos(strtolower($item->sexo), 'Hombre') !== false || strpos(strtolower($item->sexo), 'hombre') !== false
                        ? asset('backend/assets/images/hombre.png')
                        : asset('backend/assets/images/mujer.png');
                }
                $persona->total_inscripciones = $item->total_inscripciones;
                $persona->nombre_completo = trim("{$item->nombres} {$item->apellido_paterno} {$item->apellido_materno}");


                $persona->desglose = [];
                foreach ($tipos as $tipoId => $tipoNombre) {
                    $alias = 'tipo_' . $tipoId;
                    $persona->desglose[$tipoNombre] = isset($item->$alias) ? (int) $item->$alias : 0;
                }

                return $persona;
            })
            ->groupBy('sucursal_nombre');
    }

    private function getTotalPorTipo($mes, $gestion, $sucursalId = null)
    {
        $tipos = Tipo::pluck('nombre', 'id')->toArray();

        $query = Inscripcione::selectRaw('
        tipos.nombre as tipo_nombre,
        COUNT(*) as total
    ')
            ->join('ofertas_academicas', 'inscripciones.ofertas_academica_id', '=', 'ofertas_academicas.id')
            ->join('posgrados', 'ofertas_academicas.posgrado_id', '=', 'posgrados.id')
            ->join('tipos', 'posgrados.tipo_id', '=', 'tipos.id')
            ->whereYear('inscripciones.fecha_registro', $gestion)
            ->whereMonth('inscripciones.fecha_registro', $mes)
            ->where('inscripciones.estado', 'Inscrito');

        if ($sucursalId) {
            $query->where('ofertas_academicas.sucursale_id', $sucursalId); // ✅ Filtro correcto
        }

        $result = $query->groupBy('tipos.nombre')->pluck('total', 'tipo_nombre')->toArray();

        $data = [];
        foreach ($tipos as $nombre) {
            $data[$nombre] = $result[$nombre] ?? 0;
        }

        return $data;
    }


    private function getDatosGraficoBarrasPorSucursal($mes, $gestion, $sucursalId = null)
    {
        $tiposCollection = Tipo::orderBy('id')->get();
        $tiposNombres = $tiposCollection->pluck('nombre')->toArray();

        if (empty($tiposNombres)) {
            return [
                'sucursales' => [],
                'tipos' => [],
                'valores' => []
            ];
        }

        $query = Inscripcione::selectRaw('
        sucursales.nombre as sucursal_nombre,
        tipos.nombre as tipo_nombre,
        COUNT(*) as total
    ')
            ->join('ofertas_academicas', 'inscripciones.ofertas_academica_id', '=', 'ofertas_academicas.id')
            ->join('sucursales', 'ofertas_academicas.sucursale_id', '=', 'sucursales.id')
            ->join('posgrados', 'ofertas_academicas.posgrado_id', '=', 'posgrados.id')
            ->join('tipos', 'posgrados.tipo_id', '=', 'tipos.id')
            ->whereYear('inscripciones.fecha_registro', $gestion)
            ->whereMonth('inscripciones.fecha_registro', $mes)
            ->where('inscripciones.estado', 'Inscrito');

        if ($sucursalId) {
            $query->where('sucursales.id', $sucursalId);
        }

        $result = $query
            ->groupBy('sucursales.nombre', 'tipos.nombre')
            ->get();

        // Si no hay resultados, devolvemos una estructura vacía pero con los tipos definidos
        if ($result->isEmpty()) {
            return [
                'sucursales' => [], // No hay sucursales con datos
                'tipos' => $tiposNombres, // Los tipos siguen siendo los mismos
                'valores' => [] // Valores vacíos
            ];
        }

        // Extraemos las sucursales únicas
        $sucursalesUnicas = $result->pluck('sucursal_nombre')->unique()->sort()->values()->toArray();

        // Inicializamos la estructura de datos: [sucursal][tipo] = 0
        $data = [];
        foreach ($sucursalesUnicas as $sucursalNombre) {
            $data[$sucursalNombre] = [];
            foreach ($tiposNombres as $tipoNombre) {
                $data[$sucursalNombre][$tipoNombre] = 0;
            }
        }

        // Rellenamos con los valores reales
        foreach ($result as $row) {
            if (isset($data[$row->sucursal_nombre][$row->tipo_nombre])) {
                $data[$row->sucursal_nombre][$row->tipo_nombre] = (int) $row->total;
            }
        }

        return [
            'sucursales' => $sucursalesUnicas,
            'tipos' => $tiposNombres,
            'valores' => $data
        ];
    }

    public function verInscripcionesVendedor($personaId)
    {
        $persona = Persona::with('trabajador.trabajadores_cargos')->findOrFail($personaId);
        $trabajadoresCargosIds = $persona->trabajador->trabajadores_cargos->pluck('id');

        $inscripciones = Inscripcione::with([
            'ofertaAcademica.programa',
            'ofertaAcademica.sucursal.sede',
            'ofertaAcademica.posgrado.tipo', // ✅ Aseguramos que se cargue el tipo
            'estudiante.persona'
        ])
            ->whereIn('trabajadores_cargo_id', $trabajadoresCargosIds)
            ->whereIn('estado', ['Inscrito', 'Pre-Inscrito'])
            ->orderBy('fecha_registro', 'desc')
            ->get();

        // === ESTADÍSTICAS POR TIPO DE POSGRADO ===
        $estadisticasPorTipo = Inscripcione::selectRaw('
        tipos.nombre as tipo_nombre,
        COUNT(*) as total
    ')
            ->join('ofertas_academicas', 'inscripciones.ofertas_academica_id', '=', 'ofertas_academicas.id')
            ->join('posgrados', 'ofertas_academicas.posgrado_id', '=', 'posgrados.id')
            ->join('tipos', 'posgrados.tipo_id', '=', 'tipos.id')
            ->whereIn('inscripciones.trabajadores_cargo_id', $trabajadoresCargosIds)
            ->whereIn('inscripciones.estado', ['Inscrito', 'Pre-Inscrito'])
            ->groupBy('tipos.nombre')
            ->orderByDesc('total')
            ->get()
            ->pluck('total', 'tipo_nombre')
            ->toArray();

        // Todos los tipos posibles (para llenar con 0 si no hay)
        $todosTipos = Tipo::pluck('nombre')->toArray();
        $datosPorTipo = [];
        foreach ($todosTipos as $tipo) {
            $datosPorTipo[$tipo] = $estadisticasPorTipo[$tipo] ?? 0;
        }

        return view('admin.dashboard.detalle-vendedor', compact(
            'persona',
            'inscripciones',
            'datosPorTipo'
        ));
    }

    // En AdminController.php

    public function vendedorData(Request $request, $personaId)
    {
        $persona = Persona::with('trabajador.trabajadores_cargos')->findOrFail($personaId);
        $trabajadoresCargosIds = $persona->trabajador->trabajadores_cargos->pluck('id');

        $gestion = $request->get('gestion');
        $mes = $request->get('mes');

        $queryInscripciones = Inscripcione::with([
            'ofertaAcademica.programa',
            'ofertaAcademica.sucursal',
            'ofertaAcademica.posgrado.tipo',
            'estudiante.persona'
        ])
            ->whereIn('trabajadores_cargo_id', $trabajadoresCargosIds)
            ->whereIn('estado', ['Inscrito', 'Pre-Inscrito']);

        if ($gestion) {
            $queryInscripciones->whereYear('fecha_registro', $gestion);
        }
        if ($mes && $mes !== 'todos') {
            $queryInscripciones->whereMonth('fecha_registro', $mes);
        }

        $inscripciones = $queryInscripciones->orderBy('fecha_registro', 'desc')->get();

        // --- Estadísticas por mes ---
        $estadisticasPorMes = Inscripcione::selectRaw("
        YEAR(fecha_registro) as year,
        MONTH(fecha_registro) as month,
        DATE_FORMAT(fecha_registro, '%Y-%m') as mes_key,
        DATE_FORMAT(fecha_registro, '%b %Y') as mes_label,
        SUM(CASE WHEN estado = 'Inscrito' THEN 1 ELSE 0 END) as inscritos,
        SUM(CASE WHEN estado = 'Pre-Inscrito' THEN 1 ELSE 0 END) as pre_inscritos
    ")
            ->whereIn('trabajadores_cargo_id', $trabajadoresCargosIds)
            ->whereIn('estado', ['Inscrito', 'Pre-Inscrito']);

        if ($gestion) $estadisticasPorMes->whereYear('fecha_registro', $gestion);
        if ($mes && $mes !== 'todos') $estadisticasPorMes->whereMonth('fecha_registro', $mes);

        $estadisticasPorMes = $estadisticasPorMes
            ->groupBy('year', 'month', 'mes_key', 'mes_label')
            ->orderBy('year')
            ->orderBy('month')
            ->get()
            ->keyBy('mes_key');

        // --- Rango de meses según los filtros aplicados ---
        $todosMeses = [];

        // Definir rango por filtros
        if ($gestion && $mes && $mes !== 'todos') {
            // Un solo mes
            $rangoMeses = [Carbon::create($gestion, $mes, 1)];
        } elseif ($gestion) {
            // Todo el año
            $rangoMeses = [];
            for ($m = 1; $m <= 12; $m++) {
                $rangoMeses[] = Carbon::create($gestion, $m, 1);
            }
        } else {
            // Sin filtros: usar todo el rango de las inscripciones (solo si hay)
            if ($inscripciones->isNotEmpty()) {
                $minDate = $inscripciones->min('fecha_registro');
                $maxDate = $inscripciones->max('fecha_registro');
                $inicio = Carbon::create($minDate)->startOfMonth();
                $fin = Carbon::create($maxDate)->endOfMonth();
                $rangoMeses = [];
                $mesActual = clone $inicio;
                while ($mesActual->lte($fin)) {
                    $rangoMeses[] = clone $mesActual;
                    $mesActual->addMonth();
                }
            } else {
                $rangoMeses = []; // No hay datos ni rango
            }
        }

        // Construir $todosMeses con todos los meses del rango
        foreach ($rangoMeses as $carbonMes) {
            $key = $carbonMes->format('Y-m');
            $todosMeses[$key] = [
                'label' => $carbonMes->format('M Y'),
                'inscritos' => $estadisticasPorMes->get($key, (object)['inscritos' => 0])->inscritos,
                'pre_inscritos' => $estadisticasPorMes->get($key, (object)['pre_inscritos' => 0])->pre_inscritos,
            ];
        }

        // --- Estadísticas por tipo ---
        $estadisticasPorTipo = Inscripcione::selectRaw('
        tipos.nombre as tipo_nombre,
        COUNT(*) as total
    ')
            ->join('ofertas_academicas', 'inscripciones.ofertas_academica_id', '=', 'ofertas_academicas.id')
            ->join('posgrados', 'ofertas_academicas.posgrado_id', '=', 'posgrados.id')
            ->join('tipos', 'posgrados.tipo_id', '=', 'tipos.id')
            ->whereIn('inscripciones.trabajadores_cargo_id', $trabajadoresCargosIds)
            ->whereIn('inscripciones.estado', ['Inscrito', 'Pre-Inscrito']);

        if ($gestion) $estadisticasPorTipo->whereYear('inscripciones.fecha_registro', $gestion);
        if ($mes && $mes !== 'todos') $estadisticasPorTipo->whereMonth('inscripciones.fecha_registro', $mes);

        $estadisticasPorTipo = $estadisticasPorTipo
            ->groupBy('tipos.nombre')
            ->pluck('total', 'tipo_nombre')
            ->toArray();

        $todosTipos = Tipo::pluck('nombre')->toArray();
        $datosPorTipo = [];
        foreach ($todosTipos as $tipo) {
            $datosPorTipo[$tipo] = $estadisticasPorTipo[$tipo] ?? 0;
        }

        $tablaHtml = view('admin.dashboard.partials.detalle-tabla', compact('inscripciones'))->render();

        return response()->json([
            'graficoMeses' => $todosMeses,
            'graficoPorTipo' => $datosPorTipo,
            'tablaHtml' => $tablaHtml,
            'total' => $inscripciones->count(),
            'inscritos' => $inscripciones->where('estado', 'Inscrito')->count(),
            'pre_inscritos' => $inscripciones->where('estado', 'Pre-Inscrito')->count(),
        ]);
    }
}
