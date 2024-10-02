# LBAW

# ER: Requirements Specification Component

> Project vision.

## A1 - ConnectFEUP

The project aims to develop a social networking platform specifically designed for students at FEUP, addressing the common challenge of making real connections in a large university setting. The motivation behind this initiative stems from the need for a safe and trustworthy space where students can form friendships and romantic relationships, particularly benefiting those who may be shy or introverted. This platform will help students meet like-minded peers, fostering meaningful and unique connections.

The primary mission of the platform is to facilitate relationships among students, whether personal, romantic, or professional. A crucial aspect of this project is ensuring that only verified FEUP students can access the platform, thereby safeguarding personal data and maintaining user privacy. With an intuitive, user-friendly interface, the platform will incorporate Tinder-like functionalities, ensuring it is fully compatible across all devices, such as desktops, tablets, and smartphones.

To achieve this, the platform will provide a variety of features for users. It will allow new users to register easily with their FEUP credentials, sign in and out, and recover lost passwords. Users will have the ability to view public profiles, edit their personal information, and even delete their accounts if they choose to leave the platform.

Once authenticated, users will gain access to a host of additional features. They will be able to view and edit their profiles, change their profile pictures, and check personal notifications. A personalized timeline will showcase updates based on friends' activities, while robust search functionalities will help users find posts, comments, groups, and other users, enhanced by customizable search filters.

Furthermore, users will have the ability to send and manage friend requests, view friend profiles, and create and interact with posts. They can comment on, like, and reply to posts, as well as create groups, join public groups, and tag friends in their posts. They will also have the option to view their friends' feeds and ask to join public groups.

For those who create content, features will include the ability to edit and delete their posts, as well as manage post visibility. Comment authors will also have the ability to edit or delete their comments. Group members will be able to view other members, post in groups, and leave groups when they wish, while group owners will have additional privileges, such as editing group information, managing membership requests, and removing members or posts from the group.

Notifications will keep users informed about likes on their posts, comments on their content, and incoming friend requests, ensuring they remain engaged with their network. Administrators will play a vital role in managing user accounts, including locking, unlocking, and deleting accounts as needed.

To assist users, the platform will feature contextual help, bookmarks for form entries, and error messages to enhance user experience. Additionally, the site will provide information about itself, including an "About Us" section, details about its main features, and contact information for support.

Overall, this social networking platform seeks to create a vibrant and secure environment for FEUP students, facilitating authentic connections and enriching their university experience.



## A2: Actors and User stories

The _actors_ and _user stories_ contain information about the people who will use ConnectFEUP and how to use it.  


### 1. Actors

In the figure below, you can see the representation of the actors

![](IMG/Actors.png){style="display: block; margin: 0 auto" }

Below, you can see the description of each actor

| Identifier              | Name      |                                     
| ----------------------- | --------- | 
|  Administrator          | Administrator The administrator has the power to remove messages, block and unblock people. |
|  User                   | Normal User. |
|  Guest                  | Unauthenticated users can view public information, register or log in. |
|  Autenticated User      | Authenticated users can view information, comment on other people's posts, send and receive messages with other people or groups. |
|  Group Member           | Members of a group can participate, chat and post in a group, but do not have special permissions. |
|  Group Owner            | Group owners not only have the same permissions as a member, but can also edit the group name, description, add/remove members and edit the permissions of other participants. |
|  Post Author            | Users can delete or edit their post. |
|  Comment Author         | Users can edit or delete their own comments on any post. |

### 2. User Stories

> User stories organized by actor.  
> For each actor, a table containing a line for each user story, and for each user story: an identifier, a name, a priority, and a description (following the recommended pattern).
> Below is a template for presenting the user stories.




#### 2.1. Administrador 1

- **Administrator**: Manages user accounts, blocks or unblocks users, and ensures safe platform use.

