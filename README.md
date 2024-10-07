# LBAW

# ER: Requirements Specification Component

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

| Identifier   | Name                   | Priority    | User Story                                                             |
| ------------ | ---------------------- | ----------- | ----------------------------------------------------------------------- |
|  US01        | Block User              | High        | As an administrator, I want to block users, so that I can prevent abusive behavior. |
|  US02        | Suspend User Account    | High        | As an administrator, I want to temporarily suspend user accounts, so that I can address misconduct without permanent deletion. |
|  US03        | Manage Reports          | High        | As an administrator, I want to review and resolve user-submitted reports, so that I can address misconduct or inappropriate content. |
|  US04        | Manage User Roles       | High        | As an administrator, I want to assign or change user roles, so that I can manage user permissions effectively. |
|  US05        | View User Activity Logs | Medium      | As an administrator, I want to view user activity logs, so that I can monitor behavior and ensure compliance. |
|  US06       | Delete User Account     | Medium      | As an administrator, I want to delete user accounts, so that I can remove inactive or inappropriate accounts. |
|  US07        | Manage Content          | Medium      | As an administrator, I want to remove inappropriate content, so that I can maintain platform integrity. |
|  US08        | Unblock User            | Medium      | As an administrator, I want to unblock previously blocked users, so that they can regain access. |
|  US09        | Restore User Account    | Low         | As an administrator, I want to restore suspended or deleted user accounts, so that users can regain access if necessary. |
|  US10        | Broadcast Announcement  | Low         | As an administrator, I want to send platform-wide announcements, so that users stay informed about important updates. |

---

#### 2.2. User

- **User**: Manages personal profile information, account settings, and preferences.

| Identifier   | Name                   | Priority    | User Story                                                             |
| ------------ | ---------------------- | ----------- | ----------------------------------------------------------------------- |
|  US11        | View Public Timeline   | High        | As a user, I want to view the public timeline, so that I can keep up with current events. |
|  US12        | Browse Public Profiles  | Medium      | As a user, I want to browse public profiles, so that I can learn more about others before registering. |
|  US13        | View About Page        | Low         | As a user, I want to view the "About" page, so that I can understand the platform’s creators. |
|  US14        | View Contact Page      | Low         | As a user, I want to view contact information, so that I can reach out to the platform administrators. |

---

#### 2.3 Visitor

- **Visitor**: Browses public information, registers for an account, and logs in.

| Identifier   | Name                   | Priority    | User Story                                                             |
| ------------ | ---------------------- | ----------- | ----------------------------------------------------------------------- |
|  US21        | Register Account        | High        | As a visitor, I want to register a new account, so that I can access the platform's features. |
|  US22        | Log In                  | High        | As a visitor, I want to log into my account, so that I can access my personal content and settings. |

---

#### 2.4. Authenticated

- **Authenticated User**: Gains access to posting, commenting, managing friends, and joining groups.

| Identifier   | Name                   | Priority    | User Story                                                             |
| ------------ | ---------------------- | ----------- | ----------------------------------------------------------------------- |
|  US31        | Edit Profile           | High        | As an authenticated user, I want to edit my profile information, so that my information stays up to date. |
|  US32        | Recover Password       | High        | As an authenticated user, I want to recover a lost or forgotten password, so that I can regain access to my account. |
|  US33        | Log Out                | High        | As an authenticated user, I want to log out securely, so that my session ends and my data is protected. |
|  US34        | Create Post            | High        | As an authenticated user, I want to create posts, so that I can share content with others. |
|  US035       | Comment on Post        | High        | As an authenticated user, I want to comment on posts, so that I can participate in discussions. |
|  US036       | Search Posts/Users/Groups/Tasks | High | As an authenticated user, I want to search for posts, users, groups, and tasks, so that I can find specific content quickly. |
|  US37        | Change Password        | Medium      | As an authenticated user, I want to change my password, so that I can enhance my account security. |
|  US38        | Delete Account         | Medium      | As an authenticated user, I want to delete my account, so that I can remove my data from the platform. |
|  US39        | Update Profile Picture | Medium      | As an authenticated user, I want to change my profile picture, so that I can personalize my profile. |
|  US40        | Set Profile Visibility | Medium      | As an authenticated user, I want to control my profile's visibility, so that I can manage who sees my information. |
|  US41        | Manage Notifications   | Medium      | As an authenticated user, I want to manage my notification preferences, so that I only receive relevant alerts. |
|  US042       | Send Friend Request    | Medium      | As an authenticated user, I want to send friend requests, so that I can connect with other users. |
|  US043       | Join Group             | Medium      | As an authenticated user, I want to join groups, so that I can engage with like-minded people. |
|  US044       | View Notifications     | Medium      | As an authenticated user, I want to view notifications, so that I can stay informed about interactions. |
|  US045       | Manage Friend List     | Medium      | As an authenticated user, I want to manage my friends list, so that I can add or remove friends. |
|  US046       | Like Post              | Low         | As an authenticated user, I want to like posts, so that I can show appreciation or agreement. |
|  US047       | Edit Group Post        | Low         | As an authenticated user, I want to edit my posts in a group, so that I can update or correct information. |

