<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\authController; // Fixed naming convention
use App\Http\Controllers\SuperAdminController;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\EmiController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\BranchPermissionController; // Added missing import
use App\Http\Middleware\RoleDropdownPermissions; 
use App\Http\Controllers\LoanController;
use App\Http\Controllers\EmiPaymentController;
use App\Http\Controllers\LoanDisbursementReportController;






// Public routes (no authentication required)
Route::get('/', fn () => view('welcome'))->name('welcome');
Route::get('/login', [authController::class, 'showLoginForm'])->name('login');
Route::post('/login', [authController::class, 'login']);
Route::get('/register', [authController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [authController::class, 'register']);

// Authenticated routes
Route::middleware(['auth'])->group(function () {
    // Logout route
    Route::post('/logout', [authController::class, 'logout'])->name('logout');

    // Superadmin routes
    Route::prefix('superadmin')->group(function () {
        Route::get('/dashboard', [SuperAdminController::class, 'dashboard'])->name('superadmin.dashboard');
        Route::get('/users', [SuperAdminController::class, 'users'])->name('superadmin.manage_user');
        Route::get('/adminUserView', [SuperAdminController::class, 'adminUserView'])->name('superadmin.adminUserView');
        Route::get('/SubadminUserView', [SuperAdminController::class, 'SubadminUserView'])->name('superadmin.SubadminUserView');
        Route::get('/branchadminUserView', [SuperAdminController::class, 'branchadminUserView'])->name('superadmin.branchadminUserView');
        Route::get('/subbranchadminUserView', [SuperAdminController::class, 'subbranchadminUserView'])->name('superadmin.subbranchadminUserView');
        Route::get('/teammanagerAdminUserView', [SuperAdminController::class, 'teammanagerAdminUserView'])->name('superadmin.teammanagerAdminUserView');
        Route::get('/telecallerAdminUserView', [SuperAdminController::class, 'telecallerAdminUserView'])->name('superadmin.telecallerAdminUserView');
        Route::get('/AccountantAdminUserView', [SuperAdminController::class, 'AccountantAdminUserView'])->name('superadmin.AccountantAdminUserView');
        Route::get('/EmployeeAdminUserView', [SuperAdminController::class, 'EmployeeAdminUserView'])->name('superadmin.EmployeeAdminUserView');
        Route::get('/CustomerAdminUserView', [SuperAdminController::class, 'CustomerAdminUserView'])->name('superadmin.CustomerAdminUserView');

        Route::post('/users', [SuperAdminController::class, 'storeUser'])->name('superadmin.users.store');
        Route::put('/users/{user}', [SuperAdminController::class, 'updateUser'])->name('superadmin.users.update');
        Route::delete('/users/{user}', [SuperAdminController::class, 'destroyUser'])->name('superadmin.users.destroy');

        Route::get('/leads', [LeadController::class, 'index'])->name('superadmin.leads.index');
        Route::get('/leads/create', [LeadController::class, 'create'])->name('superadmin.leads.create');
        Route::post('/leads', [LeadController::class, 'store'])->name('superadmin.leads.store');
    });

    // Permission routes for Superadmin
    Route::group([], function () {
        Route::get('/superadminPermissionForAdmin', [PermissionController::class, 'superadminPermissionForAdminView'])->name('Permission.superadminPermissionForAdmin');
        Route::put('/permissions/{role}', [PermissionController::class, 'updateRolePermissions'])
            ->name('Permission.update')
            ->middleware([App\Http\Middleware\RoleDropdownPermissions::class . ':1', 'can:update']);
        Route::get('/superadminPermissionForSubAdmin', [PermissionController::class, 'superadminPermissionForSubAdminView'])->name('Permission.superadminPermissionForSubAdmin');
        Route::get('/superadminPermissionForBranch', [PermissionController::class, 'superadminPermissionForBranchView'])->name('Permission.superadminPermissionForBranch');
        Route::get('/superadminPermissionForSubBranch', [PermissionController::class, 'superadminPermissionForSubBranchView'])->name('Permission.superadminPermissionForSubBranch');
        Route::get('/superadminPermissionForAdminResion', [PermissionController::class, 'superadminPermissionForResionView'])->name('Permission.superadminPermissionForResion');
        Route::get('/superadminPermissionForTeamManager', [PermissionController::class, 'superadminPermissionForTeamManagerView'])->name('Permission.superadminPermissionForTeamManager');
        Route::get('/superadminPermissionForTelecaller', [PermissionController::class, 'superadminPermissionForTelecallerView'])->name('Permission.superadminPermissionForTelecaller');
        Route::get('/superadminPermissionForAccountant', [PermissionController::class, 'superadminPermissionForAccountantView'])->name('Permission.superadminPermissionForAccountant');
        Route::get('/superadminPermissionForCustomer', [PermissionController::class, 'superadminPermissionForCustomerView'])->name('Permission.superadminPermissionForCustomer');
        Route::get('/superadminPermissionForDisbursementManager', [PermissionController::class, 'superadminPermissionForDisbursementManagerView'])->name('Permission.superadminPermissionForDisbursementManager');
        Route::get('/superadminPermissionForEmployee', [PermissionController::class, 'superadminPermissionForEmployeeView'])->name('Permission.superadminPermissionForEmployee');
        Route::get('/superadminPermissionForCreditManager', [PermissionController::class, 'superadminPermissionForCreditManagerView'])->name('Permission.superadminPermissionForCreditManager');
    });

    // Permission routes for authenticated users
    Route::get('/permissions', [PermissionController::class, 'getPermissions'])->name('Permission.getAdminPermissions');
    Route::get('/api/user/permissions', [PermissionController::class, 'getUserPermissions'])->name('api.user.permissions');

    // Employee routes
    Route::prefix('employee')->name('employee.')->group(function () {
        Route::middleware([App\Http\Middleware\RoleDropdownPermissions::class . ':20'])->group(function () {
            Route::get('/employeeCreateLead', [LeadController::class, 'ShowCreateLeadForm'])->name('employeeCreateLead');
            Route::get('/ViewCreateLead', [LeadController::class, 'ShowLead'])->name('ViewCreateLead');
            Route::get('/viewLeadDetails/{id}', [LeadController::class, 'showLeadDetails'])->name('viewLeadDetails');
            Route::post('/approveKyc/{id}', [LeadController::class, 'approveKyc'])->name('approveKyc');
            Route::post('/rejectKyc/{id}', [LeadController::class, 'rejectKyc'])->name('rejectKyc');
            Route::post('/leads/{id}/suggestions', [LeadController::class, 'addSuggestion'])->name('addSuggestion');
            Route::post('/leads/bulk-upload', [LeadController::class, 'bulkUpload'])->name('bulkUpload');
            Route::put('/employee/kyc/update/{id}', [LeadController::class, 'updateKyc'])->name('updateKyc');
            
            // Lead creation (POST route)
            Route::post('/employee/store-lead', [LeadController::class, 'store'])->name('storeLead');
                  
            
        });
        
        
       Route::post('verify-bank/{id}', [LeadController::class, 'verifyBank'])->name('verifyBank');


        
         Route::get('/employee/deleteLead/{id}', [LeadController::class, 'destroy'])->name('deleteLead');
       
        
        // Route::get('/lead/emi-details/{leadId}', [LeadController::class, 'show'])->name('lead.emi.details');
        
        // 
        Route::get('/emi/{lead_id}', [EmiController::class, 'show'])->name('emi.show');


        
        

        Route::post('/update-branch-permission', [BranchPermissionController::class, 'updateBranchPermission'])->name('branch.permission.update');

        Route::put('/permissions/{id}', [PermissionController::class, 'updateSpecificPermission'])
            ->middleware(['auth'])
            ->name('Permission.updateSpecificPermission');

       Route::get('/leads', [LeadController::class, 'getLeads'])
    ->name('leads.get')
    ->middleware([App\Http\Middleware\RoleDropdownPermissions::class . ':1', 'can:read']);
    });

    // Admin routes
    Route::prefix('admin')->group(function () {
        Route::get('/dashboard', [AuthController::class, 'adminDashboard'])->name('admin.dashboard');
        Route::get('/adminApprovedKycUserView', [AdminController::class, 'adminApprovedKycUserViewForm'])->name('admin.adminApprovedKycUserView');
        Route::get('/pendingLead', [AdminController::class, 'adminPendingKycUserViewForm'])->name('admin.pendingLead');
       Route::get('/rejectedLead', [AdminController::class, 'adminRejectedKycUserViewForm'])->name('admin.rejectedLead');
Route::get('/adminApprovedKycUserEmiCalculation/{lead_id}', [AdminController::class, 'adminApprovedKycUserEmiCalculationViewForm'])->name('admin.adminApprovedKycUserEmiCalculation');

        Route::post('/adminApprovedKycUserEmiCalculation/{loan_id}/approve-enach', [AdminController::class, 'approveEmiForEnach'])->name('admin.approveEmiForEnach');
        Route::get('/admin/approved-enach-users', [AdminController::class, 'adminApprovedEnachUserView'])->name('admin.adminApprovedEnachUserView');
Route::post('/admin/show-emi', [AdminController::class, 'showEMI'])->name('showEMI');

Route::get('/emi-result/{lead_id}', [AdminController::class, 'showEMIResult'])->name('emi.result');

Route::get('/admin/emi/details', [AdminController::class, 'getEmiDetails'])->name('admin.emi.details');

Route::get('/superadmin/emi/details/{loanId}/json', [EmiController::class, 'getLoanDetails'])->name('emi.details.json');
// routes/web.php
  
    // Place this BELOW the admin group, in web.php

Route::post('/emi/payment', [AdminController::class, 'makePayment'])->name('admin.emi.payment');
Route::post('admin/loan/close', [AdminController::class, 'closeLoan'])->name('admin.loan.close');

    });
  




Route::get('/superadmin/emi/details/{loanId}', [EmiPaymentController::class, 'details'])->name('emi.details');


Route::post('/superadmin/loan/disburse', [LoanController::class, 'disburse'])->name('loan.disburse');
Route::get('/superadmin/loan-disbursement-report', [LoanDisbursementReportController::class, 'index'])->name('loan.disbursement.report');
    
    
    
    
Route::prefix('reports')->group(function () {
    Route::get('/disbandment', [ReportsController::class, 'disbandmentReport'])->name('disbandment');
    Route::get('/ongoing', [ReportsController::class, 'ongoingLoanReport'])->name('ongoing');
    Route::post('/ongoing/filter', [ReportsController::class, 'ongoingLoanFilter'])->name('ongoing.filter');
    Route::get('/closed', [ReportsController::class, 'closedLoanReport'])->name('closed');
    Route::get('/paid-emis', [ReportsController::class, 'allPaidEmiReport'])->name('paid_emis');
    Route::get('/overdue-emis', [ReportsController::class, 'overdueEmiReport'])->name('overdue_emis');
    Route::get('/overdue-emis-npa', [ReportsController::class, 'overdueEmiReportNpa'])->name('overdue_emis_npa');
    Route::get('/outstanding-emis', [ReportsController::class, 'outstandingemisReport'])->name('outstanding_emi');
    Route::get('/HalfPayment-emis', [ReportsController::class, 'HalfPaymentReport'])->name('HalfPayment_emi');
    Route::get('/Forclosure-emis', [ReportsController::class, 'ForclosureReport'])->name('Forclosure_emi');
});

    // Other dashboards
    Route::get('/subadmin/dashboard', [AuthController::class, 'subAdminDashboard'])->name('subadmin.dashboard');
    Route::get('/branch/dashboard', [AuthController::class, 'branchDashboard'])->name('branch.dashboard');
    Route::get('/subbranchadmin/dashboard', [AuthController::class, 'subBranchAdminDashboard'])->name('subbranchadmin.dashboard');
    Route::get('/regionhead/dashboard', [AuthController::class, 'regionHeadDashboard'])->name('regionhead.dashboard');
    Route::get('/teammanager/dashboard', [AuthController::class, 'teamManagerDashboard'])->name('teammanager.dashboard');
    Route::get('/telecaller/dashboard', [AuthController::class, 'telecallerDashboard'])->name('telecaller.dashboard');
    Route::get('/accountant/dashboard', [AuthController::class, 'accountantDashboard'])->name('accountant.dashboard');
    Route::get('/employee/dashboard', [AuthController::class, 'employeeDashboard'])->name('employee.dashboard');
    Route::get('/customer/dashboard', [AuthController::class, 'customerDashboard'])->name('customer.dashboard');
});