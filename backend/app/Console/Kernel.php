<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Registruje prilagođene Artisan komande aplikacije.
     */
    protected $commands = [
        \App\Console\Commands\SendDailyFinanceReport::class,
        \App\Console\Commands\SendDailyVehicleReservationReport::class,
        \App\Console\Commands\SendMonthlyFinanceReport::class,
        \App\Console\Commands\SendMonthlyVehicleReservationReport::class,
        \App\Console\Commands\SendYearlyFinanceReport::class,
        \App\Console\Commands\SendYearlyVehicleReservationReport::class,
    ];

    /**
     * Definiše raspored automatskog pokretanja izvještaja.
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('reports:daily-finance')->dailyAt('23:59');
        $schedule->command('reports:monthly-finance')->monthlyOn(1, '00:20');
        $schedule->command('reports:yearly-finance')->yearlyOn(1, 1, '00:30');
        $schedule->command('reports:daily-vehicle-reservations')->dailyAt('23:55');
        $schedule->command('reports:monthly-vehicle-reservations')->monthlyOn(1, '00:25');
        $schedule->command('reports:yearly-vehicle-reservations')->yearlyOn(1, 1, '00:35');
    }

    /**
     * Automatski učitava sve komande iz src/Console/Commands.
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');
        require base_path('routes/console.php');
    }
}