---

#### 2.5 Group Member

- **Group Member**: Participates in group interactions through posts and engagement.

| Identifier   | Name                   | Priority    | User Story                                                             |
| ------------ | ---------------------- | ----------- | ----------------------------------------------------------------------- |
|  US51        | Create Group Post      | High        | As a group member, I want to create posts within the group, so that I can share content with other members. |
|  US52        | Comment on Group Post  | Medium      | As a group member, I want to comment on group posts, so that I can participate in discussions. |
|  US53        | View Group Members     | Medium      | As a group member, I want to view the list of other members, so that I can see who is part of the group. |
|  US54        | Like Group Post        | Low         | As a group member, I want to like posts within the group, so that I can express agreement. |
|  US55        | Leave Group            | Low         | As a group member, I want to leave a group, so that I can stop receiving notifications from it. |

---

#### 2.6 Group Owner

- **Group Owner**: Manages group settings, membership, and controls visibility.

| Identifier   | Name                   | Priority    | User Story                                                             |
| ------------ | ---------------------- | ----------- | ----------------------------------------------------------------------- |
|  US61        | Edit Group Details     | High        | As a group owner, I want to edit the group’s details, so that I can keep its information accurate. |
|  US62        | Add/Remove Members     | High        | As a group owner, I want to add or remove members, so that I can manage group membership. |
|  US63        | Approve Join Requests  | Medium      | As a group owner, I want to approve or reject join requests, so that I can control who joins the group. |
|  US64        | Remove Group Post      | Medium      | As a group owner, I want to remove inappropriate posts, so that the group remains a safe space. |
|  US65        | Invite Members         | Medium      | As a group owner, I want to invite new members, so that I can grow the group. |
|  US66        | Change Group Privacy   | Low         | As a group owner, I want to change the group’s privacy settings, so that I can adjust its visibility. |
|  US67        | Manage Group Roles     | Low         | As a group owner, I want to assign roles, so that I can promote members to moderators. |

---

#### 2.7 Post Author

- **Post Author**: Controls the content of their own posts, with options to edit or delete them.

| Identifier   | Name                   | Priority    | User Story                                                             |
| ------------ | ---------------------- | ----------- | ----------------------------------------------------------------------- |
|  US71        | Edit Post              | High        | As a post author, I want to edit my posts, so that I can update or correct the information. |
|  US72        | Delete Post            | High        | As a post author, I want to delete my posts, so that I can remove content permanently. |
|  US73        | Manage Post Visibility | Medium      | As a post author, I want to manage the visibility of my posts, so that I can control who sees my content. |
|  US74        | Tag Friends in Post    | Low      | As a post author, I want to tag friends in my posts, so that I can engage them in my content. |
|  US75        | Add Media to Post      | Low         | As a post author, I want to add media to my posts, so that I can enrich the content with images or videos. |

---

#### 2.8 Comment Author

- **Comment Author**: Edits or deletes their own comments.

| Identifier   | Name                   | Priority    | User Story                                                             |
| ------------ | ---------------------- | ----------- | ----------------------------------------------------------------------- |
|  US81        | Edit Comment           | High        | As a comment author, I want to edit my comments, so that I can update or correct them. |
|  US82        | Delete Comment         | High        | As a comment author, I want to delete my comments, so that I can remove them permanently. |
|  US83        | Reply to Comment       | Medium      | As a comment author, I want to reply to comments, so that I can engage in discussions. |
|  US84        | Like Comment           | Low         | As a comment author, I want to like comments, so that I can show agreement or appreciation. |
|  US85        | Tag Friends in Comment | Low         | As a comment author, I want to tag friends in my comments, so that I can involve them in the conversation. |

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

---

***
GROUP24116, 01/10/2024

* Alexandre Chouzende - 202207429@up.pt
* Rui Borges - 202207475@up.pt
* Pedro Paixão- 202008467@up.pt
* Tiago Torrado - 202206399@up.pt

