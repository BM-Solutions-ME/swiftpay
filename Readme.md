# Api Rest

Executa a transferência de valores entre contas de usuários.

# Configuração do Docker Compose para Ambiente PHP

Este repositório contém uma configuração de ambiente Docker Compose pronta para uso com PHP, Nginx, MariaDB e Memcached. Este setup é ideal para desenvolvedores PHP que precisam de um ambiente rápido e consistente para desenvolvimento.

---

## Conteúdo

- [Pré-requisitos](#pré-requisitos)
- [Serviços](#serviços)
- [Instruções de Uso](#instruções-de-uso)
- [Variáveis de Ambiente](#variáveis-de-ambiente)
- [Volumes](#volumes)
- [Rede](#rede)
- [Problemas Conhecidos](#problemas-conhecidos)
- [Licença](#licença)

---

## Pré-requisitos

Certifique-se de ter os seguintes softwares instalados:

- [Docker](https://www.docker.com/)
- [Docker Compose](https://docs.docker.com/compose/)

---

## Serviços

### Memcached
- **Imagem**: `memcached:alpine`
- **Rede**: `app_network`

### MariaDB
- **Imagem**: `mariadb:11.0`
- **Nome do Container**: `systemdb`
- **Volumes**:
  - Montagem do diretório local: `.:/application`
  - Volume persistente: `mariadb_data:/var/lib/mysql`
- **Portas**:
  - `8003:3306`
- **Variáveis de Ambiente**:
  - `MYSQL_ROOT_PASSWORD`, `MYSQL_DATABASE`, `MYSQL_USER`, `MYSQL_PASSWORD`

### Webserver (Nginx)
- **Imagem**: `nginx:alpine`
- **Volumes**:
  - Montagem do diretório local: `.:/application`
  - Configuração personalizada: `./phpdocker/nginx/nginx.conf:/etc/nginx/conf.d/default.conf`
- **Portas**:
  - `80:80`

### PHP-FPM
- **Imagem Customizada**: `phpdocker/php-fpm`
- **Volumes**:
  - Montagem do diretório local: `.:/application`
  - Arquivo de configuração: `./phpdocker/php-fpm/php-ini-overrides.ini:/etc/php/8.2/fpm/conf.d/99-overrides.ini`

---

## Instruções de Uso

1. Clone este repositório:
   ```bash
   git clone <URL_DO_REPOSITÓRIO>
   cd <NOME_DO_REPOSITÓRIO>
