# Correção de Performance - Docker no Windows

## Problema Identificado
O Laravel está levando **36 segundos** para inicializar devido a um problema conhecido de performance do Docker Desktop no Windows com volumes montados.

## Causa Raiz
No Windows, o Docker Desktop usa um sistema de arquivos virtualizado para montar volumes, o que é extremamente lento para aplicações PHP que leem muitos arquivos (como o Laravel).

## Soluções Imediatas

### Solução 1: Usar WSL2 (Recomendada)
1. Mova o projeto para dentro do WSL2
2. Execute o Docker a partir do WSL2
3. Performance será nativa do Linux

### Solução 2: Otimizações no docker-compose.yml
Adicione configurações de cache nos volumes:

```yaml
services:
  app:
    volumes:
      - ./:/var/www:cached  # Adicione :cached
      - ./docker/php/local.ini:/usr/local/etc/php/conf.d/local.ini:ro
```

### Solução 3: Excluir vendor do volume
Modifique o docker-compose.yml:

```yaml
services:
  app:
    volumes:
      - ./:/var/www:cached
      - /var/www/vendor  # Exclui vendor do volume montado
      - ./docker/php/local.ini:/usr/local/etc/php/conf.d/local.ini:ro
```

Depois execute:
```bash
docker-compose down
docker-compose up -d --build
docker exec vibegarb_app composer install
```

### Solução 4: Usar Volumes Nomeados
Crie volumes Docker nativos ao invés de bind mounts:

```yaml
volumes:
  app-vendor:
    driver: local
  app-node-modules:
    driver: local

services:
  app:
    volumes:
      - ./:/var/www:cached
      - app-vendor:/var/www/vendor
      - app-node-modules:/var/www/node_modules
```

## Teste de Performance
Após aplicar as mudanças, teste com:
```bash
docker exec vibegarb_app php artisan tinker --execute="echo microtime(true) - LARAVEL_START;"
```

O tempo deve cair de 36s para menos de 1s. 