<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AreaReservadaMail extends Mailable
{
    use Queueable, SerializesModels;
    
    public $rpe;
    public $nombreCompleto;
    public $fechaReservada;
    public $horaInicio;
    public $horaFinal;

    /**
     * Create a new message instance.
     */
    public function __construct($rpe, $nombreCompleto, $fechaReservada, $horaInicio, $horaFinal)
    {
        $this->rpe = $rpe;
        $this->nombreCompleto = $nombreCompleto;
        $this->fechaReservada = $fechaReservada;
        $this->horaInicio = $horaInicio;
        $this->horaFinal = $horaFinal;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Área reservada correctamente - FEN',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.confirmacion-reservacion',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
