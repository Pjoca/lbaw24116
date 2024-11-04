# LBAW

## A1 - ConnectFEUP

The project's goal is to develop a social network platform specifically designed for FEUP students, addressing the problem of making genuine connections in a large university setting. The motivation for this platform is the need for a safe and trustworthy environment, where students can form friendships and romantic relationships, especially for those who may be shy or introverted. This platform will help students meet people compatible with them, allowing them to make meaningful and unique connections.

The main objective of the project is to help relationships among students, whether personal, romantic, or professional. A crucial aspect is ensuring that only verified FEUP students can access the platform, thereby safeguarding personal data and maintaining user privacy. With an intuitive, user-friendly interface, the platform will incorporate Tinder-like features, ensuring it is fully compatible across all devices.

The site will provide a variety of features for users. It will allow new users to register easily with their FEUP credentials, login in and logout, and recover forgotten passwords. Users will have the ability to view public profiles, edit their personal information, and even delete their accounts if they choose to leave the platform.

Once authenticated, users will be able to access a variety of features. They will be able to view and edit their profiles, change their profile pictures, and check personal notifications. A personalized timeline will showcase updates based on friend's activities, while robust search functionalities will help users find posts, comments, groups, and other users, enhanced by advacend search filters.

In addition, users will have the ability to send and manage friend requests, view friend profiles, and create and interact with posts. They can comment on, like, and reply to posts, as well as create groups, join public groups, and tag friends in their posts. They will also have the option to view their friends' feeds and ask to join public groups.

For those who create content, features will include the ability to edit and delete their posts, as well as manage post visibility. Comment authors will also have the ability to edit or delete their comments. Group members will be able to view other members, post in groups, and leave groups when they wish, while group owners will have additional privileges, such as editing group information, managing membership requests, and removing members or posts from the group.

Notifications will keep users informed about likes on their posts, comments on their content, and incoming friend requests, ensuring they remain engaged with their network. Administrators will play a vital role in managing user accounts, including locking, unlocking, and deleting accounts as needed.

To assist users, the platform will feature contextual help, bookmarks for form entries, and error messages to enhance user experience. Additionally, the site will provide information about itself, including an "About Us" section, details about its main features, and contact information for support.

Overall, this social networking platform seeks to create a vibrant and secure environment for FEUP students, facilitating authentic connections and enriching their university experience.


## A2: Actors and User stories

The actors and user stories contain information about the people who will use ConnectFEUP and how to use it.  

### 1. Actors

In the figure below, you can see the representation of the actors

![](IMG/Actors.png){style="display: block; margin: 0 auto" }

Below, you can see the description of each actor

| Identifier              | Name      |                                     
| ----------------------- | --------- | 
|  Administrator          | The administrator has the power to remove messages, block and unblock people. |
|  User                   | Normal User. |
|  Visitor                  | Unauthenticated users can view public information, register or log in. |
|  Autenticated User      | Authenticated users can view information, comment on other people's posts, send and receive messages with other people or groups. |
|  Group Member           | Members of a group can participate, chat and post in a group, but do not have special permissions. |
|  Group Owner            | Group owners not only have the same permissions as a member, but can also edit the group name, description, add/remove members and edit the permissions of other participants. |
|  Post Author            | Users can delete or edit their post. |
|  Comment Author         | Users can edit or delete their own comments on any post. |

## 2. User Stories

#### 2.1. Administrator

- **Administrator**: Responsible for managing user accounts, blocking/unblocking users, overseeing platform content, and ensuring safety and compliance.

