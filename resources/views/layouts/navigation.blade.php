<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 py-3 flex items-center justify-between">
        <div class="flex items-center space-x-4">
            <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                    </a>
                </div>

            @auth
                <a href="{{ route('devices.index') }}" class="text-sm text-gray-700 hover:underline">
                    Devices
                </a>
                <a href="{{ route('presets.index') }}" class="text-sm text-gray-700 hover:underline">
                    Presets
                </a>
                <a href="{{ route('provisions.index') }}" class="text-sm text-gray-700 hover:underline">
                    Provisions
                </a>
                <a href="{{ route('files.index') }}" class="text-sm text-gray-700 hover:underline">
                    Files
                </a>
            @endauth
        </div>

        <div class="flex items-center space-x-3">
            @auth
                <span class="text-sm text-gray-600">
                    {{ auth()->user()->name }} ({{ auth()->user()->role }})
                </span>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                            class="px-3 py-1 text-sm rounded bg-gray-800 text-white hover:bg-gray-900">
                        Logout
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}"
                   class="px-3 py-1 text-sm rounded bg-blue-600 text-white hover:bg-blue-700">
                    Login
                </a>
            @endauth
        </div>
    </div>
</nav>

