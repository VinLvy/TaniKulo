<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Laravel + Tailwind</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 text-gray-800">

    <div class="min-h-screen flex items-center justify-center">
        <div class="bg-gray-200 shadow-lg rounded-lg p-8 w-full max-w-md">
            <h1 class="text-2xl font-bold mb-4 text-center">Selamat Datang di Laravel + Tailwind</h1>
            <p class="text-gray-600 text-center">Ini adalah halaman awal dengan template Tailwind CSS.</p>
            <div class="mt-6 text-center">
                <a href="#" class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded">
                    Tombol ga guna
                </a>
            </div>
        </div>
    </div>

</body>
</html>
