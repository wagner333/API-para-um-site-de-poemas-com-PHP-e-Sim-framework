# API de Poemas e Usuários

Esta API permite a criação, leitura, atualização e exclusão de poemas e usuários. A API foi construída utilizando Slim Framework com banco de dados SQLite. A seguir estão as rotas e as funcionalidades disponíveis.

## Endpoints

### Poemas

#### 1. **Criar um Poema**
- **Rota:** `POST /poemas`
- **Descrição:** Cria um novo poema.
- **Requisição:**
  - Body (JSON):
    ```json
    {
      "titulo": "Título do Poema",
      "conteudo": "Conteúdo do Poema",
      "autor": "Nome do Autor" (opcional)
    }
    ```
- **Resposta Sucesso (201):**
    ```json
    {
      "message": "Poema criado com sucesso!"
    }
    ```
- **Resposta Erro (400):**
    ```json
    {
      "error": "Dados insuficientes para criar o poema"
    }
    ```

#### 2. **Listar Todos os Poemas**
- **Rota:** `GET /poemas`
- **Descrição:** Retorna todos os poemas.
- **Resposta Sucesso (200):**
    ```json
    [
      {
        "id": 1,
        "titulo": "Título do Poema",
        "conteudo": "Conteúdo do Poema",
        "autor": "Nome do Autor"
      }
    ]
    ```

#### 3. **Exibir Poema Específico**
- **Rota:** `GET /poemas/{id}`
- **Descrição:** Retorna um poema específico pelo ID.
- **Requisição:** 
  - Parâmetro `id` na URL
- **Resposta Sucesso (200):**
    ```json
    {
      "id": 1,
      "titulo": "Título do Poema",
      "conteudo": "Conteúdo do Poema",
      "autor": "Nome do Autor"
    }
    ```
- **Resposta Erro (404):**
    ```json
    {
      "error": "Poema não encontrado"
    }
    ```

#### 4. **Atualizar Poema**
- **Rota:** `PUT /poemas/{id}`
- **Descrição:** Atualiza um poema específico.
- **Requisição (JSON):**
    ```json
    {
      "titulo": "Novo Título",
      "conteudo": "Novo Conteúdo",
      "autor": "Novo Autor"
    }
    ```
- **Resposta Sucesso (200):**
    ```json
    {
      "message": "Poema atualizado com sucesso!"
    }
    ```
- **Resposta Erro (400):**
    ```json
    {
      "error": "Dados insuficientes para atualizar o poema"
    }
    ```

#### 5. **Deletar Poema**
- **Rota:** `DELETE /poemas/{id}`
- **Descrição:** Deleta um poema específico.
- **Resposta Sucesso (200):**
    ```json
    {
      "message": "Poema deletado com sucesso!"
    }
    ```
- **Resposta Erro (404):**
    ```json
    {
      "error": "Poema não encontrado"
    }
    ```

---

### Usuários

#### 1. **Criar um Usuário**
- **Rota:** `POST /users`
- **Descrição:** Cria um novo usuário.
- **Requisição:**
  - Body (JSON):
    ```json
    {
      "name": "Nome do Usuário",
      "email": "email@dominio.com",
      "password": "senha"
    }
    ```
- **Resposta Sucesso (201):**
    ```json
    {
      "message": "Usuário criado com sucesso!"
    }
    ```
- **Resposta Erro (400):**
    ```json
    {
      "error": "Dados insuficientes para criar o usuário"
    }
    ```

#### 2. **Listar Todos os Usuários**
- **Rota:** `GET /users`
- **Descrição:** Retorna todos os usuários.
- **Resposta Sucesso (200):**
    ```json
    [
      {
        "id": 1,
        "name": "Nome do Usuário",
        "email": "email@dominio.com"
      }
    ]
    ```

#### 3. **Exibir Usuário Específico**
- **Rota:** `GET /users/{id}`
- **Descrição:** Retorna um usuário específico pelo ID.
- **Requisição:**
  - Parâmetro `id` na URL
- **Resposta Sucesso (200):**
    ```json
    {
      "id": 1,
      "name": "Nome do Usuário",
      "email": "email@dominio.com"
    }
    ```
- **Resposta Erro (404):**
    ```json
    {
      "error": "Usuário não encontrado"
    }
    ```

#### 4. **Atualizar Usuário**
- **Rota:** `PUT /users/{id}`
- **Descrição:** Atualiza os dados de um usuário específico.
- **Requisição (JSON):**
    ```json
    {
      "name": "Novo Nome",
      "email": "novoemail@dominio.com",
      "password": "nova_senha"
    }
    ```
- **Resposta Sucesso (200):**
    ```json
    {
      "message": "Usuário atualizado com sucesso!"
    }
    ```
- **Resposta Erro (400):**
    ```json
    {
      "error": "Dados insuficientes para atualizar o usuário"
    }
    ```

#### 5. **Deletar Usuário**
- **Rota:** `DELETE /users/{id}`
- **Descrição:** Deleta um usuário específico.
- **Resposta Sucesso (200):**
    ```json
    {
      "message": "Usuário deletado com sucesso!"
    }
    ```
- **Resposta Erro (404):**
    ```json
    {
      "error": "Usuário não encontrado"
    }
    ```

#### 6. **Login de Usuário**
- **Rota:** `POST /login`
- **Descrição:** Realiza o login de um usuário.
- **Requisição (JSON):**
    ```json
    {
      "email": "email@dominio.com",
      "password": "senha"
    }
    ```
- **Resposta Sucesso (200):**
    ```json
    {
      "message": "Login realizado com sucesso!"
    }
    ```
- **Resposta Erro (401):**
    ```json
    {
      "error": "Email ou senha inválidos"
    }
    ```

#### 7. **Logout de Usuário**
- **Rota:** `POST /logout`
- **Descrição:** Realiza o logout de um usuário.
- **Resposta Sucesso (200):**
    ```json
    {
      "message": "Logout realizado com sucesso!"
    }
    ```

#### 8. **Perfil do Usuário**
- **Rota:** `GET /perfil`
- **Descrição:** Exibe as informações do perfil do usuário logado.
- **Requisição:** Deve estar autenticado (sessão ativa).
- **Resposta Sucesso (200):**
    ```json
    {
      "id": 1,
      "name": "Nome do Usuário"
    }
    ```
- **Resposta Erro (401):**
    ```json
    {
      "error": "Usuário não autenticado"
    }
    ```

---

## Tecnologias

- **Framework:** Slim Framework
- **Banco de Dados:** SQLite
- **Autenticação:** Sessões

## Instalação

1. Clone este repositório.
2. Execute o comando `composer install` para instalar as dependências.
3. Configure o banco de dados SQLite em `API/database/database.sqlite`.

---

## Licença

Este projeto está licenciado sob a MIT License - veja o arquivo [LICENSE](LICENSE) para mais detalhes.
