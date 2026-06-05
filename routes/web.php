<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\DepartamentoController;
use App\Http\Controllers\AreaController;
use App\Http\Controllers\CuentasVideollamadaController;
use App\Http\Controllers\TipoController;
use App\Http\Controllers\ConvenioController;
use App\Http\Controllers\PosgradoController;
use App\Http\Controllers\OfertasAcademicaController;
use App\Http\Controllers\ModuloController;
use App\Http\Controllers\CronogramaController;
use App\Http\Controllers\ProgramaController;
use App\Http\Controllers\GradoAcademicoController;
use App\Http\Controllers\ProfesionController;
use App\Http\Controllers\UniversidadeController;
use App\Http\Controllers\SedeController;
use App\Http\Controllers\FaseController;
use App\Http\Controllers\ModalidadeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PersonaController;
use App\Http\Controllers\Admin\CargoController;
use App\Http\Controllers\Admin\TrabajadoreController;
use App\Http\Controllers\Admin\EstudianteController;
use App\Http\Controllers\Admin\DocenteController;
use App\Http\Controllers\ConceptoController;
use App\Http\Controllers\PlanesPagoController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\UserProfileController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\MoodleMatriculaController;
use App\Http\Controllers\ActividadesController;
use App\Http\Controllers\ActividadesEditorController;
use App\Http\Controllers\ActividadesGestionController;
use App\Http\Controllers\QuizPreguntasController;
use App\Http\Controllers\AcademicoController;
use App\Http\Controllers\ContabilidadController;
use App\Http\Controllers\Admin\ComprobantesPagoController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\VirtualDashboardController;
use App\Http\Controllers\VirtualEstudianteController;
use App\Http\Controllers\PreInscripcionController;

Auth::routes();

// ── Portal Virtual ────────────────────────────────────────────────────────
Route::prefix('virtual')->name('virtual.')->middleware(['auth', 'isMoodle'])->group(function () {
    Route::get('/dashboard', [VirtualDashboardController::class, 'index'])->name('dashboard');
    Route::get('/actividades/{moduloId}', [VirtualDashboardController::class, 'actividades'])->name('actividades');
    Route::get('/moodle-sso',  [VirtualDashboardController::class, 'moodleSso'])->name('moodle-sso');
    Route::get('/moodle-file', [VirtualDashboardController::class, 'moodleFile'])->name('moodle-file');
    Route::get('/inscripcion/{id}/cuotas', [VirtualDashboardController::class, 'getCuotasPendientes'])->name('inscripcion.cuotas');
    Route::post('/comprobante', [VirtualDashboardController::class, 'subirComprobante'])->name('comprobante.subir');
    Route::post('/cambiar-perfil', [VirtualDashboardController::class, 'cambiarPerfil'])->name('cambiarPerfil');
    Route::get('/recibo/{pagoId}/pdf', [VirtualDashboardController::class, 'generarReciboPdf'])->name('recibo.pdf');

    // ── Vista de módulo para docente ──────────────────────────────────────────
    Route::get('/docente/modulo/{moduloId}', [VirtualDashboardController::class, 'docenteModulo'])
        ->name('docente.modulo')
        ->middleware('checkDocente');

    // API para el módulo del docente (mismos controladores que el admin, middleware checkDocente)
    Route::prefix('docente/modulos/{moduloId}')->middleware('checkDocente')->group(function () {
        // Actividades
        Route::get('/actividades',                                   [ActividadesController::class, 'getActividades']);
        Route::get('/actividades/foro/{forumId}/discusiones',        [ActividadesController::class, 'getDiscusiones']);
        Route::post('/actividades/foro/{forumId}/discusion',         [ActividadesController::class, 'crearDiscusion']);
        // Editor de actividades
        Route::post('/secciones/guardar',                            [ActividadesEditorController::class, 'guardarSeccion']);
        Route::delete('/secciones/{sectionNumber}',                  [ActividadesEditorController::class, 'eliminarSeccion']);
        Route::get('/actividades/{cmid}/datos',                      [ActividadesEditorController::class, 'datosActividad']);
        Route::get('/actividades/{cmid}/recurso',                    [ActividadesEditorController::class, 'verArchivoRecurso']);
        Route::get('/actividades/{cmid}/adjunto-intro',              [ActividadesEditorController::class, 'verArchivoTareaIntro']);
        Route::post('/actividades/{cmid}/adjunto',                   [ActividadesEditorController::class, 'subirAdjuntoTarea']);
        Route::post('/actividades/guardar',                          [ActividadesEditorController::class, 'guardarActividad']);
        Route::delete('/actividades/{cmid}',                         [ActividadesEditorController::class, 'eliminarActividad']);
        Route::post('/reordenar',                                    [ActividadesEditorController::class, 'reordenar']);
        Route::post('/subir-archivo',                                [ActividadesEditorController::class, 'subirArchivo']);
        // Gestión (entregas, calificaciones, foros, quizzes)
        Route::get('/actividades/{cmid}/entregas',                                              [ActividadesGestionController::class, 'getSubmissions']);
        Route::post('/actividades/{cmid}/calificar',                                            [ActividadesGestionController::class, 'saveGrade']);
        Route::get('/actividades/{cmid}/foro/calificaciones',                                   [ActividadesGestionController::class, 'getForumGrades']);
        Route::post('/actividades/{cmid}/foro/calificar',                                       [ActividadesGestionController::class, 'saveForumGrade']);
        Route::get('/actividades/{cmid}/archivo/{userId}/{filename}',                           [ActividadesGestionController::class, 'downloadFile'])->where('filename', '.*');
        Route::get('/actividades/foro/{forumId}/discusiones/{discussionId}/posts',              [ActividadesGestionController::class, 'getDiscussionPosts']);
        Route::post('/actividades/foro/{forumId}/discusiones/{discussionId}/responder',         [ActividadesGestionController::class, 'replyDiscussion']);
        Route::get('/actividades/quiz/{quizId}/resultados',                                     [ActividadesGestionController::class, 'getQuizResults']);
        Route::get('/actividades/quiz/{quizId}/resultados/{attemptId}',                         [ActividadesGestionController::class, 'getAttemptDetail']);
        // Banco de preguntas
        Route::get('/actividades/quiz/{quizId}/preguntas',                                      [QuizPreguntasController::class, 'index']);
        Route::post('/actividades/quiz/{quizId}/preguntas/multichoice',                         [QuizPreguntasController::class, 'storeMultichoice']);
        Route::post('/actividades/quiz/{quizId}/preguntas/truefalse',                           [QuizPreguntasController::class, 'storeTrueFalse']);
        Route::post('/actividades/quiz/{quizId}/preguntas/matching',                            [QuizPreguntasController::class, 'storeMatching']);
        Route::get('/actividades/quiz/{quizId}/preguntas/{questionId}',                         [QuizPreguntasController::class, 'show']);
        Route::put('/actividades/quiz/{quizId}/preguntas/{questionId}/multichoice',             [QuizPreguntasController::class, 'updateMultichoice']);
        Route::put('/actividades/quiz/{quizId}/preguntas/{questionId}/truefalse',               [QuizPreguntasController::class, 'updateTrueFalse']);
        Route::put('/actividades/quiz/{quizId}/preguntas/{questionId}/matching',                [QuizPreguntasController::class, 'updateMatching']);
        Route::delete('/actividades/quiz/{quizId}/preguntas/{slotId}',                          [QuizPreguntasController::class, 'destroy']);
        // Centralizador
        Route::get('/academico/calificaciones',                      [AcademicoController::class, 'getGradeBook']);
        Route::post('/academico/ponderaciones',                      [AcademicoController::class, 'saveWeights']);
        Route::post('/centralizador/sincronizar-moodle',             [AcademicoController::class, 'sincronizarMoodle']);
        Route::get('/reporte/notas-detallado',                       [AcademicoController::class, 'reporteNotasDetallado']);
        Route::get('/reporte/notas-finales',                         [AcademicoController::class, 'reporteNotasFinales']);
        // Modo manual (sin actividades en Moodle)
        Route::post('/academico/actividad-manual',                   [AcademicoController::class, 'storeLocalItem']);
        Route::delete('/academico/actividad-manual/{itemId}',        [AcademicoController::class, 'destroyLocalItem'])->where('itemId', '-?[0-9]+');
        Route::post('/academico/calificaciones-manuales',            [AcademicoController::class, 'saveLocalGrades']);
    });

    // ── Actividades del estudiante ────────────────────────────────────────────
    Route::prefix('modulo/{moduloId}/actividad')->name('est.')->group(function () {
        // Recursos / adjuntos (visualizar / descargar) — reutilizan los métodos del editor (solo lectura)
        Route::get('/recurso/{cmid}',           [ActividadesEditorController::class, 'verArchivoRecurso'])->name('recurso.archivo');
        Route::get('/tarea/{cmid}/adjunto',     [ActividadesEditorController::class, 'verArchivoTareaIntro'])->name('tarea.adjunto');
        Route::get('/foro/{cmid}/adjunto',      [ActividadesEditorController::class, 'verArchivoForoIntro'])->name('foro.adjunto');
        // Tarea
        Route::get('/tarea/{cmid}',              [VirtualEstudianteController::class, 'getTarea'])->name('tarea.get');
        Route::post('/tarea/{cmid}/submit',      [VirtualEstudianteController::class, 'submitTarea'])->name('tarea.submit');
        Route::post('/tarea/{cmid}/archivo',     [VirtualEstudianteController::class, 'uploadTareaArchivo'])->name('tarea.archivo');
        Route::delete('/tarea/{cmid}/archivo',  [VirtualEstudianteController::class, 'eliminarTareaArchivo'])->name('tarea.archivo.eliminar');
        Route::get('/tarea/{cmid}/archivo/{filename}',  [VirtualEstudianteController::class, 'descargarTareaArchivo'])->name('tarea.archivo.descargar');

        // Foro
        Route::get('/foro/{cmid}',               [VirtualEstudianteController::class, 'getForoDiscusiones'])->name('foro.get');
        Route::post('/foro/{cmid}/discusion',    [VirtualEstudianteController::class, 'createDiscusion'])->name('foro.create');
        Route::get('/foro/{cmid}/discusion/{discussionId}',         [VirtualEstudianteController::class, 'getDiscusionPosts'])->name('foro.posts');
        Route::post('/foro/{cmid}/discusion/{discussionId}/reply',  [VirtualEstudianteController::class, 'replyDiscusion'])->name('foro.reply');

        // Cuestionario
        Route::get('/quiz/{cmid}',                              [VirtualEstudianteController::class, 'getQuiz'])->name('quiz.get');
        Route::post('/quiz/{cmid}/start',                       [VirtualEstudianteController::class, 'startQuiz'])->name('quiz.start');
        Route::get('/quiz/{cmid}/attempt/{attemptId}',           [VirtualEstudianteController::class, 'getAttemptQuestions'])->name('quiz.attempt');
        Route::post('/quiz/{cmid}/attempt/{attemptId}/submit',  [VirtualEstudianteController::class, 'submitQuiz'])->name('quiz.submit');
    });
});

