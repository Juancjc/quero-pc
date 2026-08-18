---
name: commit-standardization
description: "Ative esta skill sempre que for criar um commit git neste projeto. Define o padrão Conventional Commits (feat, fix, chore, docs, style, refactor, perf, test, build, ci, revert) com mensagens escritas em português. Use ao gerar `git commit`, revisar mensagens de commit, ou quando o usuário pedir para padronizar/formatar commits."
metadata:
  author: quero-pc
---

# Padronização de Commits

Este projeto segue o padrão **Conventional Commits**, com a descrição da mensagem escrita em **português**.

## Formato

```
<tipo>(<escopo opcional>): <descrição curta em português, no imperativo>

<corpo opcional explicando o "porquê", não o "o quê">

<rodapé opcional: breaking changes, issues relacionadas>
```

## Tipos permitidos

| Tipo       | Quando usar                                                              |
|------------|---------------------------------------------------------------------------|
| `feat`     | Nova funcionalidade para o usuário                                       |
| `fix`      | Correção de bug                                                          |
| `chore`    | Tarefas de manutenção que não afetam código de produção (deps, config)   |
| `docs`     | Alterações apenas em documentação                                       |
| `style`    | Formatação, espaçamento, ponto e vírgula — sem mudança de lógica         |
| `refactor` | Reestruturação de código sem alterar comportamento                      |
| `perf`     | Melhoria de performance                                                  |
| `test`     | Adição ou ajuste de testes                                               |
| `build`    | Mudanças no sistema de build ou dependências externas                   |
| `ci`       | Mudanças em arquivos e scripts de CI/CD                                  |
| `revert`   | Reversão de um commit anterior                                          |

## Regras da descrição

- Escreva em **português**.
- Use o modo **imperativo**: "adiciona", "corrige", "remove" (não "adicionado", "corrigindo").
- Comece com letra minúscula.
- Não termine com ponto final.
- Seja específico sobre o que mudou, evite mensagens genéricas como "ajustes" ou "correções".
- Limite a primeira linha a ~72 caracteres; detalhes adicionais vão no corpo.

## Escopo (opcional)

Use o escopo entre parênteses para indicar a área afetada, quando ajudar a esclarecer o commit. Exemplos de escopo neste projeto: `computadores`, `componentes`, `migrations`, `auth`.

```
feat(computadores): adiciona cadastro de novos computadores
fix(componentes): corrige validação de campos obrigatórios
```

## Breaking changes

Quando o commit introduz uma mudança que quebra compatibilidade, adicione `!` após o tipo/escopo e explique no rodapé:

```
feat(api)!: altera formato de resposta do endpoint de listagem

BREAKING CHANGE: o campo `dados` foi renomeado para `itens`
```

## Exemplos

```
feat: adiciona migration para tabela de computadores
fix: corrige relacionamento entre componentes e computadores
chore: atualiza dependências do composer
docs: documenta estrutura da tabela de componentes
refactor(computadores): extrai lógica de validação para form request
test: adiciona teste de criação de computador com componentes
```

## Ao criar o commit

- Siga o fluxo padrão do Claude Code para commits (verificar `git status`, `git diff` e `git log` antes de commitar).
- Use heredoc para passar a mensagem, mantendo a primeira linha no formato `<tipo>(<escopo>): <descrição>` e, se necessário, um corpo detalhando o "porquê".
- Nunca traduza os tipos (`feat`, `fix`, `chore`, etc.) — eles permanecem em inglês por convenção; apenas a descrição é em português.
