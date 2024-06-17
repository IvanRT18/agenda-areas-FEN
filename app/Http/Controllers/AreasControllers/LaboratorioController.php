<?php

namespace App\Http\Controllers\AreasControllers;

use App\Models\AreasModels\LaboratorioModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Mail\AreaReservadaMail;
use App\Models\AreasModels\LaboratorioConfiguracion;
use App\Models\DiaDeshabilitado;
use App\Models\Usuario;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class LaboratorioController extends Controller
{
    public function index()
    {
        return view('home');
    }

    public function store(Request $request)
    {
        // Validación de datos de entrada
        $validator = Validator::make($request->all(), [
            'fecha_reservada' => 'required|date|after_or_equal:' . now()->format('Y-m-d'),
            'hora_reserva_inicio' => 'required|date_format:H:i',
            'hora_reserva_final' => 'required|date_format:H:i|after:hora_reserva_inicio',
            'numero_equipos' => 'required|integer|min:0|max:40',
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
            
            $overlap = LaboratorioModel::where('fecha_reservada', $fechaReservada)
            ->where('ubicacion', $request->numero_equipos > 30  ? 'Adelante' : 'Atrás')->where(function($query) use ($horaInicio, $horaFinal) {
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
        $usuario = Usuario::where('clave', session('user'))->first();
        $reservacion = new LaboratorioModel();
        $reservacion->rpe = $usuario->getRpe();
        $reservacion->numero_equipos = $request->numero_equipos;
        $reservacion->fecha_reservada = $request->fecha_reservada;
        $reservacion->hora_reserva_inicio = $request->hora_reserva_inicio;
        $reservacion->hora_reserva_final = $request->hora_reserva_final;
        $reservacion->usa_proyector = $request->usa_proyector == 'true';
        $reservacion->ubicacion = $request->numero_equipos > 30 ? 'Adelante' : 'Atrás';
        $reservacion->req_adicionales = $request->req_adicionales;
        $reservacion->materia = $request->materia;
        $reservacion->actividad = $request->actividad;
        $reservacion->nombre_curso = $request->nombre_curso;
        $reservacion->save();

        return redirect()->back()->with('status', 'Se ha reservado el laboratorio correctamente');
    }

    public function showAll(Request $request)
    {
        $reservaciones = LaboratorioModel::all();

        return view('areas.laboratorio.admin', compact('reservaciones'));
    }

    public function showHorasDia(Request $request)
    {
        // $reservaciones = LaboratorioModel::where('fechaReservada', $fecha)->get();
        return view('areas.laboratorio.ver');
    }

    // Recibe una fecha (string) y devuelve las horas que se han agendado ese día
    public function horasAgendadas($fecha)
    {
        $horasAgendadas = LaboratorioModel::where('fecha_reservada', $fecha)->get(['hora_reserva_inicio', 'hora_reserva_final', 'materia', 'actividad', 'ubicacion']);
        return response()->json($horasAgendadas);
    }

    // Recibe una fecha (string) y devuelve las horas que se han agendado ese día solo en la parte de Adelante*
    public function horasAgendadasAdelante($fecha)
    {
        // Consulta para obtener las horas agendadas en la fecha especificada con ubicación 'Adelante'
        $horasAgendadas = LaboratorioModel::where('fecha_reservada', $fecha)
            ->where('ubicacion', 'Adelante')
            ->get(['hora_reserva_inicio', 'hora_reserva_final', 'materia', 'actividad', 'ubicacion']);

        return response()->json($horasAgendadas);
    }

    // Recibe una fecha (string) y devuelve las horas que se han agendado ese día solo en la parte de Atrás*
    public function horasAgendadasAtras($fecha)
    {
        // Consulta para obtener las horas agendadas en la fecha especificada con ubicación 'Adelante'
        $horasAgendadas = LaboratorioModel::where('fecha_reservada', $fecha)
            ->where('ubicacion', 'Atrás')
            ->get(['hora_reserva_inicio', 'hora_reserva_final', 'materia', 'actividad', 'ubicacion']);

        return response()->json($horasAgendadas);
    }

    // Recibe una fecha (string) y devuelve las horas que se han agendado ese día (solo horas)
    public function horasDisabled($fecha)
    {
        $disabledHours = LaboratorioModel::where('fecha_reservada', $fecha)->get();
        return response()->json($disabledHours);
    }

    // Edita una cita
    public function edit($id){
        $reservacion = LaboratorioModel::find($id);
        return view('areas.laboratorio.editar', compact('reservacion'));
    }

    public function update(Request $request, $id)
    {
        // Validación de datos de entrada
        $validator = Validator::make($request->all(), [
            'fecha_reservada' => 'required|date',
            'hora_reserva_inicio' => 'required|date_format:H:i',
            'hora_reserva_final' => 'required|date_format:H:i|after:hora_reserva_inicio',
            'numero_equipos' => 'required|integer|min:0',
            'materia' => 'required|string',
            'actividad' => 'required|string',
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
            
            $overlap = LaboratorioModel::where('fecha_reservada', $fechaReservada)
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

        // Actualizar la reservación si no hay solapamientos
        $reservacion = LaboratorioModel::find($id);
        $reservacion->rpe = $request->input('rpe');
        $reservacion->fecha_reservada = $request->input('fecha_reservada');
        $reservacion->hora_reserva_inicio = $request->input('hora_reserva_inicio');
        $reservacion->hora_reserva_final = $request->input('hora_reserva_final');
        $reservacion->numero_equipos = $request->input('numero_equipos');
        $reservacion->usa_proyector = $request->input('usa_proyector');
        $reservacion->req_adicionales = $request->input('req_adicionales');
        $reservacion->materia = $request->input('materia');
        $reservacion->actividad = $request->input('actividad');
        $reservacion->nombre_curso = $request->input('nombre_curso');
        $reservacion->ubicacion = $request->input('ubicacion');
        $reservacion->update();

        return redirect()->back()->with('status', 'Reservación actualizada correctamente');
    }

    public function delete($id)
    {
        $reservacion = LaboratorioModel::find($id);
        $reservacion->delete();
        return redirect()->back()->with('status', 'Reservación borrada correctamente');
    }

    public function diasInhabiles()
    {
        return view('dias_deshabilitados');
    }

    public function configure()
    {
        $configuracion = LaboratorioConfiguracion::first();
        return view('areas.laboratorio.configure', compact('configuracion'));
    }

    public function updateConfiguration(Request $request)
    {
        if ( LaboratorioConfiguracion::first()) 
        {
            $configuracion =  LaboratorioConfiguracion::first();
            $configuracion->numero_computadoras = $request->input('numero_computadoras');
            $configuracion->update();
        }
        else
        {
            $configuracion = new LaboratorioConfiguracion();
            $configuracion->numero_computadoras = $request->input('numero_computadoras');
            $configuracion->save();
        }
        return redirect()->back()->with('status','Configuración actualizada correctamente');
    }
}