// Página principal pública
Route::redirect('/', '/login');
Route::get('/oferta/{id}', [LandingController::class, 'show'])->name('oferta.detalle');
Route::get('/catalogo', [LandingController::class, 'catalogo'])->name('catalogo');

// Pre-inscripción pública (enlace generado por asesores)
Route::get('/preinscripcion/{token}', [PreInscripcionController::class, 'show'])->name('preinscripcion.show');
Route::post('/preinscripcion/{token}', [PreInscripcionController::class, 'store'])->name('preinscripcion.store');
Route::get('/preinscripcion/{token}/exito', [PreInscripcionController::class, 'exito'])->name('preinscripcion.exito');
Route::post('/preinscripcion/check/disponibilidad', [PreInscripcionController::class, 'checkDisponibilidad'])->name('preinscripcion.check');

// Dashboard
Route::prefix('admin')->middleware(['auth', 'isAdmin'])->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/dashboard/data', [AdminController::class, 'dashboardData'])->name('admin.dashboard.data');
    Route::get('/vendedor/inscripciones/{personaId}', [AdminController::class, 'verInscripcionesVendedor'])->name('admin.vendedor.inscripciones');
    Route::get('/vendedor/inscripciones/{personaId}/data', [AdminController::class, 'vendedorData'])->name('admin.vendedor.data');

    Route::get('index/{locale}', [App\Http\Controllers\HomeController::class, 'lang']);
    Route::post('/update-profile/{id}', [App\Http\Controllers\HomeController::class, 'updateProfile'])->name('updateProfile');
    Route::post('/update-password/{id}', [App\Http\Controllers\HomeController::class, 'updatePassword'])->name('updatePassword');

    // Departamentos
    Route::prefix('departamentos')->group(function () {
    Route::get('/', [DepartamentoController::class, 'indexAdmin'])->name('admin.departamentos.index');
    Route::get('/listar', [DepartamentoController::class, 'listar'])->name('admin.departamentos.listar');
    Route::post('/', [DepartamentoController::class, 'guardar'])->name('admin.departamentos.guardar');
    Route::put('/{id}', [DepartamentoController::class, 'actualizar'])->name('admin.departamentos.actualizar');
    Route::delete('/{id}', [DepartamentoController::class, 'eliminar'])->name('admin.departamentos.eliminar');
    Route::post('/{id}/ciudades', [DepartamentoController::class, 'agregarCiudad'])->name('admin.departamentos.agregarCiudad');
    Route::put('/{id}/ciudades/{ciudadId}', [DepartamentoController::class, 'actualizarCiudad'])->name('admin.departamentos.actualizarCiudad');
    Route::delete('/{id}/ciudades/{ciudadId}', [DepartamentoController::class, 'eliminarCiudad'])->name('admin.departamentos.eliminarCiudad');
    Route::get('/{id}/ciudades/listar', [DepartamentoController::class, 'listarCiudades'])->name('admin.departamentos.listarCiudades');
});

Route::controller(UserProfileController::class)->group(function () {
        Route::get('/profile', 'profile')->name('admin.profile');
        Route::get('/profile/ver', 'getProfileData')->name('admin.profile.data');
        Route::post('/profile/update-personal', 'updatePersonal')->name('admin.profile.update-personal');
        Route::post('/profile/upload-foto', 'uploadFoto')->name('admin.profile.upload-foto');
        Route::post('/profile/update-estudios', 'updateEstudios')->name('admin.profile.update-estudios');
        Route::get('/profile/estudios', 'getEstudios')->name('admin.profile.estudios');
        Route::post('/profile/update-cargo-data', 'updateCargoData')->name('admin.profile.update-cargo-data');
        Route::get('/profile/cargos', 'getCargos')->name('admin.profile.cargos');
        Route::get('/profile/marketing/ofertas-activas', 'getOfertasMarketingActivas')->name('admin.profile.marketing.ofertas-activas');
        Route::post('/profile/marketing/convertir-inscrito', 'convertirPreInscritoAInscrito')->name('admin.profile.marketing.convertir-inscrito');
        Route::get('/profile/marketing/oferta/{id}/planes-pago', 'obtenerPlanesPagoOferta')->name('admin.profile.marketing.oferta.planes-pago');
        Route::get('/profile/marketing/inscripcion/{id}/formulario-pdf', 'generarFormularioPdf')->name('admin.profile.marketing.inscripcion.formulario-pdf');
        Route::post('/profile/change-password', 'changePassword')->name('admin.profile.change-password');
        Route::post('/users/reset-password', 'resetPassword')->name('admin.users.reset-password');
        Route::get('/profile/marketing/enlace-con-plan', 'generarEnlaceConPlan')->name('admin.profile.marketing.enlace-con-plan');
        Route::get('/profile/marketing/inscritos-documentos', 'getInscritosDocumentos')->name('admin.profile.marketing.inscritos-documentos');
        Route::post('/profile/marketing/subir-respaldo', 'subirRespaldoPago')->name('admin.profile.marketing.subir-respaldo');
        Route::get('/profile/marketing/inscripcion/{id}/cuotas', 'obtenerCuotasInscripcion')->name('admin.profile.marketing.inscripcion.cuotas');
        Route::post('/profile/documento/subir', [ProfileController::class, 'uploadDocument'])->name('admin.profile.documento.subir');
        Route::post('/profile/documento/verificar', [ProfileController::class, 'verifyDocument'])->name('admin.profile.documento.verificar');
    });
}); // Fin grupo admin