| Identifier   | Name      | Priority    | Description                                           |
| ------------ | --------- | ----------- | ----------------------------------------------------- |
|  US01        | US Name 1 | High        | As an \<actor\>, I want to \<what\>, so that \<why\>. |
|  US02        | US Name 2 | Medium      | As an \<actor\>, I want to \<what\>, so that \<why\>. |
|  US03        | US Name 3 | Low         | As an \<actor\>, I want to \<what\>, so that \<why\>. |

#### 2.2. Utilizador

- **User**: Manages personal profile information and account settings.

| Identifier   | Name      | Priority    | Description                                           |
| ------------ | --------- | ----------- | ----------------------------------------------------- |
|  US01        | US Name 1 | High        | As an \<actor\>, I want to \<what\>, so that \<why\>. |
|  US02        | US Name 2 | Medium      | As an \<actor\>, I want to \<what\>, so that \<why\>. |
|  US03        | US Name 3 | Low         | As an \<actor\>, I want to \<what\>, so that \<why\>. |

### 2.3 Visitante

- **Visitor**: Browses public information, registers, and logs in.

| Identifier   | Name      | Priority    | Description                                           |
| ------------ | --------- | ----------- | ----------------------------------------------------- |
|  US01        | US Name 1 | High        | As an \<actor\>, I want to \<what\>, so that \<why\>. |
|  US02        | US Name 2 | Medium      | As an \<actor\>, I want to \<what\>, so that \<why\>. |
|  US03        | US Name 3 | Low         | As an \<actor\>, I want to \<what\>, so that \<why\>. |


#### 2.4. Autenticado

- **Authenticated User**: Has access to features like posting, commenting, managing friends, and joining groups.

| Identifier   | Name      | Priority    | Description                                           |
| ------------ | --------- | ----------- | ----------------------------------------------------- |
|  US01        | US Name 1 | High        | As an \<actor\>, I want to \<what\>, so that \<why\>. |
|  US02        | US Name 2 | Medium      | As an \<actor\>, I want to \<what\>, so that \<why\>. |
|  US03        | US Name 3 | Low         | As an \<actor\>, I want to \<what\>, so that \<why\>. |

### 2.5 Membro do grupo

- **Group Member**: Interacts within groups by posting and engaging with other group members.

| Identifier   | Name      | Priority    | Description                                           |
| ------------ | --------- | ----------- | ----------------------------------------------------- |
|  US01        | US Name 1 | High        | As an \<actor\>, I want to \<what\>, so that \<why\>. |
|  US02        | US Name 2 | Medium      | As an \<actor\>, I want to \<what\>, so that \<why\>. |
|  US03        | US Name 3 | Low         | As an \<actor\>, I want to \<what\>, so that \<why\>. |

### 2.6 Proprietário do grupo

- **Group Owner**: Manages group settings and membership, controls group visibility.

| Identifier   | Name      | Priority    | Description                                           |
| ------------ | --------- | ----------- | ----------------------------------------------------- |
|  US01        | US Name 1 | High        | As an \<actor\>, I want to \<what\>, so that \<why\>. |
|  US02        | US Name 2 | Medium      | As an \<actor\>, I want to \<what\>, so that \<why\>. |
|  US03        | US Name 3 | Low         | As an \<actor\>, I want to \<what\>, so that \<why\>. |

### 2.7 Autor da publicação

- **Post Author**: Edits or deletes their own posts.

| Identifier   | Name      | Priority    | Description                                           |
| ------------ | --------- | ----------- | ----------------------------------------------------- |
|  US01        | US Name 1 | High        | As an \<actor\>, I want to \<what\>, so that \<why\>. |
|  US02        | US Name 2 | Medium      | As an \<actor\>, I want to \<what\>, so that \<why\>. |
|  US03        | US Name 3 | Low         | As an \<actor\>, I want to \<what\>, so that \<why\>. |

### 2.8 Autor do comentário

- **Comment Author**: Edits or deletes their own comments.

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

![](IMG/SiteMap.png){style="display: block; margin: 0 auto" }


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