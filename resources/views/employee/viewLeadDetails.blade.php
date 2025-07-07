@extends('superadmin.superadmin')

@section('title', 'Lead Details - ' . e($lead->lead_id ?? 'N/A'))

@section('sidebar')
    @include('superadmin.superadminSidebar')
@endsection

@section('styles')
    <style>
        :root {
            --primary: #2563eb;
            --primary-dark: #1e40af;
            --success: #22c55e;
            --warning: #facc15;
            --danger: #ef4444;
            --info: #38bdf8;
            --purple: #8b5cf6;
            --teal: #14b8a6;
            --bg-light: #f9fafb;
            --bg-dark: #1e293b;
            --text-dark: #1e293b;
            --text-muted: #6b7280;
            --shadow: 0 6px 24px rgba(0,0,0,0.1);
            --shadow-sm: 0 2px 8px rgba(0,0,0,0.05);
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        [data-theme="dark"] {
            --bg-light: #1e293b;
            --bg-dark: #111827;
            --text-dark: #f1f5f9;
            --text-muted: #9ca3af;
        }
        body {
            background: var(--bg-light);
            font-family: 'Inter', sans-serif;
            color: var(--text-dark);
            transition: var(--transition);
            overflow-x: hidden;
        }
        .sidebar {
            min-height: 100vh;
            background: var(--bg-dark);
            color: #f1f5f9;
            box-shadow: 4px 0 20px rgba(0,0,0,0.2);
            transition: var(--transition);
            position: sticky;
            top: 0;
            width: 280px;
        }
        .sidebar .nav-link {
            color: #d1d5db;
            font-weight: 500;
            border-radius: 0.5rem;
            margin: 0.3rem 0.5rem;
            padding: 0.8rem 1rem;
            transition: var(--transition);
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        .sidebar .nav-link.active, .sidebar .nav-link:hover {
            color: #fff;
            background: linear-gradient(90deg, var(--primary) 60%, var(--primary-dark) 100%);
            box-shadow: var(--shadow-sm);
            transform: translateX(4px);
        }
        .profile-img {
            width: 80px;
            height: 80px;
            border-radius: 12px;
            border: 3px solid #fff;
            box-shadow: var(--shadow-sm);
            transition: var(--transition);
        }
        .profile-img:hover {
            transform: scale(1.05);
        }
        .card-stats {
            border-radius: 1rem;
            background: #fff;
            transition: var(--transition);
            box-shadow: var(--shadow);
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            width: 100%;
            min-height: 350px;
            box-sizing: border-box;
        }
        .card-stats:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 40px rgba(0,0,0,0.15);
        }
        .card-stats .card-body {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 1.5rem;
        }
        .img-thumbnail {
            max-width: 150px;
            margin: 5px;
            border-radius: 0.5rem;
        }
        .lead-details-row {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
        }
        .lead-details-row > div {
            flex: 1 1 100%;
            min-width: 0;
        }
        @media (min-width: 768px) {
            .lead-details-row > div {
                flex: 1 1 calc(50% - 0.5rem);
            }
        }
        @media (min-width: 992px) {
            .lead-details-row > div {
                flex: 1 1 calc(25% - 0.75rem);
            }
            .sidebar-toggle {
                display: none;
            }
        }
        @media (max-width: 991.98px) {
            .sidebar {
                position: fixed;
                left: -240px;
                width: 240px;
                z-index: 1040;
                transition: left 0.3s ease;
            }
            .sidebar.show {
                left: 0;
            }
            main {
                margin-left: 0 !important;
            }
        }
        .btn-loading {
            position: relative;
            pointer-events: none;
        }
        .btn-loading::after {
            content: '';
            position: absolute;
            width: 16px;
            height: 16px;
            border: 2px solid #fff;
            border-top: 2px solid transparent;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
            top: 50%;
            right: 10px;
            transform: translateY(-50%);
        }
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        .modal-content {
            border-radius: 1rem;
            box-shadow: var(--shadow);
            background: var(--bg-light);
        }
        .badge {
            font-size: 0.875rem;
            padding: 0.5em 1em;
        }
        .modal-body.preview-container {
            position: relative;
            min-height: 550px;
            padding: 1.5rem;
            display: flex;
            justify-content: center;
            align-items: center;
            background: var(--bg-light);
        }
        .preview-container > * {
            display: none;
            max-width: 100%;
            max-height: 500px;
            width: 100%;
            height: auto;
            border-radius: 0.5rem;
            box-shadow: var(--shadow-sm);
        }
        .preview-container > *.active {
            display: block;
        }
        #documentIframe {
            height: 500px;
            border: none;
            width: 100%;
        }
        .modal-footer {
            border-top: 1px solid var(--text-muted);
            padding: 1rem;
        }
        .form-control[readonly] {
            background-color: #e9ecef;
            opacity: 1;
        }
    </style>
@endsection

