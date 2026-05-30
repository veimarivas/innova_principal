<?php

namespace App\Http\Controllers;

use App\Models\Modulo;
use App\Services\QuestionBankService;
use Illuminate\Http\Request;

class QuizPreguntasController extends Controller
{
    public function __construct(private QuestionBankService $questionBank) {}

    private function getModulo(int $moduloId): Modulo
    {
        $modulo = Modulo::findOrFail($moduloId);
        if (!$modulo->moodle_course_id) {
            abort(400, 'El módulo no tiene curso Moodle asignado.');
        }
        return $modulo;
    }

    /**
     * GET /admin/posgrads/modulos/{modulo}/actividades/quiz/{quizId}/preguntas
     */
    public function index(int $moduloId, int $quizId)
    {
        $modulo = $this->getModulo($moduloId);
        $questions = $this->questionBank->getQuizQuestions($quizId);
        return response()->json(['success' => true, 'questions' => $questions]);
    }

    /**
     * POST /admin/posgrads/modulos/{modulo}/actividades/quiz/{quizId}/preguntas/multichoice
     */
    public function storeMultichoice(Request $request, int $moduloId, int $quizId)
    {
        $modulo = $this->getModulo($moduloId);

        $data = $request->validate([
            'name'         => 'required|string|max:255',
            'questiontext' => 'required|string',
            'defaultmark'  => 'nullable|numeric|min:0',
            'single'       => 'nullable|in:true,false',
            'options'      => 'required|array|min:2',
            'options.*.text'     => 'required|string',
            'options.*.fraction' => 'required|numeric|between:0,1',
            'options.*.feedback' => 'nullable|string',
        ]);

        try {
            $questionId = $this->questionBank->createMultipleChoice(
                $quizId,
                $modulo->moodle_course_id,
                $data
            );

            return response()->json([
                'success'     => true,
                'question_id' => $questionId,
                'message'     => 'Pregunta creada correctamente.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al crear la pregunta: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * POST /admin/posgrads/modulos/{modulo}/actividades/quiz/{quizId}/preguntas/truefalse
     */
    public function storeTrueFalse(Request $request, int $moduloId, int $quizId)
    {
        $modulo = $this->getModulo($moduloId);

        $data = $request->validate([
            'name'          => 'required|string|max:255',
            'questiontext'  => 'required|string',
            'defaultmark'   => 'nullable|numeric|min:0',
            'correctanswer' => 'required|in:true,false',
            'truefeedback'  => 'nullable|string',
            'falsefeedback' => 'nullable|string',
        ]);

        try {
            $questionId = $this->questionBank->createTrueFalse(
                $quizId,
                $modulo->moodle_course_id,
                $data
            );

            return response()->json([
                'success'     => true,
                'question_id' => $questionId,
                'message'     => 'Pregunta creada correctamente.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al crear la pregunta: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * POST /admin/posgrads/modulos/{modulo}/actividades/quiz/{quizId}/preguntas/matching
     */
    public function storeMatching(Request $request, int $moduloId, int $quizId)
    {
        $modulo = $this->getModulo($moduloId);

        $data = $request->validate([
            'name'         => 'required|string|max:255',
            'questiontext' => 'required|string',
            'defaultmark'  => 'nullable|numeric|min:0',
            'pairs'        => 'required|array|min:2',
            'pairs.*.question' => 'required|string',
            'pairs.*.answer'   => 'required|string',
        ]);

        try {
            $questionId = $this->questionBank->createMatching(
                $quizId,
                $modulo->moodle_course_id,
                $data
            );

            return response()->json([
                'success'     => true,
                'question_id' => $questionId,
                'message'     => 'Pregunta creada correctamente.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al crear la pregunta: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * GET /admin/posgrads/modulos/{modulo}/actividades/quiz/{quizId}/preguntas/{questionId}
     */
    public function show(int $moduloId, int $quizId, int $questionId)
    {
        $this->getModulo($moduloId);
        $details = $this->questionBank->getQuestionDetails($questionId);
        if (empty($details)) {
            return response()->json(['success' => false, 'message' => 'Pregunta no encontrada.'], 404);
        }
        return response()->json(['success' => true, 'question' => $details]);
    }

    /**
     * PUT /admin/posgrads/modulos/{modulo}/actividades/quiz/{quizId}/preguntas/{questionId}/multichoice
     */
    public function updateMultichoice(Request $request, int $moduloId, int $quizId, int $questionId)
    {
        $this->getModulo($moduloId);
        $data = $request->validate([
            'name'                   => 'required|string|max:255',
            'questiontext'           => 'required|string',
            'defaultmark'            => 'nullable|numeric|min:0',
            'single'                 => 'nullable|in:true,false',
            'options'                => 'required|array|min:2',
            'options.*.text'         => 'required|string',
            'options.*.fraction'     => 'required|numeric|between:0,1',
            'options.*.feedback'     => 'nullable|string',
        ]);
        try {
            $this->questionBank->updateMultipleChoice($questionId, $data);
            return response()->json(['success' => true, 'message' => 'Pregunta actualizada correctamente.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error al actualizar: ' . $e->getMessage()], 500);
        }
    }

    /**
     * PUT /admin/posgrads/modulos/{modulo}/actividades/quiz/{quizId}/preguntas/{questionId}/truefalse
     */
    public function updateTrueFalse(Request $request, int $moduloId, int $quizId, int $questionId)
    {
        $this->getModulo($moduloId);
        $data = $request->validate([
            'name'          => 'required|string|max:255',
            'questiontext'  => 'required|string',
            'defaultmark'   => 'nullable|numeric|min:0',
            'correctanswer' => 'required|in:true,false',
        ]);
        try {
            $this->questionBank->updateTrueFalse($questionId, $data);
            return response()->json(['success' => true, 'message' => 'Pregunta actualizada correctamente.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error al actualizar: ' . $e->getMessage()], 500);
        }
    }

    /**
     * PUT /admin/posgrads/modulos/{modulo}/actividades/quiz/{quizId}/preguntas/{questionId}/matching
     */
    public function updateMatching(Request $request, int $moduloId, int $quizId, int $questionId)
    {
        $this->getModulo($moduloId);
        $data = $request->validate([
            'name'               => 'required|string|max:255',
            'questiontext'       => 'required|string',
            'defaultmark'        => 'nullable|numeric|min:0',
            'pairs'              => 'required|array|min:2',
            'pairs.*.question'   => 'required|string',
            'pairs.*.answer'     => 'required|string',
        ]);
        try {
            $this->questionBank->updateMatching($questionId, $data);
            return response()->json(['success' => true, 'message' => 'Pregunta actualizada correctamente.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error al actualizar: ' . $e->getMessage()], 500);
        }
    }

    /**
     * DELETE /admin/posgrads/modulos/{modulo}/actividades/quiz/{quizId}/preguntas/{slotId}
     */
    public function destroy(int $moduloId, int $quizId, int $slotId)
    {
        $this->getModulo($moduloId);

        $ok = $this->questionBank->removeFromQuiz($quizId, $slotId);

        if ($ok) {
            return response()->json(['success' => true, 'message' => 'Pregunta eliminada del cuestionario.']);
        }

        return response()->json(['success' => false, 'message' => 'No se encontró la pregunta en el cuestionario.'], 404);
    }
}