// Áreas (fuera del grupo admin, agregar middleware)
Route::prefix('admin/areas')->middleware(['auth', 'isAdmin'])->group(function () {
    Route::get('/', [AreaController::class, 'index'])->name('admin.areas.index');
    Route::get('/listar', [AreaController::class, 'listar'])->name('admin.areas.listar');
    Route::post('/verificar', [AreaController::class, 'verificarNombre'])->name('admin.areas.verificar');
    Route::post('/', [AreaController::class, 'guardar'])->name('admin.areas.guardar');
    Route::put('/{id}', [AreaController::class, 'actualizar'])->name('admin.areas.actualizar');
    Route::delete('/{id}', [AreaController::class, 'eliminar'])->name('admin.areas.eliminar');
});

// Cuentas de Videollamada
Route::prefix('admin/cuentas-videollamada')->middleware(['auth', 'isAdmin'])->group(function () {
    Route::get('/', [CuentasVideollamadaController::class, 'index'])->name('admin.cuentas-videollamada.index');
    Route::get('/listar', [CuentasVideollamadaController::class, 'listar'])->name('admin.cuentas-videollamada.listar');
    Route::get('/{id}/ver', [CuentasVideollamadaController::class, 'ver'])->name('admin.cuentas-videollamada.ver');
    Route::post('/verificar', [CuentasVideollamadaController::class, 'verificarNombre'])->name('admin.cuentas-videollamada.verificar');
    Route::post('/', [CuentasVideollamadaController::class, 'guardar'])->name('admin.cuentas-videollamada.guardar');
    Route::put('/{id}', [CuentasVideollamadaController::class, 'actualizar'])->name('admin.cuentas-videollamada.actualizar');
    Route::delete('/{id}', [CuentasVideollamadaController::class, 'eliminar'])->name('admin.cuentas-videollamada.eliminar');
});

// Tipos
    Route::prefix('tipos')->group(function () {
    Route::get('/', [TipoController::class, 'index'])->name('admin.tipos.index');
    Route::get('/listar', [TipoController::class, 'listar'])->name('admin.tipos.listar');
    Route::post('/verificar', [TipoController::class, 'verificarNombre'])->name('admin.tipos.verificar');
    Route::post('/', [TipoController::class, 'guardar'])->name('admin.tipos.guardar');
    Route::put('/{id}', [TipoController::class, 'actualizar'])->name('admin.tipos.actualizar');
    Route::delete('/{id}', [TipoController::class, 'eliminar'])->name('admin.tipos.eliminar');
});

// Convenios
Route::prefix('admin/convenios')->middleware(['auth', 'isAdmin'])->group(function () {
    Route::get('/', [ConvenioController::class, 'index'])->name('admin.convenios.index');
    Route::get('/listar', [ConvenioController::class, 'listar'])->name('admin.convenios.listar');
    Route::post('/verificar', [ConvenioController::class, 'verificarNombre'])->name('admin.convenios.verificar');
    Route::post('/', [ConvenioController::class, 'guardar'])->name('admin.convenios.guardar');
    Route::put('/{id}', [ConvenioController::class, 'actualizar'])->name('admin.convenios.actualizar');
    Route::delete('/{id}', [ConvenioController::class, 'eliminar'])->name('admin.convenios.eliminar');
});

