<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AssessmentValidationFinished extends Mailable
{
    use Queueable, SerializesModels;

    public $typeLabel;
    public $unitName;
    public $periodLabel;
    public $deadline;

    public function __construct($typeLabel, $unitName, $periodLabel, $deadline)
    {
        $this->typeLabel = $typeLabel;
        $this->unitName = $unitName;
        $this->periodLabel = $periodLabel;
        $this->deadline = $deadline;
    }

    public function build()
    {
        return $this
            ->subject("Validasi Pusat {$this->typeLabel} Selesai - SIdak")
            ->view('emails.assessment.validation-finished');
    }
}
