<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Laravel</title>
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
        @vite('resources/css/app.css')
    </head>
    <body>
        <header class="text-center mt-5">
            <h1 class="text-4xl font-bold ">Hola</h1>
            <p class="text-xl"> <span class="font-bold">Identificate</span> y accede a tu Tienda Online </p>
        </header>
        <main class="flex justify-center mt-20">
            <div class="md:w-4/12 bg-slate-800 p-6 rounded-lg shadow-xl ">
                <form action=" {{ route('login') }}" method="POST">
                    @csrf

                    @if (session('mensaje'))
                    <p class="bg-red-500 text-white my-2 rounded-lg text-sm p-2 text-center">{{ session('mensaje') }}</p>
                    @endif

                    <div class="mb-5">
                        <label for="email" class="mb-2 block uppercase text-gray-400 font-bold" >
                            Email
                        </label>
                        <input
                        id="email"
                        name="email"
                        type="email"
                        placeholder="Your Email"
                        class="border p-3 w-full rounded-lg @error('email') border-red-500
                        @enderror"
                        value="{{ old('email') }}"
                        />

                        @error('email')
                            <p class="bg-red-500 text-white my-2 rounded-lg text-sm p-2 text-center">{{ $message }}</p>
                        @enderror

                    </div>
                    <div class="mb-5">
                        <label for="password" class="mb-2 block uppercase text-gray-400 font-bold" >
                            Password
                        </label>
                        <input
                        id="password"
                        name="password"
                        type="text"
                        placeholder="Your Password"
                        class="border p-3 w-full rounded-lg @error('password') border-red-500
                        @enderror"
                        />

                        @error('password')
                            <p class="bg-red-500 text-white my-2 rounded-lg text-sm p-2 text-center">{{ $message }}</p>
                        @enderror

                    </div>

                    <input
                    type="submit"
                    value="Login"
                    class="bg-sky-600 hover:bg-sky-700 transition-colors cursor-pointer uppercase font-bold w-full p-3 text-white rounded-lg"
                    />
                </form>
        </main>
    </body>
</html>
