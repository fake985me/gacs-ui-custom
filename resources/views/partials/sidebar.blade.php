<aside class="w-64 bg-slate-900 text-slate-100 hidden md:flex flex-col">
    <div class="px-4 py-4 border-b border-slate-800">
        <div class="font-bold text-lg">GenieACS Laravel UI</div>
        <div class="text-xs text-slate-400">Laravel 10 + GenieACS NBI</div>
    </div>

    <nav class="flex-1 px-2 py-4 space-y-1 text-sm">
        <a href="{{ route('dashboard') }}" class="block px-3 py-2 rounded hover:bg-slate-800 @if(request()->routeIs('dashboard')) bg-slate-800 font-semibold @endif">
            Dashboard
        </a>
        <a href="{{ route('devices.index') }}" class="block px-3 py-2 rounded hover:bg-slate-800 @if(request()->routeIs('devices.*')) bg-slate-800 font-semibold @endif">
            Devices
        </a>
        <a href="{{ route('presets.index') }}" class="block px-3 py-2 rounded hover:bg-slate-800 @if(request()->routeIs('presets.*')) bg-slate-800 font-semibold @endif">
            Presets
        </a>
        <a href="{{ route('provisions.index') }}" class="block px-3 py-2 rounded hover:bg-slate-800 @if(request()->routeIs('provisions.*')) bg-slate-800 font-semibold @endif">
            Provisions
        </a>
        <a href="{{ route('files.index') }}" class="block px-3 py-2 rounded hover:bg-slate-800 @if(request()->routeIs('files.*')) bg-slate-800 font-semibold @endif">
            Files
        </a>
        <a href="{{ route('tasks.index') }}" class="block px-3 py-2 rounded hover:bg-slate-800 @if(request()->routeIs('tasks.*')) bg-slate-800 font-semibold @endif">
            Tasks (stub)
        </a>
        <a href="{{ route('logs.index') }}" class="block px-3 py-2 rounded hover:bg-slate-800 @if(request()->routeIs('logs.*')) bg-slate-800 font-semibold @endif">
            Logs (stub)
        </a>
        <a href="{{ route('config.index') }}" class="block px-3 py-2 rounded hover:bg-slate-800 @if(request()->routeIs('config.*')) bg-slate-800 font-semibold @endif">
            Config (stub)
        </a>
    </nav>
</aside>
