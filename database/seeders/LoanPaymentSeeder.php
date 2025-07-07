<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LoanPayment;
use Carbon\Carbon;

class LoanPaymentSeeder extends Seeder
{
    public function run()
    {
        // Create a new LoanPayment entry
        $payment = new LoanPayment();
        $payment->loan_id = 2;
        $payment->emi_number = 6; // New EMI number to avoid duplicate
        $payment->amount = 280.00;
        $payment->principal_paid = 0.00;
        $payment->interest_paid = 0.00;
        $payment->emi_date = '2025-12-30';
        $payment->status = 'Unpaid';
        $payment->penalty_amount = 0.00;
        $payment->is_first_time_overdue = false;
        $payment->fixed_penalty_applied = false;
        $payment->last_penalty_date = null;
        $payment->created_at = Carbon::now();
        $payment->updated_at = Carbon::now();

        // Save the entry
        $success = $payment->save();

        if ($success) {
            echo "LoanPayment entry created successfully with ID: " . $payment->id . "\n";
        } else {
            echo "Failed to create LoanPayment entry.\n";
        }

        // Optional: Fetch and display the entry
        $createdPayment = LoanPayment::find($payment->id);
        if ($createdPayment) {
            echo "Fetched entry: " . json_encode($createdPayment->toArray()) . "\n";
        } else {
            echo "Failed to fetch the created entry.\n";
        }
    }
}