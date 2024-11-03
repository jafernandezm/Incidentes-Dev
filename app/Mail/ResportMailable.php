<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\ResultadoEscaneo;

class ResportMailable extends Mailable
{
    use Queueable, SerializesModels;
    public $dork;
    public $escaneoResultado; // Nuevo campo para almacenar escaneoResultado
    public $resultados;
    public function __construct($dork, $escaneoResultado, $resultados)
    {
        $this->dork = $dork;
        $this->escaneoResultado = $escaneoResultado; // Almacena el valor de escaneoResultado
        $this->resultados = $resultados;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            from: config('mail.from.address'),
            subject: "Nuevo Incidente: {$this->dork->titulo}"
        );
    }

    /**
     * Build the message.
     */
    public function build()
    {
        $asunto = "Nuevo Incidente: {$this->dork->titulo}";
        
        return $this->subject($asunto)
                    ->view('emails.reporte')
                    ->with([
                        'dork' => $this->dork,
                        'escaneoResultado' => $this->escaneoResultado,
                        'resultados' => $this->resultados,
                    ]);
    }

    public function attachments(): array
    {
        return [];
    }
}