// Posgrados
Route::prefix('admin/posgrads')->name('admin.posgrads.')->middleware(['auth', 'isAdmin'])->group(function () {
    Route::get('/', [PosgradoController::class, 'index'])->name('index');
    Route::get('/listar', [PosgradoController::class, 'listar'])->name('listar');
    Route::post('/verificar', [PosgradoController::class, 'verificarNombre'])->name('verificar');
    Route::post('/', [PosgradoController::class, 'guardar'])->name('guardar');
    Route::put('/{id}', [PosgradoController::class, 'actualizar'])->name('actualizar');
    Route::delete('/{id}', [PosgradoController::class, 'eliminar'])->name('eliminar');
    Route::patch('/{id}/estado', [PosgradoController::class, 'cambiarEstado'])->name('cambiar-estado');
    Route::get('/{id}/ofertas/listar', [OfertasAcademicaController::class, 'listar'])->name('ofertas.listar');
    Route::get('/ofertas/{id}/detalle', [OfertasAcademicaController::class, 'detalle'])->name('ofertas.detalle');
    Route::get('/ofertas/{ofertaId}/planes-conceptos', [OfertasAcademicaController::class, 'listarPlanesConceptos'])->name('ofertas.planes-conceptos.listar');
    Route::get('/ofertas/{ofertaId}/planes-pago/disponibles', [OfertasAcademicaController::class, 'listarPlanesPagoDisponibles'])->name('ofertas.planes-pago.disponibles');
    Route::get('/ofertas/{ofertaId}/conceptos/disponibles', [OfertasAcademicaController::class, 'listarConceptosDisponibles'])->name('ofertas.conceptos.disponibles');
    Route::post('/ofertas/{ofertaId}/planes-conceptos', [OfertasAcademicaController::class, 'guardarPlanesConcepto'])->name('ofertas.planes-conceptos.guardar');
    Route::put('/ofertas/planes-conceptos/{id}', [OfertasAcademicaController::class, 'actualizarPlanesConcepto'])->name('ofertas.planes-conceptos.actualizar');
    Route::delete('/ofertas/planes-conceptos/{id}', [OfertasAcademicaController::class, 'eliminarPlanesConcepto'])->name('ofertas.planes-conceptos.eliminar');
    Route::get('/ofertas/{ofertaId}/planes-conceptos/verificar-principal', [OfertasAcademicaController::class, 'verificarPlanPrincipal'])->name('ofertas.planes-conceptos.verificar-principal');
    Route::get('/ofertas/{ofertaId}/planes-conceptos/precio-base/{conceptoId}', [OfertasAcademicaController::class, 'obtenerPrecioBase'])->name('ofertas.planes-conceptos.precio-base');
    Route::post('/ofertas/{ofertaId}/planes-conceptos/multiple', [OfertasAcademicaController::class, 'guardarPlanesConceptoMultiple'])->name('ofertas.planes-conceptos.multiple');
    Route::post('/ofertas', [OfertasAcademicaController::class, 'guardar'])->name('ofertas.guardar');
    Route::put('/ofertas/{id}', [OfertasAcademicaController::class, 'actualizar'])->name('ofertas.actualizar');
    Route::delete('/ofertas/{id}', [OfertasAcademicaController::class, 'eliminar'])->name('ofertas.eliminar');
    Route::post('/ofertas/verificar-programa', [OfertasAcademicaController::class, 'verificarPrograma'])->name('ofertas.verificar-programa');
    Route::get('/ofertas/{ofertaId}/modulos/listar', [ModuloController::class, 'listar'])->name('modulos.listar');
    Route::get('/ofertas/{ofertaId}/modulos/{moduloId}/detalle', [ModuloController::class, 'detalle'])->name('modulos.detalle');
    Route::post('/modulos/batch', [ModuloController::class, 'guardarBatch'])->name('modulos.guardar-batch');
    Route::post('/modulos', [ModuloController::class, 'guardar'])->name('modulos.guardar');
    Route::put('/modulos/{id}', [ModuloController::class, 'actualizar'])->name('modulos.actualizar');
    Route::delete('/modulos/{id}', [ModuloController::class, 'eliminar'])->name('modulos.eliminar');
    Route::post('/modulos/buscar-docente', [ModuloController::class, 'buscarDocente'])->name('modulos.buscar-docente');
    Route::post('/modulos/registrar-docente', [ModuloController::class, 'registrarDocente'])->name('modulos.registrar-docente');
    Route::post('/modulos/registrar-persona-docente', [ModuloController::class, 'registrarPersonaYDocente'])->name('modulos.registrar-persona-docente');
    Route::post('/docentes/registrar', [ModuloController::class, 'registrarDocenteCompleto'])->name('docentes.registrar');
    Route::get('/ofertas/{ofertaId}/modulos/{moduloId}/horarios', [ModuloController::class, 'listarHorarios'])->name('modulos.horarios.listar');
    Route::post('/modulos/{moduloId}/horarios', [ModuloController::class, 'guardarHorario'])->name('modulos.horarios.guardar');
    Route::put('/horarios/{id}', [ModuloController::class, 'actualizarHorario'])->name('modulos.horarios.actualizar');
    Route::delete('/horarios/{id}', [ModuloController::class, 'eliminarHorario'])->name('modulos.horarios.eliminar');
    Route::post('/modulos/{moduloId}/matricular-todos', [ModuloController::class, 'matricularTodos'])->name('modulos.matricular-todos');
    Route::post('/inscripciones/{inscripcionId}/matricular-modulo', [ModuloController::class, 'matricularEstudiante'])->name('inscripciones.matricular-modulo');
    Route::post('/modulos/registrar-estudios-docente', [ModuloController::class, 'registrarEstudiosDocente'])->name('modulos.registrar-estudios-docente');
    Route::put('/horarios/{id}/estado', [ModuloController::class, 'cambiarEstadoHorario'])->name('modulos.horarios.estado');
    Route::put('/modulos/{id}/estado', [ModuloController::class, 'cambiarEstadoModulo'])->name('modulos.estado');
    Route::post('/horarios/{id}/reprogramar', [ModuloController::class, 'reprogramarHorario'])->name('modulos.horarios.reprogramar');
    Route::get('/personas/listar-trabajadores', [ModuloController::class, 'listarTrabajadores'])->name('personas.listar-trabajadores');
    Route::post('/modulos/{moduloId}/enlace-videollamada', [ModuloController::class, 'asignarEnlaceVideollamada'])->name('modulos.enlace-videollamada');
    Route::put('/horarios/{id}/grabacion', [ModuloController::class, 'actualizarGrabacionHorario'])->name('modulos.horarios.grabacion');

    // Moodle — matrícula de estudiantes en módulos
    Route::get('/modulos/{moduloId}/moodle/estudiantes', [MoodleMatriculaController::class, 'estadoEstudiantes'])->name('moodle.modulo.estudiantes');
    Route::post('/modulos/{moduloId}/moodle/matricular', [MoodleMatriculaController::class, 'matricular'])->name('moodle.modulo.matricular');
    Route::post('/modulos/{moduloId}/moodle/crear-curso', [MoodleMatriculaController::class, 'crearCurso'])->name('moodle.modulo.crear-curso');
    Route::post('/modulos/{moduloId}/moodle/crear-curso-y-matricular-docente', [ModuloController::class, 'crearCursoYMatricularDocente'])->name('moodle.modulo.crear-curso-y-matricular-docente');
    Route::get('/ofertas/{ofertaId}/moodle/control-acceso', [MoodleMatriculaController::class, 'controlAccesoData'])->name('moodle.oferta.control-acceso');
    Route::post('/modulos/{moduloId}/moodle/suspender-acceso', [MoodleMatriculaController::class, 'suspenderAcceso'])->name('moodle.modulo.suspender-acceso');
    Route::post('/modulos/{moduloId}/moodle/matricular-todos', [MoodleMatriculaController::class, 'matricularTodosEnMoodle'])->name('moodle.modulo.matricular-todos');
    Route::post('/modulos/{moduloId}/moodle/matricular-uno/{inscripcionId}', [MoodleMatriculaController::class, 'matricularUnoEnMoodle'])->name('moodle.modulo.matricular-uno');
    Route::get('/modulos/{moduloId}/actividades', [ActividadesController::class, 'getActividades'])->name('moodle.modulo.actividades');

    // Editor de Actividades
    Route::prefix('modulos/{moduloId}')->name('admin.modulos.')->group(function () {
        Route::post('secciones/guardar',     [ActividadesEditorController::class, 'guardarSeccion'])->name('secciones.guardar');
        Route::delete('secciones/{sectionNumber}', [ActividadesEditorController::class, 'eliminarSeccion'])->name('secciones.eliminar');
        Route::get('actividades/{cmid}/datos',    [ActividadesEditorController::class, 'datosActividad'])->name('actividades.datos');
        Route::get('actividades/{cmid}/recurso',  [ActividadesEditorController::class, 'verArchivoRecurso'])->name('actividades.recurso');
        Route::get('actividades/{cmid}/adjunto-intro', [ActividadesEditorController::class, 'verArchivoTareaIntro'])->name('actividades.adjunto-intro');
        Route::post('actividades/{cmid}/adjunto', [ActividadesEditorController::class, 'subirAdjuntoTarea'])->name('actividades.adjunto');
        Route::post('actividades/guardar',        [ActividadesEditorController::class, 'guardarActividad'])->name('actividades.guardar');
        Route::delete('actividades/{cmid}',  [ActividadesEditorController::class, 'eliminarActividad'])->name('actividades.eliminar');
        Route::post('reordenar',             [ActividadesEditorController::class, 'reordenar'])->name('reordenar');
        Route::post('subir-archivo',         [ActividadesEditorController::class, 'subirArchivo'])->name('subir-archivo');

        // Gestión de Actividades (calificaciones, foros, quizzes)
        Route::get('actividades/{cmid}/entregas',    [ActividadesGestionController::class, 'getSubmissions'])->name('actividades.entregas');
        Route::post('actividades/{cmid}/calificar',  [ActividadesGestionController::class, 'saveGrade'])->name('actividades.calificar');
        Route::get('actividades/{cmid}/foro/calificaciones', [ActividadesGestionController::class, 'getForumGrades'])->name('actividades.foro.calificaciones');
        Route::post('actividades/{cmid}/foro/calificar',     [ActividadesGestionController::class, 'saveForumGrade'])->name('actividades.foro.calificar');
        Route::get('actividades/{cmid}/archivo/{userId}/{filename}', [ActividadesGestionController::class, 'downloadFile'])->name('actividades.descargar')->where('filename', '.*');
        Route::get('actividades/foro/{forumId}/discusiones/{discussionId}/posts', [ActividadesGestionController::class, 'getDiscussionPosts'])->name('actividades.foro.posts');
        Route::post('actividades/foro/{forumId}/discusiones/{discussionId}/responder', [ActividadesGestionController::class, 'replyDiscussion'])->name('actividades.foro.responder');
        Route::get('actividades/quiz/{quizId}/resultados',        [ActividadesGestionController::class, 'getQuizResults'])->name('actividades.quiz.resultados');
        Route::get('actividades/quiz/{quizId}/resultados/{attemptId}', [ActividadesGestionController::class, 'getAttemptDetail'])->name('actividades.quiz.detalle');

        // Banco de preguntas de cuestionarios
        Route::get('actividades/quiz/{quizId}/preguntas',                [QuizPreguntasController::class, 'index'])->name('actividades.quiz.preguntas');
        Route::post('actividades/quiz/{quizId}/preguntas/multichoice',   [QuizPreguntasController::class, 'storeMultichoice'])->name('actividades.quiz.preguntas.multichoice');
        Route::post('actividades/quiz/{quizId}/preguntas/truefalse',     [QuizPreguntasController::class, 'storeTrueFalse'])->name('actividades.quiz.preguntas.truefalse');
        Route::post('actividades/quiz/{quizId}/preguntas/matching',      [QuizPreguntasController::class, 'storeMatching'])->name('actividades.quiz.preguntas.matching');
        Route::get('actividades/quiz/{quizId}/preguntas/{questionId}',   [QuizPreguntasController::class, 'show'])->name('actividades.quiz.preguntas.show');
        Route::put('actividades/quiz/{quizId}/preguntas/{questionId}/multichoice', [QuizPreguntasController::class, 'updateMultichoice'])->name('actividades.quiz.preguntas.update.multichoice');
        Route::put('actividades/quiz/{quizId}/preguntas/{questionId}/truefalse',   [QuizPreguntasController::class, 'updateTrueFalse'])->name('actividades.quiz.preguntas.update.truefalse');
        Route::put('actividades/quiz/{quizId}/preguntas/{questionId}/matching',    [QuizPreguntasController::class, 'updateMatching'])->name('actividades.quiz.preguntas.update.matching');
        Route::delete('actividades/quiz/{quizId}/preguntas/{slotId}',    [QuizPreguntasController::class, 'destroy'])->name('actividades.quiz.preguntas.destroy');
    });

// Proxy para servir imágenes desde Moodle
Route::get('/moodle-img/{courseId}/{sectionId}/{fileName}', function($courseId, $sectionId, $fileName) {
    $token = config('moodle.token');
    $moodleUrl = rtrim(config('moodle.url'), '/');
    $url = $moodleUrl . '/webservice/pluginfile.php/' . $courseId . '/course/section/' . $sectionId . '/' . $fileName . '?token=' . $token;
    
    try {
        $response = Http::timeout(30)->get($url);
        if ($response->successful()) {
            $contentType = $response->header('Content-Type', 'image/png');
            return response($response->body(), 200, ['Content-Type' => $contentType, 'Cache-Control' => 'public, max-age=86400']);
        }
    } catch (\Exception $e) {
        Log::error('Moodle image proxy error: ' . $e->getMessage());
    }
    return response('Image not found', 404);
});

// Proxy para imágenes de Moodle - evita CORS y problemas de DNS
Route::get('/moodle-proxy-image/{path}', function($path, \Illuminate\Http\Request $request) {
    $token = config('moodle.token');
    $moodleUrl = rtrim(config('moodle.url'), '/');
    $fullUrl = $moodleUrl . '/webservice/pluginfile.php/' . $path . '?token=' . $token;
    
    try {
        $response = \Illuminate\Support\Facades\Http::timeout(30)->get($fullUrl);
        if ($response->successful()) {
            return response($response->body())
                ->withHeaders([
                    'Content-Type' => $response->header('Content-Type', 'image/png'),
                    'Cache-Control' => 'public, max-age=86400',
                ]);
        }
    } catch (\Exception $e) {
        \Log::error('Moodle proxy error: ' . $e->getMessage());
    }
    abort(404);
})->where('path', '.*');
    Route::get('/modulos/{moduloId}/actividades/foro/{forumId}/discusiones', [ActividadesController::class, 'getDiscusiones'])->name('moodle.modulo.foro.discusiones');
    Route::post('/modulos/{moduloId}/actividades/foro/{forumId}/discusion', [ActividadesController::class, 'crearDiscusion'])->name('moodle.modulo.foro.discusion');
    Route::get('/modulos/{moduloId}/academico/calificaciones', [AcademicoController::class, 'getGradeBook'])->name('academico.modulo.calificaciones');
    Route::post('/modulos/{moduloId}/academico/ponderaciones', [AcademicoController::class, 'saveWeights'])->name('academico.modulo.ponderaciones');
    Route::post('/modulos/{moduloId}/centralizador/sincronizar-moodle', [AcademicoController::class, 'sincronizarMoodle'])->name('centralizador.sincronizar');
    Route::get('/modulos/{moduloId}/reporte/notas-detallado', [AcademicoController::class, 'reporteNotasDetallado'])->name('modulo.reporte.notas-detallado');
    Route::get('/modulos/{moduloId}/reporte/notas-finales', [AcademicoController::class, 'reporteNotasFinales'])->name('modulo.reporte.notas-finales');

    Route::get('/ofertas/{ofertaId}/participante/{inscripcionId}/plan-pagos', [OfertasAcademicaController::class, 'planPagosParticipante'])
        ->name('ofertas.participante.plan-pagos');

    Route::post('/ofertas/{ofertaId}/inscripciones/{inscripcionId}/cambiar-a-inscrito', [OfertasAcademicaController::class, 'cambiarAInscrito'])
        ->name('ofertas.inscripciones.cambiar-a-inscrito');
    Route::post('/ofertas/{ofertaId}/crear-cuentas-moodle', [OfertasAcademicaController::class, 'crearCuentasMoodle'])
        ->name('ofertas.crear-cuentas-moodle');

    Route::get('/ofertas/{ofertaId}/planes-pago/configurados', [OfertasAcademicaController::class, 'listarPlanesConfigurados'])
        ->name('ofertas.planes-pago.configurados');

    // Cronograma General
    Route::get('/cronograma', [CronogramaController::class, 'index'])->name('cronograma.index');
    Route::get('/cronograma/sucursales/{sedeId}', [CronogramaController::class, 'listarSucursales'])->name('cronograma.sucursales');
    Route::get('/cronograma/sucursal/{sucursalId}/ofertas', [CronogramaController::class, 'listarOfertasConHorarios'])->name('cronograma.ofertas-con-horarios');
    Route::get('/cronograma/ofertas/{sucursalId}', [CronogramaController::class, 'listarOfertas'])->name('cronograma.ofertas');
    Route::get('/cronograma/horarios', [CronogramaController::class, 'listarHorarios'])->name('cronograma.horarios');
});

