<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ReportService;
use App\Mail\DailyFinanceReportMail;
use App\Mail\DailyVehicleReservationReportMail;
use App\Mail\MonthlyFinanceReportMail;
use App\Mail\MonthlyVehicleReservationReportMail;
use App\Mail\YearlyFinanceReportMail;
use App\Mail\YearlyVehicleReservationReportMail;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function sendDailyFinance(ReportService $service)
    {
        $date = now()->toDateString();
        $finance = $service->dailyFinance($date);
        $count = $service->dailyCount($date);
        $emails = [
            'prihodi@kotor.me',
            'mirjana.grbovic@kotor.me',
        ];
        foreach ($emails as $email) {
            Mail::to($email)->send(new DailyFinanceReportMail($date, $finance, $count));
        }
        return response()->json(['status' => 'Dnevni finansijski izvještaj je poslat!']);
    }

    public function sendDailyVehicleReservations(ReportService $service)
    {
        $date = Carbon::yesterday()->toDateString();
        $reservationsByType = $service->dailyVehicleReservationsByType($date);
        $emails = [
            'prihodi@kotor.me',
            'mirjana.grbovic@kotor.me',
        ];
        foreach ($emails as $email) {
            Mail::to($email)->send(new DailyVehicleReservationReportMail($date, $reservationsByType));
        }
        return response()->json(['status' => 'Dnevni izvještaj po tipu vozila je poslat!']);
    }

    public function sendMonthlyFinance(ReportService $service)
    {
        $date = Carbon::now()->subMonth();
        $month = $date->month;
        $year = $date->year;
        $finance = $service->monthlyFinance($month, $year);
        $emails = [
            'prihodi@kotor.me',
            'mirjana.grbovic@kotor.me',
        ];
        foreach ($emails as $email) {
            Mail::to($email)->send(new MonthlyFinanceReportMail($month, $year, $finance));
        }
        return response()->json(['status' => 'Mjesečni finansijski izvještaj je poslat!']);
    }

    public function sendMonthlyVehicleReservations(ReportService $service)
    {
        $month = Carbon::now()->subMonth()->format('m');
        $year = Carbon::now()->subMonth()->year;
        $reservationsByType = $service->monthlyVehicleReservationsByType($month, $year);
        $emails = [
            'prihodi@kotor.me',
            'mirjana.grbovic@kotor.me',
        ];
        foreach ($emails as $email) {
            Mail::to($email)->send(new MonthlyVehicleReservationReportMail($month, $year, $reservationsByType));
        }
        return response()->json(['status' => 'Mjesečni izvještaj po tipu vozila je poslat!']);
    }

    public function sendYearlyFinance(ReportService $service)
    {
        $year = Carbon::now()->subYear()->year;
        $financePerMonth = $service->yearlyFinance($year);
        $totalFinance = $financePerMonth->sum('prihod');
        $emails = [
            'prihodi@kotor.me',
            'mirjana.grbovic@kotor.me',
        ];
        foreach ($emails as $email) {
            Mail::to($email)->send(new YearlyFinanceReportMail($year, $financePerMonth, $totalFinance));
        }
        return response()->json(['status' => 'Godišnji finansijski izvještaj je poslat!']);
    }

    public function sendYearlyVehicleReservations(ReportService $service)
    {
        $year = Carbon::now()->subYear()->year;
        $reservationsByType = $service->yearlyVehicleReservationsByType($year);
        $emails = [
            'prihodi@kotor.me',
            'mirjana.grbovic@kotor.me',
        ];
        foreach ($emails as $email) {
            Mail::to($email)->send(new YearlyVehicleReservationReportMail($year, $reservationsByType));
        }
        return response()->json(['status' => 'Godišnji izvještaj po tipu vozila je poslat!']);
    }
}