| Identifier   | Name                   | Priority    | User Story |  Responsible                                                           |
| ------------ | ---------------------- | ----------- | ----------------------------------------------------------------------- | ---------------------- |
|  US01        | Block User              | High        | As an administrator, I want to block users, so that I can prevent abusive behavior. | Rui Borges |
|  US02        | Suspend User Account    | High        | As an administrator, I want to temporarily suspend user accounts, so that I can address misconduct without permanent deletion. | Tiago Torrado |
|  US03        | Manage Reports          | High        | As an administrator, I want to review and resolve user-submitted reports, so that I can address misconduct or inappropriate content. | Pedro Paixão |
|  US04        | Manage User Roles       | High        | As an administrator, I want to assign or change user roles, so that I can manage user permissions effectively. | Alexandre Chouzende |
|  US05        | View User Activity Logs | Medium      | As an administrator, I want to view user activity logs, so that I can monitor behavior and ensure compliance. | Tiago Torrado |
|  US06       | Delete User Account     | Medium      | As an administrator, I want to delete user accounts, so that I can remove inactive or inappropriate accounts. | Rui Borges |
|  US07        | Manage Content          | Medium      | As an administrator, I want to remove inappropriate content, so that I can maintain platform integrity. | Alexandre Chouzende
|  US08        | Unblock User            | Medium      | As an administrator, I want to unblock previously blocked users, so that they can regain access. | Pedro Paixão |
|  US09        | Restore User Account    | Low         | As an administrator, I want to restore suspended or deleted user accounts, so that users can regain access if necessary. | Rui Borges |
|  US10        | Broadcast Announcement  | Low         | As an administrator, I want to send platform-wide announcements, so that users stay informed about important updates. | Tiago Torrado |

---

#### 2.2. User

- **User**: Manages personal profile information, account settings, and preferences.

| Identifier   | Name                   | Priority    | User Story |  Responsible                                                           |
| ------------ | ---------------------- | ----------- | ----------------------------------------------------------------------- | ---------------------- |
|  US11        | View Public Timeline   | High        | As a user, I want to view the public timeline, so that I can keep up with current events. | Tiago Torrado |
|  US12        | Browse Public Profiles  | Medium      | As a user, I want to browse public profiles, so that I can learn more about others before registering. | Rui Borges |
|  US13        | View About Page        | Low         | As a user, I want to view the "About" page, so that I can understand the platform’s creators. | Alexandre Chouzende |
|  US14        | View Contact Page      | Low         | As a user, I want to view contact information, so that I can reach out to the platform administrators. | Pedro Paixão |

---

#### 2.3 Visitor

- **Visitor**: Browses public information, registers for an account, and logs in.

| Identifier   | Name                   | Priority    | User Story |  Responsible                                                           |
| ------------ | ---------------------- | ----------- | ----------------------------------------------------------------------- | ---------------------- |
|  US21        | Register Account        | High        | As a visitor, I want to register a new account, so that I can access the platform's features. | Alexandre Chouzende |
|  US22        | Log In                  | High        | As a visitor, I want to log into my account, so that I can access my personal content and settings. | Pedro Paixão |

---

#### 2.4. Authenticated

- **Authenticated User**: Gains access to posting, commenting, managing friends, and joining groups.

