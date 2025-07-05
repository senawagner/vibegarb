<!DOCTYPE html>
<html>
<head>
    <title>Vibe Garb</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-blue-500 text-white p-8">
    <h1 class="text-4xl font-bold">Vibe Garb - Funcionando!</h1>
    <p class="text-xl mt-4">Se você vê este texto estilizado, o CSS está funcionando.</p>
    <a href="{{ route('products.index') }}" class="bg-white text-blue-500 px-4 py-2 rounded mt-4 inline-block">Ver Produtos</a>
</body>
</html> 