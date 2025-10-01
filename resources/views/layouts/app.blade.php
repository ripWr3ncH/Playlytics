<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Playlytics - Football Analytics & Player Statistics')</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom CSS -->
    <style>
        /* Theme Variables */
        :root {
            --primary-blue: #2563EB;
            --primary-gradient: linear-gradient(135deg, #2563EB 0%, #1D4ED8 50%, #1E40AF 100%);
            --accent-purple: #7C3AED;
            --accent-cyan: #06B6D4;
            
            /* Dark Theme (default) */
            --bg-primary: #0F172A;
            --bg-secondary: #1E293B;
            --text-primary: #F1F5F9;
            --text-secondary: #94A3B8;
            --border-color: #334155;
            --shadow-color: rgba(0, 0, 0, 0.4);
        }
        
        /* Light Theme */
        [data-theme="light"] {
            --bg-primary: #F8FAFC;
            --bg-secondary: #FFFFFF;
            --text-primary: #0F172A;
            --text-secondary: #475569;
            --border-color: #E2E8F0;
            --shadow-color: rgba(0, 0, 0, 0.1);
        }
        
        .bg-primary { background: var(--primary-gradient); }
        .bg-primary-solid { background-color: var(--primary-blue); }
        .bg-dark { background-color: var(--bg-primary); }
        .bg-card { background-color: var(--bg-secondary); }
        .text-light { color: var(--text-primary); }
        .text-muted { color: var(--text-secondary); }
        .text-primary { color: var(--primary-blue); }
        .border-gray-700 { border-color: var(--border-color); }
        .border-gray-200 { border-color: var(--border-color); }
        
        /* Live score animation */
        .live-pulse {
            animation: pulse-blue 2s infinite;
        }
        
        @keyframes pulse-blue {
            0%, 100% { background: var(--primary-gradient); }
            50% { background-color: var(--primary-blue); }
        }
        
        /* Smooth transitions */
        .transition-all {
            transition: all 0.3s ease;
        }
        
        /* Enhanced navigation hover effects */
        .nav-link {
            position: relative;
            overflow: hidden;
        }
        
        .nav-link::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(37, 99, 235, 0.1), transparent);
            transition: left 0.5s ease-in-out;
        }
        
        .nav-link:hover::before {
            left: 100%;
        }
        
        /* Logo hover effect */
        .logo-hover {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .logo-hover:hover {
            transform: rotate(10deg) scale(1.1);
        }
        
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }
        
        ::-webkit-scrollbar-track {
            background: var(--bg-primary);
        }
        
        ::-webkit-scrollbar-thumb {
            background: var(--primary-blue);
            border-radius: 4px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: var(--accent-purple);
        }
        
        /* SVG logo styling */
        .league-logo {
            max-width: 100%;
            max-height: 100%;
            width: auto;
            height: auto;
        }
        
        .league-logo svg {
            width: 100%;
            height: 100%;
        }
        
        /* Theme toggle button styles */
        .theme-toggle {
            position: relative;
            overflow: hidden;
        }
        
        .theme-toggle::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            background: radial-gradient(circle, var(--primary-blue) 0%, transparent 70%);
            opacity: 0.1;
            transform: translate(-50%, -50%);
            transition: all 0.3s ease;
        }
        
        .theme-toggle:hover::before {
            width: 40px;
            height: 40px;
        }
        
        /* Smooth theme transitions for all elements */
        * {
            transition: background-color 0.3s ease, color 0.3s ease, border-color 0.3s ease;
        }
        
        /* Light theme specific styles */
        [data-theme="light"] .bg-card {
            background-color: var(--bg-secondary);
            box-shadow: 0 1px 3px var(--shadow-color);
        }
        
        [data-theme="light"] footer {
            border-top: 1px solid var(--border-color);
        }
        
        /* Navigation theme styles */
        nav {
            background-color: var(--bg-secondary);
            border-bottom: 1px solid var(--border-color);
        }
    </style>
    
    @stack('styles')
