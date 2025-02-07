<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
    
        <div class="w-64 bg-gray-800">
            <div class="flex items-center justify-center h-16 bg-gray-900">
                <span class="text-white font-bold text-xl">
                    @if(session()->has('logged_in'))
                        <p>Hello, {{ session('user.last_name') }} {{ session('user.first_name') }}</p>
                    @endif
                </span>
            </div>
            <nav class="mt-4">
                <a href="{{ route('profile') }}" class="flex items-center px-6 py-3 text-gray-300 hover:bg-gray-700">
                    <span>Profile</span>
                </a>
                <a href="{{ route('authors') }}" class="flex items-center px-6 py-3 text-gray-300 hover:bg-gray-700">
                    <span>Authors</span>
                </a>
                <a href="{{ route('books.create') }}" class="flex items-center px-6 py-3 text-gray-300 hover:bg-gray-700">
                    <span>Books</span>
                </a>
                <form method="POST"
                 action="{{ route('logout') }}" 
                 class="px-6 py-3">
                    @csrf
                    <button type="submit" class="text-gray-300 hover:text-white">Logout</button>
                </form>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="flex-1">
            <!-- Top Navigation -->
            <header class="bg-white shadow">
                <div class="px-4 py-4">
                    <h2 class="text-xl font-semibold text-gray-800">{{ $title }}</h2>
                </div>
            </header>

            <!-- Page Content -->
            <main class="p-6">
                @if(session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <strong class="font-bold">Error!</strong>
                        <span class="block sm:inline">{{ session('error') }}</span>
                    </div>
                @endif

                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <strong class="font-bold">Success!</strong>
                        <span class="block sm:inline">{{ session('success') }}</span>
                    </div>
                @endif
                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>