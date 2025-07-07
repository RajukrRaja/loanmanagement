@extends('superadmin.superadmin')

@section('title', 'EMI Calculator')

@section('sidebar')
    @include('superadmin.superadminSidebar')
@endsection

@section('styles')
<style>
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background: #f9fafc;
        color: #4670a0;
        margin: 0;
        padding: 0;
    }
    .form-table {
        display: flex;
        gap: 20px;
        margin: 20px auto;
        max-width: 1200px;
        background: #fff;
        padding: 20px;
        border: 1px solid #cbd5e0;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        flex-wrap: wrap;
    }
    .form-column {
        width: 48%;
        min-width: 300px;
    }
    .form-cell {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 12px;
        border: 1px solid #e2e8f0;
        border-radius: 4px;
        padding: 10px;
    }
    .form-label {
        font-weight: 600;
        font-size: 15px;
        color: #1a202c;
        margin-right: 10px;
        flex: 1;
        white-space: nowrap;
    }
    .form-control-sm {
        width: 120px;
        text-align: right;
        font-size: 15px;
        font-weight: 600;
        color: #2d3748;
        border: 1px solid #cbd5e0;
        border-radius: 4px;
        padding: 6px 10px;
        -moz-appearance: textfield;
    }
    .form-control-sm::-webkit-outer-spin-button,
    .form-control-sm::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
    .form-control-sm[readonly] {
        background-color: #edf2f7;
        border: none;
        cursor: not-allowed;
    }
    .total-row {
        border-top: 2px solid #cbd5e0;
        padding-top: 12px;
        font-size: 18px;
        font-weight: 700;
    }
    .btn-container {
        margin-top: 30px;
        max-width: 800px;
        margin-left: auto;
        margin-right: auto;
    }
    .input-wrapper {
        display: flex;
        flex-direction: column;
        align-items: center;
        margin-bottom: 20px;
        max-width: 600px;
        width: 100%;
        margin-left: auto;
        margin-right: auto;
    }
    .input-wrapper.hidden {
        display: none;
    }
    .reason-input {
        width: 100%;
        min-height: 80px;
        resize: vertical;
        padding: 8px;
        font-size: 15px;
        border: 1px solid #cbd5e0;
        border-radius: 4px;
    }
    .centered-buttons {
        text-align: center;
    }
    .btn-sm {
        padding: 8px 16px;
        font-size: 15px;
        font-weight: bold;
        border: none;
        border-radius: 4px;
        margin: 5px;
        cursor: pointer;
        transition: background-color 0.3s;
    }
    .btn-primary {
        background-color: #38a169;
        color: white;
    }
    .btn-primary:hover {
        background-color: #2f855a;
    }
    .btn-primary:disabled {
        background-color: #a0aec0;
        cursor: not-allowed;
    }
    .btn-danger {
        background-color: #e53e3e;
        color: white;
    }
    .btn-danger:hover {
        background-color: #c53030;
    }
    .error {
        display: block;
        color: #e53e3e;
        font-size: 13px;
        margin-top: 4px;
        text-align: right;
    }
    @media (max-width: 768px) {
        .form-table {
            flex-direction: column;
        }
        .form-column {
            width: 100%;
        }
        .form-cell {
            flex-direction: column;
            align-items: flex-start;
        }
        .form-control-sm {
            width: 100%;
            text-align: left;
        }
        .error {
            text-align: left;
        }
        .reason-input {
            width: 100%;
        }
    }
</style>
@endsection

