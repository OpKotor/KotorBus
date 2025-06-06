<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Barryvdh\DomPDF\Facade\Pdf;

class MonthlyVehicleReservationReportMail extends Mailable
{
    use Queueable, SerializesModels;

    public $reservationsByType;
    public $month;
    public $year;

    /**
     * Konstruktor - prosljeđuje podatke za izvještaj
     */
    public function __construct($month, $year, $reservationsByType)
    {
        $this->month = $month;
        $this->year = $year;
        $this->reservationsByType = $reservationsByType;
    }

    /**
     * Priprema email sa mjesečnim izvještajem o rezervacijama po tipu vozila u pdf-u
     */
    public function build()
    {
        // Generišemo PDF koristeći odgovarajući blade šablon iz resources/views/reports
        $pdf = Pdf::loadView('reports.monthly_vehicle_reservation_report_pdf', [
            'month' => $this->month,
            'year' => $this->year,
            'reservationsByType' => $this->reservationsByType
        ]);

        return $this->subject('Mjesečni izvještaj o rezervacijama po tipu vozila')
            ->text('emails.empty') // obavezno kreiraj prazan emails/empty.blade.php
            ->attachData(
                $pdf->output(),
                'mjesecni_izvjestaj_rezervacije_po_voznom_parku.pdf',
                ['mime' => 'application/pdf']
            );
    }
}