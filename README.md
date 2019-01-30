# Alfredd

## Fase: Estrutura básica

### Introdução

Para iniciar o projeto em ambiente local, os requisitos mínimos devem ser atendidos:

* PHP 7.2 ou superior;
* Apache 2 ou Nginx;
* Mysql 5.6 ou superior (recomendado o Mysql 8)

### Instalação

Clonar o projeto usando:

`git clone git@github.com:LucasHenriqueOliveira/alfredd.git alfredd`
 
Em seguida instalar os pacotes:

`composer install`

Copiar o arquivo `.env.example` como `.env` e configurar os dados de ambiente.

Adicionar a chave de criptografia:

`php artisan key:generate`
`php artisan jwt:secret`

Criar a estrutura de dados (tabelas) e carga inicia do banco:

`php artisan migrate:refresh --seed`