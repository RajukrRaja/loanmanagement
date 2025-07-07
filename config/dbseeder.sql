-- Use the loan_management_system database
USE loan_management_system;

-- Step 1: Insert Sample Regions
INSERT INTO regions (region_name) VALUES
('North Region'),
('South Region');

-- Step 2: Insert Sample Branches
INSERT INTO branches (branch_name, parent_branch_id, region_id) VALUES
('Main Branch Delhi', NULL, (SELECT region_id FROM regions WHERE region_name = 'North Region')),
('Sub Branch Delhi East', (SELECT branch_id FROM branches WHERE branch_name = 'Main Branch Delhi'), (SELECT region_id FROM regions WHERE region_name = 'North Region')),
('Main Branch Chennai', NULL, (SELECT region_id FROM regions WHERE region_name = 'South Region'));

-- Step 3: Insert Sample Users (password_hash is a placeholder; in production, use proper hashing like bcrypt)
INSERT INTO users (username, email, password_hash, role_id, branch_id) VALUES
('superadmin', 'superadmin@example.com', 'hashed_password_1', (SELECT role_id FROM roles WHERE role_name = 'Super Admin'), NULL),
('admin1', 'admin1@example.com', 'hashed_password_2', (SELECT role_id FROM roles WHERE role_name = 'Admin'), (SELECT branch_id FROM branches WHERE branch_name = 'Main Branch Delhi')),
('subadmin1', 'subadmin1@example.com', 'hashed_password_3', (SELECT role_id FROM roles WHERE role_name = 'Sub Admin'), (SELECT branch_id FROM branches WHERE branch_name = 'Sub Branch Delhi East')),
('branchuser1', 'branchuser1@example.com', 'hashed_password_4', (SELECT role_id FROM roles WHERE role_name = 'Branch Login'), (SELECT branch_id FROM branches WHERE branch_name = 'Main Branch Chennai')),
('regionhead1', 'regionhead1@example.com', 'hashed_password_5', (SELECT role_id FROM roles WHERE role_name = 'Region Head'), NULL),
('teammanager1', 'teammanager1@example.com', 'hashed_password_6', (SELECT role_id FROM roles WHERE role_name = 'Team Manager'), (SELECT branch_id FROM branches WHERE branch_name = 'Main Branch Delhi')),
('telecaller1', 'telecaller1@example.com', 'hashed_password_7', (SELECT role_id FROM roles WHERE role_name = 'Telecaller'), (SELECT branch_id FROM branches WHERE branch_name = 'Main Branch Delhi')),
('accountant1', 'accountant1@example.com', 'hashed_password_8', (SELECT role_id FROM roles WHERE role_name = 'Accountant'), (SELECT branch_id FROM branches WHERE branch_name = 'Main Branch Chennai')),
('employee1', 'employee1@example.com', 'hashed_password_9', (SELECT role_id FROM roles WHERE role_name = 'Employee'), (SELECT branch_id FROM branches WHERE branch_name = 'Sub Branch Delhi East')),
('customer1', 'customer1@example.com', 'hashed_password_10', (SELECT role_id FROM roles WHERE role_name = 'Customer'), NULL),
('customer2', 'customer2@example.com', 'hashed_password_11', (SELECT role_id FROM roles WHERE role_name = 'Customer'), NULL);

-- Step 4: Insert Sample Leads
INSERT INTO leads (customer_id, assigned_to_user_id, branch_id, status, follow_up_notes) VALUES
((SELECT user_id FROM users WHERE username = 'customer1'), (SELECT user_id FROM users WHERE username = 'telecaller1'), (SELECT branch_id FROM branches WHERE branch_name = 'Main Branch Delhi'), 'New', 'Initial contact made, awaiting KYC documents'),
((SELECT user_id FROM users WHERE username = 'customer2'), (SELECT user_id FROM users WHERE username = 'telecaller1'), (SELECT branch_id FROM branches WHERE branch_name = 'Main Branch Chennai'), 'In Progress', 'Follow-up scheduled for next week');

-- Step 5: Insert Sample Loans
INSERT INTO loans (customer_id, branch_id, amount, status) VALUES
((SELECT user_id FROM users WHERE username = 'customer1'), (SELECT branch_id FROM branches WHERE branch_name = 'Main Branch Delhi'), 500000.00, 'Approved'),
((SELECT user_id FROM users WHERE username = 'customer2'), (SELECT branch_id FROM branches WHERE branch_name = 'Main Branch Chennai'), 300000.00, 'Pending');

-- Step 6: Insert Sample EMIs
INSERT INTO emis (loan_id, amount, due_date, status, payment_date) VALUES
((SELECT loan_id FROM loans WHERE customer_id = (SELECT user_id FROM users WHERE username = 'customer1')), 15000.00, '2025-07-01', 'Pending', NULL),
((SELECT loan_id FROM loans WHERE customer_id = (SELECT user_id FROM users WHERE username = 'customer1')), 15000.00, '2025-08-01', 'Pending', NULL),
((SELECT loan_id FROM loans WHERE customer_id = (SELECT user_id FROM users WHERE username = 'customer2')), 10000.00, '2025-07-15', 'Pending', NULL);

-- Step 7: Insert Sample Documents
INSERT INTO documents (user_id, loan_id, document_type, file_path, status) VALUES
((SELECT user_id FROM users WHERE username = 'customer1'), (SELECT loan_id FROM loans WHERE customer_id = (SELECT user_id FROM users WHERE username = 'customer1')), 'KYC', '/documents/customer1_kyc.pdf', 'Pending'),
((SELECT user_id FROM users WHERE username = 'customer2'), (SELECT loan_id FROM loans WHERE customer_id = (SELECT user_id FROM users WHERE username = 'customer2')), 'KYC', '/documents/customer2_kyc.pdf', 'Approved');

-- Step 8: Insert Sample Accounting Entries
INSERT INTO accounting_entries (user_id, loan_id, entry_type, amount, description) VALUES
((SELECT user_id FROM users WHERE username = 'accountant1'), (SELECT loan_id FROM loans WHERE customer_id = (SELECT user_id FROM users WHERE username = 'customer1')), 'Voucher', 15000.00, 'EMI collection for July'),
((SELECT user_id FROM users WHERE username = 'accountant1'), NULL, 'Ledger', 5000.00, 'Branch cash deposit');

-- Step 9: Insert Sample Wallet Requests
INSERT INTO wallet_requests (user_id, branch_id, amount, status) VALUES
((SELECT user_id FROM users WHERE username = 'employee1'), (SELECT branch_id FROM branches WHERE branch_name = 'Sub Branch Delhi East'), 10000.00, 'Pending'),
((SELECT user_id FROM users WHERE username = 'branchuser1'), (SELECT branch_id FROM branches WHERE branch_name = 'Main Branch Chennai'), 20000.00, 'Approved');