</head>
<body class="h-full bg-dark text-light font-sans transition-colors duration-300">
    <!-- Navigation -->
    <nav class="bg-card shadow-lg sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <div class="flex items-center">
                    <a href="{{ route('home') }}" class="flex items-center space-x-2 group">
                        <div class="bg-primary w-10 h-10 rounded-full flex items-center justify-center logo-hover shadow-lg group-hover:shadow-primary/50">
                            <i class="fas fa-chart-line text-white text-lg"></i>
                        </div>
                        <span class="text-xl font-bold text-light group-hover:text-primary transition-colors duration-300">Playlytics</span>
                    </a>
                </div>
                
                <!-- Main Navigation -->
                <div class="hidden md:flex items-center space-x-8">
                    <a href="{{ route('home') }}" class="nav-link group relative px-3 py-2 rounded-lg transition-all duration-300 hover:bg-primary/10 {{ request()->routeIs('home') ? 'text-primary bg-primary/20' : 'text-light' }}">
                        <i class="fas fa-home mr-2 transform group-hover:scale-110 transition-transform duration-300"></i>Home
                        <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-primary transition-all duration-300 group-hover:w-full"></span>
                    </a>
                    <a href="{{ route('matches.index') }}" class="nav-link group relative px-3 py-2 rounded-lg transition-all duration-300 hover:bg-primary/10 {{ request()->routeIs('matches.*') ? 'text-primary bg-primary/20' : 'text-light' }}">
                        <i class="fas fa-calendar-alt mr-2 transform group-hover:scale-110 transition-transform duration-300"></i>Matches
                        <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-primary transition-all duration-300 group-hover:w-full"></span>
                    </a>
                    <a href="{{ route('leagues.index') }}" class="nav-link group relative px-3 py-2 rounded-lg transition-all duration-300 hover:bg-primary/10 {{ request()->routeIs('leagues.*') ? 'text-primary bg-primary/20' : 'text-light' }}">
                        <i class="fas fa-trophy mr-2 transform group-hover:scale-110 transition-transform duration-300"></i>Leagues
                        <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-primary transition-all duration-300 group-hover:w-full"></span>
                    </a>
                    <a href="{{ route('teams.index') }}" class="nav-link group relative px-3 py-2 rounded-lg transition-all duration-300 hover:bg-primary/10 {{ request()->routeIs('teams.*') ? 'text-primary bg-primary/20' : 'text-light' }}">
                        <i class="fas fa-users mr-2 transform group-hover:scale-110 transition-transform duration-300"></i>Teams
                        <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-primary transition-all duration-300 group-hover:w-full"></span>
                    </a>
                    <a href="{{ route('players.index') }}" class="nav-link group relative px-3 py-2 rounded-lg transition-all duration-300 hover:bg-primary/10 {{ request()->routeIs('players.*') ? 'text-primary bg-primary/20' : 'text-light' }}">
                        <i class="fas fa-user mr-2 transform group-hover:scale-110 transition-transform duration-300"></i>Players
                        <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-primary transition-all duration-300 group-hover:w-full"></span>
                    </a>
                </div>
                
                <!-- Theme Toggle & Mobile Menu -->
                <div class="flex items-center space-x-4">
                    <!-- Theme Toggle Button -->
                    <button id="theme-toggle" class="theme-toggle p-2 rounded-lg text-light hover:text-primary hover:bg-primary/10 transition-all duration-300 group" title="Toggle theme">
                        <i class="fas fa-sun text-lg sun-icon hidden group-hover:rotate-180 transition-transform duration-300"></i>
                        <i class="fas fa-moon text-lg moon-icon group-hover:-rotate-180 transition-transform duration-300"></i>
                    </button>
                    
                    <!-- Mobile menu button -->
                    <div class="md:hidden">
                        <button id="mobile-menu-button" class="text-light hover:text-primary">
                            <i class="fas fa-bars text-xl"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Mobile menu -->
        <div id="mobile-menu" class="md:hidden hidden bg-card border-t border-gray-700">
            <div class="px-2 pt-2 pb-3 space-y-1">
                <a href="{{ route('home') }}" class="block px-3 py-2 rounded-lg text-light hover:text-primary hover:bg-primary/10 transition-all duration-300 transform hover:translate-x-2">
                    <i class="fas fa-home mr-2"></i>Home
                </a>
                <a href="{{ route('matches.index') }}" class="block px-3 py-2 rounded-lg text-light hover:text-primary hover:bg-primary/10 transition-all duration-300 transform hover:translate-x-2">
                    <i class="fas fa-calendar-alt mr-2"></i>Matches
                </a>
                <a href="{{ route('leagues.index') }}" class="block px-3 py-2 rounded-lg text-light hover:text-primary hover:bg-primary/10 transition-all duration-300 transform hover:translate-x-2">
                    <i class="fas fa-trophy mr-2"></i>Leagues
                </a>
                <a href="{{ route('teams.index') }}" class="block px-3 py-2 rounded-lg text-light hover:text-primary hover:bg-primary/10 transition-all duration-300 transform hover:translate-x-2">
                    <i class="fas fa-users mr-2"></i>Teams
                </a>
                <a href="{{ route('players.index') }}" class="block px-3 py-2 rounded-lg text-light hover:text-primary hover:bg-primary/10 transition-all duration-300 transform hover:translate-x-2">
                    <i class="fas fa-user mr-2"></i>Players
                </a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="min-h-screen">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-card mt-16 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <div class="flex items-center space-x-2 mb-4 md:mb-0">
                    <div class="bg-primary w-8 h-8 rounded-full flex items-center justify-center">
                        <i class="fas fa-chart-line text-white"></i>
                    </div>
                    <span class="text-lg font-bold text-light">Playlytics</span>
                </div>
                <div class="text-muted text-sm">
                    © {{ date('Y') }} Playlytics. All rights reserved.
                </div>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="{{ asset('js/live-scores.js') }}"></script>
    <script>
        // Theme Management
        class ThemeManager {
            constructor() {
                this.theme = localStorage.getItem('theme') || 'dark';
                this.init();
            }
            
            init() {
                this.applyTheme(this.theme);
                this.setupEventListeners();
            }
            
            setupEventListeners() {
                const themeToggle = document.getElementById('theme-toggle');
                if (themeToggle) {
                    themeToggle.addEventListener('click', () => this.toggleTheme());
                }
            }
            
            applyTheme(theme) {
                document.documentElement.setAttribute('data-theme', theme);
                this.updateThemeIcon(theme);
                localStorage.setItem('theme', theme);
            }
            
            updateThemeIcon(theme) {
                const sunIcon = document.querySelector('.sun-icon');
                const moonIcon = document.querySelector('.moon-icon');
                
                if (sunIcon && moonIcon) {
                    if (theme === 'light') {
                        sunIcon.classList.remove('hidden');
                        moonIcon.classList.add('hidden');
                    } else {
                        sunIcon.classList.add('hidden');
                        moonIcon.classList.remove('hidden');
                    }
                }
            }
            
            toggleTheme() {
                this.theme = this.theme === 'dark' ? 'light' : 'dark';
                this.applyTheme(this.theme);
                
                // Add smooth transition effect
                document.body.style.transition = 'background-color 0.3s ease, color 0.3s ease';
                setTimeout(() => {
                    document.body.style.transition = '';
                }, 300);
            }
        }
        
        // Initialize when DOM is loaded
        document.addEventListener('DOMContentLoaded', function() {
            new ThemeManager();
            
            // Mobile menu toggle
            const mobileMenuButton = document.getElementById('mobile-menu-button');
            if (mobileMenuButton) {
                mobileMenuButton.addEventListener('click', function() {
                    const menu = document.getElementById('mobile-menu');
                    if (menu) {
                        menu.classList.toggle('hidden');
                    }
                });
            }
        });
        
        // Live score updates (simulated)
        function updateLiveScores() {
            // This would connect to a real-time API in production
            const liveElements = document.querySelectorAll('.live-score');
            liveElements.forEach(element => {
                // Add pulse animation to live scores
                element.classList.add('live-pulse');
            });
        }
        
        // Update scores every 30 seconds
        setInterval(updateLiveScores, 30000);
        
        // Initial load
        updateLiveScores();
    </script>
    
    @stack('scripts')
</body>
</html>