@section('content')
<main class="col-lg-10 col-md-9 ms-sm-auto px-md-4 py-4 main-content">
    <div class="d-flex justify-content-between align-items-center pb-2 mb-3 border-bottom">
        <div class="d-flex align-items-center gap-2">
            <button class="btn btn-outline-primary sidebar-toggle" id="sidebarToggle" aria-label="Toggle Sidebar">
                <i class="fas fa-bars"></i>
            </button>
            <h2 class="h4 fw-bold mb-0">EMI Calculator</h2>
        </div>
    </div>

    {{-- Show Errors --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Form has errors:</strong>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Success/Error messages --}}
    @if (session('error'))
        <div class="alert alert-danger text-center">{{ session('error') }}</div>
    @endif

    @if (session('success'))
        <div class="alert alert-success text-center">{{ session('success') }}</div>
    @endif

    <form id="loanEmiCalculatorForm" action="{{ route('admin.approveEmiForEnach', $loan->lead_id) }}" method="POST">
        @csrf
        <input type="hidden" name="loan_id" value="{{ $loan->loan_id }}">

        {{-- Calculated Hidden Fields --}}
        <input type="hidden" id="processing_fees_hidden" name="processing_fees" value="{{ round($loan->processing_fees ?? 0, 2) }}">
        <input type="hidden" id="gst_amount_hidden" name="gst_amount" value="{{ round($loan->gst_amount ?? 0, 2) }}">
        <input type="hidden" id="insurance_charges_hidden" name="insurance_charges" value="{{ round($loan->insurance_charges ?? 0, 2) }}">
        <input type="hidden" id="credit_report_charges_hidden" name="credit_report_charges" value="{{ round($loan->credit_report_charges ?? 0, 2) }}">
        <input type="hidden" id="disbursement_amount_hidden" name="disbursement_amount" value="{{ round($loan->disbursement_amount ?? 0, 2) }}">

        <div class="form-table d-flex">
            {{-- Left Form --}}
            <div class="form-column me-4">
                @php
                    $leftFields = [
                        'approved_loan_amount' => ['label' => 'Loan Amount', 'required' => true],
                        'processing_fees' => ['label' => 'Processing Fees', 'required' => true],
                        'insurance_charges' => ['label' => 'Insurance Charges', 'required' => false],
                        'interest_rate' => ['label' => 'Interest Rate (%)', 'required' => true],
                        'total_interest' => ['label' => 'Total Interest', 'readonly' => true],
                        'total_payment' => ['label' => 'Total Loan', 'readonly' => true],
                    ];
                @endphp
                @foreach ($leftFields as $field => $config)
                    <div class="form-cell mb-3">
                        <label class="form-label" for="{{ $field }}">{{ $config['label'] }}</label>
                        <input type="number"
                            class="form-control form-control-sm"
                            id="{{ $field }}"
                            name="{{ $field }}"
                            value="{{ old($field, round($loan->$field ?? 0, 2)) }}"
                            {{ $config['readonly'] ?? false ? 'readonly' : '' }}
                            {{ $config['required'] ?? false ? 'required' : '' }}
                            min="{{ $field === 'approved_loan_amount' ? 1 : ($field === 'interest_rate' ? 0.01 : 0) }}"
                            step="0.01">
                        <span id="{{ $field }}Error" class="error"></span>
                    </div>
                @endforeach
            </div>

            {{-- Right Form --}}
            <div class="form-column">
                @php
                    $rightFields = [
                        'tenure_months' => ['label' => 'Tenure (Months)', 'required' => true],
                        'credit_report_charges' => ['label' => 'Credit Report Charges', 'required' => false],
                        'gst_on_processing_fees' => ['label' => 'GST on Processing Fees (%)', 'required' => true],
                        'emi' => ['label' => 'EMI', 'readonly' => true],
                        'disbursement_amount' => ['label' => 'Disbursement Amount', 'readonly' => true],
                    ];
                @endphp
                @foreach ($rightFields as $field => $config)
                    <div class="form-cell mb-3">
                        <label class="form-label" for="{{ $field }}">{{ $config['label'] }}</label>
                        <input type="number"
                            class="form-control form-control-sm"
                            id="{{ $field }}"
                            name="{{ $field }}"
                            value="{{ old($field, round($loan->$field ?? 0, 2)) }}"
                            {{ $config['readonly'] ?? false ? 'readonly' : '' }}
                            {{ $config['required'] ?? false ? 'required' : '' }}
                            min="{{ $field === 'tenure_months' ? 1 : 0 }}"
                            step="0.01">
                        <span id="{{ $field }}Error" class="error"></span>
                    </div>
                @endforeach

                {{-- Calculation Method --}}
                <div class="form-cell mb-3">
                    <label class="form-label" for="calculation_method">Calculation Method</label>
                    <select class="form-control form-control-sm" id="calculation_method" name="calculation_method" required>
                        <option value="flat" {{ old('calculation_method', $loan->calculation_method ?? 'flat') === 'flat' ? 'selected' : '' }}>Flat Rate</option>
                        <option value="reduce" {{ old('calculation_method', $loan->calculation_method ?? 'flat') === 'reduce' ? 'selected' : '' }}>Reducing Balance</option>
                    </select>
                    <span id="calculation_methodError" class="error"></span>
                </div>
            </div>
        </div>

        {{-- Rejection Reason --}}
        <div class="input-wrapper my-3 hidden">
            <label class="form-label" for="rejection_reason">Rejection Reason</label>
            <textarea class="form-control reason-input"
                id="rejection_reason"
                name="rejection_reason"
                placeholder="Enter reason for rejection"
                rows="3">{{ old('rejection_reason', $loan->rejection_reason ?? '') }}</textarea>
            <span id="rejection_reasonError" class="error"></span>
        </div>

        {{-- Submit Buttons --}}
        <div class="centered-buttons text-center btn-container">
            @if ($loan->bank_verification_status === 'Verified')
                <button type="submit" class="btn btn-sm btn-primary me-2" name="action" value="approve">Approve</button>
            @else
                <button type="submit" class="btn btn-sm btn-primary me-2" name="action" value="approve" disabled>Approve</button>
                <p class="text-danger mt-2">Approval disabled until bank is verified.</p>
            @endif
            <button type="submit" class="btn btn-sm btn-danger" name="action" value="reject">Reject</button>
        </div>
    </form>
