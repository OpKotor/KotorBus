<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Barryvdh\DomPDF\Facade\Pdf;

class DailyFinanceReportMail extends Mailable
{
    use Queueable, SerializesModels;

    public $pdf;
    public $periodLabel;
    public $periodString;
    public $total;
    public $count;
    public $date;

    /**
     * Konstruktor prima podatke za izvještaj.
     *
     * @param string $date Datum izvještaja
     * @param float $total Finansijski zbir
     * @param int $count  Broj rezervacija
     */
    public function __construct($date, $total, $count)
    {
        $this->date = $date;
        $this->total = $total;
        $this->count = $count;

        $this->periodLabel = 'Dnevni finansijski izvještaj';
        $this->periodString = $date;

        // PDF se generiše ovdje, da bi mogao biti attachovan direktno
        $this->pdf = Pdf::loadView('reports.financial_daily', [
            'total' => $this->total,
            'count' => $this->count,
            'date' => $this->date,
        ]);
    }

    public function build()
    {
        return $this->subject($this->periodLabel)
            ->view('emails.daily_finance')
            ->with([
                'total' => $this->total,
                'count' => $this->count,
                'date' => $this->date,
            ])
            ->attachData($this->pdf->output(), 'daily-finance-report.pdf');
    }
}