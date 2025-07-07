@extends('superadmin.superadmin')

@section('title', 'Create Lead')

@section('styles')
<style>
    body {
        background: linear-gradient(135deg, #e3f0ff 0%, #fafcff 100%);
        font-family: 'Segoe UI', Arial, sans-serif;
    }
    .card {
        border: none;
        border-radius: 12px;
        background: #fff;
        transition: transform 0.2s;
    }
    .card:hover {
        transform: translateY(-5px);
    }
    .search-bar {
        max-width: 300px;
        border-radius: 20px;
    }
    .sidebar-toggle {
        display: none;
    }
    .form-label {
        font-weight: 500;
        font-size: 0.9rem;
    }
    .form-control, .form-select {
        border-radius: 8px;
        transition: all 0.2s;
    }
    .form-control:focus, .form-select:focus {
        border-color: #2563eb;
        box-shadow: 0 0 0 3px rgba(37,99,235,0.1);
    }
    .btn-primary {
        border-radius: 20px;
        background: #2563eb;
        border-color: #2563eb;
        font-weight: 500;
        transition: all 0.2s;
    }
    .btn-primary:hover {
        background: #1e40af;
        border-color: #1e40af;
    }
    .alert {
        border-radius: 8px;
    }
    @media (max-width: 991.98px) {
        main {
            margin-left: 0 !important;
        }
        .sidebar-toggle {
            display: block;
        }
    }
</style>
@endsection

@section('sidebar')
   @include('superadmin.superadminSidebar')
@endsection

@section('content')
<main class="col-lg-10 col-md-9 ms-sm-auto px-md-4 py-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
        <div class="d-flex align-items-center">
            <button class="btn btn-outline-primary sidebar-toggle me-3" id="sidebarToggle"><i class="fas fa-bars"></i></button>
            <h2 class="h3 mb-0 fw-bold">Create New Lead</h2>
        </div>
        <div class="d-flex align-items-center gap-2">
            <input type="text" class="form-control search-bar" placeholder="Search..." id="searchInput">
        </div>
    </div>

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
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card p-4 shadow-sm">
        <form action="{{ route('employee.storeLead') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row g-3">
                <!-- Basic Details -->
                <div class="col-md-6">
                    <label class="form-label">First Name <span class="text-danger">*</span></label>
                    <input type="text" name="first_name" class="form-control @error('first_name') is-invalid @enderror" value="{{ old('first_name') }}" required>
                    @error('first_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Last Name <span class="text-danger">*</span></label>
                    <input type="text" name="last_name" class="form-control @error('last_name') is-invalid @enderror" value="{{ old('last_name') }}" required>
                    @error('last_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Mobile Number <span class="text-danger">*</span></label>
                    <input type="text" name="mobile_no" class="form-control @error('mobile_no') is-invalid @enderror" value="{{ old('mobile_no') }}" pattern="[0-9]{10}" maxlength="10" required>
                    @error('mobile_no')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}">
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Date of Birth</label>
                    <input type="date" name="date_of_birth" class="form-control @error('date_of_birth') is-invalid @enderror" value="{{ old('date_of_birth') }}">
                    @error('date_of_birth')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <!-- Identification Details -->
                <div class="col-md-6">
                    <label class="form-label">PAN Number</label>
                    <input type="text" name="pan_number" class="form-control @error('pan_number') is-invalid @enderror" value="{{ old('pan_number') }}" pattern="[A-Z]{5}[0-9]{4}[A-Z]{1}" maxlength="10">
                    @error('pan_number')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Aadhaar Number</label>
                    <input type="text" name="aadhar_number" class="form-control @error('aadhar_number') is-invalid @enderror" value="{{ old('aadhar_number') }}" pattern="[0-9]{12}" maxlength="12">
                    @error('aadhar_number')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <!-- Address Details -->
                <div class="col-md-6">
                    <label class="form-label">Residential Address</label>
                    <textarea name="residential_address" class="form-control @error('residential_address') is-invalid @enderror">{{ old('residential_address') }}</textarea>
                    @error('residential_address')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Office Address</label>
                    <textarea name="office_address" class="form-control @error('office_address') is-invalid @enderror">{{ old('office_address') }}</textarea>
                    @error('office_address')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Permanent Address</label>
                    <textarea name="permanent_address" class="form-control @error('permanent_address') is-invalid @enderror">{{ old('permanent_address') }}</textarea>
                    @error('permanent_address')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">PIN Code</label>
                    <input type="text" name="pin_code" class="form-control @error('pin_code') is-invalid @enderror" value="{{ old('pin_code') }}" pattern="[0-9]{6}" maxlength="6">
                    @error('pin_code')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">State</label>
                    <select name="state" class="form-select @error('state') is-invalid @enderror">
                        <option value="">Select State</option>
                        <option value="Maharashtra" {{ old('state') == 'Maharashtra' ? 'selected' : '' }}>Maharashtra</option>
                        <option value="Karnataka" {{ old('state') == 'Karnataka' ? 'selected' : '' }}>Karnataka</option>
                        <option value="Tamil Nadu" {{ old('state') == 'Tamil Nadu' ? 'selected' : '' }}>Tamil Nadu</option>
                    </select>
                    @error('state')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <!-- Occupation & Income -->
                <div class="col-md-6">
                    <label class="form-label">Occupation Type</label>
                    <select name="occupation_type" class="form-select @error('occupation_type') is-invalid @enderror">
                        <option value="">Select Occupation Type</option>
                        <option value="Salaried" {{ old('occupation_type') == 'Salaried' ? 'selected' : '' }}>Salaried</option>
                        <option value="Self-Employed" {{ old('occupation_type') == 'Self-Employed' ? 'selected' : '' }}>Self-Employed</option>
                        <option value="Student" {{ old('occupation_type') == 'Student' ? 'selected' : '' }}>Student</option>
                    </select>
                    @error('occupation_type')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Monthly Income</label>
                    <input type="number" name="monthly_income" class="form-control @error('monthly_income') is-invalid @enderror" value="{{ old('monthly_income') }}" min="0">
                    @error('monthly_income')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <!-- Loan Requirement -->
                <div class="col-md-6">
                    <label class="form-label">Loan Demand Amount</label>
                    <input type="number" name="loan_demand_amount" class="form-control @error('loan_demand_amount') is-invalid @enderror" value="{{ old('loan_demand_amount') }}" min="0">
                    @error('loan_demand_amount')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <!-- Document Uploads -->
                <div class="col-md-6">
                    <label class="form-label">Aadhaar Card Image (Front)</label>
                    <input type="file" name="aadhar_card_image_front" class="form-control @error('aadhar_card_image_front') is-invalid @enderror" accept=".jpg,.jpeg,.png">
                    @error('aadhar_card_image_front')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Aadhaar Card Image (Back)</label>
                    <input type="file" name="aadhar_card_image_back" class="form-control @error('aadhar_card_image_back') is-invalid @enderror" accept=".jpg,.jpeg,.png">
                    @error('aadhar_card_image_back')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">PAN Card Image</label>
                    <input type="file" name="pan_card" class="form-control @error('pan_card') is-invalid @enderror" accept=".jpg,.jpeg,.png">
                    @error('pan_card')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Selfie</label>
                    <input type="file" name="selfie_picture" class="form-control @error('selfie_picture') is-invalid @enderror" accept=".jpg,.jpeg,.png" capture="user">
                    @error('selfie_picture')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Shop/Business Image</label>
                    <input type="file" name="shop_business_image" class="form-control @error('shop_business_image') is-invalid @enderror" accept=".jpg,.jpeg,.png">
                    @error('shop_business_image')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Bank Statement (Optional)</label>
                    <input type="file" name="bank_statement_pdf_path" class="form-control @error('bank_statement_pdf_path') is-invalid @enderror" accept=".pdf">
                    @error('bank_statement_pdf_path')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <button type="submit" class="btn btn-primary mt-4">Submit Lead</button>
        </form>
    </div>

    <footer class="pt-3 mt-4 text-muted border-top text-center small">
        © 2025 Loan Management System. All rights reserved.
    </footer>
</main>
@endsection

@section('scripts')
<script>
    // Initialize Tooltips
    document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => new bootstrap.Tooltip(el));

    // Sidebar Toggle
    document.getElementById('sidebarToggle').addEventListener('click', () => {
        document.getElementById('sidebarMenu').classList.toggle('show');
    });

    // Dropdown Toggle
    document.querySelectorAll('.dropdown-container .dropdown-toggle').forEach(toggle => {
        toggle.addEventListener('click', (e) => {
            e.preventDefault();
            const dropdown = toggle.nextElementSibling;
            const isShown = dropdown.classList.contains('show');
            document.querySelectorAll('.dropdown-menu').forEach(menu => menu.classList.remove('show'));
            document.querySelectorAll('.dropdown-toggle').forEach(t => t.classList.remove('show'));
            if (!isShown) {
                dropdown.classList.add('show');
                toggle.classList.add('show');
            }
        });
    });

    // Close dropdown when clicking outside
    document.addEventListener('click', (e) => {
        const dropdowns = document.querySelectorAll('.dropdown-menu');
        const toggles = document.querySelectorAll('.dropdown-toggle');
        if (!Array.from(toggles).some(toggle => toggle.contains(e.target)) && !Array.from(dropdowns).some(dropdown => dropdown.contains(e.target))) {
            dropdowns.forEach(dropdown => dropdown.classList.remove('show'));
            toggles.forEach(toggle => toggle.classList.remove('show'));
        }
    });
</script>
@endsection