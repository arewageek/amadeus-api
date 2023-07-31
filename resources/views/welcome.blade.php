<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ env('APP_NAME') }}</title>

        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

        <!-- Styles -->
        @livewireStyles

        <!-- Scripts -->
        <link rel="stylesheet" href="resources/css/app.css" />
        
        <script src="https://cdn.tailwindcss.com"></script>
    </head>
    <body class="font-sans antialiased">
        <x-jet-banner />

            <script>
                const authURL = "https://test.api.amadeus.com/v1/security/oauth2/token"
                const authOptions = [
                    'grant_type: client_credentials',
                    'client_id: AX4mjut1nnAEhYU0o5bxHACZVuJ33mO7',
                    'client_secret: ZWG4nSLrufAKVJCc'
                ]

                fetch(authURL, {
                    method: 'post',
                    header: {
                        'Content-Type': 'application/json',
                    },
                    body: authOptions
                })
                .then(res => console.log(res))
                .catch(err => console.error(err))
            </script>

            <!-- Page Content -->
            <main class="w-screen h-screen py-[30pt] bg-slate-500 flex items-center flex-col px-[10pt]">
                <form method="post" class="w-full md:w-1/2 flex justify-center flex-wrap bg-slate-600 rounded-xl py-[20pt] px-[10p] shadow-lg">
                    <div class="w-3/4 py-4">
                        <input name="start" class="bg-slate-200 py-3 px-4 w-full rounded-2xl" list="from_list" />
                        <datalist id="from_list">
                            
                        </datalist>
                    </div>
                </form>
            </main>
        </div>

        @stack('modals')

        @livewireScripts
    </body>
</html>