</main>

{{-- JS --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const approveBtn = document.querySelector('button[name="action"][value="approve"]');
        const rejectBtn = document.querySelector('button[name="action"][value="reject"]');
        const reasonBox = document.querySelector('.input-wrapper');

        // Toggle rejection reason
        if (approveBtn && rejectBtn && reasonBox) {
            approveBtn.addEventListener('click', () => reasonBox.classList.add('hidden'));
            rejectBtn.addEventListener('click', () => {
                reasonBox.classList.remove('hidden');
                document.getElementById('rejection_reason').focus();
            });
        }

        // Auto-calculate GST + Disbursement
        const fields = ['approved_loan_amount', 'processing_fees', 'gst_on_processing_fees', 'insurance_charges', 'credit_report_charges'];
        fields.forEach(id => {
            const el = document.getElementById(id);
            if (el) el.addEventListener('input', updateCalculatedFields);
        });

        function updateCalculatedFields() {
            const loanAmt = parseFloat(document.getElementById('approved_loan_amount').value) || 0;
            const procFee = parseFloat(document.getElementById('processing_fees').value) || 0;
            const gstRate = parseFloat(document.getElementById('gst_on_processing_fees').value) || 0;
            const insCharge = parseFloat(document.getElementById('insurance_charges').value) || 0;
            const creditReport = parseFloat(document.getElementById('credit_report_charges').value) || 0;

            const gstAmt = Number((procFee * gstRate / 100).toFixed(2));
            const disbAmt = Number((procFee + gstAmt + insCharge + creditReport).toFixed(2));

            document.getElementById('gst_amount').value = gstAmt;
            document.getElementById('disbursement_amount').value = disbAmt;
            document.getElementById('gst_amount_hidden').value = gstAmt;
            document.getElementById('disbursement_amount_hidden').value = disbAmt;
        }

        updateCalculatedFields();
    });
</script>
@endsection


