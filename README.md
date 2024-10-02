# LBAW

# ER: Requirements Specification Component

> Project vision.

## A1 - TinderFEUP

### Contexto 

Fazer conexões reais com colegas de universidade pode ser um desafio, especialmente numa faculdade de grandes dimensões como a FEUP. Quer se trate de interesses românticos ou estabelecer novas amizades, os alunos podem sentir dificuldades em encontrar pessoas com os mesmos interesses. Este projeto tem como objetivo ajudar, até os mais introvertidos, a criar relações únicas e verdadeiras. 

### Motivação

A motivação para realizar este projeto vem de uma necessidade de criar uma plataforma onde os estudantes da FEUP possam encontrar o amor, num espaço seguro e de confiança. Esta rede social poderá ajudar, até os alunos mais tímidos, a socializar e, possivelmente, encontrar o seu par de sonhos. 

### Objetivos

A principal missão desta plataforma é ajudar os estudantes da mesma faculdade a criar relações, sejam elas significativas, pessoais ou profissionais.  
 Assegurar que apenas alunos verificados da FEUP possam utilizar a plataforma é um importante fator em conta, de forma a proteger os dados pessoais. 
Uma interface fácil e semelhante ao Tinder compatível com todos os dispositivos.

### Principais características

**Utilizador:**

- Registo
- Iniciar/terminar sessão
- Recuperar palavra-passe
- Ver um perfil público
- Editar perfil
- Eliminar conta

**Utilizador Autenticado:**

- Ver perfil
- Editar perfil
- Mudar foto do perfil
- Ver notificações pessoais
- Ver linha de tempo personalizada
- Pesquisa de publicações, comentários, grupos e utilizadores
- Filtros de pesquisa
- Enviar pedidos de amizade
- Ver perfis amigos
- Gerir os pedidos de amizade recebidos
- Gerir amigos
- Criar publicação
- Comentar publicações
- Gostar de publicações
- Responder a comentários
- Criar grupos
- Ver o feed dos utilizadores
- Juntar-se a um grupo público
- Gerir notificações
- Marcar amigos em publicações
- Ver feed dos amigos
- Pedir para entrar em grupos públicos

**Autor da publicação:**

- Editar publicação
- Apagar publicação
- Gerir a visibilidade da publicação

**Autor do comentário:**

- Editar comentário
- Apagar comentário

**Membro do grupo:**

- Ver membros do grupo
- Publicar no grupo
- Sair do grupo


**Proprietário do grupo:**

- Editar informações do grupo
- Remover membro
- Convidar para o grupo
- Remover publicação do grupo
- Alterar a visibilidade do grupo
- Gerir convites para grupo
- Gerir pedidos de adesão

**Notificações:**
- Gostar da sua própria publicação
- Comentários na própria publicação
- Pedido de amizade

**Administrador:**
- Administrar contas de utilizadores
- Bloquear e desbloquear contas de utilizador
- Eliminar contas de utilizador


**Ajuda:**

- Marcadores para entradas de formulários
- Mensagens de erro contextuais
- Ajuda contextual



**Informações sobre o site:**
- Sobre nós
- Principais caraterísticas
- Contactos



## A2: Actors and User stories

Os _actors_ e as _stories_ dos utilizadores contêm informações sobre as pessoas que vão utilizar o TinderFEUP e de que forma utilizar. 


### 1. Actors

Na figura abaixo, é possível ver a representação dos _actors_

![](IMG/Actors.png)

Em baixo, é possível ver a descrição de cada _actor_

| Identifier              | Name      |                                     
| ----------------------- | --------- | 
|  Administrador          | O administrador tem o poder de remover mensagens, bloquear e desbloquear pessoas |
|  Utilizador             | Utilizador normal |
|  Visitante              | Utilizadores não autenticados podem consultar informações públicas, registarem-se ou iniciar a sessão  |
|  Autenticado            | Utilizadores autenticados podem consultar informações, comentar em publicações de outras pessoas, enviar e receber mensagens com outras pessoas ou grupos |
|  Membro do grupo        | Membros de um grupo podem participar, conversar e publicar num grupo, mas não tèm permissões especiais |
|  Proprietário do grupo  | Proprietários de um grupo além de terem as mesmas permissões que um membro, podem também editar o nome do grupo, a descrição, adicionar/remover membros e editar as permissões de outros participantes |
|  Autor da publicação    | Os utilizadores podem eliminar ou editar a sua publicação |
|  Autor do comentário    | Os utilizadores podem em qualquer publicação, além de editar ou eliminar os seus próprios comentários |

### 2. User Stories

> User stories organized by actor.  
> For each actor, a table containing a line for each user story, and for each user story: an identifier, a name, a priority, and a description (following the recommended pattern).
> Below is a template for presenting the user stories.




#### 2.1. Administrador

