<?php

namespace App\Http\Controllers\AreasControllers;

use App\Models\AreasModels\AuditorioModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use App\Http\Controllers\Controller;
use App\Mail\AreaReservadaMail;
use App\Models\AreasModels\AuditorioConfiguracion;
use App\Models\DiaDeshabilitado;
use Illuminate\Support\Facades\Mail;

class AuditorioController extends Controller
{
    public function index()
    {
        return view('home');
    }

    public function store(Request $request)
    {
        // Obtener la fecha de hoy y la fecha límite (15 días después de hoy)
        $today = now();
        $maxDate = now()->addDays(15);
        $auditorioConfig = AuditorioConfiguracion::first();

        // Validación de datos de entrada
        $validator = Validator::make($request->all(), [
            'fecha_reservada_auditorio' => [
                'required',
                'date',
                'after:' . $today->format('Y-m-d'),
                'before_or_equal:' . $maxDate->format('Y-m-d'),
            ],
            'hora_reserva_inicio_auditorio' => 'required|date_format:H:i',
            'hora_reserva_final_auditorio' => 'required|date_format:H:i|after:hora_reserva_inicio_auditorio',
            'numero_microfonos_auditorio' => 'required|integer|min:1|max:' . $auditorioConfig->numero_microfonos,
        ]);

        // Si hay errores, lanzar una excepción de validación
        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        // Agregar reglas de validación personalizadas usando after
        $validator->after(function ($validator) use ($request) {
            $fechaReservada = $request->fecha_reservada_auditorio;

            // Verificar si la fecha seleccionada está deshabilitada
            if (DiaDeshabilitado::where('dias_deshabilitado', $fechaReservada)->exists()) {
                $validator->errors()->add('fecha_reservada', 'La fecha seleccionada no está disponible para reservaciones.');
            }

            // Verificar si la hora de inicio/final no están reservadas y no se traslapen reservaciones 
            $horaInicio = $request->hora_reserva_inicio_auditorio;
            $horaFinal = $request->hora_reserva_final_auditorio;
            
            $overlap = AuditorioModel::where('fecha_reservada', $fechaReservada)
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

        // Crear una nueva instancia de la reserva
        $reservacion = new AuditorioModel();
        $reservacion->rpe = session('user');
        $reservacion->fecha_reservada = $request->fecha_reservada_auditorio;
        $reservacion->hora_reserva_inicio = $request->hora_reserva_inicio_auditorio;
        $reservacion->hora_reserva_final = $request->hora_reserva_final_auditorio;
        $reservacion->numero_microfonos = $request->numero_microfonos_auditorio;

        // Establecer los valores booleanos basados en las entradas del formulario
        $reservacion->usa_proyector = $request->has('usa_proyector_auditorio');
        $reservacion->usa_computadora = $request->has('usa_computadora_auditorio');
        $reservacion->usa_bocinas = $request->has('usa_bocinas_auditorio');

        // Guardar la reserva
        $reservacion->save();

        // $correo = new AreaReservadaMail($reservacion->rpe, 'Iván', $reservacion->fecha_reservada, $reservacion->hora_reserva_inicio, $reservacion->hora_reserva_final);
        // Mail::to('ivan18rt@outlook.com')->send($correo); Funciona si manda correo

        return redirect()->back()->with('status', 'Se ha reservado el auditorio correctamente');
    }

    public function showAll(Request $request)
    {
        $reservaciones = AuditorioModel::all();

        return view('areas.auditorio.admin', compact('reservaciones'));
    }

    public function showHorasDia(Request $request)
    {
        // $reservaciones = AuditorioModel::where('fechaReservada', $fecha)->get();
        return view('areas.auditorio.ver');
    }

    // Recibe una fecha (string) y devuelve las horas que se han agendado ese día
    public function horasAgendadas($fecha)
    {
        $horasAgendadas = AuditorioModel::where('fecha_reservada', $fecha)->get();
        return response()->json($horasAgendadas);
    }

    // Edita una reservacion
    public function edit($id){
        $reservacion = AuditorioModel::find($id);
        return view('areas.auditorio.editar', compact('reservacion'));
    }

    public function update(Request $request, $id)
    {
        // Validación de datos de entrada
        $validator = Validator::make($request->all(), [
            'fecha_reservada' => 'required|date',
            'hora_reserva_inicio' => 'required|date_format:H:i',
            'hora_reserva_final' => 'required|date_format:H:i|after:hora_reserva_inicio',
            'numero_microfonos' => 'required|integer|min:0|max:2',
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
            
            $overlap = AuditorioModel::where('fecha_reservada', $fechaReservada)
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

        $reservacion = AuditorioModel::find($id);
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
        $reservacion = AuditorioModel::find($id);
        $reservacion->delete();
        return redirect()->back()->with('status', 'Reservación borrada correctamente');
    }

    public function diasInhabiles()
    {
        return view('dias_deshabilitados');
    }

    public function configure()
    {
        $configuracion = AuditorioConfiguracion::first();
        return view('areas.auditorio.configure', compact('configuracion'));
    }

    public function updateConfiguration(Request $request)
    {
        if ( AuditorioConfiguracion::first()) 
        {
            $configuracion =  AuditorioConfiguracion::first();
            $configuracion->numero_microfonos = $request->input('numero_microfonos');
            $configuracion->update();
        }
        else
        {
            $configuracion = new AuditorioConfiguracion();
            $configuracion->numero_microfonos = $request->input('numero_microfonos');
            $configuracion->save();
        }
        return redirect()->back()->with('status','Configuración actualizada correctamente');
    }
}
