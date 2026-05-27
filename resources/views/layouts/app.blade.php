<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name') }}</title>

    <!-- <link rel="icon" type="image/png" href="{{ asset('logo.png') }}"> -->

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet">
</head>

<body class="bg-gradient-to-br from-slate-50 via-white to-emerald-50/20 font-body">

    <!-- SIDEBAR -->
    @include('layouts.sidebar')

    <!-- HEADER -->
    @include('layouts.header')
    @if(session('success'))

        <div
            x-data="{ 
                show: true,

                init() {
                    setTimeout(() => {
                        this.show = false;
                    }, 5000)
                }
            }"

            x-show="show"

            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 transform translate-y-[20px] scale-95"
            x-transition:enter-end="opacity-100 transform translate-y-0 scale-100"

            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"

            class="fixed bottom-5 right-5 z-50 w-[340px] bg-white border border-emerald-100 rounded-2xl shadow-2xl"
        >

            <div class="flex items-start gap-4 p-4">

                <!-- ICON -->
                <div class="flex-shrink-0 w-11 h-11 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center">
                    <span class="material-symbols-outlined text-[24px]">
                        check_circle
                    </span>
                </div>

                <!-- TEXT -->
                <div class="flex-1">
                    <p class="text-[11px] uppercase tracking-widest text-gray-400 font-bold mb-1">
                        Success
                    </p>

                    <p class="text-sm font-semibold text-gray-700 leading-relaxed">
                        {{ session('success') }}
                    </p>
                </div>
            </div>

        </div>


    @endif
    @if($errors->updatePassword->any())

    <div
        x-data="{ 
            show: true,

            init() {
                setTimeout(() => {
                    this.show = false;
                }, 5000)
            }
        }"

        x-show="show"

        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 transform translate-y-[20px] scale-95"
        x-transition:enter-end="opacity-100 transform translate-y-0 scale-100"

        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"

        class="fixed bottom-5 right-5 z-50 w-[340px] bg-white border border-red-100 rounded-2xl shadow-2xl"
    >

        <div class="flex items-start gap-4 p-4">

            <!-- ICON -->
            <div class="flex-shrink-0 w-11 h-11 rounded-full bg-red-100 text-red-600 flex items-center justify-center">
                <span class="material-symbols-outlined text-[24px]">
                    error
                </span>
            </div>

            <!-- TEXT -->
            <div class="flex-1">
                <p class="text-[11px] uppercase tracking-widest text-gray-400 font-bold mb-1">
                    Error
                </p>

                <p class="text-sm font-semibold text-gray-700 leading-relaxed">
                    {{ $errors->updatePassword->first() }}
                </p>
            </div>
        </div>

    </div>

    @endif

    <!-- CONTENT -->
    <main id="mainContent" class="ml-64 pt-20 p-6 transition-all duration-300">
        {{ $slot }}
    </main>
<script>

    const toggleBtn = document.getElementById('toggleSidebar');
    const sidebar = document.getElementById('sidebar');
    const mainContent = document.getElementById('mainContent');
    const headerContent = document.getElementById('headerContent');

    // cek state sebelumnya
    let collapsed = localStorage.getItem('sidebar-collapsed') === 'true';

    // apply saat load
    applySidebarState();

    toggleBtn.addEventListener('click', () => {

        collapsed = !collapsed;

        localStorage.setItem('sidebar-collapsed', collapsed);

        applySidebarState();

    });

    function applySidebarState() {

        if (collapsed) {

            sidebar.classList.remove('w-64');
            sidebar.classList.add('w-0');

            mainContent.classList.remove('ml-64');
            mainContent.classList.add('ml-0');

            headerContent.classList.remove('left-64');
            headerContent.classList.add('left-0');

        } else {

            sidebar.classList.remove('w-0');
            sidebar.classList.add('w-64');

            mainContent.classList.remove('ml-0');
            mainContent.classList.add('ml-64');

            headerContent.classList.remove('left-0');
            headerContent.classList.add('left-64');

        }

    }

</script>
</body>
</html>