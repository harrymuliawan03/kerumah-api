<?php

namespace App\Console\Commands;

use App\Unit;
use Carbon\Carbon;
use Illuminate\Console\Command;

class UpdateLatePayments extends Command
{
    protected $signature = 'payments:update-late';
    protected $description = 'Update late payments';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): void
    {
        // $units = Unit::where('tanggal_jatuh_tempo', '<', Carbon::now()->subMonth())->get();

        // foreach ($units as $unit) {
        //     $listPayments = $unit->listPayments()->where('isLate', 0)->get();
        //     foreach ($listPayments as $payment) {
        //         $payment->update(['isLate' => 1]);
        //     }
        // }

        $this->info('Late payments updated successfully.');
    }
}