@section('content')
    @if(isset($lead) && is_object($lead))
        <main class="col-lg-10 col-md-9 ms-sm-auto px-md-4 py-4" role="main">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
                <div class="d-flex align-items-center gap-2">
                    <button class="btn btn-outline-primary sidebar-toggle" id="sidebarToggle" aria-label="Toggle Sidebar">
                        <i class="fas fa-bars"></i>
                    </button>
                    <h2 class="h3 mb-0 fw-bold">{{ e($lead->first_name . ' ' . ($lead->middle_name ? $lead->middle_name . ' ' : '') . $lead->last_name) }}'s Profile</h2>
                </div>
                <div class="d-flex align-items-center gap-2 flex-wrap">
                    <a href="" class="btn btn-secondary" aria-label="Back to Leads">
                        <i class="fas fa-arrow-left"></i> Back to Leads
                    </a>
                    @can('approve-kyc', 20)
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#approveKycModal" aria-label="Approve KYC">Approve KYC</button>
                    @endcan
                    @can('reject-kyc', 20)
                        <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#rejectKycModal" aria-label="Reject KYC">Reject KYC</button>
                    @endcan
                    <button class="btn theme-toggle" id="themeToggle" data-bs-toggle="tooltip" title="Toggle Theme" aria-label="Toggle Theme">
                        <i class="fas fa-moon"></i>
                    </button>
                </div>
            </div>

            <!-- Flash Messages -->
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Lead Details -->
            <div class="row lead-details-row">
                <div>
                    <div class="card card-stats shadow-sm">
                        <div class="card-body">
                            <h5 class="fw-bold">Personal Details</h5>
                            <p><strong>Name:</strong> {{ e($lead->first_name . ' ' . ($lead->middle_name ? $lead->middle_name . ' ' : '') . $lead->last_name) }}</p>
                            <p><strong>Email:</strong> {{ e($lead->email ?? 'N/A') }}</p>
                            <p><strong>Mobile:</strong> {{ e($lead->mobile_no ?? 'N/A') }}</p>
                         <p><strong>Date of Birth:</strong> 
    {{ $lead->date_of_birth ? \Carbon\Carbon::parse($lead->date_of_birth)->format('d M Y') : 'N/A' }}
