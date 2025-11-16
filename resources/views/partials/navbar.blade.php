<header class="w-full bg-white border-b border-slate-200 px-4 py-3 flex items-center justify-between">
    <div class="md:hidden">
        <span class="text-sm text-slate-500">Menu</span>
    </div>
    <div class="font-semibold text-slate-800">
        @yield('title', 'GenieACS UI')
    </div>
    <div class="text-xs text-slate-500">
        NBI: {{ config('genieacs.nbi_url') }}
    </div>
</header>
