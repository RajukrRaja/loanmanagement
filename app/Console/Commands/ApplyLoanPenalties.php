<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\LoanPayment;
use App\Models\Loan;
use Carbon\Carbon;

class ApplyLoanPenalties extends Command
{
    protected $signature = 'penalty:apply-daily';
    protected $description = 'Apply penalties to overdue loan payments — 1 day simulated as 1 minute';

    public function handle()
    {
        $fixedPenalty = 500;             // ₹500 one-time fixed penalty
        $dailyRate = 0.001;              // 0.1% per day (simulated as per minute)
        $maxSimulatedDays = 30;          // Cap at 30 simulated days (30 minutes)
        $now = Carbon::now();            // Current time: 2025-07-03 13:50:00 IST

        $overduePayments = LoanPayment::where('status', 'Unpaid')
            ->where('emi_date', '<', $now)
            ->get();

        $this->info("Found {$overduePayments->count()} overdue payments at {$now->toDateTimeString()}");

        if ($overduePayments->isEmpty()) {
            $this->line('ℹ️ No overdue payments found for processing.');
            return;
        }

        foreach ($overduePayments as $payment) {
            // Validate loan
            $loan = $payment->loan ?? Loan::find($payment->loan_id);
            if (!$loan || !$payment->loan_id) {
                $this->error("⚠️ Loan not found for EMI ID {$payment->id}, loan_id: {$payment->loan_id}");
                continue;
            }

            // Calculate outstanding principal
            $outstanding = $this->getOutstandingPrincipal($loan);
            if ($outstanding <= 0) {
                $this->warn("💡 Outstanding is ₹0 for Loan ID {$loan->loan_id}, skipping EMI ID {$payment->id}");
                continue;
            }

            // Use last_penalty_date if available, otherwise fallback to emi_date
            $lastPenaltyDate = $payment->last_penalty_date
                ? Carbon::parse($payment->last_penalty_date)
                : Carbon::parse($payment->emi_date);

            // Simulate 1 day = 1 minute
            $overdueMinutes = $lastPenaltyDate->diffInMinutes($now);
            $simulatedDays = min($overdueMinutes, $maxSimulatedDays);
            $this->info("EMI ID {$payment->id}: emi_date={$payment->emi_date}, last_penalty_date={$lastPenaltyDate}, overdueMinutes={$overdueMinutes}, simulatedDays={$simulatedDays}");

            if ($simulatedDays === 0) {
                $this->line("⏱️ Skipped EMI ID {$payment->id} — already penalized within the last minute.");
                continue;
            }

            // Apply penalties
            if (!$payment->fixed_penalty_applied) {
                $payment->penalty_amount += $fixedPenalty;
                $payment->fixed_penalty_applied = true;
                $payment->is_first_time_overdue = true;
                $payment->last_penalty_date = $now;
                $this->info("✅ Applied ₹500 first-time penalty to EMI ID {$payment->id}");
            } else {
                $penaltyToAdd = round($outstanding * $dailyRate * $simulatedDays, 2);
                $payment->penalty_amount += $penaltyToAdd;
                $payment->is_first_time_overdue = false;
                $payment->last_penalty_date = $now;
                $this->info("💸 EMI ID {$payment->id}: ₹{$penaltyToAdd} penalty for {$simulatedDays} simulated days (Outstanding ₹{$outstanding})");
            }

            // Save updated payment
            $payment->save();
        }

        $this->line("✅ All overdue penalties processed successfully at {$now->toDateTimeString()}");
    }

    protected function getOutstandingPrincipal($loan)
    {
        $totalPrincipal = floatval($loan->approved_loan_amount ?? 0);
        $totalPaid = LoanPayment::where('loan_id', $loan->loan_id)
            ->where('status', 'Paid')
            ->sum('principal_paid');
        $outstanding = max($totalPrincipal - floatval($totalPaid), 0);
        $this->info("Loan ID {$loan->loan_id}: Principal ₹{$totalPrincipal}, Paid ₹{$totalPaid}, Outstanding ₹{$outstanding}");
        return $outstanding;
    }
}
