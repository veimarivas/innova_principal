<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class QuestionBankService
{
    /**
     * Obtiene o crea la categoría de preguntas para el contexto del módulo quiz.
     * Usa el contexto del módulo (quiz) si se proporciona quizId, o el del curso como fallback.
     */
    public function ensureCourseCategory(int $courseId, ?int $quizId = null): int
    {
        $db = DB::connection('moodle');

        $contextId = null;

        // Preferir contexto del módulo quiz si tenemos quizId
        $quizCtx = null;
        if ($quizId) {
            $modId = $db->table('modules')->where('name', 'quiz')->value('id');
            $cm = $db->table('course_modules')
                ->where('module', $modId)
                ->where('instance', $quizId)
                ->first();
            if ($cm) {
                $quizCtx = $db->table('context')
                    ->where('contextlevel', 70)
                    ->where('instanceid', $cm->id)
                    ->first();
            }
        }

        if ($quizCtx) {
            $contextId = (int)$quizCtx->id;
        } else {
            $courseCtx = $db->table('context')
                ->where('contextlevel', 50)
                ->where('instanceid', $courseId)
                ->first();
            if (!$courseCtx) {
                throw new \RuntimeException("Course context not found for course $courseId");
            }
            $contextId = (int)$courseCtx->id;
        }

        $catName = $quizCtx ? 'Default for quiz ' . $quizId : 'Default for course ' . $courseId;

        $cat = $db->table('question_categories')
            ->where('contextid', $contextId)
            ->where('name', $catName)
            ->first();

        if ($cat) {
            return (int)$cat->id;
        }

        // Buscar categoría "top" existente en este contexto
        $topCat = $db->table('question_categories')
            ->where('contextid', $contextId)
            ->where('name', 'top')
            ->first();

        $maxSort = $db->table('question_categories')
            ->where('contextid', $contextId)
            ->max('sortorder') ?? 0;

        $stamp = md5(time() . $courseId . ($quizId ?? '') . rand());

        $catId = $db->table('question_categories')->insertGetId([
            'name'        => $catName,
            'contextid'   => $contextId,
            'info'        => '',
            'infoformat'  => 0,
            'stamp'       => $stamp,
            'parent'      => $topCat ? (int)$topCat->id : 0,
            'sortorder'   => $maxSort + 1,
            'idnumber'    => '',
        ]);

        return (int)$catId;
    }

    /**
     * Crea una pregunta de opción múltiple y la agrega a un quiz.
     */
    public function createMultipleChoice(int $quizId, int $courseId, array $data): ?int
    {
        $db = DB::connection('moodle');
        $now = time();
        $catId = $this->ensureCourseCategory($courseId, $quizId);

        $questionId = $this->insertBaseQuestion($db, [
            'name'           => $data['name'],
            'questiontext'   => $data['questiontext'],
            'qtype'          => 'multichoice',
            'defaultmark'    => $data['defaultmark'] ?? 1,
            'penalty'        => 0.3333333,
            'timecreated'    => $now,
            'timemodified'   => $now,
            'createdby'      => 0,
            'modifiedby'     => 0,
        ], $catId);

        // qtype_multichoice_options
        $single = ($data['single'] ?? 'true') === 'true' ? 1 : 0;
        $db->table('qtype_multichoice_options')->insert([
            'questionid'          => $questionId,
            'layout'              => 0,
            'single'              => $single,
            'shuffleanswers'      => 1,
            'correctfeedback'     => '',
            'correctfeedbackformat' => 1,
            'partiallycorrectfeedback' => '',
            'partiallycorrectfeedbackformat' => 1,
            'incorrectfeedback'   => '',
            'incorrectfeedbackformat' => 1,
            'answernumbering'     => 'abc',
            'shownumcorrect'      => 0,
            'showstandardinstruction' => 0,
        ]);

        // question_answers (opciones)
        foreach ($data['options'] as $opt) {
            $answerId = $db->table('question_answers')->insertGetId([
                'question'       => $questionId,
                'answer'         => $opt['text'],
                'answerformat'   => 1,
                'fraction'       => (float)($opt['fraction'] ?? 0),
                'feedback'       => $opt['feedback'] ?? '',
                'feedbackformat' => 1,
            ]);
        }

        $this->linkToQuiz($db, $quizId, $courseId, $questionId, $data['defaultmark'] ?? 1);

        return $questionId;
    }

    /**
     * Crea una pregunta verdadero/falso y la agrega a un quiz.
     */
    public function createTrueFalse(int $quizId, int $courseId, array $data): ?int
    {
        $db = DB::connection('moodle');
        $now = time();
        $catId = $this->ensureCourseCategory($courseId, $quizId);

        $questionId = $this->insertBaseQuestion($db, [
            'name'           => $data['name'],
            'questiontext'   => $data['questiontext'],
            'qtype'          => 'truefalse',
            'defaultmark'    => $data['defaultmark'] ?? 1,
            'penalty'        => 1,
            'timecreated'    => $now,
            'timemodified'   => $now,
            'createdby'      => 0,
            'modifiedby'     => 0,
        ], $catId);

        // Dos respuestas: true (fraction=1 si es correcta, sino 0) y false
        $correctFraction = ($data['correctanswer'] ?? 'true') === 'true' ? 1 : 0;
        $wrongFraction   = $correctFraction === 1 ? 0 : 1;

        $trueId = $db->table('question_answers')->insertGetId([
            'question'       => $questionId,
            'answer'         => 'Verdadero',
            'answerformat'   => 1,
            'fraction'       => (float)$correctFraction,
            'feedback'       => $data['truefeedback'] ?? '',
            'feedbackformat' => 1,
        ]);

        $falseId = $db->table('question_answers')->insertGetId([
            'question'       => $questionId,
            'answer'         => 'Falso',
            'answerformat'   => 1,
            'fraction'       => (float)$wrongFraction,
            'feedback'       => $data['falsefeedback'] ?? '',
            'feedbackformat' => 1,
        ]);

        // question_truefalse
        $db->table('question_truefalse')->insert([
            'question'       => $questionId,
            'trueanswer'     => $trueId,
            'falseanswer'    => $falseId,
            'showstandardinstruction' => 0,
        ]);

        $this->linkToQuiz($db, $quizId, $courseId, $questionId, $data['defaultmark'] ?? 1);

        return $questionId;
    }

    /**
     * Crea una pregunta de coincidencia (matching) y la agrega a un quiz.
     */
    public function createMatching(int $quizId, int $courseId, array $data): ?int
    {
        $db = DB::connection('moodle');
        $now = time();
        $catId = $this->ensureCourseCategory($courseId, $quizId);

        $questionId = $this->insertBaseQuestion($db, [
            'name'           => $data['name'],
            'questiontext'   => $data['questiontext'],
            'qtype'          => 'match',
            'defaultmark'    => $data['defaultmark'] ?? 1,
            'penalty'        => 0.3333333,
            'timecreated'    => $now,
            'timemodified'   => $now,
            'createdby'      => 0,
            'modifiedby'     => 0,
        ], $catId);

        // qtype_match_options
        $db->table('qtype_match_options')->insert([
            'questionid'                    => $questionId,
            'shuffleanswers'                => 1,
            'correctfeedback'               => '',
            'correctfeedbackformat'         => 1,
            'partiallycorrectfeedback'      => '',
            'partiallycorrectfeedbackformat' => 1,
            'incorrectfeedback'             => '',
            'incorrectfeedbackformat'       => 1,
            'shownumcorrect'                => 0,
        ]);

        // qtype_match_subquestions (pares pregunta-respuesta)
        foreach ($data['pairs'] as $pair) {
            $db->table('qtype_match_subquestions')->insert([
                'questionid'         => $questionId,
                'questiontext'       => $pair['question'],
                'questiontextformat' => 1,
                'answertext'         => $pair['answer'],
            ]);
        }

        $this->linkToQuiz($db, $quizId, $courseId, $questionId, $data['defaultmark'] ?? 1);

        return $questionId;
    }

    /**
     * Obtiene todas las preguntas de un quiz.
     */
    public function getQuizQuestions(int $quizId): array
    {
        $db = DB::connection('moodle');
        $prefix = $db->getTablePrefix();

        $sql = "SELECT qs.id as slot_id, qs.slot, qs.maxmark, qs.page,
                       q.id as question_id, q.name, q.qtype, q.questiontext, q.defaultmark
                FROM {$prefix}quiz_slots qs
                JOIN {$prefix}question_references qr ON qr.itemid = qs.id AND qr.component = 'mod_quiz' AND qr.questionarea = 'slot'
                JOIN {$prefix}question_bank_entries qbe ON qbe.id = qr.questionbankentryid
                JOIN {$prefix}question_versions qv ON qv.questionbankentryid = qbe.id AND qv.status = 'ready'
                JOIN {$prefix}question q ON q.id = qv.questionid
                WHERE qs.quizid = ?
                ORDER BY qs.slot ASC";

        return $db->select($sql, [$quizId]);
    }

    /**
     * Elimina una pregunta de un quiz (slot).
     */
    public function removeFromQuiz(int $quizId, int $slotId): bool
    {
        $db = DB::connection('moodle');
        $slot = $db->table('quiz_slots')->where('id', $slotId)->where('quizid', $quizId)->first();
        if (!$slot) return false;

        // Obtener questionbankentryid desde question_references
        $ref = $db->table('question_references')
            ->where('component', 'mod_quiz')
            ->where('questionarea', 'slot')
            ->where('itemid', $slotId)
            ->first();

        $db->table('question_references')
            ->where('component', 'mod_quiz')
            ->where('questionarea', 'slot')
            ->where('itemid', $slotId)
            ->delete();

        $db->table('quiz_slots')->where('id', $slotId)->delete();

        // Reordenar slots
        $remaining = $db->table('quiz_slots')
            ->where('quizid', $quizId)
            ->orderBy('slot')
            ->get();

        foreach ($remaining as $i => $s) {
            $db->table('quiz_slots')
                ->where('id', $s->id)
                ->update(['slot' => $i + 1]);
        }

        return true;
    }

    // ── Métodos privados ──

    private function insertBaseQuestion($db, array $data, ?int $catId = null): int
    {
        $questionId = $db->table('question')->insertGetId([
            'parent'               => 0,
            'name'                 => $data['name'],
            'questiontext'         => $data['questiontext'],
            'questiontextformat'   => 1,
            'generalfeedback'      => '',
            'generalfeedbackformat' => 1,
            'defaultmark'          => $data['defaultmark'],
            'penalty'              => $data['penalty'],
            'qtype'                => $data['qtype'],
            'length'               => 1,
            'stamp'                => md5(time() . $data['name'] . rand()),
            'timecreated'          => $data['timecreated'],
            'timemodified'         => $data['timemodified'],
            'createdby'            => $data['createdby'],
            'modifiedby'           => $data['modifiedby'],
        ]);

        $catId = $catId ?? $data['catid'] ?? $this->getDefaultCategoryId($db);
        if (!$catId) {
            throw new \RuntimeException("No question category available");
        }

        // question_bank_entries (idnumber debe ser único por categoría)
        $uniqueId = preg_replace('/[^a-zA-Z0-9]/', '', $data['name'] . '_' . $data['timecreated']);
        $bankId = $db->table('question_bank_entries')->insertGetId([
            'questioncategoryid' => $catId,
            'idnumber'           => substr($uniqueId, 0, 50),
            'ownerid'            => 0,
            'nextversion'        => 2,
        ]);

        // question_versions
        $db->table('question_versions')->insert([
            'questionbankentryid' => $bankId,
            'version'             => 1,
            'questionid'          => $questionId,
            'status'              => 'ready',
        ]);

        return $questionId;
    }

    private function getDefaultCategoryId($db): int
    {
        $cat = $db->table('question_categories')->orderBy('id')->first();
        return $cat ? (int)$cat->id : 0;
    }

    private function linkToQuiz($db, int $quizId, int $courseId, int $questionId, float $maxmark): void
    {
        // Obtener el max slot actual
        $maxSlot = $db->table('quiz_slots')->where('quizid', $quizId)->max('slot') ?? 0;
        $newSlot = $maxSlot + 1;

        // Obtener question_bank_entries id
        $entry = $db->table('question_versions')
            ->where('questionid', $questionId)
            ->first();

        if (!$entry) {
            throw new \RuntimeException("Question version not found for question $questionId");
        }

        $slotId = $db->table('quiz_slots')->insertGetId([
            'quizid'          => $quizId,
            'slot'            => $newSlot,
            'page'            => 0,
            'displaynumber'   => (string)$newSlot,
            'requireprevious' => 0,
            'maxmark'         => $maxmark,
            'quizgradeitemid' => null,
        ]);

        // Buscar el context del módulo (quiz) para usingcontextid
        $modId = $db->table('modules')->where('name', 'quiz')->value('id');
        $cm = $db->table('course_modules')
            ->where('module', $modId)
            ->where('instance', $quizId)
            ->first();

        $usingContextId = 0;
        if ($cm) {
            $mc = $db->table('context')
                ->where('contextlevel', 70)
                ->where('instanceid', $cm->id)
                ->first();
            if ($mc) $usingContextId = (int)$mc->id;
        }

        if (!$usingContextId) {
            // Fallback al contexto del curso
            $cc = $db->table('context')
                ->where('contextlevel', 50)
                ->where('instanceid', $courseId)
                ->first();
            $usingContextId = $cc ? (int)$cc->id : 0;
        }

        $db->table('question_references')->insert([
            'usingcontextid'      => $usingContextId,
            'component'           => 'mod_quiz',
            'questionarea'        => 'slot',
            'itemid'              => $slotId,
            'questionbankentryid' => (int)$entry->questionbankentryid,
            'version'             => 1,
        ]);
    }
}
