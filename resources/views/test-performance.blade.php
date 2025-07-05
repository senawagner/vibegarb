<!DOCTYPE html>
<html>
<head>
    <title>Teste de Performance</title>
</head>
<body>
    <h1>Teste de Performance - Vibe Garb</h1>
    <p>Tempo de geração: {{ round(microtime(true) - LARAVEL_START, 4) }} segundos</p>
    <p>Memória usada: {{ round(memory_get_usage() / 1024 / 1024, 2) }} MB</p>
    <a href="/">Voltar para Home</a>
</body>
</html> 