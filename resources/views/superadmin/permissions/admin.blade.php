@extends('superadmin.superadmin')

@section('title', 'Admin Dashboard - Dropdown Item Management')

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
            margin: 0;
            display: flex;
            min-height: 100vh;
        }
        .sidebar {
            min-height: 100vh;
            background: var(--bg-dark);
            color: #f3f4f6;
            box-shadow: var(--shadow);
            transition: width 0.3s ease;
            position: sticky;
            top: 0;
            width: 260px;
            z-index: 1000;
        }
        .sidebar.collapsed {
            width: 80px;
        }
        .sidebar .nav-link {
            color: #d1d5db;
            font-weight: 500;
            border-radius: 8px;
            margin: 0.4rem;
            padding: 0.8rem;
            transition: var(--transition);
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        .sidebar.collapsed .nav-link span {
            display: none;
        }
        .sidebar .nav-link.active, .sidebar .nav-link:hover {
            color: #fff;
            background: linear-gradient(90deg, var(--primary) 50%, var(--primary-dark) 100%);
            box-shadow: var(--shadow-sm);
        }
        .sidebar .nav-link i {
            width: 24px;
            text-align: center;
        }
        .profile-img {
            width: 64px;
            height: 64px;
            border-radius: 10px;
            border: 2px solid #fff;
            box-shadow: var(--shadow-sm);
            transition: var(--transition);
        }
        .profile-img:hover {
            transform: scale(1.1);
        }
        .sidebar.collapsed .profile-info {
            display: none;
        }
        .sidebar h5 {
            font-weight: 700;
            letter-spacing: 0.03em;
        }
        .badge.bg-success {
            background: linear-gradient(90deg, var(--success) 50%, #059669 100%) !important;
            box-shadow: var(--shadow-sm);
        }
        .notification-badge {
            position: relative;
        }
        .notification-badge::after {
            content: '5';
            position: absolute;
            top: -6px;
            right: -6px;
            background: var(--danger);
            color: #fff;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
        }
        .dropdown-menu {
            background: var(--bg-dark);
            border: none;
            box-shadow: var(--shadow);
            border-radius: 8px;
            max-height: 60vh;
            overflow-y: auto;
        }
        .dropdown-menu::-webkit-scrollbar {
            width: 8px;
        }
        .dropdown-menu::-webkit-scrollbar-thumb {
            background: var(--primary);
            border-radius: 4px;
        }
        .dropdown-item {
            color: #e5e7eb;
            padding: 0.7rem 1.5rem;
            transition: var(--transition);
        }
        .dropdown-item:hover {
            background: var(--primary);
            color: #fff;
        }
        .custom-checkbox input[type="checkbox"] {
            width: 1.3rem;
            height: 1.3rem;
            border: 2px solid var(--text-muted);
            border-radius: 6px;
            transition: var(--transition);
            cursor: pointer;
        }
        .custom-checkbox input[type="checkbox"]:checked {
            background-color: var(--primary);
            border-color: var(--primary);
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20' fill='white'%3E%3Cpath fill-rule='evenodd' d='M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z' clip-rule='evenodd'/%3E%3C/svg%3E");
            background-size: 1.1rem;
        }
        .custom-checkbox label {
            cursor: pointer;
        }
        .loading {
            opacity: 0.6;
            cursor: not-allowed;
        }
        .animate-slideIn {
            animation: slideIn 0.4s ease-out;
        }
        @keyframes slideIn {
            from { opacity: 0; transform: translateX(-20px); }
            to { opacity: 1; transform: translateX(0); }
        }
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: var(--shadow-sm);
            transition: var(--transition);
        }
        .card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow);
        }
        .btn-primary {
            background: var(--primary);
            border: none;
            transition: var(--transition);
        }
        .btn-primary:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
        }
        .accordion-button {
            font-weight: 500;
            color: var(--text-dark);
        }
        .accordion-button:not(.collapsed) {
            background: var(--primary);
            color: #fff;
        }
        .table th.w-10 { width: 10%; }
        .table th.w-12 { width: 12%; }
        .table th.w-20 { width: 20%; }
        .table th.w-25 { width: 25%; }
        .table th.w-50 { width: 50%; }
        .btn-sm { font-size: 0.8rem; padding: 0.2rem 0.5rem; }
        main {
            flex: 1;
            overflow-y: auto;
            min-height: 100vh;
            margin-left: 260px;
            transition: margin-left 0.3s ease;
        }
        @media (max-width: 991.98px) {
            .sidebar {
                position: fixed;
                left: -260px;
                width: 260px;
                z-index: 1050;
                transition: left 0.3s ease;
            }
            .sidebar.show {
                left: 0;
            }
            main {
                margin-left: 0 !important;
            }
        }
        .sidebar.collapsed + main {
            margin-left: 80px;
        }
    </style>
@endsection

@section('sidebar')
    @include('superadmin.superadminSidebar')
@endsection