// Ofertas Académicas Globales
Route::prefix('admin/ofertas-academicas')->middleware(['auth', 'isAdmin'])->group(function () {
    Route::get('/', [OfertasAcademicaController::class, 'indexGlobal'])->name('admin.ofertas.index');
    Route::get('/listar-global', [OfertasAcademicaController::class, 'listarGlobal'])->name('admin.ofertas.listarGlobal');
    Route::get('/{id}/configuraciones-precio', [OfertasAcademicaController::class, 'listarConfiguracionesPrecio'])->name('admin.ofertas.configuracionesPrecio');
    Route::get('/{id}/plan/{planId}/detalle', [OfertasAcademicaController::class, 'obtenerDetallePlan'])->name('admin.ofertas.detallePlan');
    Route::post('/{id}/cuotas/actualizar', [OfertasAcademicaController::class, 'actualizarCuotas'])->name('admin.ofertas.actualizarCuotas');
    Route::get('/{id}/inscripciones', [OfertasAcademicaController::class, 'listarInscripciones'])->name('admin.ofertas.inscripciones');
    Route::put('/{id}/cambiar-fase', [OfertasAcademicaController::class, 'cambiarFase'])->name('admin.ofertas.cambiarFase');
    Route::post('/{id}/inscripciones', [OfertasAcademicaController::class, 'registrarInscripcion'])->name('admin.ofertas.registrarInscripcion');
    Route::get('/inscripciones/{inscripcionId}/cuotas', [OfertasAcademicaController::class, 'getCuotasInscripcion'])->name('admin.ofertas.inscripcion.cuotas');
    Route::post('/inscripciones/comprobante', [OfertasAcademicaController::class, 'subirComprobanteInscripcion'])->name('admin.ofertas.inscripcion.comprobante');
});


// Buscar persona (reutilizar endpoint de estudiantes)
Route::prefix('admin/estudiantes')->middleware(['auth', 'isAdmin'])->group(function () {
    Route::post('/buscar-carnet', [EstudianteController::class, 'buscarCarnet'])->name('admin.estudiantes.buscarCarnet');
    Route::post('/registrar-persona', [EstudianteController::class, 'guardarPersona'])->name('admin.estudiantes.guardarPersona');
    Route::post('/registrar', [EstudianteController::class, 'registrarEstudiante'])->name('admin.estudiantes.registrar');
    Route::post('/validar-campos', [EstudianteController::class, 'validarCampos'])->name('admin.estudiantes.validarCampos');
});

// Programas (quick add)
Route::post('/admin/programas', [ProgramaController::class, 'guardar'])->name('admin.programas.guardar');

// Grados Académicos
Route::prefix('admin/grados-academicos')->middleware(['auth', 'isAdmin'])->name('admin.grados-academicos.')->group(function () {
    Route::get('/', [GradoAcademicoController::class, 'index'])->name('index');
    Route::get('/listar', [GradoAcademicoController::class, 'listar'])->name('listar');
    Route::post('/verificar', [GradoAcademicoController::class, 'verificarNombre'])->name('verificar');
    Route::post('/', [GradoAcademicoController::class, 'guardar'])->name('guardar');
    Route::put('/{id}', [GradoAcademicoController::class, 'actualizar'])->name('actualizar');
    Route::delete('/{id}', [GradoAcademicoController::class, 'eliminar'])->name('eliminar');
});