| Identifier   | Name                                   | Priority    | User Story                                                                 | Responsible          |
| ------------ | -------------------------------------- | ----------- | -------------------------------------------------------------------------- | -------------------- |
| US31         | Edit Profile                           | High        | As an authenticated user, I want to edit my profile information, so that my information stays up to date. | Alexandre Chouzende  |
| US32         | Recover Password                       | High        | As an authenticated user, I want to recover a lost or forgotten password, so that I can regain access to my account. | Pedro Paixão         |
| US33         | Log Out                                | High        | As an authenticated user, I want to log out securely, so that my session ends and my data is protected. | Tiago Torrado        |
| US34         | Create Post                            | High        | As an authenticated user, I want to create posts, so that I can share content with others. | Rui Borges           |
| US035        | Comment on Post                        | High        | As an authenticated user, I want to comment on posts, so that I can participate in discussions. | Alexandre Chouzende  |
| US036        | Search Posts/Users/Groups/Tasks        | High        | As an authenticated user, I want to search for posts, users, groups, and tasks, so that I can find specific content quickly. | Pedro Paixão         |
| US37         | Change Password                        | Medium      | As an authenticated user, I want to change my password, so that I can enhance my account security. | Tiago Torrado        |
| US38         | Delete Account                         | Medium      | As an authenticated user, I want to delete my account, so that I can remove my data from the platform. | Rui Borges           |
| US39         | Update Profile Picture                 | Medium      | As an authenticated user, I want to change my profile picture, so that I can personalize my profile. | Alexandre Chouzende  |
| US40         | Set Profile Visibility                 | Medium      | As an authenticated user, I want to control my profile's visibility, so that I can manage who sees my information. | Pedro Paixão         |
| US41         | Manage Notifications                   | Medium      | As an authenticated user, I want to manage my notification preferences, so that I only receive relevant alerts. | Tiago Torrado        |
| US042        | Send Friend Request                    | Medium      | As an authenticated user, I want to send friend requests, so that I can connect with other users. | Rui Borges           |
| US043        | Join Group                             | Medium      | As an authenticated user, I want to join groups, so that I can engage with like-minded people. | Alexandre Chouzende  |
| US044        | View Notifications                     | Medium      | As an authenticated user, I want to view notifications, so that I can stay informed about interactions. | Pedro Paixão         |
| US045        | Manage Friend List                     | Medium      | As an authenticated user, I want to manage my friends list, so that I can add or remove friends. | Tiago Torrado        |
| US046        | Like Post                              | Low         | As an authenticated user, I want to like posts, so that I can show appreciation or agreement. | Rui Borges           |
| US047        | Edit Group Post                        | Low         | As an authenticated user, I want to edit my posts in a group, so that I can update or correct information. | Alexandre Chouzende  |

---

#### 2.5 Group Member

- **Group Member**: Participates in group interactions through posts and engagement.

| Identifier   | Name                    | Priority    | User Story                                                                 | Responsible          |
| ------------ | ----------------------- | ----------- | -------------------------------------------------------------------------- | -------------------- |
| US51         | Create Group Post        | High        | As a group member, I want to create posts within the group, so that I can share content with other members. | Tiago Torrado        |
| US52         | Comment on Group Post    | Medium      | As a group member, I want to comment on group posts, so that I can participate in discussions. | Pedro Paixão         |
| US53         | View Group Members       | Medium      | As a group member, I want to view the list of other members, so that I can see who is part of the group. | Rui Borges           |
| US54         | Like Group Post          | Low         | As a group member, I want to like posts within the group, so that I can express agreement. | Alexandre Chouzende  |
| US55         | Leave Group              | Low         | As a group member, I want to leave a group, so that I can stop receiving notifications from it. | Pedro Paixão         |

---

#### 2.6 Group Owner

- **Group Owner**: Manages group settings, membership, and controls visibility.

| Identifier   | Name                    | Priority    | User Story                                                                 | Responsible          |
| ------------ | ----------------------- | ----------- | -------------------------------------------------------------------------- | -------------------- |
| US61         | Edit Group Details       | High        | As a group owner, I want to edit the group’s details, so that I can keep its information accurate. | Rui Borges           |
| US62         | Add/Remove Members       | High        | As a group owner, I want to add or remove members, so that I can manage group membership. | Tiago Torrado        |
| US63         | Approve Join Requests    | Medium      | As a group owner, I want to approve or reject join requests, so that I can control who joins the group. | Pedro Paixão         |
| US64         | Remove Group Post        | Medium      | As a group owner, I want to remove inappropriate posts, so that the group remains a safe space. | Alexandre Chouzende  |
| US65         | Invite Members           | Medium      | As a group owner, I want to invite new members, so that I can grow the group. | Pedro Paixão         |
| US66         | Change Group Privacy     | Low         | As a group owner, I want to change the group’s privacy settings, so that I can adjust its visibility. | Tiago Torrado        |
| US67         | Manage Group Roles       | Low         | As a group owner, I want to assign roles, so that I can promote members to moderators. | Rui Borges           |

