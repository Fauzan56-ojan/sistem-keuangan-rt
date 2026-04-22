<!DOCTYPE html>
<html class="light" lang="id">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Login - Sistem Keuangan RT</title>

    @vite('resources/css/app.css', 'resources/js/app.js')
    
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" rel="stylesheet" />
    
</head>
<body class="bg-background-rt font-body text-on-surface-rt min-h-screen flex flex-col items-center justify-center p-6 relative overflow-hidden">

    <div class="absolute inset-0 z-0 bg-gradient-to-br from-emerald-100/50 via-background-rt to-emerald-50/50"></div>
    <div class="absolute -top-40 -right-40 w-[30rem] h-[30rem] bg-emerald-200/20 rounded-full blur-[120px]"></div>
    <div class="absolute -bottom-40 -left-40 w-[30rem] h-[30rem] bg-emerald-300/10 rounded-full blur-[120px]"></div>

    <main class="relative z-10 w-full max-w-md bg-surface-rt rounded-3xl shadow-2xl p-8 md:p-10 border border-slate-100 scale-90">
        <header class="text-center mb-10">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-emerald-50 rounded-2xl mb-6 shadow-sm">
                <span class="material-symbols-outlined text-primary-rt text-4xl">account_balance_wallet</span>
            </div>
            <h1 class="font-headline text-3xl font-extrabold tracking-tight text-on-surface-rt mb-2">Sistem Keuangan RT</h1>
            <p class="text-on-surface-variant-rt font-medium text-sm">Silakan login untuk melanjutkan</p>
        </header>

        @if (session('status'))
            <div class="mb-4 font-medium text-sm text-green-600 bg-green-50 p-3 rounded-xl border border-green-100 text-center">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="space-y-6">
            @csrf

            <div class="space-y-2">
                <label class="block text-sm font-semibold text-slate-700 ml-1" for="username">Username</label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <span class="material-symbols-outlined text-slate-400 group-focus-within:text-primary-rt transition-colors">person</span>
                    </div>
                    <input class="block w-full pl-12 pr-4 py-4 bg-input-bg-rt border border-slate-100 rounded-2xl focus:ring-2 focus:ring-emerald-200 focus:bg-white focus:border-emerald-300 transition-all placeholder:text-slate-400 text-slate-900 @error('username') ring-2 ring-red-300 border-red-400 @enderror" 
                        id="username" name="username" value="{{ old('username') }}" placeholder="Masukkan username" type="text" required autofocus />
                </div>
                @error('username')
                    <p class="text-red-500 text-xs mt-1 ml-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="space-y-2">
                <label class="block text-sm font-semibold text-slate-700 ml-1" for="password">Password</label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <span class="material-symbols-outlined text-slate-400 group-focus-within:text-primary-rt transition-colors">lock</span>
                    </div>
                    <input class="block w-full pl-12 pr-12 py-4 bg-input-bg-rt border border-slate-100 rounded-2xl focus:ring-2 focus:ring-emerald-200 focus:bg-white focus:border-emerald-300 transition-all placeholder:text-slate-400 text-slate-900 @error('password') ring-2 ring-red-300 border-red-400 @enderror" 
                        id="password" name="password" placeholder="Masukkan password" type="password" required />
                    
                    <button class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-400 hover:text-primary-rt transition-colors" type="button" onclick="const p = document.getElementById('password'); p.type = p.type === 'password' ? 'text' : 'password'">
                        <span class="material-symbols-outlined">visibility</span>
                    </button>
                </div>
                @error('password')
                    <p class="text-red-500 text-xs mt-1 ml-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="pt-2">
                <button class="w-full bg-primary-rt text-white font-headline font-bold py-4 px-6 rounded-2xl hover:bg-emerald-800 active:scale-[0.98] transition-all duration-200 shadow-lg shadow-emerald-200" type="submit">
                    Login
                </button>
            </div>
        </form>

        <div class="mt-2 pt-2 border-t border-slate-100 flex justify-center">
            <div class="flex items-center gap-2.5 px-4 py-2 bg-slate-50 rounded-full border border-slate-100">
                <span class="material-symbols-outlined text-primary-rt text-lg">verified_user</span>
                <span class="text-sm font-medium text-slate-500">Gunakan akun terdaftar</span>
            </div>
        </div>
    </main>

    <div class="absolute bottom-[-10%] left-1/2 -translate-x-1/2 w-[80%] h-32 bg-emerald-500/10 rounded-[100%] blur-[100px] pointer-events-none"></div>

</body>
</html>