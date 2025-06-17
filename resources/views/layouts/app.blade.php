<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Travel Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .sidebar {
            width: 250px;
            transition: all 0.3s;
        }
        .main-content {
            margin-left: 250px;
            transition: all 0.3s;
        }
        @media (max-width: 768px) {
            .sidebar {
                margin-left: -250px;
            }
            .main-content {
                margin-left: 0;
            }
            .sidebar.active {
                margin-left: 0;
            }
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <div class="sidebar bg-gray-800 text-white fixed h-full">
            <div class="p-4">
                <h2 class="text-2xl font-semibold">Travel Management</h2>
            </div>
            <nav class="mt-4">
                <a href="{{ route('dashboard') }}" class="block py-2 px-4 hover:bg-gray-700 {{ request()->routeIs('dashboard') ? 'bg-gray-700' : '' }}">
                    <i class="fas fa-home mr-2"></i> Dashboard
                </a>
                <a href="{{ route('leads.index') }}" class="block py-2 px-4 hover:bg-gray-700 {{ request()->routeIs('leads.*') ? 'bg-gray-700' : '' }}">
                    <i class="fas fa-users mr-2"></i> Leads
                </a>
                @if(auth()->user()->role === 'admin')
                <a href="{{ route('leads.create') }}" class="block py-2 px-4 hover:bg-gray-700">
                    <i class="fas fa-plus mr-2"></i> Add Lead
                </a>
                <a href="{{ route('employees.index') }}" class="block py-2 px-4 hover:bg-gray-700 {{ request()->routeIs('employees.*') ? 'bg-gray-700' : '' }}">
                    <i class="fas fa-user-tie mr-2"></i> Employees
                </a>
                @endif
                <form method="POST" action="{{ route('logout') }}" class="block">
                    @csrf
                    <button type="submit" class="w-full text-left py-2 px-4 hover:bg-gray-700">
                        <i class="fas fa-sign-out-alt mr-2"></i> Logout
                    </button>
                </form>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="main-content flex-1">
            <!-- Top Navigation -->
            <nav class="bg-white shadow-lg">
                <div class="max-w-7xl mx-auto px-4">
                    <div class="flex justify-between h-16">
                        <div class="flex items-center">
                            <button id="sidebarToggle" class="md:hidden">
                                <i class="fas fa-bars"></i>
                            </button>
                        </div>
                        <div class="flex items-center">
                            <span class="text-gray-700">{{ auth()->user()->name }}</span>
                            <span class="ml-2 px-2 py-1 text-xs rounded-full {{ auth()->user()->role === 'admin' ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800' }}">
                                {{ ucfirst(auth()->user()->role) }}
                            </span>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Page Content -->
            <main class="p-6">
                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <span class="block sm:inline">{{ session('success') }}</span>
                    </div>
                @endif

                @if($errors->any())
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <ul>
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <script>
        // Toggle sidebar on mobile
        document.getElementById('sidebarToggle').addEventListener('click', function() {
            document.querySelector('.sidebar').classList.toggle('active');
        });
    </script>
</body>
</html> 