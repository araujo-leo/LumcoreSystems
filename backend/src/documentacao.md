# Documentação da API

Este documento descreve as rotas e os formatos esperados para interação com a API.

## Headers Comuns

Todas as requisições devem incluir os seguintes headers:

```http
Content-Type: application/json
```
---

## Endpoints

### 1. Contato

**Rota:** `POST /?route=contact`

**Parâmetros:**

```json
{
  "email": "exemplo@email.com",
  "name": "Nome do Usuário"
  "mensagem": "mensagem"
}
```

**Resposta:**

```json
{
  "success": true,
  "message": "Confirmação enviada."
}
```

---

### 2. Inscrição na Newsletter

**Rota:** `POST /?route=subscribe`

**Parâmetros:**

```json
{
  "email": "exemplo@email.com",
  "name": "Nome do Usuário"
}
```

**Resposta:**

```json
{
  "success": true,
  "message": "E-mail cadastrado e e-mail de boas-vindas enviado!"
}
```

---

### 3. Cancelar Inscrição

**Rota:** `POST /?route=unsubscribe`

**Parâmetros:**

```json
{
  "email": "exemplo@email.com"
}
```

**Resposta:**

```json
{
  "success": true,
  "message": "E-mail removido da newsletter!"
}
```

---

### 4. Enviar Newsletter

**Rota:** `POST /?route=send-news`

**Headers:**

```http
Authorization: Bearer SEU_TOKEN_AQUI
```

**Parâmetros:**

```json
{
  "subject": "Assunto da newsletter",
  "body": "Conteúdo da newsletter"
}
```

**Resposta:**

```json
{
  "success": true,
  "message": "Newsletter enviada para todos os e-mails!"
}
```

---

## Observações

- As respostas de erro seguem o formato:

```json
{
  "success": false,
  "message": "Mensagem de erro."
}
```

- Certifique-se de enviar as requisições com o `Content-Type: application/json`.
- Para requisições autenticadas, envie o token no header `Authorization`.

Caso tenha dúvidas ou precise de melhorias, entre em contato!