---

#### 2.7 Post Author

- **Post Author**: Controls the content of their own posts, with options to edit or delete them.

| Identifier   | Name                   | Priority    | User Story                                                                 | Responsible          |
| ------------ | ---------------------- | ----------- | -------------------------------------------------------------------------- | -------------------- |
| US71         | Edit Post              | High        | As a post author, I want to edit my posts, so that I can update or correct the information. | Alexandre Chouzende  |
| US72         | Delete Post            | High        | As a post author, I want to delete my posts, so that I can remove content permanently. | Pedro Paixão         |
| US73         | Manage Post Visibility | Medium      | As a post author, I want to manage the visibility of my posts, so that I can control who sees my content. | Rui Borges           |
| US74         | Tag Friends in Post    | Low         | As a post author, I want to tag friends in my posts, so that I can engage them in my content. | Tiago Torrado        |
| US75         | Add Media to Post      | Low         | As a post author, I want to add media to my posts, so that I can enrich the content with images or videos. | Pedro Paixão         |

---

#### 2.8 Comment Author

- **Comment Author**: Edits or deletes their own comments.

| Identifier   | Name                   | Priority    | User Story                                                             | Responsible          |
| ------------ | ---------------------- | ----------- | ----------------------------------------------------------------------- | -------------------- |
| US81         | Edit Comment           | High        | As a comment author, I want to edit my comments, so that I can update or correct them. | Alexandre Chouzende  |
| US82         | Delete Comment         | High        | As a comment author, I want to delete my comments, so that I can remove them permanently. | Pedro Paixão         |
| US83         | Reply to Comment       | Medium      | As a comment author, I want to reply to comments, so that I can engage in discussions. | Tiago Torrado        |
| US84         | Like Comment           | Low         | As a comment author, I want to like comments, so that I can show agreement or appreciation. | Rui Borges           |
| US85         | Tag Friends in Comment | Low         | As a comment author, I want to tag friends in my comments, so that I can involve them in the conversation. | Pedro Paixão         |

### 3. Supplementary Requirements

#### 3.1. Business rules

| **Identifier**  | **Name**                                      | **Description**                                                                                                                                                                                                     |
|-------------|-------------------------------------------|-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| BR01        | User Voting/Commenting/Reviewing on Own Stories/Answers | A user can vote, comment, and view their own posts and comments.                                                                                                                                               |
| BR02        | Content Ownership and Deletion            | When a user is deleted or removes their account, their content (posts, comments, groups, messages) remains visible but anonymous. The profile will indicate that the user is deleted, preventing further interaction. |
| BR03        | Group Ownership Transfer upon Deletion or Exit | If a group owner deletes their account, ownership is transferred to another member. If no member is eligible, the group is deleted.                                                                             |
| BR04        | Date Validations (Entry Date/Exit Date)   | Exit dates associated with user activities (e.g., joining/leaving a group) must be greater than or equal to the entry date.                                                                                     |
| BR05        | Mandatory Profile Completion for Engagement Features | Users must complete a minimum percentage of their profile before they can engage in features like sending friend requests, joining groups, posting content, or matching with others.                            |
| BR06        | Account Creation and Log In               | Only FEUP students can create accounts. All account-related actions (login, sign-in, change password) must be done using the student’s institutional email.                                                      |
| BR101       | Profile Privacy Settings                  | Profiles can be public or private. Private profiles are only visible to friends.                                                                                                                                |

#### 3.2. Technical requirements

