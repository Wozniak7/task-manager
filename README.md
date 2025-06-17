## 📝 Gerenciador de Tarefas Multi-Usuário em PHP
Este projeto é um Gerenciador de Tarefas completo desenvolvido em **PHP puro (Vanilla PHP), com MySQL** para armazenamento de dados e uma interface moderna construída com **HTML, CSS e Bootstrap.** Ele oferece funcionalidades **CRUD (Criar, Ler, Atualizar, Excluir)** e, mais importante, inclui um sistema robusto de autenticação e autorização multi-usuário, garantindo que cada usuário veja e gerencie apenas suas próprias tarefas.

---

## ✨ Visão Geral
O objetivo principal deste projeto é demonstrar minhas habilidades em desenvolvimento web backend utilizando PHP, incluindo interação com banco de dados relacional, gerenciamento de sessões, segurança básica (hashing de senhas), e construção de uma interface de usuário responsiva e interativa.

---

## 🚀 Funcionalidades Principais
**Autenticação de Usuário:**

**Registro:** Crie novas contas de usuário com nome de usuário e senha.

**Login:** Autentique usuários existentes com segurança (senhas armazenadas como hashes).

**Logout:** Encerre sessões de usuário de forma segura.

**Gerenciamento de Tarefas Multi-Usuário:**

*Cada usuário tem suas próprias tarefas, que são privadas e não visíveis para outros.*

**Criação de Tarefas:** Adicione novas tarefas com título, descrição, data limite e status inicial.

**Listagem de Tarefas:** Visualize suas tarefas de forma organizada.

**Edição de Tarefas:** Modifique detalhes de tarefas existentes.

**Exclusão de Tarefas:** Remova tarefas.

**Marcar como Concluída/Pendente:** Altere o status de uma tarefa rapidamente com um checkbox (usando AJAX para atualização instantânea sem recarregar a página).

**Filtragem e Ordenação:**

*Filtre tarefas por status ("Pendente" ou "Concluída").*

*Ordene tarefas por data de criação, data limite ou título (ascendente/descendente).*

**Paginação:**

*Divide a lista de tarefas em várias páginas, melhorando a performance e a usabilidade para grandes quantidades de dados.*

**Interface Gráfica Aprimorada:**

**Bootstrap 5:** Proporciona um design responsivo e moderno.

**Notificações Toast:** Mensagens de sucesso, erro e informação são exibidas em pop-ups não intrusivos (usando Toastify-JS).

**Modal de Confirmação:** A exclusão de tarefas utiliza um modal visualmente agradável para confirmação, melhorando a experiência do usuário.

---

## 🛠️ Tecnologias Utilizadas
**PHP (Vanilla PHP):** Linguagem principal do lado do servidor.

**MySQL:** Sistema de gerenciamento de banco de dados relacional para persistência dos dados.

**HTML5:** Estrutura das páginas web.

**CSS3:** Estilização personalizada.

**Bootstrap 5:** Framework CSS para componentes e responsividade.

**Toastify-JS:** Biblioteca JavaScript para notificações "toast" na interface.

**mysqli:** Extensão do PHP para interação segura com bancos de dados MySQL (utilizando Prepared Statements para prevenção de SQL Injection).

**Hashing de Senhas:** password_hash() e password_verify() do PHP para armazenamento seguro de credenciais.

**Sessões PHP:** Para gerenciamento de estado do usuário (login/logout).

---

## ⚙️ Como Rodar Localmente
Para executar este projeto em sua máquina, você precisará de um ambiente de servidor web com PHP e MySQL.

**Pré-requisitos:**
*XAMPP, WAMP ou MAMP: Ferramentas que empacotam Apache, PHP e MySQL para fácil instalação. Recomenda-se XAMPP.*

**Um editor de código (como VS Code).**

**Passos de Configuração:**
Clone o Repositório:

`git clone https://github.com/Wozniak7/Go_Project.git`
# Navegue até a pasta onde você salvará o projeto PHP (ex: Go_Project/task-manager)
`cd Go_Project/task-manager `

*(Certifique-se de mover/clonar o conteúdo do projeto PHP para a pasta htdocs do seu XAMPP/WAMP/MAMP, por exemplo: C:\xampp\htdocs\task-manager no Windows.)*

**Inicie os Serviços:**

**Abra o painel de controle do XAMPP (ou similar).**

**Inicie os módulos Apache e MySQL.**

Crie o Banco de Dados:

Abra seu navegador e acesse phpMyAdmin: http://localhost/phpmyadmin/.

Clique em **"Novo"** ou **"New"** na barra lateral.

Crie um novo banco de dados com o nome **task_manager_db**.

Selecione o banco de dados task_manager_db na barra lateral.

*Vá para a aba "SQL" e execute os seguintes comandos para criar as tabelas users e tasks:*

`CREATE TABLE users (`
    `id INT AUTO_INCREMENT PRIMARY KEY,`
    `username VARCHAR(50) NOT NULL UNIQUE,`
    `password VARCHAR(255) NOT NULL,`
    `created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP`
`);`

`CREATE TABLE tasks (`
    `id INT AUTO_INCREMENT PRIMARY KEY,`
    `user_id INT NOT NULL,`
    `title VARCHAR(255) NOT NULL,`
    `description TEXT,`
    `due_date DATE,`
    `status VARCHAR(50) DEFAULT 'Pendente',`
    `created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,`
    `CONSTRAINT fk_user_id FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE`
`);`

*Configure a Conexão com o Banco de Dados:*

**Abra o arquivo db.php na raiz da pasta do seu projeto (task-manager).**

*Verifique se as credenciais correspondem às do seu ambiente MySQL (usuário root e senha vazia "" são padrões para XAMPP/WAMP/MAMP). Se você tem uma senha para o root, insira-a aqui.*

**Acesse a Aplicação:**

Abra seu navegador e vá para: http://localhost/task-manager/ (ou o nome da sua pasta no htdocs).

Você será redirecionado para a página de login.

---

## 🧪 Como Testar
Registro de Usuário:

Na página de login, clique em **"Registre-se aqui".**

Crie um novo nome de **usuário e senha.**

*Após o registro, você será automaticamente logado e redirecionado para a lista de tarefas.*

**Gerenciamento de Tarefas:**

Adicione novas tarefas usando o botão **"➕ Adicionar Nova Tarefa".**

Edite tarefas existentes clicando em **"Editar".**

Exclua tarefas usando o botão **"Excluir" (com confirmação via modal).**

Mude o status de tarefas rapidamente usando o checkbox **"Marcar como Concluída" (a atualização é instantânea via AJAX).**

**Filtragem e Ordenação:**

*Use os dropdowns na parte superior para filtrar tarefas por status ou ordenar por diferentes critérios.*

**Paginação:**

*Se você tiver mais de 5 tarefas, a navegação de paginação aparecerá na parte inferior da lista.*

**Testar Separação de Usuários:**

Faça logout **("Sair").**

Registre uma nova conta de usuário e faça login com ela.

Você verá que a lista de tarefas estará vazia ou conterá apenas as tarefas que você criar com esta nova conta, demonstrando a separação de dados por usuário.

---