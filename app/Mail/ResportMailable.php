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
    
        // Verifica la cantidad de resultados y divide si es necesario
        $maxResultsPerPart = 10;  // Define un límite máximo de resultados por correo (ajusta según sea necesario)
        
        // Si la colección tiene más de $maxResultsPerPart elementos, la dividimos
        $resultadosDivididos = [];
        if ($this->resultados->count() > $maxResultsPerPart) {
            // Divide la colección en partes más pequeñas
            $resultadosDivididos = $this->resultados->chunk($maxResultsPerPart);
        } else {
            // Si no excede el límite, se pasa tal cual
            $resultadosDivididos[] = $this->resultados;
        }
    
        // Muestra en consola los fragmentos de resultados (solo para depuración)
        print_r($resultadosDivididos);
        
        return $this->subject($asunto)
                    ->view('emails.reporte')
                    ->with([
                        'dork' => $this->dork,
                        'escaneoResultado' => $this->escaneoResultado,
                        'resultadosDivididos' => $resultadosDivididos,  // Pasamos los fragmentos divididos
                    ]);
    }

    public function attachments(): array
    {
        return [];
    }
}
