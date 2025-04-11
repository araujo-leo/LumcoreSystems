# Projeto Full Stack - Backend em PHP e Frontend em HTML/CSS/JS

Este projeto é uma aplicação web full stack com backend em PHP e frontend em HTML, CSS e JavaScript. Utiliza bibliotecas modernas como jQuery, Bootstrap e ScrollReveal.

## 📁 Estrutura de Pastas

```
├── backend
│   ├── public
│   │   └── index.php
│   ├── src
│   │   ├── Controllers
│   │   │   ├── ContactController.php
│   │   │   └── NewsletterController.php
│   │   ├── Core
│   │   │   ├── Database.php
│   │   │   └── Mailer.php
│   │   ├── Middleware
│   │   │   └── AuthMiddleware.php
│   │   └── views
│   │       └── emails
│   │           └── subscribe.html
│   └── documentacao.md
├── vendor
│   ├── composer
│   ├── graham-campbell
│   ├── phpmailer
│   ├── phpoption
│   ├── symfony
│   ├── vlucas
│   └── autoload.php
├── .env
├── .envExample
├── composer-setup.php
├── composer.json
├── composer.lock
├── frontend
│   ├── assets
│   │   ├── img
│   │   ├── script.js
│   │   └── style.css
│   └── index.html
└── README.md

```

## 🛠️ Tecnologias Utilizadas

### Backend:
- PHP
- Composer (gerenciador de dependências)
- PHPMailer (envio de emails)
- Middleware para autenticação
- Estrutura MVC básica
- Comunicação via api

### Frontend:
- HTML5
- CSS3
- JavaScript (puro e com jQuery)
- Bootstrap 
- ScrollReveal (animações ao rolar)

## 🚀 Como Rodar o Projeto

### Pré-requisitos

- PHP 7.4+
- Composer
- Servidor local (ex: XAMPP, Laragon)

### Passos

1. Clone o repositório:
   ```bash
   git clone https://github.com/araujo-leo/LumcoreSystems.git
   ```

2. Acesse a pasta do backend:
   ```bash
   cd backend
   ```

3. Instale as dependências PHP:
   ```bash
   composer install
   ```

4. Renomeie `.envExample` para `.env` e configure suas variáveis.

5. Inicie o servidor local apontando para `public/index.php`.

6. Acesse o frontend:
   - Abra `frontend/index.html` em um navegador para visualizar o site.

