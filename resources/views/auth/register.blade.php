<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Register as a Super Admin for Loan Management Software with role-based access.">
    <title>Register Super Admin - Loan Management Software</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.4.2/css/all.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.11.5/gsap.min.js"></script>
    <style>
        :root {
            --primary-color: #1a3c6d;
            --secondary-color: #0d6efd;
            --accent-color: #34d399;
            --error-color: #dc3545;
            --background-gradient: linear-gradient(135deg, #e2e8f0 0%, #bfdbfe 100%);
            --glass-bg: rgba(255, 255, 255, 0.1);
            --glass-border: rgba(255, 255, 255, 0.2);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--background-gradient);
            min-height: 100vh;
            overflow-x: hidden;
            position: relative;
        }

        .hero-section {
            display: flex;
            min-height: 100vh;
            align-items: center;
            justify-content: center;
            position: relative;
            padding: 2rem;
        }

        .register-container {
            display: flex;
            max-width: 1200px;
            width: 100%;
            background: var(--glass-bg);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            border: 1px solid var(--glass-border);
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
            overflow: hidden;
            animation: slideIn 0.8s ease-out;
        }

        .form-section {
            flex: 1;
            padding: 3rem;
            background: #ffffff;
            border-radius: 20px 0 0 20px;
        }

        .sidebar-section {
            flex: 1;
            padding: 3rem;
            background: var(--primary-color);
            color: #ffffff;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .sidebar-section h3 {
            font-size: 1.75rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
        }

        .sidebar-section ul {
            list-style: none;
            font-size: 1rem;
            line-height: 1.8;
        }

        .sidebar-section ul li {
            display: flex;
            align-items: center;
            margin-bottom: 1rem;
            transition: transform 0.3s;
        }

        .sidebar-section ul li:hover {
            transform: translateX(10px);
        }

        .sidebar-section ul li i {
            margin-right: 0.75rem;
            color: var(--accent-color);
        }

        .form-section h2 {
            font-size: 2rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 1.5rem;
            text-align: center;
        }

        .form-group {
            position: relative;
            margin-bottom: 1.75rem;
        }

        .form-group label {
            font-size: 0.9rem;
            font-weight: 500;
            color: var(--primary-color);
            margin-bottom: 0.5rem;
            display: block;
        }

        .form-group input {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1px solid #d1d5db;
            border-radius: 10px;
            font-size: 1rem;
            transition: border-color 0.3s, box-shadow 0.3s;
            background: #f9fafb;
        }

        .form-group input:focus {
            outline: none;
            border-color: var(--secondary-color);
            box-shadow: 0 0 10px rgba(13, 110, 253, 0.2);
        }

        .form-group .password-toggle {
            position: absolute;
            right: 1rem;
            top: 65%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #6b7280;
        }

        .error-message {
            font-size: 0.85rem;
            color: var(--error-color);
            margin-top: 0.25rem;
            display: flex;
            align-items: center;
        }

        .error-message::before {
            content: '⚠';
            margin-right: 0.5rem;
        }

        .btn-primary {
            background: var(--secondary-color);
            border: none;
            border-radius: 10px;
            padding: 0.85rem;
            font-size: 1rem;
            font-weight: 500;
            width: 100%;
            transition: background 0.3s, transform 0.2s, box-shadow 0.3s;
        }

        .btn-primary:hover {
            background: #005cbf;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 92, 191, 0.3);
        }

        .login-link {
            color: var(--secondary-color);
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s;
        }

        .login-link:hover {
            color: #005cbf;
            text-decoration: underline;
        }

        .password-strength {
            margin-top: 0.5rem;
            font-size: 0.85rem;
            color: #6b7280;
        }

        .progress-bar {
            height: 5px;
            background: #e5e7eb;
            border-radius: 5px;
            overflow: hidden;
            margin-top: 0.5rem;
        }

        .progress-fill {
            height: 100%;
            transition: width 0.3s;
        }

        .progress-fill.weak { background: var(--error-color); width: 33%; }
        .progress-fill.medium { background: #f59e0b; width: 66%; }
        .progress-fill.strong { background: var(--accent-color); width: 100%; }

        .particles {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: -1;
        }

        .particle {
            position: absolute;
            background: rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            animation: float 15s infinite;
        }

        @keyframes float {
            0% { transform: translateY(0); opacity: 0.5; }
            50% { opacity: 0.8; }
            100% { transform: translateY(-100vh); opacity: 0; }
        }

        @keyframes slideIn {
            from { opacity: 0; transform: translateY(50px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @media (max-width: 768px) {
            .register-container {
                flex-direction: column;
            }
            .form-section, .sidebar-section {
                border-radius: 20px;
            }
            .form-section {
                padding: 2rem;
            }
            .sidebar-section {
                padding: 2rem;
            }
            .form-section h2 {
                font-size: 1.75rem;
            }
        }

        @media (max-width: 576px) {
            .register-container {
                margin: 1rem;
            }
            .form-section, .sidebar-section {
                padding: 1.5rem;
            }
            .form-section h2 {
                font-size: 1.5rem;
            }
        }

        /* High-contrast mode */
        @media (prefers-contrast: high) {
            body {
                background: #000;
                color: #fff;
            }
            .register-container {
                background: #333;
                border: 1px solid #fff;
            }
            .form-section {
                background: #222;
            }
            .form-group input {
                background: #444;
                color: #fff;
                border-color: #fff;
            }
            .btn-primary {
                background: #fff;
                color: #000;
            }
            .btn-primary:hover {
                background: #ccc;
            }
            .login-link {
                color: #fff;
            }
        }
    </style>
</head>
<body>
    <div class="particles" id="particles"></div>
    <section class="hero-section">
        <div class="register-container">
            <div class="form-section">
                <h2>Create Super Admin Account</h2>
                <form method="POST" action="{{ route('register') }}" id="register-form">
                    @csrf
                    <div class="form-group">
                        <label for="name" class="form-label">Full Name</label>
                        <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" required aria-describedby="name-error" autofocus>
                        @error('name')
                            <div class="error-message" id="name-error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}" required aria-describedby="email-error">
                        @error('email')
                            <div class="error-message" id="email-error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="password" class="form-label">Password</label>
                        <div class="relative">
                            <input type="password" name="password" id="password" class="form-control" required aria-describedby="password-error">
                            <span class="password-toggle" onclick="togglePassword('password')"><i class="fas fa-eye"></i></span>
                        </div>
                        <div class="password-strength" id="password-strength">Password Strength: <span id="strength-text">Weak</span></div>
                        <div class="progress-bar">
                            <div class="progress-fill" id="password-progress"></div>
                        </div>
                        @error('password')
                            <div class="error-message" id="password-error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="password_confirmation" class="form-label">Confirm Password</label>
                        <div class="relative">
                            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required>
                            <span class="password-toggle" onclick="togglePassword('password_confirmation')"><i class="fas fa-eye"></i></span>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Register Now</button>
                </form>
                <div class="mt-4 text-center">
                    <a href="{{ route('login') }}" class="login-link">Already have an account? Sign in</a>
                </div>
            </div>
            <div class="sidebar-section">
                <h3>Super Admin Registration</h3>
                <ul>
                    <li><i class="fas fa-shield-alt"></i> Full administrative control over the system</li>
                    <li><i class="fas fa-users"></i> Create and manage users with any role</li>
                    <li><i class="fas fa-lock"></i> Secure access with advanced encryption</li>
                    <li><i class="fas fa-chart-line"></i> Oversee all loan management operations</li>
                    <li><i class="fas fa-headset"></i> Priority support for administrators</li>
                </ul>
            </div>
        </div>
    </section>
    <script>
        // Password toggle functionality
        function togglePassword(fieldId) {
            const field = document.getElementById(fieldId);
            const icon = field.nextElementSibling.querySelector('i');
            if (field.type === 'password') {
                field.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                field.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }

        // Password strength meter
        const passwordInput = document.getElementById('password');
        const strengthText = document.getElementById('strength-text');
        const progressFill = document.getElementById('password-progress');

        passwordInput.addEventListener('input', () => {
            const value = passwordInput.value;
            let strength = 0;
            if (value.length > 0) strength += 1;
            if (value.length >= 8) strength += 1;
            if (/[A-Z]/.test(value) && /[0-9]/.test(value) && /[^A-Za-z0-9.test(value)) strength += 1;

            switch (strength) {
                case 1:
                    strengthText.textContent = 'Weak';
                    progressFill.className = 'progress-fill weak';
                    break;
                case 2:
                    strengthText.textContent = 'Medium';
                    progressFill.className = 'progress-fill medium';
                    break;
                case 3:
                    strengthText.textContent = 'Strong';
                    progressFill.className = 'progress-fill strong';
                    break;
                default:
                    strengthText.textContent = 'Weak';
                    progressFill.className = 'progress-fill weak';
            }
        });

        // GSAP animations
        gsap.from('.form-section', { opacity: 0, x: -50, duration: 1, ease: 'power3.out' });
        gsap.from('.sidebar-section', { opacity: 0, x: 50, duration: 1, ease: 'power3.out', delay: 0.2 });
        gsap.from('.form-group', { opacity: 0, y: 20, duration: 0.8, stagger: 0.2, delay: 0.5 });

        // Particle background
        const particlesContainer = document.getElementById('particles');
        for (let i = 0; i < 50; i++) {
            const particle = document.createElement('div');
            particle.classList.add('particle');
            particle.style.width = `${Math.random() * 10 + 5}px`;
            particle.style.height = particle.style.width;
            particle.style.left = `${Math.random() * 100}vw`;
            particle.style.top = `${Math.random() * 100}vh`;
            particle.style.animationDuration = `${Math.random() * 10 + 5}s`;
            particle.style.animationDelay = `${Math.random() * 5}s`;
            particlesContainer.appendChild(particle);
        }

        // Form progress indicator
        const form = document.getElementById('register-form');
        const inputs = form.querySelectorAll('input');
        inputs.forEach(input => {
            input.addEventListener('input', () => {
                const filled = Array.from(inputs).filter(inp => inp.value.trim() !== '').length;
                const progress = (filled / inputs.length) * 100;
                console.log(`Form completion: ${progress}%`); // For debugging
            });
        }));

        // Keyboard accessibility
        inputs.forEach(input => {
            input.addEventListener('keypress', (event => {
                if (event.key === 'Enter' && input !== inputs[inputs.length - 1]) {
                    event.preventDefault();
                    const nextInput = inputs[Array.from(inputs).indexOf(input) + 1];
                    if (nextInput) nextInput.focus();
                }
            }));
        }));
    </script>
</body>
</html>