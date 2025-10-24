# CRUD OCP Demo

Projeto didático em PHP 8 que demonstra um CRUD simples de produtos, organizado em camadas (Domain, Application, Infra e camada pública) e com uma violação intencional do Princípio do Aberto/Fechado (OCP) no cálculo de preços. Os dados são persistidos em um arquivo `storage/products.txt` para facilitar a execução local.

## Requisitos

- PHP 8.0 ou superior com extensões padrão habilitadas
- Composer (para instalar autoload e usar scripts)
- Servidor embutido do PHP ou qualquer servidor web apontando para `public/`

## Estrutura

```
├─ public/                # Páginas PHP acessadas pelo navegador
│  ├─ index.php           # Lista produtos e mostra ações CRUD
│  ├─ create.php          # Formulário de criação
│  ├─ update.php          # Formulário de edição
│  └─ delete.php          # Confirmação de exclusão
├─ src/
│  ├─ Domain/             # Entidades e contratos
│  │  ├─ Product.php
│  │  └─ ProductRepository.php
│  ├─ Application/        # Casos de uso
│  │  └─ ProductService.php (contém o switch com violação de OCP)
│  └─ Infra/              # Implementações concretas
│     └─ FileProductRepository.php
└─ storage/
   └─ products.txt        # Base de dados simples em JSON por linha
```

## Passo a passo para rodar localmente

1. **Clonar o repositório**
   ```bash
   git clone <url-do-repositorio>
   cd solid-ocp-excercise
   ```

2. **Instalar dependências e autoload**
   Como o projeto depende apenas do PHP nativo, basta gerar o autoload:
   ```bash
   composer install   # cria pasta vendor se não existir
   composer dump-autoload
   ```

3. **Iniciar o servidor de desenvolvimento**
   ```bash
   composer serve
   ```
   O Composer executa o script `php -S localhost:8000 -t public`, expondo as páginas.

4. **Acessar a aplicação**
   Abra `http://localhost:8000` no navegador. A partir da página inicial você poderá:
   - Criar novos produtos;
   - Editar produtos existentes;
   - Excluir produtos.

Os registros ficam salvos em `storage/products.txt`, cada linha em JSON. Se quiser começar do zero, basta apagar o conteúdo desse arquivo (não apague o arquivo para evitar erros de permissão).

## Observando a violação de OCP

O método `ProductService::calculatePrice()` (`src/Application/ProductService.php`) usa um `switch` para tratar cada tipo de produto. Sempre que um novo tipo é adicionado, o serviço precisa ser alterado, caracterizando a violação do Princípio do Aberto/Fechado. Use isso como base para propor uma refatoração (por exemplo, criando estratégias ou objetos específicos de precificação).

## Scripts úteis

- `composer serve` — Sobe o servidor embutido apontando para `public/`.
- `composer dump` — Regenera o autoload PSR-4 caso você adicione novas classes.

## Próximos passos sugeridos

- Implementar testes automatizados para o `ProductService` e `FileProductRepository`.
- Refatorar a precificação para remover a violação de OCP.
- Substituir a persistência em arquivo por um banco de dados real quando for necessário.
