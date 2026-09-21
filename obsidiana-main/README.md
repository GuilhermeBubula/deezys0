# Deezys — Código gerado a partir dos wireframes

Este pacote transforma os 9 frames do wireframe em um pequeno sistema
funcional em **PHP + MySQL + CSS + JavaScript**.

## Mapeamento wireframe → arquivo

| Frame | Tela                          | Arquivo              |
|-------|-------------------------------|-----------------------|
| 1     | Cadastro                      | `cadastro.php`        |
| 3     | Login                         | `login.php`           |
| 4     | Lista de empresas             | `empresas.php`        |
| 7     | Nova empresa                  | `nova_empresa.php`    |
| 5     | Lista de clientes / busca     | `clientes.php` (+ `cliente_novo.php`) |
| 6     | Novo pedido                   | `novo_pedido.php`     |
| 8     | Lista de pedidos com status   | `pedidos.php`         |
| 9     | Calendário                    | `calendario.php`      |

## Estrutura de pastas

```
deezys/
├── config.php              # credenciais do banco
├── schema.sql              # script de criação das tabelas
├── includes/
│   ├── db.php               # conexão PDO
│   ├── auth.php             # sessão / proteção de rotas
│   ├── sidebar.php          # menu lateral de ícones (Frames 4-9)
│   └── topbar.php           # avatar + menu do usuário
├── assets/
│   ├── css/style.css        # todo o estilo visual (tema navy/cinza)
│   └── js/script.js         # interatividade (menu, busca, calendário)
├── cadastro.php
├── login.php
├── logout.php
├── empresas.php
├── nova_empresa.php
├── clientes.php
├── cliente_novo.php
├── novo_pedido.php
├── pedidos.php
└── calendario.php
```

## Como rodar

1. Crie o banco executando `schema.sql` no MySQL:
   ```bash
   mysql -u root -p < schema.sql
   ```
2. Ajuste as credenciais em `config.php`.
3. Coloque a pasta `deezys/` dentro do seu servidor (Apache/XAMPP/Laragon)
   e acesse `http://localhost/deezys/cadastro.php`.
4. Crie uma conta, faça login, cadastre uma empresa, um cliente e um pedido.

## Observações de implementação

- **Autenticação**: senhas armazenadas com `password_hash()`/`password_verify()`.
- **Segurança**: todas as queries usam *prepared statements* (PDO).
- **CSS**: um único arquivo (`style.css`) reaproveitado em todas as telas,
  usando variáveis (`:root`) para as cores do tema (azul-marinho, cinza,
  verde/amarelo/vermelho dos status).
- **JavaScript**:
  - `initAvatarMenu` — abre/fecha o menu do avatar no topo.
  - `initSidebarToggle` — recolhe/expande a barra lateral de ícones.
  - `initFiltroLista` — busca ao vivo (usada em clientes e calendário).
  - `initSelecaoCliente` — destaca o cliente selecionado na lista.
  - `initCalendario` — monta o grid do mês em JavaScript puro a partir
    dos prazos de pedidos vindos do PHP (`data-eventos`), sem recarregar
    a página ao trocar mês/ano.
- Os botões "cancelar/confirmar" do Frame 6 e os status coloridos
  (concluído = verde, em andamento = amarelo, pendente = vermelho) do
  Frame 8 foram implementados fielmente ao wireframe.

Sinta-se à vontade para pedir ajustes de estilo, novas telas (ex: edição
de empresa, detalhe de pedido) ou a versão com autenticação via API.