// Profesiones
Route::prefix('admin/profesiones')->middleware(['auth', 'isAdmin'])->name('admin.profesiones.')->group(function () {
    Route::get('/', [ProfesionController::class, 'index'])->name('index');
    Route::get('/listar', [ProfesionController::class, 'listar'])->name('listar');
    Route::post('/verificar', [ProfesionController::class, 'verificarNombre'])->name('verificar');
    Route::post('/', [ProfesionController::class, 'guardar'])->name('guardar');
    Route::put('/{id}', [ProfesionController::class, 'actualizar'])->name('actualizar');
    Route::delete('/{id}', [ProfesionController::class, 'eliminar'])->name('eliminar');
});

// Universidades
Route::prefix('admin/universidades')->middleware(['auth', 'isAdmin'])->name('admin.universidades.')->group(function () {
    Route::get('/', [UniversidadeController::class, 'index'])->name('index');
    Route::get('/listar', [UniversidadeController::class, 'listar'])->name('listar');
    Route::post('/verificar', [UniversidadeController::class, 'verificarNombre'])->name('verificar');
    Route::post('/', [UniversidadeController::class, 'guardar'])->name('guardar');
    Route::put('/{id}', [UniversidadeController::class, 'actualizar'])->name('actualizar');
    Route::delete('/{id}', [UniversidadeController::class, 'eliminar'])->name('eliminar');
});

// Sedes
Route::prefix('admin/sedes')->middleware(['auth', 'isAdmin'])->name('admin.sedes.')->group(function () {
    Route::get('/', [SedeController::class, 'indexAdmin'])->name('index');
    Route::get('/listar', [SedeController::class, 'listar'])->name('listar');
    Route::post('/', [SedeController::class, 'guardar'])->name('guardar');
    Route::put('/{id}', [SedeController::class, 'actualizar'])->name('actualizar');
    Route::delete('/{id}', [SedeController::class, 'eliminar'])->name('eliminar');
    Route::post('/{id}/sucursales', [SedeController::class, 'agregarSucursal'])->name('agregarSucursal');
    Route::put('/{id}/sucursales/{sucursalId}', [SedeController::class, 'actualizarSucursal'])->name('actualizarSucursal');
    Route::delete('/{id}/sucursales/{sucursalId}', [SedeController::class, 'eliminarSucursal'])->name('eliminarSucursal');
});

// Fases
Route::prefix('admin/fases')->middleware(['auth', 'isAdmin'])->name('admin.fases.')->group(function () {
    Route::get('/', [FaseController::class, 'index'])->name('index');
    Route::get('/listar', [FaseController::class, 'listar'])->name('listar');
    Route::post('/verificar', [FaseController::class, 'verificarNombre'])->name('verificar');
    Route::post('/', [FaseController::class, 'guardar'])->name('guardar');
    Route::put('/{id}', [FaseController::class, 'actualizar'])->name('actualizar');
    Route::delete('/{id}', [FaseController::class, 'eliminar'])->name('eliminar');
});

// Modalidades
Route::prefix('admin/modalidades')->middleware(['auth', 'isAdmin'])->name('admin.modalidades.')->group(function () {
    Route::get('/', [ModalidadeController::class, 'index'])->name('index');
    Route::get('/listar', [ModalidadeController::class, 'listar'])->name('listar');
    Route::post('/verificar', [ModalidadeController::class, 'verificarNombre'])->name('verificar');
    Route::post('/', [ModalidadeController::class, 'guardar'])->name('guardar');
    Route::put('/{id}', [ModalidadeController::class, 'actualizar'])->name('actualizar');
    Route::delete('/{id}', [ModalidadeController::class, 'eliminar'])->name('eliminar');
});

// Personas
Route::prefix('admin/personas')->middleware(['auth', 'isAdmin'])->name('admin.personas.')->group(function () {
    Route::get('/', [PersonaController::class, 'index'])->name('index');
    Route::get('/{id}/ver', [PersonaController::class, 'ver'])->name('ver');
    Route::get('/listar', [PersonaController::class, 'listar'])->name('listar');
    Route::get('/listar-departamentos', [PersonaController::class, 'listarDepartamentos'])->name('listarDepartamentos');
    Route::get('/listar-ciudades', [PersonaController::class, 'listarCiudades'])->name('listarCiudades');
    Route::get('/listar-grados', [PersonaController::class, 'listarGrados'])->name('listarGrados');
    Route::get('/listar-profesiones', [PersonaController::class, 'listarProfesiones'])->name('listarProfesiones');
    Route::get('/listar-universidades', [PersonaController::class, 'listarUniversidades'])->name('listarUniversidades');
    Route::post('/verificar-carnet', [PersonaController::class, 'verificarCarnet'])->name('verificarCarnet');
    Route::post('/verificar-correo', [PersonaController::class, 'verificarCorreo'])->name('verificarCorreo');
    Route::post('/', [PersonaController::class, 'guardar'])->name('guardar');
    Route::put('/{id}', [PersonaController::class, 'actualizar'])->name('actualizar');
    Route::delete('/{id}', [PersonaController::class, 'eliminar'])->name('eliminar');
    Route::post('/{id}/estudios', [PersonaController::class, 'agregarEstudio'])->name('agregarEstudio');
    Route::put('/{id}/estudios/{estudioId}', [PersonaController::class, 'actualizarEstudio'])->name('actualizarEstudio');
    Route::delete('/{id}/estudios/{estudioId}', [PersonaController::class, 'eliminarEstudio'])->name('eliminarEstudio');
});

// Usuarios
Route::prefix('admin/users')->middleware(['auth', 'isAdmin'])->name('admin.users.')->group(function () {
    Route::get('/', [UserController::class, 'index'])->name('index');
    Route::get('/listar', [UserController::class, 'listar'])->name('listar');
    Route::post('/buscar-carnet', [UserController::class, 'buscarPorCarnet'])->name('buscarCarnet');
    Route::post('/', [UserController::class, 'guardar'])->name('guardar');
    Route::delete('/{id}', [UserController::class, 'eliminar'])->name('eliminar');
    Route::post('/{id}/reiniciar-password', [UserController::class, 'reiniciarPassword'])->name('reiniciarPassword');
    Route::post('/{id}/toggle-estado',     [UserController::class, 'toggleEstado'])->name('toggleEstado');
});

// Cargos
Route::prefix('admin/cargos')->middleware(['auth', 'isAdmin'])->name('admin.cargos.')->group(function () {
    Route::get('/', [CargoController::class, 'index'])->name('index');
    Route::get('/listar', [CargoController::class, 'listar'])->name('listar');
    Route::post('/verificar', [CargoController::class, 'verificarNombre'])->name('verificar');
    Route::post('/', [CargoController::class, 'guardar'])->name('guardar');
    Route::put('/{id}', [CargoController::class, 'actualizar'])->name('actualizar');
    Route::delete('/{id}', [CargoController::class, 'eliminar'])->name('eliminar');
});