| Identifier   | Name      | Priority    | Description                                           |
| ------------ | --------- | ----------- | ----------------------------------------------------- |
|  US01        | US Name 1 | High        | As an \<actor\>, I want to \<what\>, so that \<why\>. |
|  US02        | US Name 2 | Medium      | As an \<actor\>, I want to \<what\>, so that \<why\>. |
|  US03        | US Name 3 | Low         | As an \<actor\>, I want to \<what\>, so that \<why\>. |

#### 2.2. Utilizador

| Identifier   | Name      | Priority    | Description                                           |
| ------------ | --------- | ----------- | ----------------------------------------------------- |
|  US01        | US Name 1 | High        | As an \<actor\>, I want to \<what\>, so that \<why\>. |
|  US02        | US Name 2 | Medium      | As an \<actor\>, I want to \<what\>, so that \<why\>. |
|  US03        | US Name 3 | Low         | As an \<actor\>, I want to \<what\>, so that \<why\>. |

### 2.3 Visitante

| Identifier   | Name      | Priority    | Description                                           |
| ------------ | --------- | ----------- | ----------------------------------------------------- |
|  US01        | US Name 1 | High        | As an \<actor\>, I want to \<what\>, so that \<why\>. |
|  US02        | US Name 2 | Medium      | As an \<actor\>, I want to \<what\>, so that \<why\>. |
|  US03        | US Name 3 | Low         | As an \<actor\>, I want to \<what\>, so that \<why\>. |


#### 2.4. Autenticado

| Identifier   | Name      | Priority    | Description                                           |
| ------------ | --------- | ----------- | ----------------------------------------------------- |
|  US01        | US Name 1 | High        | As an \<actor\>, I want to \<what\>, so that \<why\>. |
|  US02        | US Name 2 | Medium      | As an \<actor\>, I want to \<what\>, so that \<why\>. |
|  US03        | US Name 3 | Low         | As an \<actor\>, I want to \<what\>, so that \<why\>. |

### 2.5 Membro do grupo

| Identifier   | Name      | Priority    | Description                                           |
| ------------ | --------- | ----------- | ----------------------------------------------------- |
|  US01        | US Name 1 | High        | As an \<actor\>, I want to \<what\>, so that \<why\>. |
|  US02        | US Name 2 | Medium      | As an \<actor\>, I want to \<what\>, so that \<why\>. |
|  US03        | US Name 3 | Low         | As an \<actor\>, I want to \<what\>, so that \<why\>. |

### 2.6 Proprietário do grupo

| Identifier   | Name      | Priority    | Description                                           |
| ------------ | --------- | ----------- | ----------------------------------------------------- |
|  US01        | US Name 1 | High        | As an \<actor\>, I want to \<what\>, so that \<why\>. |
|  US02        | US Name 2 | Medium      | As an \<actor\>, I want to \<what\>, so that \<why\>. |
|  US03        | US Name 3 | Low         | As an \<actor\>, I want to \<what\>, so that \<why\>. |

### 2.7 Autor da publicação

| Identifier   | Name      | Priority    | Description                                           |
| ------------ | --------- | ----------- | ----------------------------------------------------- |
|  US01        | US Name 1 | High        | As an \<actor\>, I want to \<what\>, so that \<why\>. |
|  US02        | US Name 2 | Medium      | As an \<actor\>, I want to \<what\>, so that \<why\>. |
|  US03        | US Name 3 | Low         | As an \<actor\>, I want to \<what\>, so that \<why\>. |

### 2.8 Autor do comentário

| Identifier   | Name      | Priority    | Description                                           |
| ------------ | --------- | ----------- | ----------------------------------------------------- |
|  US01        | US Name 1 | High        | As an \<actor\>, I want to \<what\>, so that \<why\>. |
|  US02        | US Name 2 | Medium      | As an \<actor\>, I want to \<what\>, so that \<why\>. |
|  US03        | US Name 3 | Low         | As an \<actor\>, I want to \<what\>, so that \<why\>. |


### 3. Supplementary Requirements

> Section including business rules, technical requirements, and restrictions.  
> For each subsection, a table containing identifiers, names, and descriptions for each requirement.

#### 3.1. Business rules

#### 3.2. Technical requirements

#### 3.3. Restrictions


---


## A3: Information Architecture

> Brief presentation of the artifact goals.


### 1. Sitemap

> Sitemap presenting the overall structure of the web application.  
> Each page must be identified in the sitemap.  
> Multiple instances of the same page (e.g. student profile in SIGARRA) are presented as page stacks.


### 2. Wireframes

> Wireframes for, at least, two main pages of the web application.
> Do not include trivial use cases (e.g. about page, contacts).


#### UIxx: Page Name

#### UIxx: Page Name


---


## Revision history

Changes made to the first submission:
1. Item 1
1. ...

***
GROUP24116, 01/10/2024

* Alexandre Chouzende - 202207429@up.pt
* Rui Borges - 202207475@up.pt
* Pedro Paixão- 202008467@up.pt
* Tiago Torrado - 202206399@up.pt