@extends('superadmin.superadmin')

@section('title', 'Super Admin Dashboard')

@section('sidebar')
    @include('superadmin.superadminSidebar')
@endsection




@section('scripts')
    <script>
        // No route dependencies in JavaScript
        window.routes = {
            updateUser: "/superadmin/users/update/:user",
            deleteUser: "/superadmin/users/destroy/:user",
            updateLoan: "/superadmin/loans/update/:loan",
            disburseLoan: "/superadmin/loans/disburse/:loan",
            deleteTemplate: "/superadmin/templates/destroy/:template",
            createLead: "/superadmin/leads/store",
            bulkUpload: "/superadmin/leads/bulk-upload",
            viewLeadDetails: "/superadmin/leads/show/:lead",
            approveKyc: "/superadmin/kyc/approve/:kyc",
            rejectKyc: "/superadmin/kyc/reject/:kyc",
            addSuggestion: "/superadmin/suggestions/store"
        };

        // Initialize Tooltips
        document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => new bootstrap.Tooltip(el));

        // Theme Toggle
        document.getElementById('themeToggle').addEventListener('click', () => {
            const isDark = document.body.getAttribute('data-theme') === 'dark';
            document.body.setAttribute('data-theme', isDark ? 'light' : 'dark');
            document.getElementById('themeToggle').innerHTML = `<i class="fas fa-${isDark ? 'moon' : 'sun'}"></i>`;
        });

        // Form Submission Handler
        const handleFormSubmit = async (formId, url, method, successMsg, errorMsg, modalId) => {
            const form = document.getElementById(formId);
            if (form) {
                form.addEventListener('submit', async (e) => {
                    e.preventDefault();
                    const formData = new FormData(form);
                    const headers = {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    };
                    let body;
                    if (formId === 'bulkUploadForm') {
                        body = formData;
                    } else {
                        const data = Object.fromEntries(formData);
                        headers['Content-Type'] = 'application/json';
                        body = JSON.stringify(data);
                    }
                    try {
                        if (!url && !form.action) {
                            console.error('Form action URL is missing');
                            alert('Error: Form action URL is missing');
                            return;
                        }
                        const response = await fetch(url || form.action, {
                            method,
                            headers,
                            body
                        });
                        if (response.ok) {
                            alert(successMsg);
                            if (modalId) bootstrap.Modal.getInstance(document.getElementById(modalId)).hide();
                            window.location.reload();
                        } else {
                            const errorData = await response.json();
                            console.error('Form submission error:', errorData);
                            alert(errorMsg + ': ' + (errorData.message || 'Unknown error'));
                        }
                    } catch (error) {
                        console.error('Network error:', error);
                        alert('Network error: ' + error.message);
                    }
                });
            }
        };

        // Form submission handlers
        handleFormSubmit('newUserForm', null, 'POST', 'User added successfully', 'Error adding user', 'newUserModal');
        // Add handlers for other forms as needed
    </script>
@endsection


