@extends('superadmin.superadmin')

@section('sidebar')
    @include('superadmin.superadminSidebar')
@endsection

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

    <!-- Main Content -->
    <main class="emi-details emi-main-content" aria-label="EMI Calculation Result Page">
        <!-- Alerts -->
        @if(session('success'))
            <div class="emi-alert emi-alert-success fade-in" role="alert" tabindex="0">
                {{ session('success') }}
                <button type="button" class="emi-alert-close" aria-label="Close">×</button>
            </div>
        @endif
        @if(session('error'))
            <div class="emi-alert emi-alert-danger fade-in" role="alert" tabindex="0">
                {{ session('error') }}
                <button type="button" class="emi-alert-close" aria-label="Close">×</button>
            </div>
        @endif

        @if(!isset($loan) || !$loan->loan_id)
            <div class="emi-alert emi-alert-danger fade-in" role="alert" tabindex="0">
                Unable to load loan details. Please contact support.
                <button type="button" class="emi-alert-close" aria-label="Close">×</button>
            </div>
        @else
            <section style="padding: 1rem; border: 1px solid #ddd; border-radius: 8px; margin-bottom: 1.5rem; background: #f9f9fb; font-family: 'Segoe UI', Tahoma, Geneva, Arial, sans-serif;" aria-labelledby="loan-details-title">
                <h5 id="loan-details-title" style="text-align: center; margin-bottom: 1rem; font-size: 1.2rem; color: #333;">
                    <strong>Disbursement Date:</strong> {{ \Carbon\Carbon::parse($loan->disbursement_date ?? now())->format('d-m-Y') }}
                </h5>
                <ul style="list-style: none; padding: 0; margin: 0; display: flex; flex-wrap: wrap; gap: 1rem;">
                    <li style="flex: 1 1 200px;"><strong>Loan Amount:</strong> ₹{{ number_format(round(floatval($loan->approved_loan_amount ?? $loan->loan_amount ?? 0), 0), 0) }}</li>
                    <li style="flex: 1 1 200px;"><strong>Disbursement Amount:</strong> ₹{{ number_format($loan->disbursement_amount ?? 0, 0) }}</li>
                    <li style="flex: 1 1 200px;"><strong>Amount Paid:</strong> ₹{{ number_format($loan->amount_paid ?? 0, 0) }}</li>
                    <li style="flex: 1 1 200px;"><strong>Amount Unpaid:</strong> ₹{{ number_format($loan->amount_unpaid ?? 0, 0) }}</li>
                    <li style="flex: 1 1 200px;"><strong>Pending Principal:</strong> ₹{{ number_format($loan->unpaid_principal ?? 0, 0) }}</li>
                    <li style="flex: 1 1 200px;"><strong>Unpaid Interest:</strong> ₹{{ number_format($loan->unpaid_interest ?? 0, 0) }}</li>
                    <li style="flex: 1 1 200px;"><strong>Total Penalty:</strong> ₹{{ number_format($loan->total_penalty ?? 0, 0) }}</li>
                    <li style="flex: 1 1 200px;"><strong>Paid Penalty:</strong> ₹{{ number_format($loan->paid_penalty ?? 0, 0) }}</li>
                    <li style="flex: 1 1 200px;"><strong>Remaining Penalty:</strong> ₹{{ number_format($loan->remaining_penalty ?? 0, 0) }}</li>
                    <li style="flex: 1 1 200px;"><strong>Total Amount:</strong> ₹{{ number_format($loan->total_amount ?? 0, 0) }}</li>
                    <li style="flex: 1 1 200px;"><strong>Interest:</strong> ₹{{ number_format($loan->total_interest ?? 0, 0) }}</li>
                    <li style="flex: 1 1 200px;"><strong>Status:</strong> {{ $loan->emi_status ?? '0/' . ($loan->tenure_months ?? 12) . ' Paid' }}</li>
                </ul>
                <div style="margin-top: 2rem; display: flex; flex-wrap: wrap; justify-content: center; gap: 0.75rem; align-items: center;">
                    <select id="viewToggle" aria-label="Select view type" style="padding: 0.5rem 0.75rem; min-width: 160px; border: 1px solid #ccc; border-radius: 4px; font-size: 0.95rem;">
                        <option value="default" selected>Default View</option>
                        <option value="lessor">Lessor View</option>
                    </select>
                    <a href="#" style="padding: 0.5rem 1rem; background: #007bff; color: #fff; text-decoration: none; border-radius: 4px; font-size: 0.9rem;">Print EMI Statement</a>
                    <a href="#" style="padding: 0.5rem 1rem; background: #dc3545; color: #fff; text-decoration: none; border-radius: 4px; font-size: 0.9rem;">Penalty Pay</a>
                    <a href="#" style="padding: 0.5rem 1rem; background: #28a745; color: #fff; text-decoration: none; border-radius: 4px; font-size: 0.9rem;">History</a>
                </div>
            </section>

            <!-- EMI Payment Schedule -->
            <section class="emi-card" aria-labelledby="emi-schedule-title">
                <div class="emi-card-body">
                    <div class="emi-table-container">
                        <div id="loadingOverlay" class="emi-loading-overlay d-none">
                            <div class="emi-spinner-border" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                        <!-- Default Table -->
                        <div id="defaultTableView" class="emi-table-view">
                            <table class="emi-table">
                                <thead>
                                    <tr>
                                        <th>Sr no.</th>
                                        <th>EMI</th>
                                        <th>Amount</th>
                                        <th>Paid So Far</th>
                                        <th>Penalty Amount</th>
                                        <th>Interest</th>
                                        <th>Status</th>
                                        <th>Emi Date</th>
                                        <th>Emi Payment Date</th>
                                        <th>Rest Amount</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $isForeclosed = $loan->current_status === 'closed';
                                        $flatInterest = $loan->interest_type === 'blogs'
                                            ? round(($loan->total_interest ?? 0) / max(($loan->tenure_months ?? 1), 1), 2)
                                            : 0;
                                        $serialNumber = 1;

                                        $normalPaidEmis = $loanPayments
                                            ->where('status', 'Paid')
                                            ->where('pay_type', 'normal')
                                            ->sortBy('emi_number');

                                        $lastForeclosePayment = $loanPayments
                                            ->where('pay_type', 'foreclose')
                                            ->sortByDesc('emi_number')
                                            ->first();

                                        $foreclosureDate = $lastForeclosePayment && $lastForeclosePayment->emi_payment_date
                                            ? \Carbon\Carbon::parse($lastForeclosePayment->emi_payment_date)->format('d/m/Y')
                                            : ($isForeclosed ? \Carbon\Carbon::now()->format('d/m/Y') : '-');

                                        $foreclosedPayments = $loanPayments->where('pay_type', 'foreclose')->where('status', 'Paid');
                                        $pendingPrincipal = $foreclosedPayments->sum('principal_paid');
                                        $unpaidInterest = $foreclosedPayments->sum('interest_paid');
                                        $remainingPenalty = $foreclosedPayments->sum('penalty_amount');
                                        $interestRate = floatval($loan->interest_rate ?? 12);
                                        $foreclosureCharges = round($pendingPrincipal * 0.04, 2);
                                        $nextInterest = round($pendingPrincipal * $interestRate / 1200, 2);
                                        $closureAmount = round($pendingPrincipal + $nextInterest + $foreclosureCharges + $remainingPenalty + $unpaidInterest, 2);
                                    @endphp

                                    @if ($isForeclosed)
                                        @foreach ($normalPaidEmis as $payment)
                                            @php
                                                $schedule = $emiSchedules->where('month', $payment->emi_number)->first();
                                                $dueDate = $schedule ? \Carbon\Carbon::parse($schedule->due_date)->format('d/m/Y') : '-';
                                                $emiPaymentDate = $payment->emi_payment_date
                                                    ? \Carbon\Carbon::parse($payment->emi_payment_date)->format('d/m/Y')
                                                    : '-';
                                                $penalty = floatval($payment->penalty_amount ?? 0);
                                                $paidSoFar = floatval($payment->amount ?? ($schedule->emi_amount ?? 0));
                                                $restAmount = floatval($payment->rest_amount ?? 0);
                                                $interest = $flatInterest;
                                            @endphp
                                            <tr>
                                                <td>{{ $serialNumber++ }}</td>
                                                <td>{{ $payment->emi_number }}</td>
                                                <td>₹{{ number_format($schedule->emi_amount ?? 0, 0) }}</td>
                                                <td>₹{{ number_format($paidSoFar, 0) }}</td>
                                                <td>₹{{ number_format($penalty, 0) }}</td>
                                                <td>₹{{ number_format($interest, 0) }}</td>
                                                <td>{{ $payment->status }}</td>
                                                <td>{{ $dueDate }}</td>
                                                <td>{{ $emiPaymentDate }}</td>
                                                <td>₹{{ number_format($restAmount, 0) }}</td>
                                                <td><button type="button" class="emi-btn emi-btn-secondary emi-btn-sm" disabled>Paid</button></td>
                                            </tr>
                                        @endforeach
                                        <!-- Foreclosure Summary -->
                                        <tr style="background-color: #fef5f5;">
                                            <td colspan="11" align="center" style="padding: 16px 0;">
                                                <div style="font-size: 22px; font-weight: bold; margin-bottom: 10px;">
                                                    Loan Closed Summary
                                                </div>
                                                <div style="display: flex; justify-content: center; align-items: center; flex-wrap: wrap; gap: 30px; font-size: 18px; font-weight: bold; text-align: center;">
                                                    <div style="min-width: 120px;">
                                                        <div style="color: #888;">Principal</div>
                                                        <div style="font-size: 20px;">₹{{ number_format($pendingPrincipal, 0) }}</div>
                                                    </div>
                                                    <div style="min-width: 120px;">
                                                        <div style="color: #888;">Interest</div>
                                                        <div style="font-size: 20px;">₹{{ number_format($nextInterest, 0) }}</div>
                                                    </div>
                                                    <div style="min-width: 140px;">
                                                        <div style="color: #888;">Charges (4%)</div>
                                                        <div style="font-size: 20px;">₹{{ number_format($foreclosureCharges, 0) }}</div>
                                                    </div>
                                                    <div style="min-width: 120px;">
                                                        <div style="color: #888;">Penalty</div>
                                                        <div style="font-size: 20px;">₹{{ number_format($remainingPenalty, 0) }}</div>
                                                    </div>
                                                    <div style="min-width: 150px;">
                                                        <div style="color: #888;">Unpaid Interest</div>
                                                        <div style="font-size: 20px;">₹{{ number_format($unpaidInterest, 0) }}</div>
                                                    </div>
                                                    <div style="min-width: 150px;">
                                                        <div style="color: #b30000;">Total</div>
                                                        <div style="font-size: 22px; font-weight: bold; color: #b30000;">
                                                            ₹{{ number_format($closureAmount, 0) }}
                                                        </div>
                                                    </div>
                                                    <div style="min-width: 120px;">
                                                        <div style="color: #888;">Date</div>
                                                        <div style="font-size: 18px;">{{ $foreclosureDate }}</div>
                                                    </div>
                                                </div>
                                                <div style="font-size: 14px; color: green; margin-top: 10px;">
                                                    This loan is closed via foreclose.
                                                </div>
                                            </td>
                                        </tr>
                                    @else
                                        @foreach ($emiSchedules as $schedule)
                                            @php
                                                $payment = $loanPayments->where('emi_number', $schedule->month)->first();
                                                $dueDate = \Carbon\Carbon::parse($schedule->due_date)->format('d/m/Y');
                                                $emiPaymentDate = $payment && $payment->status === 'Paid' && $payment->emi_payment_date
                                                    ? \Carbon\Carbon::parse($payment->emi_payment_date)->format('d/m/Y')
                                                    : '-';
                                                $penalty = floatval($payment->penalty_amount ?? 0);
                                                $status = $payment->status ?? 'Unpaid';
                                                $paidSoFar = $payment && $payment->status === 'Unpaid'
                                                    ? floatval($payment->half_payment_paid ?? 0)
                                                    : floatval($payment->amount ?? $schedule->emi_amount);
                                                $restAmount = $payment
                                                    ? floatval($payment->rest_amount ?? ($schedule->emi_amount - ($payment->half_payment_paid ?? 0)))
                                                    : floatval($schedule->emi_amount);
                                                $interest = $flatInterest;
                                            @endphp
                                            <tr>
                                                <td>{{ $serialNumber++ }}</td>
                                                <td>{{ $schedule->month }}</td>
                                                <td>₹{{ number_format($schedule->emi_amount, 0) }}</td>
                                                <td>₹{{ number_format($paidSoFar, 0) }}</td>
                                                <td>₹{{ number_format($penalty, 0) }}</td>
                                                <td>₹{{ number_format($interest, 0) }}</td>
                                                <td>{{ $status }}</td>
                                                <td>{{ $dueDate }}</td>
                                                <td>{{ $emiPaymentDate }}</td>
                                                <td>₹{{ number_format($restAmount, 0) }}</td>
                                                <td>
                                                    @if($status === 'Unpaid')
                                                        <button type="button" class="emi-btn emi-btn-danger emi-btn-sm pay-btn"
                                                            data-emi-number="{{ $schedule->month }}"
                                                            data-loan-id="{{ $loan->loan_id }}"
                                                            data-expected-amount="{{ $schedule->emi_amount + $penalty }}"
                                                            data-half-payment-paid="{{ $payment->half_payment_paid ?? 0 }}">
                                                            Pay
                                                        </button>
                                                    @else
                                                        <button type="button" class="emi-btn emi-btn-secondary emi-btn-sm" disabled>Paid</button>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                        <!-- Lessor Table -->
                        <div id="lessorTableView" class="emi-table-view" style="display: none;">
                            <table class="emi-table">
                                <thead>
                                    <tr>
                                        <th>Month</th>
                                        <th>Opening Bal</th>
                                        <th>EMI</th>
                                        <th>Principal</th>
                                        <th>Interest</th>
                                        <th>Closing Bal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($emiSchedules as $schedule)
                                        @php
                                            $monthName = \Carbon\Carbon::parse($schedule->due_date)->format('F Y');
                                        @endphp
                                        <tr>
                                            <td>{{ $monthName }}</td>
                                            <td>₹{{ number_format($schedule->opening_balance, 0) }}</td>
                                            <td>₹{{ number_format($schedule->emi_amount, 0) }}</td>
                                            <td>₹{{ number_format($schedule->principal, 0) }}</td>
                                            <td>₹{{ number_format($schedule->interest, 0) }}</td>
                                            <td>₹{{ number_format($schedule->closing_balance, 0) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- Close Loan Button -->
                    @if($loan->current_status !== 'closed')
                        @php
                            $next_month_interest = round(($loan->unpaid_principal * ($loan->interest_rate ?? 12) / 1200), 2);
                            $closureAmount = round(($loan->unpaid_principal ?? 0) + $next_month_interest + ($loan->unpaid_principal ?? 0) * 0.04 + ($loan->remaining_penalty ?? 0) + ($loan->unpaid_interest ?? 0), 2);
                        @endphp
                        <div style="margin-top: 1rem; text-align: center;">
                            <button type="button" 
                                    class="emi-btn emi-btn-primary emi-btn-close-loan" 
                                    data-closure-amount="{{ $closureAmount }}"
                                    data-bs-toggle="modal" 
                                    data-bs-target="#closeLoanModal"
                                    style="background-color: red; color: white; width: 200px;">
                                Close Loan
                            </button>
                        </div>
                    @endif
                </div>
            </section>

            <!-- Payment Modal -->
            <div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="paymentModalLabel">Make EMI Payment</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form id="paymentForm" action="{{ route('admin.emi.payment') }}" method="POST">
                                @csrf
                                <input type="hidden" name="lead_id" value="{{ $loan->lead_id }}">
                                <input type="hidden" name="loan_id" value="{{ $loan->loan_id }}">

                                <div class="mb-3">
                                    <label for="emiNumber" class="form-label">EMI Number</label>
                                    <input type="text" class="form-control" id="emiNumber" name="emi_number" readonly>
                                </div>

                                <div class="mb-3">
                                    <label for="expectedAmount" class="form-label">Expected Amount (₹)</label>
                                    <input type="text" class="form-control" id="expectedAmount" readonly>
                                </div>

                                <div class="mb-3">
                                    <label for="amount" class="form-label">Amount to Pay (₹)</label>
                                    <input type="number" step="0.01" class="form-control" id="amount" name="amount" required>
                                    <div class="invalid-feedback">Please enter a valid amount (minimum ₹0.01).</div>
                                    <small id="partialNote" class="form-text text-muted" style="display: none;"></small>
                                </div>

                                <div class="mb-3">
                                    <label for="paymentMethod" class="form-label">Payment Method</label>
                                    <select class="form-select" id="paymentMethod" name="payment_method" required>
                                        <option value="" disabled selected>Select Payment Method</option>
                                        <option value="credit_card">Credit Card</option>
                                        <option value="debit_card">Debit Card</option>
                                        <option value="net_banking">Net Banking</option>
                                        <option value="upi">UPI</option>
                                        <option value="cash">Cash</option>
                                    </select>
                                    <div class="invalid-feedback">Please select a valid payment method.</div>
                                </div>

                                <div class="mb-3 d-none" id="cardDetails">
                                    <label for="cardNumber" class="form-label">Card Number</label>
                                    <input type="text" class="form-control" id="cardNumber" name="card_number" placeholder="1234 5678 9012 3456">
                                    <div class="invalid-feedback">Please enter a valid 16-digit card number.</div>
                                </div>

                                <div class="mb-3 d-none" id="upiDetails">
                                    <label for="upiId" class="form-label">UPI ID</label>
                                    <input type="text" class="form-control" id="upiId" name="upi_id" placeholder="example@upi">
                                    <div class="invalid-feedback">Please enter a valid UPI ID (e.g., example@upi).</div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" form="paymentForm" class="btn btn-primary">Confirm Payment</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Close Loan Modal -->
            <div class="modal fade" id="closeLoanModal" tabindex="-1" aria-labelledby="closeLoanModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="closeLoanModalLabel">Close Loan</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form id="closeLoanForm" action="{{ route('admin.loan.close') }}" method="POST">
                                @csrf
                                <input type="hidden" name="lead_id" value="{{ $loan->lead_id }}">
                                <input type="hidden" name="loan_id" value="{{ $loan->loan_id }}">

                                <div class="mb-3">
                                    <label class="form-label">Pending Principal</label>
                                    <input type="number" step="0.01" class="form-control" name="unpaid_principal" value="{{ round($loan->unpaid_principal ?? 0, 2) }}">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Next Month Interest</label>
                                    <input type="number" step="0.01" class="form-control" name="next_month_interest" value="{{ round(($loan->unpaid_principal * ($loan->interest_rate ?? 12) / 1200), 2) }}">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Foreclosure Charges (4%)</label>
                                    <input type="number" step="0.01" class="form-control" name="foreclosure_charges" value="{{ round(($loan->unpaid_principal ?? 0) * 0.04, 2) }}">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Remaining Penalty</label>
                                    <input type="number" step="0.01" class="form-control" name="remaining_penalty" value="{{ round($loan->remaining_penalty ?? 0, 2) }}">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Unpaid Interest</label>
                                    <input type="number" step="0.01" class="form-control" name="unpaid_interest" value="{{ round($loan->unpaid_interest ?? 0, 2) }}">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Total Closure Amount</label>
                                    <input type="number" step="0.01" class="form-control" name="closure_amount" value="{{ $closureAmount }}" readonly>
                                </div>

                                <div class="mb-3">
                                    <label for="closePaymentMethod" class="form-label">Payment Method</label>
                                    <select class="form-select" id="closePaymentMethod" name="payment_method" required>
                                        <option value="" disabled selected>Select Payment Method</option>
                                        <option value="credit_card">Credit Card</option>
                                        <option value="debit_card">Debit Card</option>
                                        <option value="net_banking">Net Banking</option>
                                        <option value="upi">UPI</option>
                                    </select>
                                    <div class="invalid-feedback">Please select a valid payment method.</div>
                                </div>

                                <div class="mb-3 d-none" id="closeCardDetails">
                                    <label for="closeCardNumber" class="form-label">Card Number</label>
                                    <input type="text" class="form-control" id="closeCardNumber" name="card_number" placeholder="1234 5678 9012 3456">
                                    <div class="invalid-feedback">Please enter a valid 16-digit card number.</div>
                                </div>

                                <div class="mb-3 d-none" id="closeUpiDetails">
                                    <label for="closeUpiId" class="form-label">UPI ID</label>
                                    <input type="text" class="form-control" id="closeUpiId" name="upi_id" placeholder="example@upi">
                                    <div class="invalid-feedback">Please enter a valid UPI ID (e.g., example@upi).</div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" form="closeLoanForm" class="btn btn-primary">Confirm Closure</button>
                        </div>
                    </div>
                </div>
            </div>

            <style>
                @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=block');

                :root {
                    --emi-primary: #2563eb;
                    --emi-primary-dark: #1e40af;
                    --emi-success: #10b981;
                    --emi-success-dark: #059669;
                    --emi-danger: #ef4444;
                    --emi-danger-dark: #dc2626;
                    --emi-secondary: #6b7280;
                    --emi-bg-light: #f3f4f6;
                    --emi-bg-white: #ffffff;
                    --emi-text-dark: #1f2a44;
                    --emi-border-light: #e5e7eb;
                    --emi-shadow: 0 6px 24px rgba(0, 0, 0, 0.08);
                    --emi-transition: all 0.3s ease;
                }

                .emi-details {
                    font-family: 'Inter', sans-serif;
                    color: var(--emi-text-dark);
                    line-height: 1.6;
                }

                .emi-main-content {
                    max-width: 1400px;
                    margin: 2rem auto;
                    padding: 0 1rem;
                    display: flex;
                    flex-direction: column;
                    gap: 2rem;
                }

                .emi-card {
                    background: var(--emi-bg-white);
                    border-radius: 0.75rem;
                    box-shadow: var(--emi-shadow);
                    padding: 1.5rem;
                }

                .emi-table-container {
                    overflow-x: auto;
                    position: relative;
                    min-height: 300px;
                }

                .emi-table {
                    width: 100%;
                    border-collapse: collapse;
                    margin-bottom: 0;
                }

                .emi-table thead th {
                    background: var(--emi-primary);
                    color: var(--emi-bg-white);
                    font-weight: 600;
                    padding: 0.75rem;
                    border: none;
                    text-align: left;
                    font-size: 0.95rem;
                }

                .emi-table tbody tr {
                    transition: background var(--emi-transition);
                }

                .emi-table tbody tr:hover {
                    background: var(--emi-bg-light);
                }

                .emi-table td {
                    padding: 0.75rem;
                    font-size: 0.9rem;
                    vertical-align: middle;
                    border-bottom: 1px solid var(--emi-border-light);
                }

                .emi-table-view {
                    transition: opacity var(--emi-transition);
                }

                .emi-table-view[style*="display: none"] {
                    opacity: 0;
                    pointer-events: none;
                }

                .emi-table-view:not([style*="display: none"]) {
                    opacity: 1;
                }

                .emi-loading-overlay {
                    position: absolute;
                    top: 0;
                    left: 0;
                    right: 0;
                    bottom: 0;
                    background: rgba(255, 255, 255, 0.8);
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    z-index: 10;
                    opacity: 0;
                    transition: opacity var(--emi-transition);
                }

                .emi-loading-overlay:not(.d-none) {
                    opacity: 1;
                }

                .emi-spinner-border {
                    width: 2rem;
                    height: 2rem;
                    border: 0.25rem solid var(--emi-primary);
                    border-right-color: transparent;
                    border-radius: 50%;
                    animation: spin 1s linear infinite;
                }

                @keyframes spin {
                    0% { transform: rotate(0deg); }
                    100% { transform: rotate(360deg); }
                }

                .emi-alert {
                    border-radius: 1rem;
                    padding: 1rem 1.5rem;
                    box-shadow: var(--emi-shadow);
                    font-size: 0.95rem;
                    margin-bottom: 2rem;
                    position: relative;
                    transition: opacity var(--emi-transition);
                }

                .emi-alert-success {
                    background: #d1fae5;
                    color: #065f46;
                }

                .emi-alert-danger {
                    background: #fee2e2;
                    color: #991b1b;
                }

                .emi-alert-close {
                    background: none;
                    border: none;
                    font-size: 1rem;
                    color: var(--emi-text-dark);
                    cursor: pointer;
                    position: absolute;
                    top: 1rem;
                    right: 1rem;
                    width: 1.5rem;
                    height: 1.5rem;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                }

                .emi-btn {
                    padding: 0.75rem 1.5rem;
                    font-weight: 500;
                    border-radius: 0.75rem;
                    text-align: center;
                    transition: var(--emi-transition);
                    border: none;
                    display: inline-block;
                    cursor: pointer;
                    text-decoration: none;
                    font-size: 0.95rem;
                }

                .emi-btn-primary {
                    background: var(--emi-primary);
                    color: var(--emi-bg-white);
                }

                .emi-btn-primary:hover {
                    background: var(--emi-primary-dark);
                    transform: translateY(-2px);
                    box-shadow: 0 6px 16px rgba(0, 0, 0, 0.2);
                }

                .emi-btn-success {
                    background: var(--emi-success);
                    color: var(--emi-bg-white);
                }

                .emi-btn-success:hover {
                    background: var(--emi-success-dark);
                    transform: translateY(-2px);
                    box-shadow: 0 6px 16px rgba(0, 0, 0, 0.2);
                }

                .emi-btn-danger {
                    background: var(--emi-danger);
                    color: var(--emi-bg-white);
                }

                .emi-btn-danger:hover {
                    background: var(--emi-danger-dark);
                    transform: translateY(-2px);
                    box-shadow: 0 6px 16px rgba(0, 0, 0, 0.2);
                }

                .emi-btn-secondary {
                    border: 1px solid var(--emi-secondary);
                    color: var(--emi-secondary);
                    background: transparent;
                }

                .emi-btn-secondary:hover {
                    background: var(--emi-bg-light);
                    color: var(--emi-text-dark);
                    transform: translateY(-2px);
                }

                .emi-btn-sm {
                    padding: 0.5rem 1rem;
                    font-size: 0.9rem;
                }

                .highlighted-row {
                    background: #e6f3ff;
                    transition: background 0.5s ease;
                }

                /* Responsive Design */
                @media (max-width: 992px) {
                    .emi-main-content {
                        margin: 1.5rem auto;
                        padding: 0 0.75rem;
                    }
                    .emi-table thead th,
                    .emi-table td {
                        font-size: 0.85rem;
                        padding: 0.5rem;
                    }
                    .emi-btn {
                        padding: 0.5rem 1rem;
                        font-size: 0.9rem;
                    }
                    .modal-dialog {
                        max-width: 90%;
                    }
                }

                @media (max-width: 768px) {
                    .emi-main-content {
                        margin: 1rem auto;
                        padding: 0 0.5rem;
                    }
                    .emi-table {
                        font-size: 0.8rem;
                    }
                    .emi-table thead th,
                    .emi-table td {
                        padding: 0.5rem;
                    }
                    .emi-btn {
                        padding: 0.5rem 0.75rem;
                        font-size: 0.85rem;
                    }
                }

                @media (max-width: 576px) {
                    .emi-details h5 {
                        font-size: 1.1rem;
                    }
                    .modal-dialog {
                        margin: 0.5rem;
                        max-width: 95%;
                    }
                    .modal-title {
                        font-size: 1.1rem;
                    }
                    .emi-btn {
                        font-size: 0.8rem;
                    }
                }
            </style>

            <!-- Bootstrap JS and Popper -->
            <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDvM2r8z5b8z5m5b5v5q5f5b5v5q5f5b5v5q5f" crossorigin="anonymous"></script>
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>

            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    console.log('EMI page initialized at ' + new Date().toLocaleString('en-IN', { timeZone: 'Asia/Kolkata' }));

                    const leadId = @json($loan->lead_id ?? null);
                    const loanId = @json($loan->loan_id ?? null);
                    const closureAmount = @json($closureAmount ?? 0);

                    if (!loanId || !leadId) {
                        console.error('Missing loanId or leadId');
                        showAlert('Invalid loan data. Please contact support.', 'danger');
                        return;
                    }

                    function round(value) {
                        return Math.round(value * 100) / 100;
                    }

                    function showAlert(message, type = 'success') {
                        const alertContainer = document.querySelector('.emi-main-content');
                        if (!alertContainer) {
                            console.warn('Alert container (.emi-main-content) not found.');
                            console.log(`[${type.toUpperCase()}] ${message}`);
                            return;
                        }
                        const alert = document.createElement('div');
                        alert.className = `emi-alert emi-alert-${type} fade-in`;
                        alert.setAttribute('role', 'alert');
                        alert.innerHTML = `
                            ${message}
                            <button type="button" class="emi-alert-close" aria-label="Close">×</button>
                        `;
                        alertContainer.insertBefore(alert, alertContainer.firstChild);
                        setTimeout(() => alert.remove(), 5000);
                    }

                    // Open Payment Modal
                    function openPaymentModal(emiNumber, expectedAmount, halfPaymentPaid = 0) {
                        const emiInput = document.getElementById('emiNumber');
                        const expectedInput = document.getElementById('expectedAmount');
                        const amountInput = document.getElementById('amount');
                        const note = document.getElementById('partialNote');

                        const totalAmount = parseFloat(expectedAmount);
                        const paid = parseFloat(halfPaymentPaid);
                        const due = (totalAmount - paid).toFixed(2);

                        emiInput.value = emiNumber;
                        expectedInput.value = totalAmount.toFixed(2);
                        amountInput.value = due;
                        amountInput.setAttribute('min', 0.01);

                        if (paid > 0) {
                            note.innerText = `₹${paid.toFixed(2)} already paid. Remaining due: ₹${due}`;
                            note.style.display = 'block';
                        } else {
                            note.innerText = '';
                            note.style.display = 'none';
                        }

                        const paymentMethod = document.getElementById('paymentMethod');
                        paymentMethod.selectedIndex = 0;
                        document.getElementById('cardDetails').classList.add('d-none');
                        document.getElementById('upiDetails').classList.add('d-none');
                        document.getElementById('cardNumber').value = '';
                        document.getElementById('upiId').value = '';

                        const paymentModal = new bootstrap.Modal(document.getElementById('paymentModal'), {
                            backdrop: 'static',
                            keyboard: true
                        });
                        paymentModal.show();
                    }

                    // View Toggle Logic
                    const viewToggle = document.getElementById('viewToggle');
                    const defaultTableView = document.getElementById('defaultTableView');
                    const lessorTableView = document.getElementById('lessorTableView');
                    const loadingOverlay = document.getElementById('loadingOverlay');

                    if (viewToggle && defaultTableView && lessorTableView && loadingOverlay) {
                        viewToggle.addEventListener('change', function () {
                            loadingOverlay.classList.remove('d-none');
                            setTimeout(() => {
                                if (this.value === 'default') {
                                    defaultTableView.style.display = 'block';
                                    lessorTableView.style.display = 'none';
                                } else {
                                    defaultTableView.style.display = 'none';
                                    lessorTableView.style.display = 'block';
                                }
                                loadingOverlay.classList.add('d-none');
                            }, 300);
                        });
                    } else {
                        console.warn('View toggle components missing');
                    }

                    // EMI Payment Modal
                    const paymentModal = document.getElementById('paymentModal');
                    const paymentForm = document.getElementById('paymentForm');
                    const paymentMethod = document.getElementById('paymentMethod');
                    const cardDetails = document.getElementById('cardDetails');
                    const upiDetails = document.getElementById('upiDetails');
                    const cardNumber = document.getElementById('cardNumber');
                    const upiId = document.getElementById('upiId');
                    const paymentSubmitBtn = document.querySelector('button[form="paymentForm"]');
                    const amountInput = document.getElementById('amount');
                    const expectedAmountInput = document.getElementById('expectedAmount');

                    if (!paymentModal || !paymentForm || !paymentMethod || !cardDetails || !upiDetails || !cardNumber || !upiId || !paymentSubmitBtn || !amountInput || !expectedAmountInput) {
                        console.error('Payment form components are missing');
                        showAlert('Payment form setup incomplete. Contact support.', 'danger');
                        return;
                    }

                    document.querySelectorAll('.pay-btn').forEach(button => {
                        button.addEventListener('click', function (e) {
                            e.preventDefault();
                            const emiNumber = this.getAttribute('data-emi-number');
                            const loanId = this.getAttribute('data-loan-id');
                            const expectedAmount = parseFloat(this.getAttribute('data-expected-amount')) || 0;
                            const halfPaymentPaid = parseFloat(this.getAttribute('data-half-payment-paid')) || 0;
                            openPaymentModal(emiNumber, expectedAmount, halfPaymentPaid);
                        });
                    });

                    paymentMethod.addEventListener('change', function () {
                        cardDetails.classList.add('d-none');
                        upiDetails.classList.add('d-none');
                        cardNumber.classList.remove('is-invalid');
                        upiId.classList.remove('is-invalid');
                        cardNumber.value = '';
                        upiId.value = '';

                        if (this.value === 'credit_card' || this.value === 'debit_card') {
                            cardDetails.classList.remove('d-none');
                        } else if (this.value === 'upi') {
                            upiDetails.classList.remove('d-none');
                        }
                    });

                    paymentForm.addEventListener('submit', function (e) {
                        e.preventDefault();

                        let isValid = true;

                        if (!paymentMethod.value) {
                            paymentMethod.classList.add('is-invalid');
                            isValid = false;
                        } else {
                            paymentMethod.classList.remove('is-invalid');
                        }

                        if ((paymentMethod.value === 'credit_card' || paymentMethod.value === 'debit_card') && !/^\d{16}$/.test(cardNumber.value)) {
                            cardNumber.classList.add('is-invalid');
                            isValid = false;
                        } else {
                            cardNumber.classList.remove('is-invalid');
                        }

                        if (paymentMethod.value === 'upi' && !/^[a-zA-Z0-9.\-_]{2,256}@[a-zA-Z]{2,64}$/.test(upiId.value)) {
                            upiId.classList.add('is-invalid');
                            isValid = false;
                        } else {
                            upiId.classList.remove('is-invalid');
                        }

                        const amount = parseFloat(amountInput.value);
                        const expectedAmount = parseFloat(expectedAmountInput.value);
                        if (isNaN(amount) || amount < 0.01) {
                            amountInput.classList.add('is-invalid');
                            isValid = false;
                        } else {
                            amountInput.classList.remove('is-invalid');
                        }

                        if (!isValid) {
                            showAlert('Please correct the errors in the form.', 'danger');
                            return;
                        }

                        if (amount < expectedAmount) {
                            if (!confirm(`The entered amount (₹${amount.toFixed(2)}) is less than the expected amount (₹${expectedAmount.toFixed(2)}). This will be recorded as a partial payment, and the EMI will remain unpaid. Proceed?`)) {
                                return;
                            }
                        }

                        const formData = new FormData(paymentForm);

                        paymentSubmitBtn.disabled = true;
                        paymentSubmitBtn.textContent = 'Processing...';

                        fetch(paymentForm.action, {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Accept': 'application/json'
                            }
                        })
                        .then(response => {
                            if (!response.ok) {
                                return response.text().then(text => {
                                    throw new Error(`Server responded with status ${response.status}: ${text}`);
                                });
                            }
                            return response.json();
                        })
                        .then(data => {
                            if (data.success) {
                                const emiNumber = formData.get('emi_number');
                                const paymentRow = [...document.querySelectorAll('#defaultTableView .emi-table tbody tr')].find(row =>
                                    row.querySelector('td:nth-child(2)').textContent === emiNumber);
                                if (paymentRow) {
                                    paymentRow.querySelector('td:nth-child(7)').textContent = data.status;
                                    paymentRow.querySelector('td:nth-child(4)').textContent = `₹${data.paid_amount.toLocaleString('en-IN')}`;
                                    paymentRow.querySelector('td:nth-child(10)').textContent = `₹${(data.rest_amount || 0).toLocaleString('en-IN')}`;
                                    if (data.status === 'Paid') {
                                        const btn = paymentRow.querySelector('.pay-btn');
                                        btn.disabled = true;
                                        btn.textContent = 'Paid';
                                        btn.classList.remove('emi-btn-danger');
                                        btn.classList.add('emi-btn-secondary');
                                    }
                                    paymentRow.classList.add('highlighted-row');
                                    setTimeout(() => paymentRow.classList.remove('highlighted-row'), 2000);
                                }

                                if (data.loanDetails) {
                                    const list = document.querySelector('section[aria-labelledby="loan-details-title"] ul');
                                    if (list) {
                                        const map = {
                                            'Amount Paid': `₹${data.loanDetails.amountPaid.toLocaleString('en-IN')}`,
                                            'Amount Unpaid': `₹${data.loanDetails.amountUnpaid.toLocaleString('en-IN')}`,
                                            'Pending Principal': `₹${data.loanDetails.unpaidPrincipal.toLocaleString('en-IN')}`,
                                            'Unpaid Interest': `₹${data.loanDetails.unpaidInterest.toLocaleString('en-IN')}`,
                                            'Total Penalty': `₹${data.loanDetails.totalPenalty.toLocaleString('en-IN')}`,
                                            'Paid Penalty': `₹${data.loanDetails.paidPenalty.toLocaleString('en-IN')}`,
                                            'Remaining Penalty': `₹${data.loanDetails.remainingPenalty.toLocaleString('en-IN')}`,
                                            'Total Amount': `₹${data.loanDetails.totalAmount.toLocaleString('en-IN')}`,
                                            'Interest': `₹${data.loanDetails.totalInterest.toLocaleString('en-IN')}`,
                                            'Status': data.loanDetails.status
                                        };
                                        list.querySelectorAll('li').forEach(li => {
                                            for (const label in map) {
                                                if (li.textContent.includes(label)) {
                                                    li.innerHTML = `<strong>${label}:</strong> ${map[label]}`;
                                                }
                                            }
                                        });
                                    }
                                }

                                showAlert(data.message);
                                bootstrap.Modal.getInstance(paymentModal).hide();
                                paymentForm.reset();
                                cardDetails.classList.add('d-none');
                                upiDetails.classList.add('d-none');
                            } else {
                                showAlert(data.message || 'Payment failed.', 'danger');
                            }
                        })
                        .catch(error => {
                            console.error('Payment error:', error);
                            showAlert(error.message || 'Payment processing error. Please contact support.', 'danger');
                        })
                        .finally(() => {
                            paymentSubmitBtn.disabled = false;
                            paymentSubmitBtn.textContent = 'Confirm Payment';
                        });
                    });

                    // Close Loan Modal
                    const closeLoanForm = document.getElementById('closeLoanForm');
                    const closePaymentMethod = document.getElementById('closePaymentMethod');
                    const closeCardDetails = document.getElementById('closeCardDetails');
                    const closeUpiDetails = document.getElementById('closeUpiDetails');
                    const closeCardNumber = document.getElementById('closeCardNumber');
                    const closeUpiId = document.getElementById('closeUpiId');
                    const closeSubmitBtn = document.querySelector('button[form="closeLoanForm"]');

                    if (!closeLoanForm || !closePaymentMethod || !closeCardDetails || !closeUpiDetails || !closeCardNumber || !closeUpiId || !closeSubmitBtn) {
                        console.error('Close loan form components are missing');
                        showAlert('Close loan form setup incomplete. Contact support.', 'danger');
                        return;
                    }

                    closePaymentMethod.addEventListener('change', function () {
                        closeCardDetails.classList.add('d-none');
                        closeUpiDetails.classList.add('d-none');
                        closeCardNumber.classList.remove('is-invalid');
                        closeUpiId.classList.remove('is-invalid');
                        closeCardNumber.value = '';
                        closeUpiId.value = '';

                        if (this.value === 'credit_card' || this.value === 'debit_card') {
                            closeCardDetails.classList.remove('d-none');
                        } else if (this.value === 'upi') {
                            closeUpiDetails.classList.remove('d-none');
                        }
                    });

                    closeLoanForm.addEventListener('submit', function (e) {
                        e.preventDefault();

                        let isValid = true;

                        if (!closePaymentMethod.value) {
                            closePaymentMethod.classList.add('is-invalid');
                            isValid = false;
                        } else {
                            closePaymentMethod.classList.remove('is-invalid');
                        }

                        if ((closePaymentMethod.value === 'credit_card' || closePaymentMethod.value === 'debit_card') && !/^\d{16}$/.test(closeCardNumber.value)) {
                            closeCardNumber.classList.add('is-invalid');
                            isValid = false;
                        } else {
                            closeCardNumber.classList.remove('is-invalid');
                        }

                        if (closePaymentMethod.value === 'upi' && !/^[a-zA-Z0-9.\-_]{2,256}@[a-zA-Z]{2,64}$/.test(closeUpiId.value)) {
                            closeUpiId.classList.add('is-invalid');
                            isValid = false;
                        } else {
                            closeUpiId.classList.remove('is-invalid');
                        }

                        const unpaidPrincipal = parseFloat(document.querySelector('input[name="unpaid_principal"]').value) || 0;
                        const nextMonthInterest = parseFloat(document.querySelector('input[name="next_month_interest"]').value) || 0;
                        const foreclosureCharges = parseFloat(document.querySelector('input[name="foreclosure_charges"]').value) || 0;
                        const remainingPenalty = parseFloat(document.querySelector('input[name="remaining_penalty"]').value) || 0;
                        const unpaidInterest = parseFloat(document.querySelector('input[name="unpaid_interest"]').value) || 0;

                        const totalClosureAmount = round(unpaidPrincipal + nextMonthInterest + foreclosureCharges + remainingPenalty + unpaidInterest);
                        document.querySelector('input[name="closure_amount"]').value = totalClosureAmount;

                        if (!isValid) {
                            showAlert('Please correct the errors in the form.', 'danger');
                            return;
                        }

                        const formData = new FormData(closeLoanForm);

                        closeSubmitBtn.disabled = true;
                        closeSubmitBtn.textContent = 'Processing...';

                        fetch(closeLoanForm.action, {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Accept': 'application/json'
                            }
                        })
                        .then(response => {
                            if (!response.ok) {
                                return response.text().then(text => {
                                    throw new Error(`Server responded with status ${response.status}: ${text}`);
                                });
                            }
                            return response.json();
                        })
                        .then(data => {
                            if (data.success) {
                                showAlert('Loan closed successfully!');
                                bootstrap.Modal.getInstance(document.getElementById('closeLoanModal')).hide();
                                closeLoanForm.reset();
                                closeCardDetails.classList.add('d-none');
                                closeUpiDetails.classList.add('d-none');
                                window.location.reload();
                            } else {
                                showAlert(data.message || 'Loan closure failed.', 'danger');
                            }
                        })
                        .catch(error => {
                            console.error('Loan closure error:', error);
                            showAlert(error.message || 'Loan closure processing error. Please contact support.', 'danger');
                        })
                        .finally(() => {
                            closeSubmitBtn.disabled = false;
                            closeSubmitBtn.textContent = 'Confirm Closure';
                        });
                    });

                    // Alert Close Functionality
                    document.addEventListener('click', function (e) {
                        if (e.target.classList.contains('emi-alert-close')) {
                            e.target.parentElement.remove();
                        }
                    });

                    // Update Closure Amount Dynamically
                    const closeLoanInputs = document.querySelectorAll('#closeLoanForm input[name="unpaid_principal"], #closeLoanForm input[name="next_month_interest"], #closeLoanForm input[name="foreclosure_charges"], #closeLoanForm input[name="remaining_penalty"], #closeLoanForm input[name="unpaid_interest"]');
                    closeLoanInputs.forEach(input => {
                        input.addEventListener('input', function () {
                            const unpaidPrincipal = parseFloat(document.querySelector('input[name="unpaid_principal"]').value) || 0;
                            const nextMonthInterest = parseFloat(document.querySelector('input[name="next_month_interest"]').value) || 0;
                            const foreclosureCharges = parseFloat(document.querySelector('input[name="foreclosure_charges"]').value) || 0;
                            const remainingPenalty = parseFloat(document.querySelector('input[name="remaining_penalty"]').value) || 0;
                            const unpaidInterest = parseFloat(document.querySelector('input[name="unpaid_interest"]').value) || 0;
                            document.querySelector('input[name="closure_amount"]').value = round(unpaidPrincipal + nextMonthInterest + foreclosureCharges + remainingPenalty + unpaidInterest);
                        });
                    });
                });
            </script>
        @endif
    </main>
@endsection