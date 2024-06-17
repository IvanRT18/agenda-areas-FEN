<?php

namespace App\Http\Controllers\AreasControllers;

use App\Http\Controllers\Controller;
use App\Models\AreasModels\SalaVideoConferenciaModel;
use App\Models\AreasModels\SalaVideoconferenciasConfiguracion;
use App\Models\DiaDeshabilitado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class SalaVideoconferenciaController extends Controller
{
    public function store(Request $request)
    {
        $errors = [];

        // Validación de datos de entrada
        $validator = Validator::make($request->all(), [
            'fecha_reservada_sala_video' => 'required|date|after_or_equal:' . now()->format('Y-m-d'),
            'hora_reserva_inicio_sala_video' => 'required|date_format:H:i',
            'hora_reserva_final_sala_video' => 'required|date_format:H:i|after:hora_reserva_inicio',
            'numero_microfonos_sala_video' => 'required|integer|min:0|max:2',
        ]);

        // Agregar reglas de validación personalizadas usando after
        $validator->after(function ($validator) use ($request) {
            $fechaReservada = $request->fecha_reservada_sala_video;

            // Verificar si la fecha seleccionada está deshabilitada
            if (DiaDeshabilitado::where('dias_deshabilitado', $fechaReservada)->exists()) {
                $validator->errors()->add('fecha_reservada', 'La fecha seleccionada no está disponible para reservaciones.');
            }

            // Verificar si la hora de inicio/final no están reservadas y no se traslapen reservaciones 
            $horaInicio = $request->hora_reserva_inicio_sala_video;
            $horaFinal = $request->hora_reserva_final_sala_video;
            
            $overlap = SalaVideoConferenciaModel::where('fecha_reservada', $fechaReservada)
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

        $reservacion = new SalaVideoConferenciaModel();
        $reservacion->rpe = session('user');
        $reservacion->fecha_reservada = $request->fecha_reservada_sala_video;
        $reservacion->hora_reserva_inicio = $request->hora_reserva_inicio_sala_video;
        $reservacion->hora_reserva_final = $request->hora_reserva_final_sala_video;
        $reservacion->numero_microfonos = $request->numero_microfonos_sala_video;
        $reservacion->usa_proyector = $request->usa_proyector_sala_video == 'true';
        $reservacion->usa_computadora = $request->usa_computadora_sala_video == 'true';
        $reservacion->usa_bocinas = $request->usa_bocinas_sala_video == 'true';
        $reservacion->save();

        return redirect()->back()->with('status', 'Se ha reservado la sala de videoconferencias correctamente');
    }

    public function showAll(Request $request)
    {
        $reservaciones = SalaVideoConferenciaModel::all();
        return view('areas.sala-videoconferencias.admin', compact('reservaciones'));
    }

    //Muestra las horas reservadas del día
    public function showHorasDia(Request $request)
    {
        return view('areas.sala-videoconferencias.ver');
    }

    // Recibe una fecha (string) y devuelve las horas que se han agendado ese día
    public function horasAgendadas($fecha)
    {
        $horasAgendadas = SalaVideoConferenciaModel::where('fecha_reservada', $fecha)->get();
        return response()->json($horasAgendadas);
    }

    // Muestra vista de edición
    public function edit($id){
        $reservacion = SalaVideoConferenciaModel::find($id);
        return view('areas.sala-videoconferencias.editar', compact('reservacion'));
    }

    // Actualiza la reservación
    public function update(Request $request, $id)
    {
        // Validación de datos de entrada
        $validator = Validator::make($request->all(), [
            'fecha_reservada' => 'required|date',
            'hora_reserva_inicio' => 'required|date_format:H:i',
            'hora_reserva_final' => 'required|date_format:H:i|after:hora_reserva_inicio',
            'numero_microfonos' => 'required|integer|min:0',
            'rpe' => 'required|exists:usuarios,clave',
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
            
            $overlap = SalaVideoConferenciaModel::where('fecha_reservada', $fechaReservada)
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


        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        $reservacion = SalaVideoConferenciaModel::find($id);
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

    // Elimina una reservación
    public function delete($id)
    {
        $reservacion = SalaVideoConferenciaModel::find($id);
        $reservacion->delete();
        return redirect()->back()->with('status', 'Reservación borrada correctamente');
    }

    public function configure()
    {
        $configuracion = SalaVideoconferenciasConfiguracion::first();
        return view('areas.sala-videoconferencias.configure', compact('configuracion'));
    }

    public function updateConfiguration(Request $request)
    {
        if ( SalaVideoconferenciasConfiguracion::first()) 
        {
            $configuracion = SalaVideoconferenciasConfiguracion::first();
            $configuracion->numero_microfonos = $request->input('numero_microfonos');
            $configuracion->update();
        }
        else
        {
            $configuracion = new SalaVideoconferenciasConfiguracion();
            $configuracion->numero_microfonos = $request->input('numero_microfonos');
            $configuracion->save();
        }
        return redirect()->back()->with('status','Configuración actualizada correctamente');
    }
}