| **Identifier** | **Name**                                        | **Justification**                                                                                                                                               |
|----------------|-------------------------------------------------|-----------------------------------------------------------------------------------------------------------------------------------------------------------------|
| **TR01**       | **Secure User Authentication**                  | **To prevent outsiders from accessing the platform, maintaining the safety and exclusivity of the TinderFEUP network**                                             |
| TR02       | Scalable Architecture for Real-Time Interaction | Given the platform’s nature as a social network, users expect immediate responses for messages, notifications, and other interactions.                           |
| **TR03**       | **Data Encryption and Privacy**                 | **To ensure compliance with data protection laws (e.g., GDPR) and protect users’ personal data from unauthorized access.**                                           |
| TR04       | Rate Limiting and Anti-Spam Measures        | Helps to prevent abuse, spam, and resource exhaustion, ensuring a positive user experience and preventing overload on the platform’s infrastructure.             |
| **TR05**       | **Responsive Design and Accessibility**         | **Ensures that the platform is accessible to all users, regardless of device or ability, promoting inclusivity and a consistent experience across devices.**         |
| TR06       | System Response Times (< 2s)                | Ensures a smooth user experience by keeping response times under 2 seconds, helping maintain user engagement and attention.                                       |
| TR07       | Robustness (Error Handling)                 | The system must be able to handle runtime errors gracefully and continue operating without major service disruption.                                              |
| TR08       | Scalability                                 | The system must be designed to accommodate growth in the number of users and their interactions without degradation in performance.                              |
| TR09       | Accessibility for All Users                 | The platform should be designed to be fully accessible, ensuring usability for all users regardless of any disabilities or the web browser they use.              |

#### 3.3. Restrictions

| **Identifier** | **Name**                              | **Description**                                                                                                                                    |
|------------|-----------------------------------|------------------------------------------------------------------------------------------------------------------------------------------------|
| R01        | Age Restriction                   | Only users who are 18 years or older may use the platform, as the platform may deal with dating and sensitive social interactions.               |
| R02        | Content Moderation and Offensive Behavior | All posts, comments, and interactions must comply with the platform’s code of conduct. Offensive content will be flagged, and repeat offenders banned. |
| R03        | Data Retention Policy             | User data will be stored for a maximum period of time after account deletion, unless legally required otherwise. |

---

## A3: Information Architecture

### 1. Sitemap

The ConnectFEUP system is organized into five main areas: the Static Pages, which provide general information about the platform (e.g., FAQ and About Us); the User Pages, which allow users to manage their profiles, such as editing personal information or signing up; the Posts Pages, where users can create and interact with posts and comments; the Matchmaking Pages, which feature matchmaking feeds to help users find compatible connections; and the Message Pages, where users can manage and participate in individual or group chats. Additionally, there is an Admin Pages section dedicated to administrative features.

![](IMG/sitemap.png){style="display: block; margin: 0 auto" }
<p style="text-align:center;"><b> Figura 1: ConnectFEUP sitemap. </b></p>

### 2. Wireframes

#### UI01: Home Page

![](IMG/homepage.png){style="display: block; margin: 0 auto" }
<p style="text-align:center;"><b> Figura 2: Home Page (UI01) wireframe. </b></p>
<br>


#### UI03: User Profile

![](IMG/profile.png){style="display: block; margin: 0 auto" }
<p style="text-align:center;"><b> Figura 3: User Profile (UI03) wireframe. </b></p>
<br>

#### UI08: Matchmaking Feed

![](IMG/matchmaking.png){style="display: block; margin: 0 auto" }
<p style="text-align:center;"><b> Figura 4: User Matchmaking (UI08) wireframe. </b></p>
<br>


----

## A4: Conceptual Data Model


---

## A5: Relational Schema

### Relational Model