@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const get = id => document.getElementById(id);
    const setError = (id, message) => {
        const errorElement = get(`${id}Error`);
        if (errorElement) {
            errorElement.textContent = message;
            errorElement.style.display = message ? 'block' : 'none';
        }
    };

    const validateInput = (id, value, min, required) => {
        if (required && (value === '' || isNaN(value))) {
            setError(id, `${get(id).previousElementSibling.textContent} is required.`);
            return false;
        }
        if (min !== undefined && value < min) {
            setError(id, `${get(id).previousElementSibling.textContent} must be at least ${min}.`);
            return false;
        }
        setError(id, '');
        return true;
    };

    const calculateEMI = () => {
        const inputs = [
            { id: 'approved_loan_amount', min: 1, required: true },
            { id: 'interest_rate', min: 0.01, required: true },
            { id: 'tenure_months', min: 1, required: true },
            { id: 'processing_fees', min: 0, required: true },
            { id: 'gst_on_processing_fees', min: 0, required: true },
            { id: 'insurance_charges', min: 0, required: false },
            { id: 'credit_report_charges', min: 0, required: false },
        ];

        let isValid = true;
        inputs.forEach(input => {
            const value = parseFloat(get(input.id)?.value) || 0;
            if (!validateInput(input.id, value, input.min, input.required)) {
                isValid = false;
            }
        });

        if (!isValid) return;

        const loanAmount = parseFloat(get('approved_loan_amount').value) || 0;
        const interestRate = parseFloat(get('interest_rate').value) || 0;
        const tenure = parseInt(get('tenure_months').value) || 0;
        const processingFees = parseFloat(get('processing_fees').value) || 0;
        const gstRate = parseFloat(get('gst_on_processing_fees').value) || 0;
        const insurance = parseFloat(get('insurance_charges').value) || 0;
        const creditReport = parseFloat(get('credit_report_charges').value) || 0;
        const method = get('calculation_method').value;

        if (processingFees > loanAmount) {
            setError('processing_fees', 'Processing fees cannot exceed loan amount.');
            isValid = false;
        }

        let emi = 0, totalPayment = 0, totalInterest = 0;
        if (method === 'reduce') {
            const monthlyRate = interestRate / 12 / 100;
            const power = Math.pow(1 + monthlyRate, tenure);
            if (power <= 1) {
                setError('interest_rate', 'Invalid interest rate or tenure for reducing balance method.');
                isValid = false;
            } else {
                emi = (loanAmount && tenure) ? (loanAmount * monthlyRate * power) / (power - 1) : 0;
                emi = Number(emi.toFixed(2));
                totalPayment = Number((emi * tenure).toFixed(2));
                totalInterest = Number((totalPayment - loanAmount).toFixed(2));
            }
        } else {
            totalInterest = Number(((loanAmount * interestRate * tenure) / 1200).toFixed(2));
            totalPayment = Number((loanAmount + totalInterest).toFixed(2));
            emi = tenure ? Number((totalPayment / tenure).toFixed(2)) : 0;
        }

        const gstAmount = Number((processingFees * gstRate / 100).toFixed(2));
        const totalCharges = processingFees + gstAmount + insurance + creditReport;
        const disbursed = Number((loanAmount - totalCharges).toFixed(2));

        if (disbursed < 0) {
            setError('disbursement_amount', 'Disbursement amount cannot be negative.');
            isValid = false;
        }

        get('emi').value = isFinite(emi) ? emi.toFixed(2) : '';
        get('total_interest').value = isFinite(totalInterest) ? totalInterest.toFixed(2) : '';
        get('total_payment').value = isFinite(totalPayment) ? totalPayment.toFixed(2) : '';
        get('disbursement_amount').value = isFinite(disbursed) ? disbursed.toFixed(2) : '';

        get('processing_fees_hidden').value = isFinite(processingFees) ? processingFees.toFixed(2) : '';
        get('gst_amount_hidden').value = isFinite(gstAmount) ? gstAmount.toFixed(2) : '';
        get('insurance_charges_hidden').value = isFinite(insurance) ? insurance.toFixed(2) : '';
        get('credit_report_charges_hidden').value = isFinite(creditReport) ? creditReport.toFixed(2) : '';
        get('disbursement_amount_hidden').value = isFinite(disbursed) ? disbursed.toFixed(2) : '';

        const approveButton = document.querySelector('button[value="approve"]');
        const rejectButton = document.querySelector('button[value="reject"]');
        if (approveButton) approveButton.disabled = !isValid;
        if (rejectButton) rejectButton.disabled = !isValid;
    };

    const reasonInputWrapper = get('rejection_reason')?.closest('.input-wrapper');
    const reasonInput = get('rejection_reason');
    const form = get('loanEmiCalculatorForm');
    const rejectButton = document.querySelector('button[value="reject"]');

    if (rejectButton) {
        rejectButton.addEventListener('click', () => {
            if (reasonInputWrapper) {
                reasonInputWrapper.classList.remove('hidden');
                reasonInput.focus();
            }
        });
    }

    if (form) {
        form.addEventListener('submit', (e) => {
            const action = e.submitter?.value;
            if (action === 'reject' && reasonInput && !reasonInput.value.trim()) {
                e.preventDefault();
                setError('rejection_reason', 'Rejection reason is required.');
                reasonInput.focus();
            } else {
                setError('rejection_reason', '');
            }
            if (!validateForm()) {
                e.preventDefault();
                console.log('Form submission prevented due to validation errors.');
            }
        });
    }

    const validateForm = () => {
        let isValid = true;
        const inputs = [
            { id: 'approved_loan_amount', min: 1, required: true },
            { id: 'interest_rate', min: 0.01, required: true },
            { id: 'tenure_months', min: 1, required: true },
            { id: 'processing_fees', min: 0, required: true },
            { id: 'gst_on_processing_fees', min: 0, required: true },
            { id: 'insurance_charges', min: 0, required: false },
            { id: 'credit_report_charges', min: 0, required: false },
        ];

        inputs.forEach(input => {
            const value = parseFloat(get(input.id)?.value) || 0;
            if (!validateInput(input.id, value, input.min, input.required)) {
                isValid = false;
            }
        });

        const loanAmount = parseFloat(get('approved_loan_amount').value) || 0;
        const processingFees = parseFloat(get('processing_fees').value) || 0;
        const gstRate = parseFloat(get('gst_on_processing_fees').value) || 0;
        const insurance = parseFloat(get('insurance_charges').value) || 0;
        const creditReport = parseFloat(get('credit_report_charges').value) || 0;

        const gstAmount = Number((processingFees * gstRate / 100).toFixed(2));
        const totalCharges = processingFees + gstAmount + insurance + creditReport;
        const disbursed = Number((loanAmount - totalCharges).toFixed(2));

        if (processingFees > loanAmount) {
            setError('processing_fees', 'Processing fees cannot exceed loan amount.');
            isValid = false;
        }

        if (disbursed < 0) {
            setError('disbursement_amount', 'Disbursement amount cannot be negative.');
            isValid = false;
        }

        return isValid;
    };

    [
        'approved_loan_amount',
        'interest_rate',
        'tenure_months',
        'processing_fees',
        'gst_on_processing_fees',
        'insurance_charges',
        'credit_report_charges',
        'calculation_method'
    ].forEach(id => {
        const element = get(id);
        if (element) {
            element.addEventListener('input', calculateEMI);
        }
    });

    calculateEMI();
});
</script>
@endsection

