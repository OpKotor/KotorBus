<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Barryvdh\DomPDF\Facade\Pdf;

class DailyFinanceReportMail extends Mailable
{
    use Queueable, SerializesModels;

    public $total;
    public $date;

    /**
     * Konstruktor - prosljeđuje podatke za izvještaj
     */
    public function __construct($total, $date)
    {
        $this->total = (float)$total;
        $this->date = $date;
    }

    /**
     * Priprema email sa dnevnim finansijskim izvještajem u pdf-u
     */
    public function build()
    {
        // Generišemo PDF koristeći odgovarajući blade šablon iz resources/views/reports
        $pdf = Pdf::loadView('reports.financial_daily', [
            'total' => $this->total,
            'date' => $this->date,
        ]);

        // Vraćamo mail sa subject-om i pdf-om u atačmentu
        return $this->view('emails.daily_finance')
            ->with([
                'total' => $this->total,
                'date' => $this->date,
            ])
            ->attachData($pdf->output(), 'daily-finance-report.pdf');
    }
}