// Estudiantes
Route::prefix('admin/estudiantes')->middleware(['auth', 'isAdmin'])->name('admin.estudiantes.')->group(function () {
    Route::get('/', [EstudianteController::class, 'index'])->name('index');
    Route::get('/buscar', [EstudianteController::class, 'buscarView'])->name('buscar');
    Route::get('/buscar/api', [EstudianteController::class, 'buscar'])->name('buscarApi');
    Route::get('/{id}/detalle', [EstudianteController::class, 'verDetalle'])->name('verDetalle');
    Route::get('/{id}/cuotas-json', [EstudianteController::class, 'cuotasJson'])->name('cuotasJson');
    Route::get('/listar', [EstudianteController::class, 'listar'])->name('listar');
    Route::post('/buscar-carnet', [EstudianteController::class, 'buscarCarnet'])->name('buscarCarnet');
    Route::get('/listar-departamentos', [EstudianteController::class, 'listarDepartamentos'])->name('listarDepartamentos');
    Route::get('/listar-ciudades', [EstudianteController::class, 'listarCiudades'])->name('listarCiudades');
    Route::post('/verificar-carnet-persona', [EstudianteController::class, 'verificarCarnetPersona'])->name('verificarCarnetPersona');
    Route::post('/verificar-correo-persona', [EstudianteController::class, 'verificarCorreoPersona'])->name('verificarCorreoPersona');
    Route::post('/guardar-persona', [EstudianteController::class, 'guardarPersona'])->name('guardarPersona');
    Route::post('/registrar', [EstudianteController::class, 'registrarEstudiante'])->name('registrar');
    Route::post('/cuota/{cuota}/pagar', [EstudianteController::class, 'registrarPago'])->name('registrarPago');
    Route::post('/{id}/pago-masivo', [EstudianteController::class, 'pagoMasivo'])->name('registrarPagoMasivo');
    Route::get('/recibo/{pagoId}/pdf', [EstudianteController::class, 'generarReciboPdf'])->name('generarReciboPdf');
    Route::post('/crear-cuentas-batch', [EstudianteController::class, 'crearCuentasBatch'])->name('crearCuentasBatch');
    Route::post('/{id}/crear-cuentas', [EstudianteController::class, 'crearCuentas'])->name('crearCuentas');
    Route::post('/{id}/reset-password-moodle', [EstudianteController::class, 'resetPasswordMoodle'])->name('resetPasswordMoodle');
    Route::get('/{id}', [EstudianteController::class, 'obtenerEstudiante'])->name('obtener');
    Route::post('/{id}', [EstudianteController::class, 'actualizar'])->name('actualizar');
    Route::delete('/{id}', [EstudianteController::class, 'eliminar'])->name('eliminar');
    Route::post('/{id}/documentos/subir', [EstudianteController::class, 'subirDocumento'])->name('documentos.subir');
    Route::post('/{id}/documentos/verificar', [EstudianteController::class, 'verificarDocumento'])->name('documentos.verificar');
    Route::get('/{id}/documentos/visualizar', [EstudianteController::class, 'visualizarDocumento'])->name('documentos.visualizar');
    Route::any('/{id}/estudios/{estudioId}/set-principal', [EstudianteController::class, 'setPrincipalEstudio'])->name('estudios.setPrincipal');
    Route::delete('/{id}/estudios/{estudioId}', [EstudianteController::class, 'eliminarEstudio'])->name('estudios.eliminar');
});

// Docentes
Route::prefix('admin/docentes')->middleware(['auth', 'isAdmin'])->name('admin.docentes.')->group(function () {
    Route::get('/', [DocenteController::class, 'index'])->name('index');
    Route::get('/{id}/detalle', [DocenteController::class, 'verDetalle'])->name('verDetalle');
    Route::get('/listar', [DocenteController::class, 'listar'])->name('listar');
    Route::post('/buscar-carnet', [DocenteController::class, 'buscarCarnet'])->name('buscarCarnet');
    Route::post('/guardar-persona', [DocenteController::class, 'guardarPersona'])->name('guardarPersona');
    Route::post('/registrar', [DocenteController::class, 'registrarDocente'])->name('registrar');
    Route::post('/{id}/crear-cuentas', [DocenteController::class, 'crearCuentas'])->name('crearCuentas');
    Route::post('/{id}/estudios', [DocenteController::class, 'guardarEstudio'])->name('estudios.guardar');
    Route::patch('/{id}/estudios/{estudioId}/principal', [DocenteController::class, 'setPrincipalEstudio'])->name('estudios.setPrincipal');
    Route::post('/{id}/documentos/subir', [DocenteController::class, 'subirDocumento'])->name('documentos.subir');
    Route::post('/{id}/documentos/verificar', [DocenteController::class, 'verificarDocumento'])->name('documentos.verificar');
    Route::get('/{id}/documentos/visualizar', [DocenteController::class, 'visualizarDocumento'])->name('documentos.visualizar');
    Route::post('/{id}/estudios/{estudioId}/documentos/subir', [DocenteController::class, 'subirDocumentoEstudio'])->name('estudios.documentos.subir');
    Route::post('/{id}/estudios/{estudioId}/documentos/verificar', [DocenteController::class, 'verificarDocumentoEstudio'])->name('estudios.documentos.verificar');
    Route::get('/{id}/estudios/{estudioId}/documentos/visualizar', [DocenteController::class, 'visualizarDocumentoEstudio'])->name('estudios.documentos.visualizar');
    Route::delete('/{id}/estudios/{estudioId}', [DocenteController::class, 'eliminarEstudio'])->name('estudios.eliminar');
    Route::get('/{id}', [DocenteController::class, 'obtenerDocente'])->name('obtener');
    Route::post('/{id}/reset-password-moodle', [DocenteController::class, 'resetPasswordMoodle'])->name('resetPasswordMoodle');
    Route::delete('/{id}', [DocenteController::class, 'eliminar'])->name('eliminar');
});

// Trabajadores
Route::prefix('admin/trabajadores')->middleware(['auth', 'isAdmin'])->name('admin.trabajadores.')->group(function () {
    Route::get('/', [TrabajadoreController::class, 'index'])->name('index');
    Route::get('/listar', [TrabajadoreController::class, 'listar'])->name('listar');
    Route::post('/buscar-carnet', [TrabajadoreController::class, 'buscarCarnet'])->name('buscarCarnet');
    Route::get('/listar-cargos', [TrabajadoreController::class, 'listarCargos'])->name('listarCargos');
    Route::get('/listar-sedes', [TrabajadoreController::class, 'listarSedes'])->name('listarSedes');
    Route::post('/listar-sucursales-por-sede', [TrabajadoreController::class, 'listarSucursalesPorSede'])->name('listarSucursalesPorSede');
    Route::get('/listar-departamentos', [TrabajadoreController::class, 'listarDepartamentos'])->name('listarDepartamentos');
    Route::get('/listar-ciudades', [TrabajadoreController::class, 'listarCiudades'])->name('listarCiudades');
    Route::post('/verificar-carnet-persona', [TrabajadoreController::class, 'verificarCarnetPersona'])->name('verificarCarnetPersona');
    Route::post('/verificar-correo-persona', [TrabajadoreController::class, 'verificarCorreoPersona'])->name('verificarCorreoPersona');
    Route::post('/guardar-persona', [TrabajadoreController::class, 'guardarPersona'])->name('guardarPersona');
    Route::post('/asignar', [TrabajadoreController::class, 'asignarTrabajador'])->name('asignar');
    Route::get('/{id}', [TrabajadoreController::class, 'obtenerTrabajador'])->name('obtener');
    Route::post('/actualizar-cargos', [TrabajadoreController::class, 'actualizarCargos'])->name('actualizarCargos');
    Route::delete('/{id}', [TrabajadoreController::class, 'eliminar'])->name('eliminar');
    Route::delete('/{trabajadorId}/cargos/{cargoId}', [TrabajadoreController::class, 'eliminarCargo'])->name('eliminarCargo');
});

Route::get('/admin/trabajadores-cargos/listar', [TrabajadoreController::class, 'listarCargosActivos'])->name('admin.trabajadores.listarCargosActivos')->middleware(['auth', 'isAdmin']);
Route::get('/admin/trabajadores-cargos/para-usuario', [TrabajadoreController::class, 'listarCargosParaUsuario'])
    ->name('admin.trabajadores.cargos.para-usuario')
    ->middleware('auth');

// Conceptos
Route::prefix('admin/conceptos')->middleware(['auth', 'isAdmin'])->name('admin.conceptos.')->group(function () {
    Route::get('/', [ConceptoController::class, 'index'])->name('index');
    Route::get('/listar', [ConceptoController::class, 'listar'])->name('listar');
    Route::post('/verificar', [ConceptoController::class, 'verificarNombre'])->name('verificar');
    Route::post('/', [ConceptoController::class, 'guardar'])->name('guardar');
    Route::put('/{id}', [ConceptoController::class, 'actualizar'])->name('actualizar');
    Route::delete('/{id}', [ConceptoController::class, 'eliminar'])->name('eliminar');
});

