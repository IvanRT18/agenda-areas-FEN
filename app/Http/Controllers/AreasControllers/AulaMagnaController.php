<?php

namespace App\Http\Controllers\AreasControllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use App\Http\Controllers\Controller;
use App\Models\AreasModels\AulaMagnaConfiguracion;
use App\Models\AreasModels\AulaMagnaModel;
use App\Models\DiaDeshabilitado;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class AulaMagnaController extends Controller
{
    public function index()
    {
        return view('home');
    }

    public function store(Request $request)
    {
        // Validación de datos de entrada
        $validator = Validator::make($request->all(), [
            'fecha_reservada_magna' => 'required|date|after_or_equal:' . now()->format('Y-m-d'),
            'hora_reserva_inicio_magna' => 'required|date_format:H:i',
            'hora_reserva_final_magna' => 'required|date_format:H:i|after:hora_reserva_inicio',
            'numero_microfonos_magna' => 'required|integer|min:0|max:2',
        ]);

        // Agregar reglas de validación personalizadas usando after
        $validator->after(function ($validator) use ($request) {
            $fechaReservada = $request->fecha_reservada_magna;

            // Verificar si la fecha seleccionada está deshabilitada
            if (DiaDeshabilitado::where('dias_deshabilitado', $fechaReservada)->exists()) {
                $validator->errors()->add('fecha_reservada', 'La fecha seleccionada no está disponible para reservaciones.');
            }

            // Verificar si la hora de inicio/final no están reservadas y no se traslapen reservaciones 
            $horaInicio = $request->hora_reserva_inicio_magna;
            $horaFinal = $request->hora_reserva_final_magna;
            
            $overlap = AulaMagnaModel::where('fecha_reservada', $fechaReservada)
            ->where(function($query) use ($horaInicio, $horaFinal) {
                $query->where(function($q) use ($horaInicio, $horaFinal) {
                    $q->where('hora_reserva_inicio', '<', $horaFinal)
                    ->where('hora_reserva_final', '>', $horaInicio);
                });
            })
            ->exists();

            if ($overlap) {
                $validator->errors()->add('hora_reservada', 'La hora seleccionada ya esta ocupada.');
            }
        });

        // Si hay errores, lanzar una excepción de validación
        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        //Se guarda la reservacion si se valida que no hay confictos
        $reservacion = new AulaMagnaModel();
        $reservacion->rpe = session('user');
        $reservacion->fecha_reservada = $request->fecha_reservada_magna;
        $reservacion->hora_reserva_inicio = $request->hora_reserva_inicio_magna;
        $reservacion->hora_reserva_final = $request->hora_reserva_final_magna;
        $reservacion->numero_microfonos = $request->numero_microfonos_magna;
        $reservacion->usa_proyector = $request->usa_proyector_magna == 'true';
        $reservacion->usa_computadora = $request->usa_computadora_magna == 'true';
        $reservacion->usa_bocinas = $request->usa_bocinas_magna == 'true';
        $reservacion->save();

        return redirect()->back()->with('status', 'Se ha reservado el aula magna correctamente');
    }

    public function showAll(Request $request)
    {
        $reservaciones = AulaMagnaModel::all();
        return view('areas.aula-magna.admin', compact('reservaciones'));
    }

    public function showHorasDia(Request $request)
    {
        // $reservaciones = LaboratorioModel::where('fechaReservada', $fecha)->get();
        return view('areas.aula-magna.ver');
    }

    // Recibe una fecha (string) y devuelve las horas que se han agendado ese día
    public function horasAgendadas($fecha)
    {
        $horasAgendadas = AulaMagnaModel::where('fecha_reservada', $fecha)->get();

        return response()->json($horasAgendadas);
    }

    // Edita una reservación
    public function edit($id){
        $reservacion = AulaMagnaModel::find($id);
        return view('areas.aula-magna.editar', compact('reservacion'));
    }

    public function update(Request $request, $id)
    {
        // Validación de datos de entrada
        $validator = Validator::make($request->all(), [
            'fecha_reservada' => 'required|date',
            'hora_reserva_inicio' => 'required|date_format:H:i',
            'hora_reserva_final' => 'required|date_format:H:i|after:hora_reserva_inicio',
            'numero_microfonos' => 'required|integer|min:0|max:2',
        ]);

        // Agregar reglas de validación personalizadas usando after
        $validator->after(function ($validator) use ($request) {
            $fechaReservada = $request->fecha_reservada;

            // Verificar si la fecha seleccionada está deshabilitada
            if (DiaDeshabilitado::where('dias_deshabilitado', $fechaReservada)->exists()) {
                $validator->errors()->add('fecha_reservada', 'La fecha seleccionada no está disponible para reservaciones.');
            }

            // Verificar si la hora de inicio/final no están reservadas y no se traslapen reservaciones 
            $horaInicio = $request->hora_reserva_inicio;
            $horaFinal = $request->hora_reserva_final;
            
            $overlap = AulaMagnaModel::where('fecha_reservada', $fechaReservada)
            ->where(function($query) use ($horaInicio, $horaFinal) {
                $query->where(function($q) use ($horaInicio, $horaFinal) {
                    $q->where('hora_reserva_inicio', '<', $horaFinal)
                    ->where('hora_reserva_final', '>', $horaInicio);
                });
            })
            ->where('id', '<>', $request->id) // Excluir la reservación actual
            ->exists();

            if ($overlap) {
                $validator->errors()->add('hora_reservada', 'La hora seleccionada ya esta ocupada.');
            }
        });

        // Si hay errores, lanzar una excepción de validación
        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        $reservacion = AulaMagnaModel::find($id);
        $reservacion->id = $request->input('id');
        $reservacion->rpe = $request->input('rpe');
        $reservacion->fecha_reservada = $request->input('fecha_reservada');
        $reservacion->hora_reserva_inicio = $request->input('hora_reserva_inicio');
        $reservacion->hora_reserva_final = $request->input('hora_reserva_final');
        $reservacion->usa_proyector = $request->input('usa_proyector');
        $reservacion->numero_microfonos = $request->input('numero_microfonos');
        $reservacion->usa_computadora = $request->input('usa_computadora');
        $reservacion->usa_bocinas = $request->input('usa_bocinas');
        $reservacion->update();
        return redirect()->back()->with('status','Reservación actualizada correctamente');
    }

    public function delete($id)
    {
        $reservacion = AulaMagnaModel::find($id);
        $reservacion->delete();
        return redirect()->back()->with('status', 'Reservación borrada correctamente');
    }

    public function diasInhabiles()
    {
        return view('dias_deshabilitados');
    }

    public function configure()
    {
        $configuracion = AulaMagnaConfiguracion::first();
        return view('areas.aula-magna.configure', compact('configuracion'));
    }

    public function updateConfiguration(Request $request)
    {
        $configuracion = AulaMagnaConfiguracion::first();
        $configuracion->numero_microfonos = $request->input('numero_microfonos');
        $configuracion->save();
        return redirect()->back()->with('status','Configuración actualizada correctamente');
    }
}