</p>

                            <p><strong>Aadhaar Number:</strong> {{ e($lead->aadhar_number ?? 'N/A') }}</p>
                            <p><strong>PAN Number:</strong> {{ e($lead->pan_number ?? 'N/A') }}</p>
                        </div>
                    </div>
                </div>
                <div>
                    <div class="card card-stats shadow-sm">
                        <div class="card-body">
                            <h5 class="fw-bold">Address Information</h5>
                            <p><strong>Residential Address:</strong> {{ e($lead->residential_address ?? 'N/A') }}</p>
                            <p><strong>Office Address:</strong> {{ e($lead->office_address ?? 'N/A') }}</p>
                            <p><strong>Permanent Address:</strong> {{ e($lead->permanent_address ?? 'N/A') }}</p>
                            <p><strong>PIN Code:</strong> {{ e($lead->pin_code ?? 'N/A') }}</p>
                            <p><strong>State:</strong> {{ e($lead->state ?? 'N/A') }}</p>
                            <p><strong>District:</strong> {{ e($lead->district ?? 'N/A') }}</p>
                        </div>
                    </div>
                </div>
                <div>
                    <div class="card card-stats shadow-sm">
                        <div class="card-body">
                            <h5 class="fw-bold">Financial Information</h5>
                            <p><strong>Occupation Type:</strong> {{ e($lead->occupation_type ?? 'N/A') }}</p>
                            <p><strong>Monthly Income:</strong> ₹{{ number_format($lead->monthly_income ?? 0, 2) }}</p>
                            <p><strong>Loan Demand Amount:</strong> ₹{{ number_format($lead->loan_demand_amount ?? 0, 2) }}</p>
                            <p><strong>KYC Status:</strong>
                                <span class="badge bg-{{ $lead->kyc_status === 'Approved' ? 'success' : ($lead->kyc_status === 'Rejected' ? 'danger' : 'warning text-dark') }}">
                                    {{ e($lead->kyc_status ?? 'Pending') }}
                                </span>
                            </p>
                            <p><strong>eNACH Status:</strong> {{ e($lead->status_of_enach ?? 'N/A') }}</p>
                        </div>
                    </div>
                </div>
                <div>
                    <div class="card card-stats shadow-sm">
                        <div class="card-body">
                            <h5 class="fw-bold">Branch & Employee Details</h5>
                            <p><strong>Branch ID:</strong> {{ e($lead->branch_id ?? 'N/A') }}</p>
                            <p><strong>Branch Name:</strong> {{ e($lead->branch_name ?? 'N/A') }}</p>
                            <p><strong>Employee ID:</strong> {{ e($lead->employee_id ?? 'N/A') }}</p>
                            <p><strong>Employee Name:</strong> {{ e($lead->employee_name ?? 'N/A') }}</p>
                            <p><strong>Email:</strong> {{ e($lead->email ?? 'N/A') }}</p>
                            <p><strong>Mobile No:</strong> {{ e($lead->mobile_no ?? 'N/A') }}</p>
                            <p><strong>Alternate Mobile:</strong> {{ e($lead->alt_mobile_no ?? 'N/A') }}</p>
                            <p><strong>Updated By:</strong> {{ e($lead->updated_by ?? 'N/A') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="d-flex flex-wrap gap-2 mt-4">
                <a href="" class="btn btn-secondary" aria-label="Back to Leads">
                    <i class="fas fa-arrow-left"></i> Back to Leads
                </a>
                @can('view-lead', 20)
                    <button class="btn btn-outline-primary"
                            data-bs-toggle="modal"
                            data-bs-target="#viewDocumentModal"
                            data-document="{{ $lead->aadhar_card_image_front ? asset('storage/' . $lead->aadhar_card_image_front) : '' }}"
                            data-type="image"
                            aria-label="View Aadhaar Front">View Aadhaar Front</button>
                    <button class="btn btn-outline-primary"
                            data-bs-toggle="modal"
                            data-bs-target="#viewDocumentModal"
                            data-document="{{ $lead->aadhar_card_image_back ? asset('storage/' . $lead->aadhar_card_image_back) : '' }}"
                            data-type="image"
                            aria-label="View Aadhaar Back">View Aadhaar Back</button>
                    <button class="btn btn-outline-primary"
                            data-bs-toggle="modal"
                            data-bs-target="#viewDocumentModal"
                            data-document="{{ $lead->selfie_picture ? asset('storage/' . $lead->selfie_picture) : '' }}"
                            data-type="image"
                            aria-label="View Selfie">View Selfie</button>
                    <button class="btn btn-outline-primary"
                            data-bs-toggle="modal"
                            data-bs-target="#viewDocumentModal"
                            data-document="{{ $lead->shop_business_image ? asset('storage/' . $lead->shop_business_image) : '' }}"
                            data-type="image"
                            aria-label="View Shop/Business Image">View Shop/Business Image</button>
                    <button class="btn btn-outline-primary"
                            data-bs-toggle="modal"
                            data-bs-target="#viewDocumentModal"
                            data-document="{{ $lead->bank_statement_pdf_path ? asset('storage/' . $lead->bank_statement_pdf_path) : '' }}"
                            data-type="file"
                            aria-label="View Bank Statement">View Bank Statement</button>
                @endcan
                @can('approve-kyc', 20)
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#approveKycModal" aria-label="Approve KYC">Approve KYC</button>
                    <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#updateKycModal" aria-label="Update KYC">Update KYC</button>
                    <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#verifyBankModal" aria-label=" Bank Verify "> Bank Verify</button>
                    <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#approveLoanModal" aria-label="Approve Loans">Approve Loans</button>
                    <button class="btn btn-outline-primary disabled" aria-label="Assign Packages" disabled>Assign Packages</button>
                    <button class="btn btn-outline-primary disabled" aria-label="Mark Ongoing" disabled>Mark Ongoing</button>
                    <button class="btn btn-outline-primary disabled" aria-label="Mark Closed" disabled>Mark Closed</button>
                    <button class="btn btn-outline-primary disabled" aria-label="ENACH NUPAY" disabled>ENACH NUPAY</button>
                    <button class="btn btn-outline-primary disabled" aria-label="ENACH DGIO" disabled>ENACH DGIO</button>
                    <button class="btn btn-outline-primary disabled" aria-label="Upload Civil PDF" disabled>Upload Civil PDF</button>
                    <button class="btn btn-outline-primary disabled" aria-label="Add Grantor" disabled>Add Grantor</button>
                    <button class="btn btn-outline-primary disabled" aria-label="Add Co-Applicant" disabled>Add Co-Applicant</button>
                    <button class="btn btn-outline-primary disabled" aria-label="Check CIBIL" disabled>Check CIBIL</button>
                    <button class="btn btn-outline-primary disabled" aria-label="Check CIBIL Experience" disabled>Check CIBIL Experience</button>
                    <button class="btn btn-outline-primary disabled" aria-label="EMandate Status" disabled>EMandate Status</button>
                    <button class="btn btn-outline-primary disabled" aria-label="Upload Cheque" disabled>Upload Cheque</button>
                    <button class="btn btn-outline-primary disabled" aria-label="Upload Agreement" disabled>Upload Agreement</button>
                @endcan
                @can('reject-kyc', 20)
                    <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#rejectKycModal" aria-label="Reject KYC">Reject KYC</button>
                @endcan
            </div>

            <!-- Modals -->
            @can('approve-kyc', 20)
               <div class="modal fade" id="approveKycModal" tabindex="-1" aria-labelledby="approveKycModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('employee.approveKyc', ['id' => $lead->lead_id]) }}" id="approveKycForm">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="approveKycModalLabel">
                        Approve KYC for {{ e($lead->first_name . ' ' . ($lead->middle_name ? $lead->middle_name . ' ' : '') . $lead->last_name) }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Enter loan details for Lead ID: <strong>{{ e($lead->lead_id) }}</strong></p>

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ e($error) }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="mb-3">
                        <label for="approved_loan_amount" class="form-label">Approved Loan Amount (₹)</label>
                        <input type="number" class="form-control @error('approved_loan_amount') is-invalid @enderror"
                               id="approved_loan_amount" name="approved_loan_amount" min="0" step="0.01" required
                               value="{{ old('approved_loan_amount', $lead->approved_loan_amount ?? '') }}">
                        @error('approved_loan_amount')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label for="interest_rate" class="form-label">Interest Rate (%)</label>
                        <input type="number" class="form-control @error('interest_rate') is-invalid @enderror"
                               id="interest_rate" name="interest_rate" min="0" step="0.01" required
                               value="{{ old('interest_rate', $lead->interest_rate ?? '') }}">
                        @error('interest_rate')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label for="tenure_months_months" class="form-label">Tenure (Months)</label>
                        <input type="number" class="form-control @error('tenure_months') is-invalid @enderror"
                               id="tenure_months" name="tenure_months" min="1" required
                               value="{{ old('tenure_months', $lead->tenure_months ?? '') }}">
                        @error('tenure_months')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label for="processing_fees" class="form-label">Processing Fees (₹)</label>
                        <input type="number" class="form-control @error('processing_fees') is-invalid @enderror"
                               id="processing_fees" name="processing_fees" min="0" step="0.01"
                               value="{{ old('processing_fees', $lead->processing_fees ?? '') }}">
                        @error('processing_fees')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label for="interest_type" class="form-label">Interest Type</label>
                        <select class="form-control @error('interest_type') is-invalid @enderror"
                                id="interest_type" name="interest_type" required>
                            <option value="" disabled {{ old('interest_type') ? '' : 'selected' }}>Select Interest Type</option>
                            <option value="reduce" {{ old('interest_type', $lead->interest_type) == 'reduce' ? 'selected' : '' }}>Reducing</option>
                            <option value="flat" {{ old('interest_type', $lead->interest_type) == 'flat' ? 'selected' : '' }}>Flat</option>
                        </select>
                        @error('interest_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="approveKycSubmit">Approve KYC</button>
                </div>
            </div>
        </form>
    </div>
</div>

                <div class="modal fade" id="updateKycModal" tabindex="-1" aria-labelledby="updateKycModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <form method="POST" action="{{ route('employee.updateKyc', ['id' => $lead->lead_id]) }}" id="updateKycForm" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="updateKycModalLabel">
                                        Update KYC for {{ e($lead->first_name . ' ' . ($lead->middle_name ? $lead->middle_name . ' ' : '') . $lead->last_name) }}
                                    </h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    @if ($errors->any())
                                        <div class="alert alert-danger">
                                            <ul class="mb-0">
                                                @foreach ($errors->all() as $error)
                                                    <li>{{ e($error) }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif
                                    <div class="row g-3">
                                        <!-- Basic Details -->
                                        <div class="col-md-6">
                                            <label class="form-label">First Name <span class="text-danger">*</span></label>
                                            <input type="text" name="first_name" class="form-control @error('first_name') is-invalid @enderror" value="{{ old('first_name', $lead->first_name) }}" required>
                                            @error('first_name')
                                                <div class="invalid-feedback">{{ e($message) }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Last Name <span class="text-danger">*</span></label>
                                            <input type="text" name="last_name" class="form-control @error('last_name') is-invalid @enderror" value="{{ old('last_name', $lead->last_name) }}" required>
                                            @error('last_name')
                                                <div class="invalid-feedback">{{ e($message) }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Mobile Number <span class="text-danger">*</span></label>
                                            <input type="text" name="mobile_no" class="form-control @error('mobile_no') is-invalid @enderror" value="{{ old('mobile_no', $lead->mobile_no) }}" pattern="[0-9]{10}" maxlength="10" required>
                                            @error('mobile_no')
                                                <div class="invalid-feedback">{{ e($message) }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Email</label>
                                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $lead->email) }}">
                                            @error('email')
                                                <div class="invalid-feedback">{{ e($message) }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Date of Birth</label>
                                            <input type="date" name="date_of_birth" class="form-control @error('date_of_birth') is-invalid @enderror" value="{{ old('date_of_birth', $lead->date_of_birth) }}">
                                            @error('date_of_birth')
                                                <div class="invalid-feedback">{{ e($message) }}</div>
                                            @enderror
                                        </div>
                                        <!-- Identification Details -->
                                        <div class="col-md-6">
                                            <label class="form-label">PAN Number</label>
                                            <input type="text" name="pan_number" class="form-control @error('pan_number') is-invalid @enderror" value="{{ old('pan_number', $lead->pan_number) }}" pattern="[A-Z]{5}[0-9]{4}[A-Z]{1}" maxlength="10">
                                            @error('pan_number')
                                                <div class="invalid-feedback">{{ e($message) }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Aadhaar Number</label>
                                            <input type="text" name="aadhar_number" class="form-control @error('aadhar_number') is-invalid @enderror" value="{{ old('aadhar_number', $lead->aadhar_number) }}" pattern="[0-9]{12}" maxlength="12">
                                            @error('aadhar_number')
                                                <div class="invalid-feedback">{{ e($message) }}</div>
                                            @enderror
                                        </div>
                                        <!-- Address Details -->
                                        <div class="col-md-6">
                                            <label class="form-label">Residential Address</label>
                                            <textarea name="residential_address" class="form-control @error('residential_address') is-invalid @enderror">{{ old('residential_address', $lead->residential_address) }}</textarea>
                                            @error('residential_address')
                                                <div class="invalid-feedback">{{ e($message) }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Office Address</label>
                                            <textarea name="office_address" class="form-control @error('office_address') is-invalid @enderror">{{ old('office_address', $lead->office_address) }}</textarea>
                                            @error('office_address')
                                                <div class="invalid-feedback">{{ e($message) }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Permanent Address</label>
                                            <textarea name="permanent_address" class="form-control @error('permanent_address') is-invalid @enderror">{{ old('permanent_address', $lead->permanent_address) }}</textarea>
                                            @error('permanent_address')
                                                <div class="invalid-feedback">{{ e($message) }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">PIN Code</label>
                                            <input type="text" name="pin_code" class="form-control @error('pin_code') is-invalid @enderror" value="{{ old('pin_code', $lead->pin_code) }}" pattern="[0-9]{6}" maxlength="6">
                                            @error('pin_code')
                                                <div class="invalid-feedback">{{ e($message) }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">State</label>
                                            <select name="state" class="form-select @error('state') is-invalid @enderror">
                                                <option value="">Select State</option>
                                                <option value="Maharashtra" {{ old('state', $lead->state) == 'Maharashtra' ? 'selected' : '' }}>Maharashtra</option>
                                                <option value="Karnataka" {{ old('state', $lead->state) == 'Karnataka' ? 'selected' : '' }}>Karnataka</option>
                                                <option value="Tamil Nadu" {{ old('state', $lead->state) == 'Tamil Nadu' ? 'selected' : '' }}>Tamil Nadu</option>
                                            </select>
                                            @error('state')
                                                <div class="invalid-feedback">{{ e($message) }}</div>
                                            @enderror
                                        </div>
                                        <!-- Occupation & Income -->
                                        <div class="col-md-6">
                                            <label class="form-label">Occupation Type</label>
                                            <select name="occupation_type" class="form-select @error('occupation_type') is-invalid @enderror">
                                                <option value="">Select Occupation Type</option>
                                                <option value="Salaried" {{ old('occupation_type', $lead->occupation_type) == 'Salaried' ? 'selected' : '' }}>Salaried</option>
                                                <option value="Self-Employed" {{ old('occupation_type', $lead->occupation_type) == 'Self-Employed' ? 'selected' : '' }}>Self-Employed</option>
                                                <option value="Student" {{ old('occupation_type', $lead->occupation_type) == 'Student' ? 'selected' : '' }}>Student</option>
                                            </select>
                                            @error('occupation_type')
                                                <div class="invalid-feedback">{{ e($message) }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Monthly Income</label>
                                            <input type="number" name="monthly_income" class="form-control @error('monthly_income') is-invalid @enderror" value="{{ old('monthly_income', $lead->monthly_income) }}" min="0">
                                            @error('monthly_income')
                                                <div class="invalid-feedback">{{ e($message) }}</div>
                                            @enderror
                                        </div>
                                        <!-- Loan Requirement -->
                                        <div class="col-md-6">
                                            <label class="form-label">Loan Demand Amount</label>
                                            <input type="number" name="loan_demand_amount" class="form-control @error('loan_demand_amount') is-invalid @enderror" value="{{ old('loan_demand_amount', $lead->loan_demand_amount) }}" min="0">
                                            @error('loan_demand_amount')
                                                <div class="invalid-feedback">{{ e($message) }}</div>
                                            @enderror
                                        </div>
                                        <!-- Document Uploads -->
                                        <div class="col-md-6">
                                            <label class="form-label">Aadhaar Card Image (Front)</label>
                                            <input type="file" name="aadhar_card_image_front" class="form-control @error('aadhar_card_image_front') is-invalid @enderror" accept=".jpg,.jpeg,.png">
                                            @error('aadhar_card_image_front')
                                                <div class="invalid-feedback">{{ e($message) }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Aadhaar Card Image (Back)</label>
                                            <input type="file" name="aadhar_card_image_back" class="form-control @error('aadhar_card_image_back') is-invalid @enderror" accept=".jpg,.jpeg,.png">
                                            @error('aadhar_card_image_back')
                                                <div class="invalid-feedback">{{ e($message) }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">PAN Card Image</label>
                                            <input type="file" name="pan_card" class="form-control @error('pan_card') is-invalid @enderror" accept=".jpg,.jpeg,.png">
                                            @error('pan_card')
                                                <div class="invalid-feedback">{{ e($message) }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Selfie</label>
                                            <input type="file" name="selfie_picture" class="form-control @error('selfie_picture') is-invalid @enderror" accept=".jpg,.jpeg,.png" capture="user">
                                            @error('selfie_picture')
                                                <div class="invalid-feedback">{{ e($message) }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Shop/Business Image</label>
                                            <input type="file" name="shop_business_image" class="form-control @error('shop_business_image') is-invalid @enderror" accept=".jpg,.jpeg,.png">
                                            @error('shop_business_image')
                                                <div class="invalid-feedback">{{ e($message) }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Bank Statement (Optional)</label>
                                            <input type="file" name="bank_statement_pdf_path" class="form-control @error('bank_statement_pdf_path') is-invalid @enderror" accept=".pdf">
                                            @error('bank_statement_pdf_path')
                                                <div class="invalid-feedback">{{ e($message) }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-primary" id="updateKycSubmit">Update KYC</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="modal fade" id="verifyBankModal" tabindex="-1" aria-labelledby="verifyBankModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
<form method="POST" action="{{ route('employee.verifyBank', $lead->lead_id) }}" id="verifyBankForm" enctype="multipart/form-data">
    @csrf
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="verifyBankModalLabel">
                Verify Bank for {{ e($lead->first_name . ' ' . ($lead->middle_name ? $lead->middle_name . ' ' : '') . $lead->last_name) }}
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body">
            <p>Verify bank details for Lead ID: <strong>{{ e($lead->lead_id) }}</strong></p>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ e($error) }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="mb-3">
                <label for="account_holder_name" class="form-label">Name</label>
                <input type="text" class="form-control @error('account_holder_name') is-invalid @enderror" id="account_holder_name" name="account_holder_name" value="{{ old('account_holder_name', $lead->account_holder_name ?? '') }}" required>
                @error('account_holder_name')
                    <div class="invalid-feedback">{{ e($message) }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="bank_name" class="form-label">Bank Name</label>
                <input type="text" class="form-control @error('bank_name') is-invalid @enderror" id="bank_name" name="bank_name" value="{{ old('bank_name', $lead->bank_name ?? '') }}" required>
                @error('bank_name')
                    <div class="invalid-feedback">{{ e($message) }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="account_number" class="form-label">Account Number</label>
                <input type="text" class="form-control @error('account_number') is-invalid @enderror" id="account_number" name="account_number" value="{{ old('account_number', $lead->account_number ?? '') }}" required>
                @error('account_number')
                    <div class="invalid-feedback">{{ e($message) }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="confirm_account_number" class="form-label">Confirm Account Number</label>
                <input type="text" class="form-control @error('confirm_account_number') is-invalid @enderror" id="confirm_account_number" name="confirm_account_number" value="{{ old('confirm_account_number') }}" required>
                @error('confirm_account_number')
                    <div class="invalid-feedback">{{ e($message) }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="ifsc_code" class="form-label">IFSC Code</label>
                <input type="text" class="form-control @error('ifsc_code') is-invalid @enderror" id="ifsc_code" name="ifsc_code" value="{{ old('ifsc_code', $lead->ifsc_code ?? '') }}" maxlength="11" required>
                @error('ifsc_code')
                    <div class="invalid-feedback">{{ e($message) }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="verification_status" class="form-label">Verification Status</label>
                <select class="form-control @error('verification_status') is-invalid @enderror" id="verification_status" name="verification_status" required onchange="document.getElementById('rejection_reason_div').style.display = this.value === 'Rejected' ? 'block' : 'none';">
                    <option value="" disabled {{ old('verification_status') ? '' : 'selected' }}>Select Status</option>
                    <option value="Verified" {{ old('verification_status', $lead->bank_verification_status ?? '') == 'Verified' ? 'selected' : '' }}>Verified</option>
                    <option value="Pending" {{ old('verification_status', $lead->bank_verification_status ?? '') == 'Pending' ? 'selected' : '' }}>Pending</option>
                    <option value="Rejected" {{ old('verification_status', $lead->bank_verification_status ?? '') == 'Rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
                @error('verification_status')
                    <div class="invalid-feedback">{{ e($message) }}</div>
                @enderror
            </div>

            <div class="mb-3" id="rejection_reason_div" style="display: {{ old('verification_status', $lead->bank_verification_status ?? '') == 'Rejected' ? 'block' : 'none' }};">
                <label for="rejection_reason" class="form-label">Reason for Rejection</label>
                <textarea class="form-control @error('rejection_reason') is-invalid @enderror" id="rejection_reason" name="rejection_reason" rows="3">{{ old('rejection_reason', $lead->rejection_reason ?? '') }}</textarea>
                @error('rejection_reason')
                    <div class="invalid-feedback">{{ e($message) }}</div>
                @enderror
            </div>
        </div>

        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-primary" id="verifyBankSubmit">Verify Bank</button>
        </div>
    </div>
</form>

                    </div>
                </div>
                <div class="modal fade" id="approveLoanModal" tabindex="-1" aria-labelledby="approveLoanModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('employee.approveKyc', $lead->lead_id) }}" id="approveLoanForm">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="approveLoanModalLabel">
                        Approve Loan for <span id="leadName">{{ e($lead->full_name ?? '') }}</span>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ e($error) }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="mb-3">
                        <label for="approved_loan_amount" class="form-label">Approved Loan Amount (₹)</label>
                        <input type="number" class="form-control" name="approved_loan_amount" id="approved_loan_amount" min="0" step="0.01" required value="{{ old('approved_loan_amount', $lead->approved_loan_amount) }}">
                    </div>

                    <div class="mb-3">
                        <label for="interest_rate" class="form-label">Interest Rate (%)</label>
                        <input type="number" class="form-control" name="interest_rate" id="interest_rate" min="0" step="0.01" required value="{{ old('interest_rate', $lead->interest_rate) }}">
                    </div>

                    <div class="mb-3">
                        <label for="tenure_months" class="form-label">Tenure (Months)</label>
                        <input type="number" class="form-control" name="tenure_months" id="tenure_months" min="1" required value="{{ old('tenure_months', $lead->tenure_months) }}">
                    </div>

                    <div class="mb-3">
                        <label for="processing_fees" class="form-label">Processing Fees (₹)</label>
                        <input type="number" class="form-control" name="processing_fees" id="processing_fees" min="0" step="0.01" value="{{ old('processing_fees', $lead->processing_fees) }}">
                    </div>

                    <div class="mb-3">
                        <label for="gst_on_processing_fees" class="form-label">GST on Processing Fees (%)</label>
                        <input type="number" class="form-control" name="gst_on_processing_fees" id="gst_on_processing_fees" min="0" step="0.01" value="{{ old('gst_on_processing_fees', $lead->gst_on_processing_fees) }}">
                    </div>

                    <div class="mb-3">
                        <label for="insurance_charges" class="form-label">Insurance Charges (₹)</label>
                        <input type="number" class="form-control" name="insurance_charges" id="insurance_charges" min="0" step="0.01" value="{{ old('insurance_charges', $lead->insurance_charges) }}">
                    </div>

                    <div class="mb-3">
                        <label for="credit_report_charges" class="form-label">Credit Report Charges (₹)</label>
                        <input type="number" class="form-control" name="credit_report_charges" id="credit_report_charges" min="0" step="0.01" value="{{ old('credit_report_charges', $lead->credit_report_charges) }}">
                    </div>

                    <div class="mb-3">
                        <label for="disbursement_amount" class="form-label">Disbursement Amount (₹)</label>
                        <input type="number" class="form-control" name="disbursement_amount" id="disbursement_amount" min="0" step="0.01" value="{{ old('disbursement_amount', $lead->disbursement_amount) }}">
                    </div>

                    <div class="mb-3">
                        <label for="emi" class="form-label">EMI (₹)</label>
                        <input type="number" class="form-control" name="emi" id="emi" min="0" step="0.01" value="{{ old('emi', $lead->emi) }}">
                    </div>

                    <div class="mb-3">
                        <label for="total_payment" class="form-label">Total Payment (₹)</label>
                        <input type="number" class="form-control" name="total_payment" id="total_payment" min="0" step="0.01" value="{{ old('total_payment', $lead->total_payment) }}">
                    </div>

                    <div class="mb-3">
                        <label for="total_interest" class="form-label">Total Interest (₹)</label>
                        <input type="number" class="form-control" name="total_interest" id="total_interest" min="0" step="0.01" value="{{ old('total_interest', $lead->total_interest) }}">
                    </div>

                    <div class="mb-3">
                        <label for="interest_type" class="form-label">Interest Type</label>
                        <select name="interest_type" id="interest_type" class="form-control" required>
                            <option value="" disabled {{ old('interest_type') ? '' : 'selected' }}>Select Interest Type</option>
                            <option value="reduce" {{ old('interest_type', $lead->interest_type) == 'reduce' ? 'selected' : '' }}>Reducing</option>
                            <option value="flat" {{ old('interest_type', $lead->interest_type) == 'flat' ? 'selected' : '' }}>Flat</option>
                        </select>
                    </div>

                    <!-- Readonly Fields -->
                    <div class="mb-3">
                        <label class="form-label">Monthly Income (₹)</label>
                        <input type="text" class="form-control" value="{{ $lead->monthly_income ?? 'N/A' }}" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Loan Demand Amount (₹)</label>
                        <input type="text" class="form-control" value="{{ $lead->loan_demand_amount ?? 'N/A' }}" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">KYC Status</label>
                        <input type="text" class="form-control" value="{{ $lead->kyc_status ?? 'N/A' }}" readonly>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Approve Loan</button>
                </div>
            </div>
        </form>
    </div>
</div>

            @endcan

@can('reject-kyc', 20)
<div class="modal fade" id="rejectKycModal" tabindex="-1" aria-labelledby="rejectKycModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('employee.rejectKyc', ['id' => $lead->lead_id]) }}" id="rejectKycForm">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="rejectKycModalLabel">Reject KYC</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <p>
                        Are you sure you want to reject KYC for
                        <strong>{{ $lead->first_name }} {{ $lead->middle_name ? $lead->middle_name . ' ' : '' }}{{ $lead->last_name }}</strong>
                        (Lead ID: <strong>{{ $lead->lead_id }}</strong>)?
                    </p>

                    <div class="mb-3">
                        <label for="rejectionReason_{{ $lead->lead_id }}" class="form-label">Reason for Rejection</label>
                        <textarea
                            class="form-control @error('reason') is-invalid @enderror"
                            id="rejectionReason_{{ $lead->lead_id }}"
                            name="reason"
                            rows="3"
                            required
                        >{{ old('reason', $lead->rejection_reason ?? '') }}</textarea>
                        @error('reason')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Reject</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endcan


            @can('view-lead', 20)
                <div class="modal fade" id="viewDocumentModal" tabindex="-1" aria-labelledby="viewDocumentModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="viewDocumentModalLabel">Document Preview</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body preview-container">
                                <img id="documentImage" src="" alt="Document Preview">
                                <iframe id="documentIframe" title="Document Preview"></iframe>
                                <div id="documentError" class="alert alert-danger mt-3">Failed to load document. Please ensure the file exists and is accessible.</div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <a id="downloadDocument" href="" class="btn btn-primary" download>Download</a>
                            </div>
                        </div>
                    </div>
                </div>
            @endcan

            <!-- Footer -->
            <footer class="pt-3 mt-4 text-muted border-top text-center small">
                © {{ date('Y') }} Loan Management System. All rights reserved.
            </footer>
        </main>
    @else
        <main class="col-lg-10 col-md-9 ms-sm-auto px-md-4 py-4" role="main">
            <div class="alert alert-danger" role="alert">
                No lead data available.
            </div>
        </main>
    @endif
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script>
        // Initialize theme
        (function() {
            const theme = localStorage.getItem('theme') || 'light';
            document.documentElement.dataset.theme = theme;
            const themeToggle = document.getElementById('themeToggle');
            if (themeToggle) {
                themeToggle.innerHTML = `<i class="fas fa-${theme === 'dark' ? 'sun' : 'moon'}"></i>`;
                themeToggle.setAttribute('aria-label', `Switch to ${theme === 'dark' ? 'light' : 'dark'} theme`);
            }
        })();

        // Sidebar toggle
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebar = document.getElementById('sidebarMenu');
        if (sidebarToggle && sidebar) {
            sidebarToggle.addEventListener('click', () => {
                sidebar.classList.toggle('show');
                sidebarToggle.setAttribute('aria-expanded', sidebar.classList.contains('show'));
            });
        }

        // Theme toggle
        const themeToggle = document.getElementById('themeToggle');
        if (themeToggle) {
            themeToggle.addEventListener('click', () => {
                const isDark = document.documentElement.dataset.theme === 'dark';
                const newTheme = isDark ? 'light' : 'dark';
                document.documentElement.dataset.theme = newTheme;
                localStorage.setItem('theme', newTheme);
                themeToggle.innerHTML = `<i class="fas fa-${newTheme === 'dark' ? 'sun' : 'moon'}"></i>`;
                themeToggle.setAttribute('aria-label', `Switch to ${newTheme === 'dark' ? 'light' : 'dark'} theme`);
            });
        }

        // Initialize tooltips
        document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => {
            new bootstrap.Tooltip(el, { trigger: 'hover' });
        });

        // Document preview handler
        document.querySelectorAll('[data-document]').forEach(button => {
            button.addEventListener('click', () => {
                const documentPath = button.dataset.document;
                const documentType = button.dataset.type;
                const image = document.getElementById('documentImage');
                const iframe = document.getElementById('documentIframe');
                const error = document.getElementById('documentError');
                const modalLabel = document.getElementById('viewDocumentModalLabel');
                const downloadLink = document.getElementById('downloadDocument');

                // Reset modal
                [image, iframe, error].forEach(el => el.classList.remove('active'));
                image.src = '';
                iframe.src = '';
                downloadLink.classList.add('d-none');
                modalLabel.textContent = button.textContent + ' Preview';

                if (!documentPath) {
                    error.textContent = 'No document available for preview.';
                    error.classList.add('active');
                    bootstrap.Modal.getOrCreateInstance(document.getElementById('viewDocumentModal')).show();
                    return;
                }

                if (documentType === 'image') {
                    image.src = documentPath;
                    image.classList.add('active');
                    image.onerror = () => {
                        error.textContent = 'Unable to load image. Click below to download.';
                        error.classList.add('active');
                        image.classList.remove('active');
                        downloadLink.href = documentPath;
                        downloadLink.classList.remove('d-none');
                    };
                } else {
                    iframe.src = documentPath;
                    iframe.classList.add('active');
                    iframe.onerror = () => {
                        error.textContent = 'Unable to load document. Click below to download.';
                        error.classList.add('active');
                        iframe.classList.remove('active');
                        downloadLink.href = documentPath;
                        downloadLink.classList.remove('d-none');
                    };
                }

                downloadLink.href = documentPath;
                downloadLink.classList.remove('d-none');
                bootstrap.Modal.getOrCreateInstance(document.getElementById('viewDocumentModal')).show();
            });
        });

        // Form submission loading state
        ['approveKycForm', 'rejectKycForm', 'updateKycForm', 'verifyBankForm', 'approveLoanForm'].forEach(formId => {
            const form = document.getElementById(formId);
            if (form) {
                form.addEventListener('submit', (e) => {
                    const submitBtn = form.querySelector('button[type="submit"]');
                    if (form.checkValidity()) {
                        submitBtn.classList.add('btn-loading');
                        submitBtn.disabled = true;
                    } else {
                        e.preventDefault();
                        form.reportValidity();
                    }
                });
            }
        });

        // Client-side file validation for updateKycForm
        const updateKycForm = document.getElementById('updateKycForm');
        if (updateKycForm) {
            updateKycForm.addEventListener('submit', (e) => {
                const inputs = updateKycForm.querySelectorAll('input[type="file"]');
                let valid = true;
                inputs.forEach(input => {
                    if (input.files.length > 0) {
                        const file = input.files[0];
                        const maxSize = 5 * 1024 * 1024; // 5MB
                        if (file.size > maxSize) {
                            valid = false;
                            input.classList.add('is-invalid');
                            input.nextElementSibling.textContent = 'File size exceeds 5MB.';
                        } else if (input.name === 'bank_statement_pdf_path' && !file.type.includes('pdf')) {
                            valid = false;
                            input.classList.add('is-invalid');
                            input.nextElementSibling.textContent = 'File must be a PDF.';
                        } else if (['aadhar_card_image_front', 'aadhar_card_image_back', 'pan_card', 'selfie_picture', 'shop_business_image'].includes(input.name) && !file.type.includes('image')) {
                            valid = false;
                            input.classList.add('is-invalid');
                            input.nextElementSibling.textContent = 'File must be an image (JPG, JPEG, PNG).';
                        } else {
                            input.classList.remove('is-invalid');
                        }
                    }
                });
                if (!valid) e.preventDefault();
            });
        }

        // Toggle rejection reason field in Verify Bank modal
        const verificationStatus = document.getElementById('verification_status');
        const rejectionReasonDiv = document.getElementById('rejection_reason_div');
        if (verificationStatus && rejectionReasonDiv) {
            verificationStatus.addEventListener('change', () => {
                rejectionReasonDiv.style.display = verificationStatus.value === 'Rejected' ? 'block' : 'none';
                const rejectionReason = rejectionReasonDiv.querySelector('#rejection_reason');
                if (rejectionReason) {
                    rejectionReason.required = verificationStatus.value === 'Rejected';
                    if (verificationStatus.value !== 'Rejected') rejectionReason.value = '';
                }
            });
        }

        // IFSC code validation and bank name auto-fill
        document.addEventListener('DOMContentLoaded', function () {
            const ifscInput = document.getElementById('ifsc_code');
            const bankInput = document.getElementById('bank_name');

            if (ifscInput && bankInput) {
                ifscInput.addEventListener('blur', function () {
                    const ifsc = ifscInput.value.trim().toUpperCase();
                    bankInput.value = ''; // Clear bank name while fetching

                    // Validate IFSC format (11 characters, alphanumeric)
                    const ifscPattern = /^[A-Z]{4}0[A-Z0-9]{6}$/;
                    if (!ifscPattern.test(ifsc)) {
                        bankInput.value = '';
                        ifscInput.classList.add('is-invalid');
                        const invalidFeedback = ifscInput.nextElementSibling;
                        if (invalidFeedback && invalidFeedback.classList.contains('invalid-feedback')) {
                            invalidFeedback.textContent = 'Invalid IFSC Code format.';
                        }
                        return;
                    }

                    // Show loading state
                    bankInput.classList.add('btn-loading');

                    // Fetch bank details from Razorpay API
                    fetch(`https://ifsc.razorpay.com/${ifsc}`, {
                        method: 'GET',
                        headers: {
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Invalid IFSC Code or Bank not found');
                        }
                        return response.json();
                    })
                    .then(data => {
                        bankInput.value = data.BANK || '';
                        ifscInput.classList.remove('is-invalid');
                        if (!data.BANK) {
                            throw new Error('Bank name not available');
                        }
                    })
                    .catch(error => {
                        bankInput.value = '';
                        ifscInput.classList.add('is-invalid');
                        const invalidFeedback = ifscInput.nextElementSibling;
                        if (invalidFeedback && invalidFeedback.classList.contains('invalid-feedback')) {
                            invalidFeedback.textContent = error.message;
                        }
                    })
                    .finally(() => {
                        bankInput.classList.remove('btn-loading');
                    });
                });

                // Clear validation on input change
                ifscInput.addEventListener('input', function () {
                    ifscInput.classList.remove('is-invalid');
                    const invalidFeedback = ifscInput.nextElementSibling;
                    if (invalidFeedback && invalidFeedback.classList.contains('invalid-feedback')) {
                        invalidFeedback.textContent = '';
                    }
                });
            }
        });

        // Approve Loan Modal - Fetch Lead Details
        const approveLoanButton = document.querySelector('[data-bs-target="#approveLoanModal"]');
        if (approveLoanButton) {
            approveLoanButton.addEventListener('click', () => {
                const leadId = '{{ e($lead->lead_id) }}';
                const modal = document.getElementById('approveLoanModal');
                const loading = document.getElementById('leadDetailsLoading');
                const error = document.getElementById('leadDetailsError');
                const fields = {
                    monthly_income: document.getElementById('monthly_income'),
                    loan_demand_amount: document.getElementById('loan_demand_amount'),
                    kyc_status: document.getElementById('kyc_status'),
                };

                // Show loading spinner
                loading.classList.remove('d-none');
                error.classList.add('d-none');
                Object.values(fields).forEach(field => field.value = '');

                // Make AJAX request
                fetch(`/employee/lead/${leadId}`, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                })
                .then(response => {
                    if (!response.ok) throw new Error('Failed to fetch lead details');
                    return response.json();
                })
                .then(data => {
                    fields.monthly_income.value = data.monthly_income ? `₹${Number(data.monthly_income).toFixed(2)}` : 'N/A';
                    fields.loan_demand_amount.value = data.loan_demand_amount ? `₹${Number(data.loan_demand_amount).toFixed(2)}` : 'N/A';
                    fields.kyc_status.value = data.kyc_status || 'Pending';
                })
                .catch(err => {
                    error.textContent = err.message || 'An error occurred while fetching lead details.';
                    error.classList.remove('d-none');
                })
                .finally(() => {
                    loading.classList.add('d-none');
                });
            });
        }
    </script>
@endsection