// Contabilidad Dashboard
Route::prefix('admin/contabilidad')->middleware(['auth', 'isAdmin'])->name('admin.contabilidad.')->group(function () {
    Route::get('/', [ContabilidadController::class, 'dashboard'])->name('dashboard');
});

// Planes de Pago
Route::prefix('admin/planes-pagos')->middleware(['auth', 'isAdmin'])->name('admin.planes-pagos.')->group(function () {
    Route::get('/', [PlanesPagoController::class, 'index'])->name('index');
    Route::get('/listar', [PlanesPagoController::class, 'listar'])->name('listar');
    Route::post('/verificar', [PlanesPagoController::class, 'verificarNombre'])->name('verificar');
    Route::post('/', [PlanesPagoController::class, 'guardar'])->name('guardar');
    Route::put('/{id}', [PlanesPagoController::class, 'actualizar'])->name('actualizar');
    Route::patch('/{id}/estado', [PlanesPagoController::class, 'cambiarEstado'])->name('cambiar-estado');
    Route::delete('/{id}', [PlanesPagoController::class, 'eliminar'])->name('eliminar');
});

// Contabilidad
Route::prefix('admin/contabilidad')->middleware(['auth', 'isAdmin'])->name('admin.contabilidad.')->group(function () {
    Route::get('/deudas-retrasadas', [ContabilidadController::class, 'deudasRetrasadas'])->name('deudas-retrasadas');
    Route::get('/cuotas-proximas', [ContabilidadController::class, 'cuotasProximas'])->name('cuotas-proximas');
    Route::get('/recibos', [ContabilidadController::class, 'recibos'])->name('recibos');
    Route::post('/recibos/{pagoId}/subir-factura', [ContabilidadController::class, 'subirFactura'])->name('recibos.subir-factura');
});

// Perfil del usuario autenticado
Route::prefix('admin/profile')->name('admin.profile.')->middleware('auth')->group(function () {
    Route::get('/',                              [ProfileController::class, 'show'])->name('index');
    Route::post('/change-password',              [ProfileController::class, 'changePassword'])->name('change-password');
    Route::post('/upload-foto',                  [ProfileController::class, 'uploadFoto'])->name('upload-foto');
    Route::get('/marketing/estadisticas',        [ProfileController::class, 'getEstadisticasMarketing'])->name('marketing.estadisticas');
    Route::get('/marketing/inscripciones',       [ProfileController::class, 'getInscripcionesFiltradas'])->name('marketing.inscripciones');
    Route::get('/marketing/documentos/{estudianteId}', [ProfileController::class, 'getDocumentosEstudiante'])->name('marketing.documentos');
    Route::get('/marketing/ofertas-activas',     [ProfileController::class, 'getOfertasActivas'])->name('marketing.ofertas-activas');
    Route::post('/marketing/generar-enlace',     [ProfileController::class, 'generarEnlacePreinscripcion'])->name('marketing.generar-enlace');
    Route::get('/marketing/oferta/{id}/planes',  [ProfileController::class, 'getPlanesPagoParaOferta'])->name('marketing.oferta.planes');
    Route::get('/marketing/oferta/{ofertaId}/plan/{planId}/detalle', [ProfileController::class, 'getDetallePlanParaMarketing'])->name('marketing.oferta.plan.detalle');
    Route::post('/marketing/inscripcion/{id}/cambiar-a-inscrito', [ProfileController::class, 'cambiarPreInscritoAInscrito'])->name('marketing.inscripcion.cambiar-inscrito');
    // Comprobantes de pago
    Route::get('/marketing/inscritos-comprobante',       [ProfileController::class, 'getInscritosParaComprobante'])->name('marketing.inscritos-comprobante');
    Route::get('/marketing/inscripcion/{id}/cuotas',     [ProfileController::class, 'getCuotasPorInscripcion'])->name('marketing.inscripcion.cuotas');
    Route::post('/marketing/comprobante',                [ProfileController::class, 'subirComprobante'])->name('marketing.comprobante.subir');
});

// Comprobantes de pago (admin)
Route::prefix('admin/comprobantes')->middleware(['auth', 'isAdmin'])->name('admin.comprobantes.')->group(function () {
    Route::get('/',                    [ComprobantesPagoController::class, 'index'])->name('index');
    Route::get('/{id}/cuotas',         [ComprobantesPagoController::class, 'getCuotas'])->name('cuotas');
    Route::post('/{id}/verificar',     [ComprobantesPagoController::class, 'verificar'])->name('verificar');
    Route::patch('/{id}/rechazar',     [ComprobantesPagoController::class, 'rechazar'])->name('rechazar');
    Route::patch('/{id}/pendiente',    [ComprobantesPagoController::class, 'pendiente'])->name('pendiente');
});

// Bancos (admin)
Route::prefix('admin/bancos')->middleware(['auth', 'isAdmin'])->name('admin.bancos.')->group(function () {
    Route::get('/', [App\Http\Controllers\Admin\BancoController::class, 'index'])->name('index');
    Route::get('/listar', [App\Http\Controllers\Admin\BancoController::class, 'listar'])->name('listar');
    Route::post('/', [App\Http\Controllers\Admin\BancoController::class, 'store'])->name('store');
    Route::put('/{banco}', [App\Http\Controllers\Admin\BancoController::class, 'update'])->name('update');
    Route::delete('/{banco}', [App\Http\Controllers\Admin\BancoController::class, 'destroy'])->name('destroy');
    Route::patch('/{banco}/toggle', [App\Http\Controllers\Admin\BancoController::class, 'toggleEstado'])->name('toggle');
});

// Cuentas bancarias (admin)
Route::prefix('admin/cuentas-bancarias')->middleware(['auth', 'isAdmin'])->name('admin.cuentas-bancarias.')->group(function () {
    Route::get('/', [App\Http\Controllers\Admin\CuentaBancariaController::class, 'index'])->name('index');
    Route::post('/', [App\Http\Controllers\Admin\CuentaBancariaController::class, 'store'])->name('store');
    Route::put('/{cuentaBancaria}', [App\Http\Controllers\Admin\CuentaBancariaController::class, 'update'])->name('update');
    Route::delete('/{cuentaBancaria}', [App\Http\Controllers\Admin\CuentaBancariaController::class, 'destroy'])->name('destroy');
    Route::patch('/{cuentaBancaria}/toggle', [App\Http\Controllers\Admin\CuentaBancariaController::class, 'toggleEstado'])->name('toggle');
    Route::patch('/{cuentaBancaria}/principal', [App\Http\Controllers\Admin\CuentaBancariaController::class, 'setPrincipal'])->name('principal');
    Route::get('/{cuentaBancaria}/detalle', [App\Http\Controllers\Admin\CuentaBancariaController::class, 'detalle'])->name('detalle');
    Route::post('/{cuentaBancaria}/qr', [App\Http\Controllers\Admin\CuentaBancariaController::class, 'actualizarQr'])->name('qr');
});

// Cajas (admin)
Route::prefix('admin/cajas')->middleware(['auth', 'isAdmin'])->name('admin.cajas.')->group(function () {
    Route::get('/', [App\Http\Controllers\Admin\CajaController::class, 'index'])->name('index');
    Route::get('/listar', [App\Http\Controllers\Admin\CajaController::class, 'listar'])->name('listar');
    Route::post('/abrir', [App\Http\Controllers\Admin\CajaController::class, 'abrir'])->name('abrir');
    Route::post('/{caja}/cerrar', [App\Http\Controllers\Admin\CajaController::class, 'cerrar'])->name('cerrar');
    Route::get('/{caja}/movimientos', [App\Http\Controllers\Admin\CajaController::class, 'movimientos'])->name('movimientos');
});

Route::fallback(function () {
    $mensaje = 'Página no encontrada';
    
    if (auth()->check()) {
        $role = auth()->user()->role;
        
        if ($role === 'estudiante') {
            return redirect()->route('estudiante.dashboard')
                ->with('route_not_found', $mensaje);
        }
        
        return redirect()->route('admin.dashboard')
            ->with('route_not_found', $mensaje);
    }
    
    return redirect()->route('login');
});