| Identifier                  | Notation                                                                                                   |
|-----------------------------|------------------------------------------------------------------------------------------------------------|
| **R01**                     | user(<ins>id</ins> , username **UK NN**, email **UK NN**, name **NN**, password **NN**, visibility **CK** visibility **IN** visibility_type, account_status **CK** account_status **IN** account_status_type, last_login **DF** TODAY) |
| **R02**                     | friendship_connection(<ins>id_user1</ins> -> user, <ins>id_user2</ins> -> user, status **CK** status **IN** friendship_status_type, created_at DF TODAY, visibility **CK** visibility **IN** visibility_type) |
| **R03**                     | group(<ins>id</ins>, name **NN**, description, visibility **CK** visibility **IN** (public, private), created_at **DF** TODAY, id_owner -> user **NN**) |
| **R04**                     | group_membership(<ins>id_user</ins> -> user, id_group -> group, joined_at **DF** TODAY, status **CK** status **IN** visibility_type) |
| **R05**                     | post(<ins>id</ins>, content NN, edited_at DF TODAY, visibility CK visibility IN visibility_type, id_user -> user NN, id_group -> group) |
| **R06**                     | comment(<ins>id</ins>, content **NN**, edited_at **DF** TODAY, deleted_at **CK** deleted_at >= TODAY, id_user -> user **NN**, id_post -> post **NN**) |
| **R07**                     | like(<ins>id</ins>, created_at **DF** TODAY, id_user -> user **NN**, id_post -> post)                                         |
| **R08**                     | report(<ins>id</ins>, description **NN**, status **CK** status **IN** report_status_type, id_user -> user **NN**, id_reported_post -> post, id_reported_comment -> comment) |
| **R09**                     | notification(<ins>id</ins>, seen **DF** FALSE, id_user -> user **NN**, id_related_post -> post, id_related_comment -> comment, id_related_group -> group) |
| **R10**                     | user_notification(<ins>id_notification</ins> -> notification **NN**, type **CK** type **IN** user_notification_type) |
| **R11**                     | comment_notification(<ins>id_notification</ins> -> notification **NN**, type **CK** type **IN** comment_notification_type) |
| **R12**                     | group_notification(<ins>id_notification</ins> -> notification **NN**, type **CK** type **IN** group_notification_type)                 |
| **R13**                     | post_notification(<ins>id_notification</ins> -> notification **NN**, type **CK** type **IN** post_notification_type)                       |
| **R14**                     | like_notification(<ins>id_notification</ins> -> notification **NN**, type **CK** type **IN** like_notification_type)         |
| **R15**                     | message_notification(<ins>id_notification</ins> -> notification **NN**, type **CK** type **IN** message_notification_type)            |
| **R16** | admin(<ins>id</ins>->users) |



### Domains

| Domain | Description |
|--------|-------------|
| **TODAY** | DATE DEFAULT CURRENT_DATE |
| **visibility_type** | ENUM('public', 'private', 'group') |
| **account_status_type** | ENUM('active', 'offline', 'deleted', 'blocked') |
| **friendship_status_type** | ENUM('pending', 'accepted', 'rejected') |
| **report_status_type** | ENUM('pending', 'reviewed', 'resolved') |
| **user_notification_type** | ENUM('received_friend_request', 'accepted_friend_request', 'match') |
| **comment_notification_type** | ENUM('replied_to_comment', 'commented_on_post', 'tagged_comment') |
| **group_notification_type** | ENUM('invited_to_group') |
| **post_notification_type** | ENUM('tagged_post') |
| **like_notification_type** | ENUM('liked_comment', 'liked_post') |
| **message_notification_type** | ENUM('received_message') |
| **group_membership_status** | ENUM('pending', 'accepted', 'rejected') |


### Schema Validation

#### user:

| Identifier   | Relation Name       |
|--------------|---------------------|
| **R01**      | `user`              |
| **Functional Dependencies** | {id} → {username, email, name, password, visibility, account_status, last_login} |
| **Normal Form**            | BCNF                |



#### friendship_connection:

| Identifier   | Relation Name             |
|--------------|---------------------------|
| **R02**      | `friendship_connection`   |
| **Functional Dependencies** | {id_user1, id_user2} → {status, created_at, visibility} |
| **Normal Form**            | BCNF                |

#### group:

| Identifier   | Relation Name       |
|--------------|---------------------|
| **R03**      | `group`             |
| **Functional Dependencies** | {id} → {name, description, visibility, created_at, id_owner} |
| **Normal Form**            | BCNF                |

#### group_membership:

| Identifier   | Relation Name       |
|--------------|---------------------|
| **R04**      | `group_membership`  |
| **Functional Dependencies** | {id_user, id_group} → {joined_at, status} |
| **Normal Form**            | BCNF                |

#### post:

| Identifier   | Relation Name       |
|--------------|---------------------|
| **R05**      | `post`              |
| **Functional Dependencies** | {id} → {content, edited_at, visibility, id_user, id_group} |
| **Normal Form**            | BCNF                |

#### comment:

| Identifier   | Relation Name       |
|--------------|---------------------|
| **R06**      | `comment`           |
| **Functional Dependencies** | {id} → {content, edited_at, deleted_at, id_user, id_post} |
| **Normal Form**            | BCNF                |

#### like:

| Identifier   | Relation Name       |
|--------------|---------------------|
| **R07**      | `like`              |
| **Functional Dependencies** | {id} → {created_at, id_user, id_post} |
| **Normal Form**            | BCNF                |

#### report:

| Identifier   | Relation Name       |
|--------------|---------------------|
| **R08**      | `report`            |
| **Functional Dependencies** | {id} → {description, status, id_user, id_reported_post, id_reported_comment} |
| **Normal Form**            | BCNF                |

#### notification:

| Identifier   | Relation Name       |
|--------------|---------------------|
| **R09**      | `notification`      |
| **Functional Dependencies** | {id} → {seen, id_user, id_related_post, id_related_comment, id_related_group} |
| **Normal Form**            | BCNF                |

#### user_notification:
| Identifier   | Relation Name         |
|--------------|-----------------------|
| **R10**      | `user_notification`   |
| **Functional Dependencies** | {id_notification} → {type} |
| **Normal Form**            | BCNF                |

#### comment_notification:

| Identifier   | Relation Name         |
|--------------|-----------------------|
| **R11**      | `comment_notification`|
| **Functional Dependencies** | {id_notification} → {type} |
| **Normal Form**            | BCNF                |

#### group_notification:

| Identifier   | Relation Name         |
|--------------|-----------------------|
| **R12**      | `group_notification`  |
| **Functional Dependencies** | {id_notification} → {type} |
| **Normal Form**            | BCNF                |

#### post_notification:

| Identifier   | Relation Name         |
|--------------|-----------------------|
| **R13**      | `post_notification`   |
| **Functional Dependencies** | {id_notification} → {type} |
| **Normal Form**            | BCNF                |

#### like_notification:

| Identifier   | Relation Name         |
|--------------|-----------------------|
| **R14**      | `like_notification`   |
| **Functional Dependencies** | {id_notification} → {type} |
| **Normal Form**            | BCNF                |


#### message_notification:

| Identifier   | Relation Name         |
|--------------|-----------------------|
| **R15**      | `message_notification`|
| **Functional Dependencies** | {id_notification} → {type} |
| **Normal Form**            | BCNF                |

#### admin:

| Identifier   | Relation Name       |
|--------------|---------------------|
| **R16**      | `admin`             |
| **Functional Dependencies** | {id} → {} |
| **Normal Form**            | BCNF                |


## A6: Indexes, triggers, transactions and database population

> Brief presentation of the artifact goals.

### 1. Database Workload
 
> A study of the predicted system load (database load).
> Estimate of tuples at each relation.

