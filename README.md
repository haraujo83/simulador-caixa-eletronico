# Teste - Simulador de Caixa Eletrônico

*Projeto em andamento

Utilizei:
- Framework Mezzio https://docs.mezzio.dev/ (com pacotes do Laminas https://getlaminas.org/)
- CodeSniffer
- PHP 7.4.20
- Git com Git Flow
- Alguns Design Patterns (Table Data GateWay, Abstract Factory, Chain of Responsibility, etc.)
- Básico de Solid (principalmente o Single Responsibility)
- Clean Code

## O que foi feito
- Handlers para:
    - Busca usuários
    - Incluir usuário
    - Alterar usuário
    - Excluir usuário (não exclui da tabelas, apenas marca como 'deleted' = 1)
- Validadores:
    - Data (formato Y-m-d ou d/m/Y)
    - CPF (formatado ou somente digitos, verifica número e se já existe um usuário com o mesmo número quando for inclusão)
    - Status do usuário (verifica se o id está na tabela user_statuses)
- Testes:
    - validador de cpf
    - validador de depósito

## A fazer
- usar tickets do trello com tasks
- autenticação para acesso as api's
- docker
- migrations c/ doctrine
- validador de saque
- validador de valor disponível no caixa
- cadastro de contas por usuário
- verificação de concorrência de operações saque/depósito
- padronizar mensagens de commmit
- usar pull requests
- documentação das api's
- mais testes unitários

## Iniciando

*Base de dados é 'cx', usuário root, sem senha (para alterar, ver arquivo /config/autoload/laminas-db.global.php).

*Executar todos os comandos abaixo na pasta raiz do projeto.

Para iniciar o projeto, executar:

```bash
$ composer install
```

Para checar constraints do CodeSniffer, executar:

```bash
$ composer cs-check
```

Para testes unitários pelo PHPUnit, executar:

```bash
$ composer test
```

Para rodar em um servidor local, executar:

```bash
$ composer run --timeout=0 serve
```

Para acessar com o servidor local, abrir http://localhost:8080.
