<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CotizacionEnviada extends Mailable
{

    use Queueable, SerializesModels;

    public $cotizacion;
    public $pdfPath;
    public $adjuntos;

    public function __construct($cotizacion, $pdfPath = null, $adjuntos = [])
    {
        $this->cotizacion = $cotizacion;
        $this->pdfPath = $pdfPath;
        $this->adjuntos = $adjuntos;
    }

    public function build()
    {
        $email = $this->from(config('mail.from.address'), config('mail.from.name'))
            ->subject('Cotización #' . $this->cotizacion->id_cotizacion)
            ->view('cotizacion.enviada', [
                'cotizacion' => $this->cotizacion,
            ]);

        // ✅ Adjuntar PDF principal si existe
        if ($this->pdfPath && file_exists($this->pdfPath)) {
            $email->attach($this->pdfPath, [
                'as' => 'Cotizacion_' . $this->cotizacion->id_cotizacion . '.pdf',
                'mime' => 'application/pdf',
            ]);
        }

        // ✅ Adjuntar otros archivos si hay
        foreach ($this->adjuntos as $archivo) {
            if (file_exists($archivo)) {
                $email->attach($archivo);
            }
        }

        return $email;
    }
}
