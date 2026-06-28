<?php

namespace App\Console\Commands;

use App\Models\Booking;
use Illuminate\Console\Command;

class CancelExpiredBookingsCommand extends Command
{
    protected $signature = 'bookings:cancel-expired';

    protected $description = 'Cancel expired unpaid bookings and auto-approve pending cancellation requests after 24 hours';

    public function handle(): int
    {
        $this->info('Checking for expired bookings...');
        Booking::cancelExpiredBookings();

        $this->info('Checking for pending cancellation requests > 24 hours...');
        Booking::autoCancelPendingCancellations();

        $this->info('Done.');

        return self::SUCCESS;
    }
}
