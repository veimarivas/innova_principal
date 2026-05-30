<?php

namespace App\Http\Controllers;

use App\Models\Programa;
use App\Services\MoodleService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProgramaController extends Controller
{
    public function __construct(private MoodleService $moodle) {}

    public function guardar(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:200|unique:programas,nombre',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $nombre = strtoupper($request->nombre);

        // Crea la categoría en Moodle y obtiene su ID
        $moodleCategoryId = $this->moodle->createCategory(
            $nombre,
            config('moodle.category_parent')
        );

        $programa = Programa::create([
            'nombre'             => $nombre,
            'moodle_category_id' => $moodleCategoryId,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Programa registrado correctamente.' . ($moodleCategoryId ? ' Categoría creada en Moodle.' : ' (sin conexión a Moodle)'),
            'data'    => $programa,
        ]);
    }

    public function actualizar(Request $request, Programa $programa)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => "required|string|max:200|unique:programas,nombre,{$programa->id}",
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $nombre = strtoupper($request->nombre);

        // Sincroniza el nombre en Moodle si ya tiene categoría
        if ($programa->moodle_category_id) {
            $this->moodle->updateCategory($programa->moodle_category_id, $nombre);
        } else {
            // Si no tiene categoría aún, la crea ahora
            $moodleCategoryId = $this->moodle->createCategory($nombre, config('moodle.category_parent'));
            $programa->moodle_category_id = $moodleCategoryId;
        }

        $programa->nombre = $nombre;
        $programa->save();

        return response()->json([
            'success' => true,
            'message' => 'Programa actualizado correctamente.',
            'data'    => $programa,
        ]);
    }

    public function eliminar(Programa $programa)
    {
        if ($programa->moodle_category_id) {
            $this->moodle->deleteCategory($programa->moodle_category_id);
        }

        $programa->delete();

        return response()->json(['success' => true, 'message' => 'Programa eliminado correctamente.']);
    }
}
