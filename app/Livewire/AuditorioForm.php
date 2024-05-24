<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\AreasModels\AuditorioModel; 

class AuditorioForm extends Component
{
    public $fecha_reservada_auditorio;
    public $hora_reserva_inicio_auditorio;
    public $hora_reserva_final_auditorio;
    public $usa_proyector_auditorio = false;
    public $numero_microfonos_auditorio;
    public $usa_computadora_auditorio = false;
    public $usa_bocinas_auditorio = false;

    protected $rules = [
        'fecha_reservada_auditorio' => 'required|date',
        'hora_reserva_inicio_auditorio' => 'required|date_format:H:i',
        'hora_reserva_final_auditorio' => 'required|date_format:H:i',
        'usa_proyector_auditorio' => 'boolean',
        'numero_microfonos_auditorio' => 'nullable|integer|min:0|max:2',
        'usa_computadora_auditorio' => 'boolean',
        'usa_bocinas_auditorio' => 'boolean',
    ];

    public function submit()
    {
        $this->validate();

        AuditorioModel::create([
            'fecha_reservada_auditorio' => $this->fecha_reservada_auditorio,
            'hora_reserva_inicio_auditorio' => $this->hora_reserva_inicio_auditorio,
            'hora_reserva_final_auditorio' => $this->hora_reserva_final_auditorio,
            'usa_proyector_auditorio' => $this->usa_proyector_auditorio,
            'numero_microfonos_auditorio' => $this->numero_microfonos_auditorio,
            'usa_computadora_auditorio' => $this->usa_computadora_auditorio,
            'usa_bocinas_auditorio' => $this->usa_bocinas_auditorio,
        ]);

        session()->flash('message', 'Reservación de auditorio creada exitosamente.');
        $this->reset();
    }

    public function render()
    {
        return view('livewire.auditorio-form');
    }
}