| **Relation reference** | **Relation Name**         | **Order of magnitude**          | **Estimated growth**       |
|------------------------|--------------------------|---------------------------------|----------------------------|
| R01                    | `user`                   | thousands                       | hundreds per month         |
| R02                    | `friendship_connection`  | hundreds                        | dozens per day             |
| R03                    | `group`                  | hundreds                        | dozens per month           |
| R04                    | `group_membership`       | thousands                       | hundreds per month         |
| R05                    | `post`                   | thousands                       | thousands per month        |
| R06                    | `comment`                | thousands                       | thousands per month        |
| R07                    | `like`                   | thousands                       | thousands per month        |
| R08                    | `report`                 | hundreds                        | dozens per month           |
| R09                    | `notification`           | thousands                       | hundreds per month         |
| R10                    | `user_notification`      | hundreds                        | dozens per month           |
| R11                    | `comment_notification`    | hundreds                        | dozens per month           |
| R12                    | `group_notification`     | hundreds                        | dozens per month           |
| R13                    | `post_notification`      | hundreds                        | dozens per month           |
| R14                    | `like_notification`      | hundreds                        | dozens per month           |
| R15                    | `message_notification`    | hundreds                        | dozens per month           |
| R16                    | `admin`                  | tens                           | no growth                  |



### 2. Proposed Indices

#### 2.1. Performance Indices
 
> Indices proposed to improve performance of the identified queries.

| **Index**           | IDX01                                  |
| ---                 | ---                                    |
| **Relation**        | Relation where the index is applied    |
| **Attribute**       | Attribute where the index is applied   |
| **Type**            | B-tree, Hash, GiST or GIN              |
| **Cardinality**     | Attribute cardinality: low/medium/high |
| **Clustering**      | Clustering of the index                |
| **Justification**   | Justification for the proposed index   |
| `SQL code`                                                  ||


#### 2.2. Full-text Search Indices 

> The system being developed must provide full-text search features supported by PostgreSQL. Thus, it is necessary to specify the fields where full-text search will be available and the associated setup, namely all necessary configurations, indexes definitions and other relevant details.  

| **Index**           | IDX01                                  |
| ---                 | ---                                    |
| **Relation**        | Relation where the index is applied    |
| **Attribute**       | Attribute where the index is applied   |
| **Type**            | B-tree, Hash, GiST or GIN              |
| **Clustering**      | Clustering of the index                |
| **Justification**   | Justification for the proposed index   |
| `SQL code`                                                  ||


### 3. Triggers
 
> User-defined functions and trigger procedures that add control structures to the SQL language or perform complex computations, are identified and described to be trusted by the database server. Every kind of function (SQL functions, Stored procedures, Trigger procedures) can take base types, composite types, or combinations of these as arguments (parameters). In addition, every kind of function can return a base type or a composite type. Functions can also be defined to return sets of base or composite values.  

| **Trigger**      | TRIGGER01                              |
| ---              | ---                                    |
| **Description**  | Trigger description, including reference to the business rules involved |
| `SQL code`                                             ||

### 4. Transactions
 
> Transactions needed to assure the integrity of the data.  

| SQL Reference   | Transaction Name                    |
| --------------- | ----------------------------------- |
| Justification   | Users should  |
| Isolation level | Read Commited |
| `Complete SQL Code`                                   |


| SQL Reference   | Transaction Name                    |
| --------------- | ----------------------------------- |
| Justification   | Justification for the transaction.  |
| Isolation level | Isolation level of the transaction. |
| `Complete SQL Code`                                   |


| SQL Reference   | Transaction Name                    |
| --------------- | ----------------------------------- |
| Justification   | Justification for the transaction.  |
| Isolation level | Isolation level of the transaction. |
| `Complete SQL Code`                                   |


## Annex A. SQL Code

> The database scripts are included in this annex to the EBD component.
> 
> The database creation script and the population script should be presented as separate elements.
> The creation script includes the code necessary to build (and rebuild) the database.
> The population script includes an amount of tuples suitable for testing and with plausible values for the fields of the database.
>
> The complete code of each script must be included in the group's git repository and links added here.

### A.1. Database schema

> The complete database creation must be included here and also as a script in the repository.

### A.2. Database population

> Only a sample of the database population script may be included here, e.g. the first 10 lines. The full script must be available in the repository.

***
GROUP24116, 01/10/2024

* Alexandre Chouzende - 202207429@up.pt
* Rui Borges - 202207475@up.pt
* Pedro Paixão- 202008467@up.pt
* Tiago Torrado - 202206399@up.pt

