<?php

namespace App\Jobs;

use App\Models\Patient;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class ProcessPatient implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $patient;

    /**
     * Создание задания.
     */
    public function __construct(Patient $patient)
    {
        $this->patient = $patient;
    }

    /**
     * Выполнение задания.
     *
     * @return void
     */
    public function handle()
    {
        // Отправка на Email
        Mail::to($this->patient->email)->send(new PatientMail($this->patient));
    }
}
