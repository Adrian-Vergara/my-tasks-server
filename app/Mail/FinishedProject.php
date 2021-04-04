<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class FinishedProject extends Mailable
{
    private $data;
    private $arrayTo;

    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data, $arrayTo)
    {
        $this->data = (object)$data;
        $this->arrayTo = $arrayTo;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Proyecto Finalizado')
            ->to($this->arrayTo)
            ->with(["data" => $this->data])
            ->view('mail.finished_project');
    }
}