@section('content')
    <main class="flex-1 p-4 p-md-5">
        <header class="mb-4 animate-slideIn">
            <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-3">
                    <button id="sidebarToggle" class="btn btn-link text-dark d-lg-none">
                        <i class="fas fa-bars fs-4"></i>
                    </button>
                    <div>
                        <h1 class="fs-3 fw-bold">Dropdown Item Management</h1>
                        <p class="text-muted">Assign dropdown items and permissions to various roles.</p>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <input type="text" id="itemSearch" class="form-control form-control-sm" placeholder="Search items..." aria-label="Search dropdown items">
                    <button id="themeToggle" class="btn btn-link text-dark" title="Toggle Dark Mode" aria-label="Toggle Dark Mode">
                        <i class="fas fa-moon"></i>
                    </button>
                </div>
            </div>
        </header>

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show animate-slideIn" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show animate-slideIn" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <?php
            $dropdownGroups = [];
            $roles = ['admin', 'subadmin', 'branch', 'subbranch', 'regionhead', 'teammanager', 'telecaller', 'accountant', 'employee', 'customer', 'disbursement_manager', 'credit_manager'];
            $dropdowns = $dropdowns ?? collect([]);
            $dropdownsToShow = $dropdowns->whereIn('id', [1, 2, 3]); // Master User, Manage Permissions, Lead Management

            foreach ($dropdownsToShow as $dropdown) {
                if (!isset($dropdown->id, $dropdown->name)) {
                    continue;
                }
                $dropdownGroups[$dropdown->id] = [
                    'name' => $dropdown->name ?? 'Unnamed Dropdown',
                    'items' => [],
                ];
            }

            $roleDropdownItems = $roleDropdownItems ?? [];
            $permissions = $permissions ?? collect([]);

            foreach ($dropdownsToShow as $dropdown) {
                $items = $dropdown->items ?? collect([]);
                if ($items->isEmpty()) {
                    continue;
                }

                foreach ($items as $item) {
                    if (!isset($item->id, $item->name)) {
                        continue;
                    }

                    if (!isset($dropdownGroups[$dropdown->id]['items'][$item->name])) {
                        $permission = $permissions->firstWhere('dropdown_item_id', $item->id) ?? null;
                        $isLeadItem = $dropdown->id == 3 && strtolower($item->name) == 'lead';

                        $defaultActions = [
                            'id' => $permission ? $permission->id : null,
                            'role_id' => $permission ? $permission->role_id : null,
                            'dropdown_item_id' => $item->id,
                            'can_create' => $permission ? (bool)$permission->can_create : false,
                            'can_read' => $permission ? (bool)$permission->can_read : false,
                            'can_update' => $permission ? (bool)$permission->can_update : false,
                            'can_delete' => $permission ? (bool)$permission->can_delete : false,
                            'can_view_lead' => $isLeadItem && $permission ? (bool)$permission->can_view_lead : false,
                            'can_approve_kyc' => $isLeadItem && $permission ? (bool)$permission->can_approve_kyc : false,
                            'can_reject_kyc' => $isLeadItem && $permission ? (bool)$permission->can_reject_kyc : false,
                            'created_at' => $permission ? $permission->created_at : null,
                            'updated_at' => $permission ? $permission->updated_at : null,
                        ];

                        $hasPermissions = (
                            $defaultActions['can_create'] ||
                            $defaultActions['can_read'] ||
                            $defaultActions['can_update'] ||
                            $defaultActions['can_delete'] ||
                            ($isLeadItem && (
                                $defaultActions['can_view_lead'] ||
                                $defaultActions['can_approve_kyc'] ||
                                $defaultActions['can_reject_kyc']
                            ))
                        );
                        $isAssigned = in_array($item->id, $roleDropdownItems) || $hasPermissions;

                        $dropdownGroups[$dropdown->id]['items'][$item->name] = [
                            'id' => $item->id,
                            'description' => $item->description ?? "Access {$item->name} section",
                            'assigned' => $isAssigned,
                            'actions' => $defaultActions,
                            'is_lead' => $isLeadItem,
                        ];
                    }
                }
            }
        ?>

        <div class="row g-4">
            <!-- Left Section: Assign Dropdown Items -->
            <div class="col-lg-6">
                <div class="card animate-slideIn">
                    <div class="card-body">
                        <h2 class="card-title fs-5 fw-semibold mb-3">Assign Dropdown Items</h2>
                        <form id="items-form" action="{{ route('Permission.update', ['role' => 'admin']) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="mb-3 d-flex gap-3">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="selectAllPermissions" aria-label="Select all permissions">
                                    <label class="form-check-label" for="selectAllPermissions">Select All Permissions</label>
                                </div>
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="deselectAllPermissions" aria-label="Deselect all permissions">
                                    <label class="form-check-label" for="deselectAllPermissions">Deselect All Permissions</label>
                                </div>
                            </div>
                            <div class="accordion" id="itemsAccordion">
                                @foreach ($dropdownGroups as $dropdownId => $dropdown)
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="heading-{{ $dropdownId }}">
                                            <button class="accordion-button {{ $loop->first ? '' : 'collapsed' }}" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-{{ $dropdownId }}" aria-expanded="{{ $loop->first ? 'true' : 'false' }}" aria-controls="collapse-{{ $dropdownId }}">
                                                {{ $dropdown['name'] }}
                                            </button>
                                        </h2>
                                        <div id="collapse-{{ $dropdownId }}" class="accordion-collapse collapse {{ $loop->first ? 'show' : '' }}" aria-labelledby="heading-{{ $dropdownId }}" data-bs-parent="#itemsAccordion">
                                            <div class="accordion-body">
                                                <div class="table-responsive">
                                                    <table class="table table-bordered table-hover">
                                                        <thead class="table-light">
                                                            <tr>
                                                                <th class="w-25">Item</th>
                                                                <th class="w-10 text-center">Assign</th>
                                                                <th class="w-10 text-center">Create</th>
                                                                <th class="w-10 text-center">Read</th>
                                                                <th class="w-10 text-center">Update</th>
                                                                <th class="w-10 text-center">Delete</th>
                                                                @if ($dropdownId == 3)
                                                                    <th class="w-10 text-center">View Lead</th>
                                                                    <th class="w-10 text-center">Approve KYC</th>
                                                                    <th class="w-10 text-center">Reject KYC</th>
                                                                @endif
                                                                <th class="w-20 text-center">Actions</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @if (empty($dropdown['items']))
                                                                <tr>
                                                                    <td colspan="{{ $dropdownId == 3 ? 10 : 7 }}" class="text-center text-muted">No items available</td>
                                                                </tr>
                                                            @else
                                                                @foreach ($dropdown['items'] as $name => $item)
                                                                    <tr>
                                                                        <td class="align-middle text-capitalize" data-item="{{ $name }}">{{ $name }}</td>
                                                                        <td class="text-center align-middle">
                                                                            <div class="custom-checkbox" data-bs-toggle="tooltip" title="{{ $item['description'] }}">
                                                                                <input
                                                                                    type="checkbox"
                                                                                    name="dropdown_items[]"
                                                                                    value="{{ $item['id'] }}"
                                                                                    class="item-checkbox"
                                                                                    data-item="{{ $name }}"
                                                                                    {{ $item['assigned'] ? 'checked' : '' }}
                                                                                    id="item-{{ $item['id'] }}"
                                                                                    aria-label="Toggle access to {{ $name }}"
                                                                                >
                                                                                <label for="item-{{ $item['id'] }}" class="visually-hidden">{{ $name }}</label>
                                                                            </div>
                                                                        </td>
                                                                        @foreach (['can_create', 'can_read', 'can_update', 'can_delete'] as $action)
                                                                            <td class="text-center align-middle">
                                                                                <div class="custom-checkbox">
                                                                                    <input
                                                                                        type="checkbox"
                                                                                        name="actions[{{ $item['id'] }}][{{ $action }}]"
                                                                                        value="1"
                                                                                        class="action-checkbox"
                                                                                        data-item="{{ $name }}"
                                                                                        data-action="{{ $action }}"
                                                                                        {{ $item['actions'][$action] ? 'checked' : '' }}
                                                                                        {{ $item['assigned'] ? '' : 'disabled' }}
                                                                                        id="{{ $action }}-{{ $item['id'] }}"
                                                                                        aria-label="Toggle {{ str_replace('_', ' ', $action) }} permission for {{ $name }}"
                                                                                    >
                                                                                    <label for="{{ $action }}-{{ $item['id'] }}" class="visually-hidden">{{ ucfirst(str_replace('_', ' ', $action)) }} {{ $name }}</label>
                                                                                </div>
                                                                            </td>
                                                                        @endforeach
                                                                        @if ($dropdownId == 3 && $item['is_lead'])
                                                                            @foreach (['can_view_lead', 'can_approve_kyc', 'can_reject_kyc'] as $action)
                                                                                <td class="text-center align-middle">
                                                                                    <div class="custom-checkbox">
                                                                                        <input
                                                                                            type="checkbox"
                                                                                            name="actions[{{ $item['id'] }}][{{ $action }}]"
                                                                                            value="1"
                                                                                            class="action-checkbox"
                                                                                            data-item="{{ $name }}"
                                                                                            data-action="{{ $action }}"
                                                                                            {{ $item['actions'][$action] ? 'checked' : '' }}
                                                                                            {{ $item['assigned'] ? '' : 'disabled' }}
                                                                                            id="{{ $action }}-{{ $item['id'] }}"
                                                                                            aria-label="Toggle {{ str_replace('_', ' ', $action) }} permission for {{ $name }}"
                                                                                        >
                                                                                        <label for="{{ $action }}-{{ $item['id'] }}" class="visually-hidden">{{ ucfirst(str_replace('_', ' ', $action)) }} {{ $name }}</label>
                                                                                    </div>
                                                                                </td>
                                                                            @endforeach
                                                                        @endif
                                                                        <td class="text-center align-middle">
                                                                            <button type="button" class="btn btn-sm btn-outline-primary select-all-permissions" data-item-id="{{ $item['id'] }}">
                                                                                <i class="fas fa-check-double me-1"></i> All
                                                                            </button>
                                                                            <button type="button" class="btn btn-sm btn-outline-secondary deselect-all-permissions ms-1" data-item-id="{{ $item['id'] }}">
                                                                                <i class="fas fa-times me-1"></i> None
                                                                            </button>
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                            @endif
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="d-flex justify-content-end gap-2 mt-4">
                                <button type="button" id="reset-items" class="btn btn-outline-danger">
                                    <i class="fas fa-undo me-2"></i> Reset
                                </button>
                                <button type="submit" id="save-items" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i> Save Items
                                </button>
                                <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left me-2"></i> Back
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Right Section: View Assigned Dropdown Items -->
            <div class="col-lg-6">
                <div class="card animate-slideIn">
                    <div class="card-body">
                        <h2 class="card-title fs-5 fw-semibold mb-3">Assigned Dropdown Items</h2>
                        <div class="accordion" id="assignedItemsAccordion">
                            @foreach ($dropdownGroups as $dropdownId => $dropdown)
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="assigned-heading-{{ $dropdownId }}">
                                        <button class="accordion-button {{ $loop->first ? '' : 'collapsed' }}" type="button" data-bs-toggle="collapse" data-bs-target="#assigned-collapse-{{ $dropdownId }}" aria-expanded="{{ $loop->first ? 'true' : 'false' }}" aria-controls="assigned-collapse-{{ $dropdownId }}">
                                            {{ $dropdown['name'] }}
                                        </button>
                                    </h2>
                                    <div id="assigned-collapse-{{ $dropdownId }}" class="accordion-collapse collapse {{ $loop->first ? 'show' : '' }}" aria-labelledby="assigned-heading-{{ $dropdownId }}" data-bs-parent="#assignedItemsAccordion">
                                        <div class="accordion-body">
                                            <div class="table-responsive">
                                                <table id="items-table-{{ $dropdownId }}" class="table table-bordered table-hover">
                                                    <thead class="table-light">
                                                        <tr>
                                                            <th class="w-25">Item</th>
                                                            <th class="w-25">Description</th>
                                                            <th class="w-10 text-center">Create</th>
                                                            <th class="w-10 text-center">Read</th>
                                                            <th class="w-10 text-center">Update</th>
                                                            <th class="w-10 text-center">Delete</th>
                                                            @if ($dropdownId == 3)
                                                                <th class="w-10 text-center">View Lead</th>
                                                                <th class="w-10 text-center">Approve KYC</th>
                                                                <th class="w-10 text-center">Reject KYC</th>
                                                            @endif
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @if (empty($dropdown['items']))
                                                            <tr>
                                                                <td colspan="{{ $dropdownId == 3 ? 9 : 6 }}" class="text-center text-muted">No items assigned</td>
                                                            </tr>
                                                        @else
                                                            @foreach ($dropdown['items'] as $name => $item)
                                                                <tr style="display: {{ $item['assigned'] ? '' : 'none' }}">
                                                                    <td class="align-middle text-capitalize" data-item="{{ $name }}">{{ $name }}</td>
                                                                    <td class="align-middle">{{ $item['description'] }}</td>
                                                                    @foreach (['can_create', 'can_read', 'can_update', 'can_delete'] as $action)
                                                                        <td class="text-center align-middle">
                                                                            <i class="fas {{ $item['actions'][$action] ? 'fa-check text-success' : 'fa-times text-danger' }}"></i>
                                                                        </td>
                                                                    @endforeach
                                                                    @if ($dropdownId == 3 && $item['is_lead'])
                                                                        @foreach (['can_view_lead', 'can_approve_kyc', 'can_reject_kyc'] as $action)
                                                                            <td class="text-center align-middle">
                                                                                <i class="fas {{ $item['actions'][$action] ? 'fa-check text-success' : 'fa-times text-danger' }}"></i>
                                                                            </td>
                                                                        @endforeach
                                                                    @endif
                                                                </tr>
                                                            @endforeach
                                                        @endif
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection

@section('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            const TEMP_STORAGE_KEY = 'admin_temp_dropdown_items';

            // Initialize Tooltips
            const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
            const tooltipList = [...tooltipTriggerList].map(el => new bootstrap.Tooltip(el));

            // Dropdown Toggle Fix
            $('.dropdown-toggle').on('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                const $dropdown = $(this).next('.dropdown-menu');
                const isShown = $dropdown.hasClass('show');
                $('.dropdown-menu').removeClass('show');
                $('.dropdown-toggle').attr('aria-expanded', 'false');
                if (!isShown) {
                    $dropdown.addClass('show');
                    $(this).attr('aria-expanded', 'true');
                }
            });

            // Close Dropdowns on Outside Click
            $(document).on('click', function(e) {
                if (!$(e.target).closest('.dropdown-toggle').length && !$(e.target).closest('.dropdown-menu').length) {
                    $('.dropdown-menu').removeClass('show');
                    $('.dropdown-toggle').attr('aria-expanded', 'false');
                }
            });

            // Get valid item IDs
            const validItemIds = $('input.item-checkbox').map(function() {
                return parseInt($(this).val());
            }).get();

            // Store initial state
            function storeInitialState() {
                const initialState = {};
                $('input.item-checkbox').each(function() {
                    const itemId = parseInt($(this).val());
                    const isLeadItem = $(this).data('item').toLowerCase() === 'lead' && $(this).closest('.accordion-item').find('.accordion-button').text().trim() === 'Lead Management';
                    initialState[itemId] = {
                        assigned: $(this).prop('checked') ? 1 : 0,
                        actions: {
                            can_create: $(`input[name="actions[${itemId}][can_create]"]`).prop('checked') ? 1 : 0,
                            can_read: $(`input[name="actions[${itemId}][can_read]"]`).prop('checked') ? 1 : 0,
                            can_update: $(`input[name="actions[${itemId}][can_update]"]`).prop('checked') ? 1 : 0,
                            can_delete: $(`input[name="actions[${itemId}][can_delete]"]`).prop('checked') ? 1 : 0,
                            can_view_lead: isLeadItem ? $(`input[name="actions[${itemId}][can_view_lead]"]`).prop('checked') ? 1 : 0 : 0,
                            can_approve_kyc: isLeadItem ? $(`input[name="actions[${itemId}][can_approve_kyc]"]`).prop('checked') ? 1 : 0 : 0,
                            can_reject_kyc: isLeadItem ? $(`input[name="actions[${itemId}][can_reject_kyc]"]`).prop('checked') ? 1 : 0 : 0
                        }
                    };
                });
                localStorage.setItem(TEMP_STORAGE_KEY + '_initial', JSON.stringify(initialState));
            }

            // Load dropdown items and actions from server
            function loadItems() {
                $.ajax({
                    url: '{{ route('api.user.permissions') }}',
                    method: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                        'Accept': 'application/json'
                    },
                    success: function(response) {
                        if (response.success && response.dropdowns) {
                            let items = [];
                            let actions = {};
                            response.dropdowns.forEach(dropdown => {
                                dropdown.items.forEach(item => {
                                    const isLeadItem = dropdown.id == 3 && item.name.toLowerCase() === 'lead';
                                    if (item.permissions && (
                                        item.permissions.can_create ||
                                        item.permissions.can_read ||
                                        item.permissions.can_update ||
                                        item.permissions.can_delete ||
                                        (isLeadItem && (
                                            item.permissions.can_view_lead ||
                                            item.permissions.can_approve_kyc ||
                                            item.permissions.can_reject_kyc
                                        ))
                                    )) {
                                        items.push(item.id);
                                        actions[item.id] = {
                                            can_create: item.permissions.can_create ? 1 : 0,
                                            can_read: item.permissions.can_read ? 1 : 0,
                                            can_update: item.permissions.can_update ? 1 : 0,
                                            can_delete: item.permissions.can_delete ? 1 : 0,
                                            can_view_lead: isLeadItem ? (item.permissions.can_view_lead ? 1 : 0) : 0,
                                            can_approve_kyc: isLeadItem ? (item.permissions.can_approve_kyc ? 1 : 0) : 0,
                                            can_reject_kyc: isLeadItem ? (item.permissions.can_reject_kyc ? 1 : 0) : 0
                                        };
                                    }
                                });
                            });
                            localStorage.setItem(TEMP_STORAGE_KEY, JSON.stringify({ items, actions }));
                            updateUI(items, actions);
                            storeInitialState();
                        } else {
                            console.warn('Failed to load permissions:', response.message);
                            loadFromLocalStorage();
                        }
                    },
                    error: function(xhr) {
                        console.error('Error fetching permissions:', xhr.responseJSON?.message || xhr.statusText);
                        showNotification('Failed to load permissions: ' + (xhr.responseJSON?.message || 'Server error'), 'danger');
                        loadFromLocalStorage();
                    }
                });
            }

            // Fallback to local storage
            function loadFromLocalStorage() {
                const tempItems = localStorage.getItem(TEMP_STORAGE_KEY);
                let items = [];
                let actions = {};
                if (tempItems) {
                    try {
                        const parsed = JSON.parse(tempItems);
                        if (Array.isArray(parsed.items)) {
                            items = parsed.items.filter(id => validItemIds.includes(id));
                            actions = parsed.actions || {};
                            // Ensure lead-specific permissions are only for lead item
                            Object.keys(actions).forEach(itemId => {
                                const $checkbox = $(`input.item-checkbox[value="${itemId}"]`);
                                const isLeadItem = $checkbox.data('item').toLowerCase() === 'lead' && $checkbox.closest('.accordion-item').find('.accordion-button').text().trim() === 'Lead Management';
                                if (!isLeadItem) {
                                    actions[itemId].can_view_lead = 0;
                                    actions[itemId].can_approve_kyc = 0;
                                    actions[itemId].can_reject_kyc = 0;
                                }
                            });
                        }
                    } catch (e) {
                        console.warn('Invalid localStorage data:', e);
                    }
                }
                updateUI(items, actions);
                storeInitialState();
            }

            // Update UI
            function updateUI(items, actions) {
                $('input.item-checkbox').each(function() {
                    const itemId = parseInt($(this).val());
                    const isLeadItem = $(this).data('item').toLowerCase() === 'lead' && $(this).closest('.accordion-item').find('.accordion-button').text().trim() === 'Lead Management';
                    const hasPermissions = actions[itemId] && (
                        actions[itemId].can_create ||
                        actions[itemId].can_read ||
                        actions[itemId].can_update ||
                        actions[itemId].can_delete ||
                        (isLeadItem && (
                            actions[itemId].can_view_lead ||
                            actions[itemId].can_approve_kyc ||
                            actions[itemId].can_reject_kyc
                        ))
                    );
                    $(this).prop('checked', items.includes(itemId) || hasPermissions);
                    $(`input.action-checkbox[name^="actions[${itemId}]"]`).prop('disabled', !$(this).prop('checked'));
                    if (actions[itemId]) {
                        $(`input[name="actions[${itemId}][can_create]"]`).prop('checked', actions[itemId].can_create);
                        $(`input[name="actions[${itemId}][can_read]"]`).prop('checked', actions[itemId].can_read);
                        $(`input[name="actions[${itemId}][can_update]"]`).prop('checked', actions[itemId].can_update);
                        $(`input[name="actions[${itemId}][can_delete]"]`).prop('checked', actions[itemId].can_delete);
                        if (isLeadItem) {
                            $(`input[name="actions[${itemId}][can_view_lead]"]`).prop('checked', actions[itemId].can_view_lead);
                            $(`input[name="actions[${itemId}][can_approve_kyc]"]`).prop('checked', actions[itemId].can_approve_kyc);
                            $(`input[name="actions[${itemId}][can_reject_kyc]"]`).prop('checked', actions[itemId].can_reject_kyc);
                        }
                    } else {
                        // Initialize unchecked if no actions exist
                        $(`input.action-checkbox[name^="actions[${itemId}]"]`).prop('checked', false);
                    }
                });
                updateItemsTables(items, actions);
                updateSidebarItems();
            }

            // Check if an item has permissions
            function hasPermissionsForItem(itemId) {
                const $checkbox = $(`input.item-checkbox[value="${itemId}"]`);
                const isLeadItem = $checkbox.data('item').toLowerCase() === 'lead' && $checkbox.closest('.accordion-item').find('.accordion-button').text().trim() === 'Lead Management';
                return (
                    $(`input[name="actions[${itemId}][can_create]"]`).prop('checked') ||
                    $(`input[name="actions[${itemId}][can_read]"]`).prop('checked') ||
                    $(`input[name="actions[${itemId}][can_update]"]`).prop('checked') ||
                    $(`input[name="actions[${itemId}][can_delete]"]`).prop('checked') ||
                    (isLeadItem && (
                        $(`input[name="actions[${itemId}][can_view_lead]"]`).prop('checked') ||
                        $(`input[name="actions[${itemId}][can_approve_kyc]"]`).prop('checked') ||
                        $(`input[name="actions[${itemId}][can_reject_kyc]"]`).prop('checked')
                    ))
                );
            }

            // Get selected dropdown items
            function getSelectedItems() {
                const items = [];
                $('input.item-checkbox:checked').each(function() {
                    const itemId = parseInt($(this).val());
                    items.push(itemId);
                });
                return items;
            }

            // Get selected actions
            function getSelectedActions() {
                const actions = {};
                $('input.item-checkbox').each(function() {
                    const itemId = parseInt($(this).val());
                    const isLeadItem = $(this).data('item').toLowerCase() === 'lead' && $(this).closest('.accordion-item').find('.accordion-button').text().trim() === 'Lead Management';
                    actions[itemId] = {
                        can_create: $(`input[name="actions[${itemId}][can_create]"]`).prop('checked') ? 1 : 0,
                        can_read: $(`input[name="actions[${itemId}][can_read]"]`).prop('checked') ? 1 : 0,
                        can_update: $(`input[name="actions[${itemId}][can_update]"]`).prop('checked') ? 1 : 0,
                        can_delete: $(`input[name="actions[${itemId}][can_delete]"]`).prop('checked') ? 1 : 0,
                        can_view_lead: isLeadItem ? $(`input[name="actions[${itemId}][can_view_lead]"]`).prop('checked') ? 1 : 0 : 0,
                        can_approve_kyc: isLeadItem ? $(`input[name="actions[${itemId}][can_approve_kyc]"]`).prop('checked') ? 1 : 0 : 0,
                        can_reject_kyc: isLeadItem ? $(`input[name="actions[${itemId}][can_reject_kyc]"]`).prop('checked') ? 1 : 0 : 0
                    };
                });
                return actions;
            }

            // Update items tables
            function updateItemsTables(items, actions) {
                $('[id^="items-table-"]').each(function() {
                    const tableId = $(this).attr('id');
                    const dropdownId = parseInt(tableId.match(/\d+$/)[0]);
                    const isLeadManagement = dropdownId == 3;
                    $(this).find('tbody tr').each(function() {
                        const item = $(this).find('td:first').data('item');
                        const itemId = parseInt($(`input.item-checkbox[data-item="${item}"]`).val());
                        const isLeadItem = isLeadManagement && item.toLowerCase() === 'lead';
                        const isAssigned = items.includes(itemId) || hasPermissionsForItem(itemId);
                        $(this).toggle(isAssigned);
                        if (isAssigned && actions[itemId]) {
                            $(this).find('td:nth-child(3) i').attr('class', `fas ${actions[itemId].can_create ? 'fa-check text-success' : 'fa-times text-danger'}`);
                            $(this).find('td:nth-child(4) i').attr('class', `fas ${actions[itemId].can_read ? 'fa-check text-success' : 'fa-times text-danger'}`);
                            $(this).find('td:nth-child(5) i').attr('class', `fas ${actions[itemId].can_update ? 'fa-check text-success' : 'fa-times text-danger'}`);
                            $(this).find('td:nth-child(6) i').attr('class', `fas ${actions[itemId].can_delete ? 'fa-check text-success' : 'fa-times text-danger'}`);
                            if (isLeadItem) {
                                $(this).find('td:nth-child(7) i').attr('class', `fas ${actions[itemId].can_view_lead ? 'fa-check text-success' : 'fa-times text-danger'}`);
                                $(this).find('td:nth-child(8) i').attr('class', `fas ${actions[itemId].can_approve_kyc ? 'fa-check text-success' : 'fa-times text-danger'}`);
                                $(this).find('td:nth-child(9) i').attr('class', `fas ${actions[itemId].can_reject_kyc ? 'fa-check text-success' : 'fa-times text-danger'}`);
                            }
                        }
                    });
                });
            }

            // Update sidebar dropdowns
            function updateSidebarItems() {
                $.ajax({
                    url: '{{ route('api.user.permissions') }}',
                    method: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                        'Accept': 'application/json'
                    },
                    success: function(response) {
                        if (response.success && response.dropdowns) {
                            const $userDropdown = $('#dropdownMenuItems');
                            const $permissionsDropdown = $('#permissionsDropdownMenu');
                            $userDropdown.empty();
                            $permissionsDropdown.empty();

                            response.dropdowns.forEach(dropdown => {
                                if (dropdown.id === 1) {
                                    dropdown.items.forEach(item => {
                                        if (item && item.name && item.url && item.permissions && (
                                            item.permissions.can_create ||
                                            item.permissions.can_read ||
                                            item.permissions.can_update ||
                                            item.permissions.can_delete ||
                                            (dropdown.id == 3 && item.name.toLowerCase() === 'lead' && (
                                                item.permissions.can_view_lead ||
                                                item.permissions.can_approve_kyc ||
                                                item.permissions.can_reject_kyc
                                            ))
                                        )) {
                                            const $item = $(`
                                                <li>
                                                    <a class="dropdown-item text-capitalize" href="${item.url}">
                                                        <i class="fas fa-check-circle me-2"></i> ${item.name}
                                                    </a>
                                                </li>
                                            `);
                                            $userDropdown.append($item);
                                        }
                                    });
                                } else if (dropdown.id === 2) {
                                    dropdown.items.forEach(item => {
                                        if (item && item.name && item.url && item.permissions && (
                                            item.permissions.can_create ||
                                            item.permissions.can_read ||
                                            item.permissions.can_update ||
                                            item.permissions.can_delete
                                        )) {
                                            const $item = $(`
                                                <li>
                                                    <a class="dropdown-item text-capitalize" href="${item.url}">
                                                        <i class="fas fa-check-circle me-2"></i> ${item.name}
                                                    </a>
                                                </li>
                                            `);
                                            $permissionsDropdown.append($item);
                                        }
                                    });
                                }
                            });

                            if ($userDropdown.children().length === 0) {
                                $userDropdown.append('<li><a class="dropdown-item text-muted" href="#">No Actions assigned</a></li>');
                            }
                            if ($permissionsDropdown.children().length === 0) {
                                $permissionsDropdown.append('<li><a class="dropdown-item text-muted" href="#">No Actions assigned</a></li>');
                            }
                        } else {
                            showNotification('Failed to load sidebar items.', 'warning');
                        }
                    },
                    error: function(xhr) {
                        showNotification('Error: ' + (xhr.responseJSON?.message || 'Failed to fetch sidebar items'), 'danger');
                        if (xhr.status === 401) {
                            showNotification('Session expired. Redirecting to login...', 'danger');
                            setTimeout(() => window.location.href = '/logout', 2000);
                        }
                    }
                });
            }

            // Show notification
            function showNotification(message, type = 'success') {
                const $notification = $(`
                    <div class="alert alert-${type} alert-dismissible fade show animate-slideIn position-fixed top-0 end-0 m-3" role="alert" style="z-index: 1060;">
                        ${message}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                `);
                $('body').append($notification);
                setTimeout(() => $notification.alert('close'), 4000);
            }

            // Load initial state and items
            loadItems();

            // Item checkbox change handler
            $('.item-checkbox').on('change', function() {
                const itemId = parseInt($(this).val());
                const isChecked = $(this).prop('checked');
                const isLeadItem = $(this).data('item').toLowerCase() === 'lead' && $(this).closest('.accordion-item').find('.accordion-button').text().trim() === 'Lead Management';
                $(`input.action-checkbox[name^="actions[${itemId}]"]`).prop('disabled', !isChecked);
                if (!isChecked) {
                    $(`input.action-checkbox[name^="actions[${itemId}]"]`).prop('checked', false);
                }
                // Ensure item is checked if any permission is selected
                if (hasPermissionsForItem(itemId)) {
                    $(this).prop('checked', true);
                }
                updateItemsTables(getSelectedItems(), getSelectedActions());
                updateSidebarItems();
            });

            // Action checkbox change handler
            $('.action-checkbox').on('change', function() {
                const itemId = parseInt($(this).attr('name').match(/\d+/)[0]);
                const $itemCheckbox = $(`input.item-checkbox[value="${itemId}"]`);
                const isLeadItem = $itemCheckbox.data('item').toLowerCase() === 'lead' && $itemCheckbox.closest('.accordion-item').find('.accordion-button').text().trim() === 'Lead Management';
                const hasPermissions = hasPermissionsForItem(itemId);
                $itemCheckbox.prop('checked', hasPermissions);
                $(`input.action-checkbox[name^="actions[${itemId}]"]`).prop('disabled', !hasPermissions);
                updateItemsTables(getSelectedItems(), getSelectedActions());
                updateSidebarItems();
            });

            // Select all permissions for an item
            function selectAllPermissionsForItem(itemId) {
                const $checkbox = $(`input.item-checkbox[value="${itemId}"]`);
                const isLeadItem = $checkbox.data('item').toLowerCase() === 'lead' && $checkbox.closest('.accordion-item').find('.accordion-button').text().trim() === 'Lead Management';
                $(`input[name="actions[${itemId}][can_create]"]`).prop('checked', true);
                $(`input[name="actions[${itemId}][can_read]"]`).prop('checked', true);
                $(`input[name="actions[${itemId}][can_update]"]`).prop('checked', true);
                $(`input[name="actions[${itemId}][can_delete]"]`).prop('checked', true);
                if (isLeadItem) {
                    $(`input[name="actions[${itemId}][can_view_lead]"]`).prop('checked', true);
                    $(`input[name="actions[${itemId}][can_approve_kyc]"]`).prop('checked', true);
                    $(`input[name="actions[${itemId}][can_reject_kyc]"]`).prop('checked', true);
                }
                $(`input.action-checkbox[name^="actions[${itemId}]"]`).prop('disabled', false);
                $(`input.item-checkbox[value="${itemId}"]`).prop('checked', true);
                updateItemsTables(getSelectedItems(), getSelectedActions());
                updateSidebarItems();
            }

            // Deselect all permissions for an item
            function deselectAllPermissionsForItem(itemId) {
                const $checkbox = $(`input.item-checkbox[value="${itemId}"]`);
                const isLeadItem = $checkbox.data('item').toLowerCase() === 'lead' && $checkbox.closest('.accordion-item').find('.accordion-button').text().trim() === 'Lead Management';
                $(`input[name="actions[${itemId}][can_create]"]`).prop('checked', false);
                $(`input[name="actions[${itemId}][can_read]"]`).prop('checked', false);
                $(`input[name="actions[${itemId}][can_update]"]`).prop('checked', false);
                $(`input[name="actions[${itemId}][can_delete]"]`).prop('checked', false);
                if (isLeadItem) {
                    $(`input[name="actions[${itemId}][can_view_lead]"]`).prop('checked', false);
                    $(`input[name="actions[${itemId}][can_approve_kyc]"]`).prop('checked', false);
                    $(`input[name="actions[${itemId}][can_reject_kyc]"]`).prop('checked', false);
                }
                $(`input.item-checkbox[value="${itemId}"]`).prop('checked', false);
                $(`input.action-checkbox[name^="actions[${itemId}]"]`).prop('disabled', true);
                updateItemsTables(getSelectedItems(), getSelectedActions());
                updateSidebarItems();
            }

            // Select all permissions
            $('#selectAllPermissions').on('change', function() {
                const isChecked = $(this).prop('checked');
                $('#deselectAllPermissions').prop('checked', false);
                $('input.item-checkbox').each(function() {
                    const itemId = parseInt($(this).val());
                    if (isChecked) {
                        selectAllPermissionsForItem(itemId);
                    } else {
                        deselectAllPermissionsForItem(itemId);
                    }
                });
            });

            // Deselect all permissions
            $('#deselectAllPermissions').on('change', function() {
                const isChecked = $(this).prop('checked');
                $('#selectAllPermissions').prop('checked', false);
                $('input.item-checkbox').each(function() {
                    const itemId = parseInt($(this).val());
                    if (isChecked) {
                        deselectAllPermissionsForItem(itemId);
                    }
                });
            });

            // Per-item select all
            $('.select-all-permissions').on('click', function() {
                const itemId = parseInt($(this).data('item-id'));
                selectAllPermissionsForItem(itemId);
            });

            // Per-item deselect all
            $('.deselect-all-permissions').on('click', function() {
                const itemId = parseInt($(this).data('item-id'));
                deselectAllPermissionsForItem(itemId);
            });

            // Form submission
            $('#items-form').on('submit', function(e) {
                e.preventDefault();
                const $button = $('#save-items');
                const originalText = $button.html();
                $button.addClass('loading').html('<i class="fas fa-spinner fa-spin me-2"></i>Saving...').prop('disabled', true);

                const items = getSelectedItems();
                const actions = getSelectedActions();

                // Ensure "Lead" item (dropdown_item_id = 20) is included if checked
                const $leadCheckbox = $('input.item-checkbox[data-item="Lead"]');
                if ($leadCheckbox.length && $leadCheckbox.prop('checked')) {
                    const leadItemId = parseInt($leadCheckbox.val());
                    if (!items.includes(leadItemId)) {
                        items.push(leadItemId);
                    }
                    if (!actions[leadItemId]) {
                        actions[leadItemId] = {
                            can_create: 0,
                            can_read: 0,
                            can_update: 0,
                            can_delete: 0,
                            can_view_lead: 0,
                            can_approve_kyc: 0,
                            can_reject_kyc: 0
                        };
                    }
                }

                // Filter actions to only include items with permissions or selected items
                const filteredActions = {};
                Object.keys(actions).forEach(itemId => {
                    const action = actions[itemId];
                    const isLeadItem = $(`input.item-checkbox[value="${itemId}"]`).data('item').toLowerCase() === 'lead' && $(`input.item-checkbox[value="${itemId}"]`).closest('.accordion-item').find('.accordion-button').text().trim() === 'Lead Management';
                    if (items.includes(parseInt(itemId)) || (
                        action.can_create ||
                        action.can_read ||
                        action.can_update ||
                        action.can_delete ||
                        (isLeadItem && (
                            action.can_view_lead ||
                            action.can_approve_kyc ||
                            action.can_reject_kyc
                        ))
                    )) {
                        filteredActions[itemId] = action;
                    }
                });

                $.ajax({
                    url: $(this).attr('action'),
                    method: 'PUT',
                    data: JSON.stringify({ dropdown_items: items, actions: filteredActions }),
                    contentType: 'application/json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                        'Accept': 'application/json'
                    },
                    success: function(response) {
                        if (response.success) {
                            const serverItems = response.dropdown_items || items;
                            const serverActions = response.actions || actions;
                            $('input.item-checkbox').each(function() {
                                const itemId = parseInt($(this).val());
                                const isLeadItem = $(this).data('item').toLowerCase() === 'lead' && $(this).closest('.accordion-item').find('.accordion-button').text().trim() === 'Lead Management';
                                const hasPermissions = serverActions[itemId] && (
                                    serverActions[itemId].can_create ||
                                    serverActions[itemId].can_read ||
                                    serverActions[itemId].can_update ||
                                    serverActions[itemId].can_delete ||
                                    (isLeadItem && (
                                        serverActions[itemId].can_view_lead ||
                                        serverActions[itemId].can_approve_kyc ||
                                        serverActions[itemId].can_reject_kyc
                                    ))
                                );
                                $(this).prop('checked', serverItems.includes(itemId) || hasPermissions);
                                $(`input.action-checkbox[name^="actions[${itemId}]"]`).prop('disabled', !($(this).prop('checked') || hasPermissions));
                                if (serverActions[itemId]) {
                                    $(`input[name="actions[${itemId}][can_create]"]`).prop('checked', serverActions[itemId].can_create);
                                    $(`input[name="actions[${itemId}][can_read]"]`).prop('checked', serverActions[itemId].can_read);
                                    $(`input[name="actions[${itemId}][can_update]"]`).prop('checked', serverActions[itemId].can_update);
                                    $(`input[name="actions[${itemId}][can_delete]"]`).prop('checked', serverActions[itemId].can_delete);
                                    if (isLeadItem) {
                                        $(`input[name="actions[${itemId}][can_view_lead]"]`).prop('checked', serverActions[itemId].can_view_lead);
                                        $(`input[name="actions[${itemId}][can_approve_kyc]"]`).prop('checked', serverActions[itemId].can_approve_kyc);
                                        $(`input[name="actions[${itemId}][can_reject_kyc]"]`).prop('checked', serverActions[itemId].can_reject_kyc);
                                    }
                                }
                            });
                            localStorage.setItem(TEMP_STORAGE_KEY, JSON.stringify({ items: serverItems, actions: serverActions }));
                            updateItemsTables(serverItems, serverActions);
                            updateSidebarItems();
                            storeInitialState();
                            showNotification(response.message || 'Dropdown items and permissions saved successfully!');
                        } else {
                            showNotification(response.message || 'Failed to save permissions.', 'danger');
                        }
                    },
                    error: function(xhr) {
                        const errorMessage = xhr.responseJSON?.message || 'Failed to save permissions. Please try again.';
                        showNotification(errorMessage, 'danger');
                        console.error('Form submission error:', xhr.responseJSON || xhr.statusText);
                    },
                    complete: function() {
                        $button.removeClass('loading').html(originalText).prop('disabled', false);
                    }
                });
            });

            // Reset items
            $('#reset-items').on('click', function() {
                const initialState = JSON.parse(localStorage.getItem(TEMP_STORAGE_KEY + '_initial') || '{}');
                $('input.item-checkbox').each(function() {
                    const itemId = parseInt($(this).val());
                    const isLeadItem = $(this).data('item').toLowerCase() === 'lead' && $(this).closest('.accordion-item').find('.accordion-button').text().trim() === 'Lead Management';
                    const state = initialState[itemId] || {};
                    $(this).prop('checked', state.assigned === 1);
                    $(`input[name="actions[${itemId}][can_create]"]`).prop('checked', state.actions?.can_create === 1);
                    $(`input[name="actions[${itemId}][can_read]"]`).prop('checked', state.actions?.can_read === 1);
                    $(`input[name="actions[${itemId}][can_update]"]`).prop('checked', state.actions?.can_update === 1);
                    $(`input[name="actions[${itemId}][can_delete]"]`).prop('checked', state.actions?.can_delete === 1);
                    if (isLeadItem) {
                        $(`input[name="actions[${itemId}][can_view_lead]"]`).prop('checked', state.actions?.can_view_lead === 1);
                        $(`input[name="actions[${itemId}][can_approve_kyc]"]`).prop('checked', state.actions?.can_approve_kyc === 1);
                        $(`input[name="actions[${itemId}][can_reject_kyc]"]`).prop('checked', state.actions?.can_reject_kyc === 1);
                    }
                    $(`input.action-checkbox[name^="actions[${itemId}]"]`).prop('disabled', !$(this).prop('checked'));
                });
                $('#selectAllPermissions').prop('checked', false);
                $('#deselectAllPermissions').prop('checked', false);
                updateItemsTables(getSelectedItems(), getSelectedActions());
                updateSidebarItems();
                showNotification('Permissions reset to initial state.', 'info');
            });

            // Sidebar Toggle
            function initSidebarToggle() {
                const sidebarToggle = document.querySelector('#sidebarToggle');
                if (sidebarToggle) {
                    sidebarToggle.addEventListener('click', function() {
                        $('#sidebarMenu').toggleClass('show');
                        console.log('Sidebar toggled');
                    });
                } else {
                    console.warn('Sidebar toggle button not found, retrying in 100ms');
                    setTimeout(initSidebarToggle, 100);
                }
            }
            initSidebarToggle();

            // Collapse Sidebar
            $('#collapseSidebar').on('click', function() {
                $('#sidebarMenu').toggleClass('collapsed');
                $(this).html(`<i class="fas fa-${$('#sidebarMenu').hasClass('collapsed') ? 'expand-alt' : 'compress-alt'}"></i>`);
            });

            // Theme Toggle
            $('#themeToggle').on('click', function() {
                const isDark = $('body').attr('data-theme') === 'dark';
                $('body').attr('data-theme', isDark ? 'light' : 'dark');
                $(this).html(`<i class="fas fa-${isDark ? 'moon' : 'sun'}"></i>`);
            });

            // Search Dropdown Items
            $('#itemSearch').on('input', function() {
                const searchTerm = $(this).val().toLowerCase();
                $('.accordion-item').each(function() {
                    const $accordion = $(this);
                    let hasVisibleRows = false;
                    $accordion.find('tbody tr').each(function() {
                        const item = $(this).find('td:first').text().toLowerCase();
                        const isVisible = item.includes(searchTerm);
                        $(this).toggle(isVisible);
                        if (isVisible) hasVisibleRows = true;
                    });
                    $accordion.toggle(hasVisibleRows);
                });
            });

            // Keyboard Accessibility
            $('.nav-link, .custom-checkbox input, .btn').on('keypress', function(e) {
                if (e.which === 13) {
                    $(this).trigger('click');
                }
            });
        });
    </script>
@endsection