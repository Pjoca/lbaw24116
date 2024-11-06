


























# EBD: Database Specification Component - ConnectFEUP
05/11/2024

## A4: Conceptual Data Model

The goal of this artificat is to ensure the data model accurately represents the business rules and relationships between entities, facilitating a clear understanding of the system's data structure.

### 1. Class diagram

![LBAW-UML.drawio(2).png](uploads/4fee9235c95e3bd7ee8e7772a646db4d/LBAW-UML.drawio_2_.png)

<br>

### 2. Additional Business Rules

| Rule ID | Description |
|---------|-------------|
| **BR01** | A user cannot report their own profile. Reporting functionality is restricted to other users' profiles to prevent self-reports. |
| **BR02** | A user cannot send a friend request to themselves. Friend requests can only be directed to other users. |
| **BR03** | A user cannot send a message to themselves. Messaging is restricted to communication between different users. |

<br>

## A5: Relational Schema, validation and schema refinement

The goal of this artifact is to develop a normalized relational schema that eliminates redundancy, enforces data integrity, and aligns with the conceptual data model.

### Relational Model

| Identifier | Notation |
|------------|----------|
| **R01** | user(<ins>id</ins> , username **UK NN**, email **UK NN**, name **NN**, password **NN**, visibility **CK** visibility **IN** visibility_type, account_status **CK** account_status **IN** account_status_type, last_login **DF** TODAY) |
| **R02** | friendship_connection(<ins>id_user1</ins> -\> user, <ins>id_user2</ins> -\> user, status **CK** status **IN** friendship_status_type, created_at DF TODAY, visibility **CK** visibility **IN** visibility_type) |
| **R03** | group(<ins>id</ins>, name **NN**, description, visibility **CK** visibility **IN** visibility_type, created_at **DF** TODAY, id_owner -\> user **NN**) |
| **R04** | group_membership(<ins>id_user</ins> -\> user, id_group -\> group, joined_at **DF** TODAY, status **CK** status **IN** group_membership_status) |
| **R05** | post(<ins>id</ins>, content NN, edited_at DF TODAY, visibility **CK** visibility IN visibility_type, id_user -\> user NN, id_group -\> group) |
| **R06** | comment(<ins>id</ins>, content **NN**, edited_at **DF** TODAY, deleted_at **CK** deleted_at \>= TODAY, visibility **CK** visibility **IN** visibility_type, id_user -\> user **NN**, id_post -\> post **NN**) |
| **R07** | like(<ins>id</ins>, created_at **DF** TODAY, id_user -\> user **NN**, id_post -\> post, visibility **CK** visibility **IN** visibility_type) |
| **R08** | report(<ins>id</ins>, description **NN**, status **CK** status **IN** report_status_type, id_user -\> user **NN**, id_reported_post -\> post, id_reported_comment -\> comment) |
| **R09** | notification(<ins>id</ins>, seen **DF** FALSE, id_user -\> user **NN**, id_related_post -\> post, id_related_comment -\> comment, id_related_group -\> group) |
| **R10** | user_notification(<ins>id_notification</ins> -\> notification **NN**, type **CK** type **IN** user_notification_type) |
| **R11** | comment_notification(<ins>id_notification</ins> -\> notification **NN**, type **CK** type **IN** comment_notification_type) |
| **R12** | group_notification(<ins>id_notification</ins> -\> notification **NN**, type **CK** type **IN** group_notification_type) |
| **R13** | post_notification(<ins>id_notification</ins> -\> notification **NN**, type **CK** type **IN** post_notification_type) |
| **R14** | like_notification(<ins>id_notification</ins> -\> notification **NN**, type **CK** type **IN** like_notification_type) |
| **R15** | message_notification(<ins>id_notification</ins> -\> notification **NN**, type **CK** type **IN** message_notification_type) |
| **R16** | admin(<ins>id</ins>-\>users) |
| **R17** | message(<ins>id</ins>, content **NN**, created_at **DF** TODAY, id_sender -\> user **NN**, id_receiver -\> user **NN**, visibility **CK** visibility **IN** visibility_type) |

<br>

### Domains

| Domain | Description |
|--------|-------------|
| **TODAY** | DATE DEFAULT CURRENT_DATE |
| **visibility_type** | ENUM('public', 'private', 'group') |
| **account_status_type** | ENUM('active', 'deleted', 'blocked') |
| **friendship_status_type** | ENUM('pending', 'accepted', 'rejected') |
| **report_status_type** | ENUM('pending', 'reviewed', 'resolved') |
| **user_notification_type** | ENUM('received_friend_request', 'accepted_friend_request', 'match') |
| **comment_notification_type** | ENUM('replied_to_comment', 'commented_on_post', 'tagged_comment') |
| **group_notification_type** | ENUM('invited_to_group') |
| **post_notification_type** | ENUM('tagged_post') |
| **like_notification_type** | ENUM('liked_comment', 'liked_post') |
| **message_notification_type** | ENUM('received_message') |

<br>

### Schema Validation

#### user:

| Identifier | Relation Name |
|------------|---------------|
| **R01** | `user` |
| **Functional Dependencies** | {id} → {username, email, name, password, visibility, account_status, last_login} |
| **Normal Form** | BCNF |

<br>

#### friendship_connection:

| Identifier | Relation Name |
|------------|---------------|
| **R02** | `friendship_connection` |
| **Functional Dependencies** | {id_user1, id_user2} → {status, created_at} |
| **Normal Form** | BCNF |

<br>

#### group:

| Identifier | Relation Name |
|------------|---------------|
| **R03** | `group` |
| **Functional Dependencies** | {id} → {name, description, visibility, created_at, id_owner} |
| **Normal Form** | BCNF |

<br>

#### group_membership:

| Identifier | Relation Name |
|------------|---------------|
| **R04** | `group_membership` |
| **Functional Dependencies** | {id_user, id_group} → {joined_at, status} |
| **Normal Form** | BCNF |

<br>

#### post:

| Identifier | Relation Name |
|------------|---------------|
| **R05** | `post` |
| **Functional Dependencies** | {id} → {content, edited_at, visibility, id_user, id_group} |
| **Normal Form** | BCNF |

<br>

#### comment:

| Identifier | Relation Name |
|------------|---------------|
| **R06** | `comment` |
| **Functional Dependencies** | {id} → {content, edited_at, deleted_at, id_user, id_post, visibility} |
| **Normal Form** | BCNF |

<br>

#### like:

| Identifier | Relation Name |
|------------|---------------|
| **R07** | `like` |
| **Functional Dependencies** | {id} → {created_at, id_user, id_post, visibility} |
| **Normal Form** | BCNF |

<br>

#### report:

| Identifier | Relation Name |
|------------|---------------|
| **R08** | `report` |
| **Functional Dependencies** | {id} → {description, status, id_user, id_reported_post, id_reported_comment} |
| **Normal Form** | BCNF |

<br>

#### notification:

| Identifier | Relation Name |
|------------|---------------|
| **R09** | `notification` |
| **Functional Dependencies** | {id} → {seen, id_user, id_related_post, id_related_comment, id_related_group} |
| **Normal Form** | BCNF |

<br>

#### user_notification:

| Identifier | Relation Name |
|------------|---------------|
| **R10** | `user_notification` |
| **Functional Dependencies** | {id_notification} → {type} |
| **Normal Form** | BCNF |

<br>

#### comment_notification:

| Identifier | Relation Name |
|------------|---------------|
| **R11** | `comment_notification` |
| **Functional Dependencies** | {id_notification} → {type} |
| **Normal Form** | BCNF |

<br>

#### group_notification:

| Identifier | Relation Name |
|------------|---------------|
| **R12** | `group_notification` |
| **Functional Dependencies** | {id_notification} → {type} |
| **Normal Form** | BCNF |

<br>

#### post_notification:

| Identifier | Relation Name |
|------------|---------------|
| **R13** | `post_notification` |
| **Functional Dependencies** | {id_notification} → {type} |
| **Normal Form** | BCNF |

<br>

#### like_notification:

| Identifier | Relation Name |
|------------|---------------|
| **R14** | `like_notification` |
| **Functional Dependencies** | {id_notification} → {type} |
| **Normal Form** | BCNF |

<br>

#### message_notification:

| Identifier | Relation Name |
|------------|---------------|
| **R15** | `message_notification` |
| **Functional Dependencies** | {id_notification} → {type} |
| **Normal Form** | BCNF |

<br>

#### admin:

| Identifier | Relation Name |
|------------|---------------|
| **R16** | `admin` |
| **Functional Dependencies** | {id} → {} |
| **Normal Form** | BCNF |

<br>

#### message:

| Identifier | Relation Name |
|------------|---------------|
| **R17** | `message` |
| **Functional Dependencies** | {id} → {content, created_at, id_sender, id_receiver, visibility} |
| **Normal Form** | BCNF |

<br>

## A6: Indexes, triggers, transactions and database population

The goal of this artifact is to optimize database performance and maintain data consistency through well-defined indexes, triggers, and transaction management.

### 1. Database Workload

| **Relation reference** | **Relation Name** | **Order of magnitude** | **Estimated growth** |
|------------------------|-------------------|------------------------|----------------------|
| R01 | `user` | thousands | hundreds per month |
| R02 | `friendship_connection` | hundreds | dozens per day |
| R03 | `group` | hundreds | dozens per month |
| R04 | `group_membership` | thousands | hundreds per month |
| R05 | `post` | thousands | thousands per month |
| R06 | `comment` | thousands | thousands per month |
| R07 | `like` | thousands | thousands per month |
| R08 | `report` | hundreds | dozens per month |
| R09 | `notification` | thousands | hundreds per month |
| R10 | `user_notification` | hundreds | dozens per month |
| R11 | `comment_notification` | hundreds | dozens per month |
| R12 | `group_notification` | hundreds | dozens per month |
| R13 | `post_notification` | hundreds | dozens per month |
| R14 | `like_notification` | hundreds | dozens per month |
| R15 | `message_notification` | hundreds | dozens per month |
| R16 | `admin` | tens | no growth |
| R17 | `message` | thousands | thousands per month |

<br>

### 2. Proposed Indices

#### 2.1. Performance Indices

> Performance indexes allows to increase the speed of SELECT queries at the cost of other operations execution times. However faster search speeds can be advantageous for certain tables. Below its possible to find the performace indexes implemented:

<br>

| **Index** | IDX01 |
|-----------|-------|
| **Relation** | user |
| **Attribute** | username, email |
| **Type** | B-tree |
| **Cardinality** | Medium |
| **Clustering** | No clustering required |
| **Justification** | Speeds up user searches and logins by filtering on username and email, frequently queried fields in user-related operations. |
| **SQL code** | ``` CREATE INDEX idx_user_username_email ON "user" (username, email); ```|

<br>

| **Index** | IDX02 |
|-----------|-------|
| **Relation** | friendship_connection |
| **Attribute** | id_user1, id_user2, status |
| **Type** | B-tree |
| **Cardinality** | High |
| **Clustering** | No clustering required |
| **Justification** | Optimizes queries for friendship statuses and connections, which are expected to be frequent in a social platform’s user interactions. |
| **SQL code** | ``` CREATE INDEX idx_friendship_user1_user2_status ON friendship_connection (id_user1, id_user2, status); ```|

<br>

| **Index** | IDX03 |
|-----------|-------|
| **Relation** | notification |
| **Attribute** | id_user, seen, created_at |
| **Type** | B-tree |
| **Cardinality** | High |
| **Clustering** | No clustering required |
| **Justification** | Boosts performance for retrieving notifications by user, seen status, and creation date, useful for displaying recent or unread notifications. |
| **SQL code** | ``` CREATE INDEX idx_notification_user_seen_created_at ON notification (id_user, seen, created_at); ```|

<br>

#### 2.2. Full-text Search Indices

> The system being developed must provide full-text search features. Thus, below are the indexes that will improve the performance the most.

<br>

| **Index** | IDX04 |
|-----------|-------|
| **Relation** | user |
| **Attribute** | username, name |
| **Type** | GIN |
| **Clustering** | No clustering required |
| **Justification** | Enables full-text search on username and name, essential for fast search functionality on user profiles. |
| **SQL code** | ``` ALTER TABLE "user" ADD COLUMN user_search_tsv tsvector; UPDATE "user" SET user_search_tsv = to_tsvector('english', username CREATE INDEX idx_user_search ON "user" USING GIN (user_search_tsv); CREATE TRIGGER trg_user_search_update BEFORE INSERT OR UPDATE ON "user" FOR EACH ROW EXECUTE FUNCTION tsvector_update_trigger(user_search_tsv, 'pg_catalog.english', username, name); ``` |

<br>

| **Index** | IDX05 |
|-----------|-------|
| **Relation** | post |
| **Attribute** | content |
| **Type** | GIN |
| **Clustering** | No clustering required |
| **Justification** | Enables full-text search on post content for efficient text-based searches, improving content discovery. |
| **SQL code** | ``` ALTER TABLE post ADD COLUMN post_content_tsv tsvector; UPDATE post SET post_content_tsv = to_tsvector('english', content); CREATE INDEX idx_post_content_search ON post USING GIN (post_content_tsv); CREATE TRIGGER trg_post_content_update BEFORE INSERT OR UPDATE ON post FOR EACH ROW EXECUTE FUNCTION tsvector_update_trigger(post_content_tsv, 'pg_catalog.english', content); ``` |

<br>

| **Index** | IDX06 |
|-----------|-------|
| **Relation** | comment |
| **Attribute** | content |
| **Type** | GIN |
| **Clustering** | No clustering required |
| **Justification** | Supports full-text search on comment content, useful for searching discussions or specific replies. |
| **SQL code** | ``` ALTER TABLE comment ADD COLUMN comment_content_tsv tsvector; UPDATE comment SET comment_content_tsv = to_tsvector('english', content); CREATE INDEX idx_comment_content_search ON comment USING GIN (comment_content_tsv); CREATE TRIGGER trg_comment_content_update BEFORE INSERT OR UPDATE ON comment FOR EACH ROW EXECUTE FUNCTION tsvector_update_trigger(comment_content_tsv, 'pg_catalog.english', content); ``` |

<br>

| **Index** | IDX07 |
|-----------|-------|
| **Relation** | group |
| **Attribute** | name, description |
| **Type** | GIN |
| **Clustering** | No clustering required |
| **Justification** | Supports full-text search on group names and descriptions, making it efficient to search and filter groups based on keywords, enhancing the user experience when looking for relevant groups. |
| **SQL code** | ``` ALTER TABLE "group" ADD COLUMN group_content_tsv tsvector; UPDATE "group" SET group_content_tsv = to_tsvector('english', name SYMBOL_BREAKS_MD' ' SYMBOL_BREAKS_MD description); CREATE INDEX idx_group_content_search ON "group" USING GIN (group_content_tsv); CREATE TRIGGER trg_group_content_update BEFORE INSERT OR UPDATE ON "group" FOR EACH ROW EXECUTE FUNCTION tsvector_update_trigger(group_content_tsv, 'pg_catalog.english', name, description); ``` |

Note: the SYMBOL_BREAKS_MD is actually || , unfortunaly if used on a markdown table, it breaks the line of that particular colummn, as such we put that in order for the table to remain consistent. So whenever the SYMBOL_BREAKS_MD appears, the sql code is actually || .

<br>

### 3. Triggers

| **Trigger** | TRIGGER01 |
|-------------|-----------|
| **Description** | (BR03) If a group owner deletes their account, ownership is transferred to another member. If no member is eligible, the group is deleted. |

<br>

#### SQL code:

```sql
CREATE OR REPLACE FUNCTION trg_transfer_or_delete_group()
RETURNS TRIGGER AS $$
DECLARE
   new_owner_id INT;
BEGIN
   SELECT id INTO new_owner_id FROM "user" WHERE id != OLD.id LIMIT 1;
   IF new_owner_id IS NOT NULL THEN
       UPDATE "group" SET id_owner = new_owner_id WHERE id_owner = OLD.id;
   ELSE
       DELETE FROM "group" WHERE id_owner = OLD.id;
   END IF;

   RETURN OLD;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER before_user_delete
BEFORE DELETE ON "user"
FOR EACH ROW
EXECUTE FUNCTION trg_transfer_or_delete_group();
```

<br>

| **Trigger** | TRIGGER02 |
|-------------|-----------|
| **Description** | A user should not be able to report itself |

#### SQL code:

```sql
   CREATE OR REPLACE FUNCTION trg_no_self_report_func()
RETURNS TRIGGER AS $$
BEGIN
   IF NEW.id_user = (SELECT id_user FROM post WHERE id = NEW.id_reported_post) THEN
       RAISE EXCEPTION 'User cannot report their own post';
   END IF;

   RETURN NEW; 
END;


$$ LANGUAGE plpgsql;

CREATE TRIGGER trg_no_self_report
BEFORE INSERT ON report
FOR EACH ROW
EXECUTE FUNCTION trg_no_self_report_func();
```

<br>

| **Trigger** | TRIGGER03 |
|-------------|-----------|
| **Description** | A user should only be able to like comments or posts once |

#### SQL code:

```sql

CREATE OR REPLACE FUNCTION trg_check_unique_like()
RETURNS TRIGGER AS $$
BEGIN
   IF NEW.id_post IS NOT NULL THEN
       IF EXISTS (SELECT 1 FROM "like" WHERE id_user = NEW.id_user AND id_post = NEW.id_post) THEN
           RAISE EXCEPTION 'User has already liked this post';
       END IF;
   END IF;

   IF NEW.id_comment IS NOT NULL THEN
       IF EXISTS (SELECT 1 FROM "like" WHERE id_user = NEW.id_user AND id_comment = NEW.id_comment) THEN
           RAISE EXCEPTION 'User has already liked this comment';
       END IF;
   END IF;

   RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER before_like_insert
BEFORE INSERT ON "like"
FOR EACH ROW
EXECUTE FUNCTION trg_check_unique_like();
```

<br>

| **Trigger** | TRIGGER04 |
|-------------|-----------|
| **Description** | Delete main notification on subtype notification deletion |

#### SQL code:

```sql

-- Create the trigger function to delete related notifications
CREATE OR REPLACE FUNCTION trg_delete_related_notification()
RETURNS TRIGGER AS $$
BEGIN
   DELETE FROM notification WHERE id = OLD.id_notification;
   RETURN OLD;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER after_user_notification_delete
AFTER DELETE ON user_notification
FOR EACH ROW
EXECUTE FUNCTION trg_delete_related_notification();

CREATE TRIGGER after_comment_notification_delete
AFTER DELETE ON comment_notification
FOR EACH ROW
EXECUTE FUNCTION trg_delete_related_notification();

CREATE TRIGGER after_group_notification_delete
AFTER DELETE ON group_notification
FOR EACH ROW
EXECUTE FUNCTION trg_delete_related_notification();

CREATE TRIGGER after_post_notification_delete
AFTER DELETE ON post_notification
FOR EACH ROW
EXECUTE FUNCTION trg_delete_related_notification();

CREATE TRIGGER after_like_notification_delete
AFTER DELETE ON like_notification
FOR EACH ROW
EXECUTE FUNCTION trg_delete_related_notification();

CREATE TRIGGER after_message_notification_delete
AFTER DELETE ON message_notification
FOR EACH ROW
EXECUTE FUNCTION trg_delete_related_notification();
```
<br>


| **Trigger** | TRIGGER05 |
|-------------|-----------|
| **Description** | A user should utilize a @up.pt email to register (BR06) |

#### SQL code:

```sql

-- Create the trigger function to enforce email domain
CREATE OR REPLACE FUNCTION trg_check_feup_email()
RETURNS TRIGGER AS $$
BEGIN
   IF NEW.email !~* '^[^@]+@up\.pt$' THEN
       RAISE EXCEPTION 'Email must be a FEUP institutional email (@up.pt)';
   END IF;
   RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER before_user_insert_or_update
BEFORE INSERT OR UPDATE ON "user"
FOR EACH ROW
EXECUTE FUNCTION trg_check_feup_email();
```

<br>

| **Trigger** | TRIGGER06 |
|-------------|-----------|
| **Description** | The last login field in the user should be update upon change to status "active" |

#### SQL code:

```sql

CREATE OR REPLACE FUNCTION trg_update_last_login_func()
RETURNS TRIGGER AS $$
BEGIN
    IF NEW.account_status = 'active' THEN
        NEW.last_login := CURRENT_TIMESTAMP; 
    END IF;

    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER trg_update_last_login
BEFORE UPDATE ON "user"
FOR EACH ROW
EXECUTE FUNCTION trg_update_last_login_func();
```
<br>

| **Trigger** | TRIGGER07 |
|-------------|-----------|
| **Description** | Add notification on comments (Since we didnt consider this vital to the database integrity we included it as a trigger instead of a transaction) |

#### SQL code:

```sql

CREATE OR REPLACE FUNCTION trg_comment_on_post_notification_func()
RETURNS TRIGGER AS $$
DECLARE
   post_owner INT;
   notification_id INT;
BEGIN
   SELECT id_user INTO post_owner FROM post WHERE id = NEW.id_post;

   IF post_owner IS NOT NULL AND post_owner <> NEW.id_user THEN
       INSERT INTO notification (id_user, id_related_post, id_related_comment, seen, created_at)
       VALUES (post_owner, NEW.id_post, NEW.id, FALSE, CURRENT_TIMESTAMP)
       RETURNING id INTO notification_id; 

       INSERT INTO comment_notification (id_notification, type)
       VALUES (notification_id, 'commented_on_post'); 
   END IF;

   RETURN NEW; 
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER trg_comment_on_post_notification
AFTER INSERT ON comment
FOR EACH ROW
EXECUTE FUNCTION trg_comment_on_post_notification_func();
```

<br>

### 4. Transactions

| SQL Reference | User Account Deletion |
|---------------|-----------------------|
| Justification | Using the highest isolation level, SERIALIZABLE, ensures that no other transaction can modify or read inconsistent data related to this user during the deletion. |
| Isolation level | Serializable |

It must be reminded that there are already triggers in place to handle the deletion of related data when a user is deleted. Which makes transaction code smaller, since these operations are also performed.

#### SQL code:

```sql
BEGIN;

DELETE FROM post WHERE id_user = <user_id>;
DELETE FROM comment WHERE id_user = <user_id>;
DELETE FROM group_membership WHERE id_user = <user_id>;
DELETE FROM "user" WHERE id = <user_id>; -- Will also trigger group ownership trigger

COMMIT;
```

| SQL Reference | Creation of Post |
|---------------|------------------|
| Justification | Avoids partial deletions and ensures that no related data remains when a user account is removed. |
| Isolation level | Repeatable Read |

#### SQL code:

```sql
BEGIN;

INSERT INTO post (content, visibility, id_user, id_group)
VALUES (:content, :visibility, :user_id, :group_id)
RETURNING id INTO :post_id;

COMMIT;
```
<br>

| SQL Reference | Send Friend Request |
|---------------|---------------------|
| Justification | This operation can proceed without interfering with other operations. We want to ensure that the data being read (existing user friendships) is committed and visible, avoiding potential conflicts from concurrent inserts or updates. |
| Isolation level | Read Commited |

#### SQL code:

```sql

BEGIN;

INSERT INTO friendship_connection (id_user1, id_user2, status)
VALUES (:user_id1, :user_id2, 'pending')
RETURNING id INTO :connection_id;

INSERT INTO notification (id_user, seen, created_at)
VALUES (:user_id2, FALSE, CURRENT_TIMESTAMP)
RETURNING id INTO :notification_id;

INSERT INTO user_notification (id_notification, type)
VALUES (:notification_id, 'received_friend_request');

COMMIT;
```

<br>

| SQL Reference | Accept Friend Request |
|---------------|-----------------------|
| Justification | Accepting a friend request involves updating the status of a friendship connection. Using Serializable ensures that no other transactions can modify the friendship status concurrently, which could lead to inconsistencies, such as accepting a request that has already been canceled. It guarantees complete isolation from other transactions. |
| Isolation level | Serializable |

#### SQL code:

```sql

BEGIN;

UPDATE friendship_connection
SET status = 'accepted'
WHERE id_user1 = :user_id2 AND id_user2 = :user_id1 AND status = 'pending';

INSERT INTO notification (id_user, seen, created_at)
VALUES (:user_id1, FALSE, CURRENT_TIMESTAMP)
RETURNING id INTO :notification_id;

INSERT INTO user_notification (id_notification, type)
VALUES (:notification_id, 'accepted_friend_request');

COMMIT;
```
<br>

| SQL Reference | Removing friendship |
|---------------|---------------------|
| Justification | Similar to accepting a friendship, removing a friendship can affect multiple records (both ways in the friendship connection). Using Serializable prevents race conditions and ensures that no other transaction can modify the friendship connections between the two users during this operation, maintaining integrity. |
| Isolation level | Serializable |

#### SQL code:

```sql

BEGIN;

DELETE FROM friendship_connection
WHERE (id_user1 = :user_id1 AND id_user2 = :user_id2) OR
     (id_user1 = :user_id2 AND id_user2 = :user_id1);


INSERT INTO notification (id_user, seen, created_at)
VALUES (:other_user_id, FALSE, CURRENT_TIMESTAMP)
RETURNING id INTO :notification_id;

INSERT INTO user_notification (id_notification, type)
VALUES (:notification_id, 'removed_friend');

COMMIT;
```

<br>

| SQL Reference | Deleting a post |
|---------------|-----------------|
| Justification | Deleting a post and its associated likes and comments involves multiple deletions across different tables, and there is a risk of concurrent transactions modifying the same data. Using the Serializable isolation level prevents anomalies such as phantom reads and ensures that no other transaction can insert or modify likes or comments related to the post during this operation. This guarantees that the entire operation executes in isolation, maintaining data integrity. |
| Isolation level | Serializable |

#### SQL code:

```sql

BEGIN;

DELETE FROM "like"
WHERE id_post = :post_id;

DELETE FROM comment
WHERE id_post = :post_id;

DELETE FROM post
WHERE id = :post_id;

COMMIT;
```
<br>

## Annex A. SQL Code

<br>

### A.1. Database schema

```
-- Set search path to the ebd schema
SET search_path TO ebd;

-- Drop tables that depend on the types
DROP TABLE IF EXISTS "user" CASCADE;
DROP TABLE IF EXISTS friendship_connection CASCADE;
DROP TABLE IF EXISTS "group" CASCADE;
DROP TABLE IF EXISTS group_membership CASCADE;
DROP TABLE IF EXISTS post CASCADE;
DROP TABLE IF EXISTS comment CASCADE;
DROP TABLE IF EXISTS "like" CASCADE;
DROP TABLE IF EXISTS report CASCADE;
DROP TABLE IF EXISTS notification CASCADE;
DROP TABLE IF EXISTS user_notification CASCADE;
DROP TABLE IF EXISTS comment_notification CASCADE;
DROP TABLE IF EXISTS group_notification CASCADE;
DROP TABLE IF EXISTS post_notification CASCADE;
DROP TABLE IF EXISTS like_notification CASCADE;
DROP TABLE IF EXISTS message_notification CASCADE;
DROP TABLE IF EXISTS admin CASCADE;
DROP TABLE IF EXISTS message CASCADE;

-- Drop existing types if they exist
DROP TYPE IF EXISTS visibility_type CASCADE;
DROP TYPE IF EXISTS account_status_type CASCADE;
DROP TYPE IF EXISTS friendship_status_type CASCADE;
DROP TYPE IF EXISTS report_status_type CASCADE;
DROP TYPE IF EXISTS user_notification_type CASCADE;
DROP TYPE IF EXISTS comment_notification_type CASCADE;
DROP TYPE IF EXISTS group_notification_type CASCADE;
DROP TYPE IF EXISTS post_notification_type CASCADE;
DROP TYPE IF EXISTS like_notification_type CASCADE;
DROP TYPE IF EXISTS message_notification_type CASCADE;
DROP TYPE IF EXISTS group_membership_type CASCADE;

-- Create types

CREATE TYPE group_membership_type AS ENUM ('pending', 'accepted', 'rejected');
CREATE TYPE visibility_type AS ENUM ('public', 'private', 'group', 'anonymous');
CREATE TYPE account_status_type AS ENUM ('active', 'offline', 'deleted', 'blocked');
CREATE TYPE friendship_status_type AS ENUM ('pending', 'accepted', 'rejected');
CREATE TYPE report_status_type AS ENUM ('pending', 'reviewed', 'resolved');
CREATE TYPE user_notification_type AS ENUM ('received_friend_request', 'accepted_friend_request', 'match');
CREATE TYPE comment_notification_type AS ENUM ('replied_to_comment', 'commented_on_post', 'tagged_comment');
CREATE TYPE group_notification_type AS ENUM ('invited_to_group');
CREATE TYPE post_notification_type AS ENUM ('tagged_post');
CREATE TYPE like_notification_type AS ENUM ('liked_comment', 'liked_post');
CREATE TYPE message_notification_type AS ENUM ('received_message');

-- Create tables
CREATE TABLE "user" (
    id SERIAL PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    name VARCHAR(100) NOT NULL,
    password VARCHAR(255) NOT NULL,
    visibility visibility_type CHECK (visibility IN ('public', 'private')) NOT NULL,
    account_status account_status_type NOT NULL,
    last_login DATE DEFAULT CURRENT_DATE
);

CREATE TABLE friendship_connection (
    id_user1 INT REFERENCES "user"(id) ON DELETE CASCADE,
    id_user2 INT REFERENCES "user"(id) ON DELETE CASCADE,
    status friendship_status_type NOT NULL,
    created_at DATE DEFAULT CURRENT_DATE,
    PRIMARY KEY (id_user1, id_user2)
);

CREATE TABLE "group" (
    id SERIAL PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    visibility visibility_type CHECK (visibility IN ('public', 'private')) NOT NULL,
    created_at DATE DEFAULT CURRENT_DATE,
    id_owner INT REFERENCES "user"(id) NOT NULL
);

CREATE TABLE group_membership (
    id_user INT REFERENCES "user"(id) ON DELETE CASCADE,
    id_group INT REFERENCES "group"(id) ON DELETE CASCADE,
    joined_at DATE DEFAULT CURRENT_DATE,
    PRIMARY KEY (id_user, id_group),
    group_status group_membership_type NOT NULL DEFAULT 'pending'
);

CREATE TABLE post (
    id SERIAL PRIMARY KEY,
    content TEXT NOT NULL,
    edited_at DATE DEFAULT CURRENT_DATE,
    visibility visibility_type NOT NULL DEFAULT 'public', 
    id_user INT REFERENCES "user"(id),
    id_group INT REFERENCES "group"(id)
);

CREATE TABLE comment (
    id SERIAL PRIMARY KEY,
    content TEXT NOT NULL,
    edited_at DATE DEFAULT CURRENT_DATE,
    deleted_at DATE CHECK (deleted_at >= CURRENT_DATE),
    visibility visibility_type NOT NULL DEFAULT 'public',
    id_user INT REFERENCES "user"(id),
    id_post INT REFERENCES post(id) NOT NULL
);

CREATE TABLE "like" (
    id SERIAL PRIMARY KEY,
    created_at DATE DEFAULT CURRENT_DATE,
    id_user INT REFERENCES "user"(id),
    id_post INT REFERENCES post(id),
	id_comment INT REFERENCES comment(id),
    visibility visibility_type NOT NULL DEFAULT 'public'
);

CREATE TABLE report (
    id SERIAL PRIMARY KEY,
    description TEXT NOT NULL,
    status report_status_type NOT NULL DEFAULT 'pending',
    id_user INT REFERENCES "user"(id) NOT NULL,
    id_reported_post INT REFERENCES post(id),
    id_reported_comment INT REFERENCES comment(id)
);

CREATE TABLE notification (
    id SERIAL PRIMARY KEY,
    seen BOOLEAN DEFAULT FALSE,
    created_at DATE DEFAULT CURRENT_DATE,
    id_user INT REFERENCES "user"(id) NOT NULL,
    id_related_post INT REFERENCES post(id),
    id_related_comment INT REFERENCES comment(id),
    id_related_group INT REFERENCES "group"(id)
);

CREATE TABLE message (
    id SERIAL PRIMARY KEY,
    content TEXT NOT NULL,
    created_at DATE DEFAULT CURRENT_DATE,
    id_sender INT REFERENCES "user"(id),
    id_receiver INT REFERENCES "user"(id),
    visibility visibility_type NOT NULL DEFAULT 'public'
);


CREATE TABLE user_notification (
    id_notification INT REFERENCES notification(id) ON UPDATE CASCADE NOT NULL,
    type user_notification_type NOT NULL,
    PRIMARY KEY (id_notification)
);

CREATE TABLE comment_notification (
    id_notification INT REFERENCES notification(id) ON UPDATE CASCADE NOT NULL,
    type comment_notification_type NOT NULL,
    PRIMARY KEY (id_notification)
);

CREATE TABLE group_notification (
    id_notification INT REFERENCES notification(id) ON UPDATE CASCADE NOT NULL,
    type group_notification_type NOT NULL,
    PRIMARY KEY (id_notification)
);

CREATE TABLE post_notification (
    id_notification INT REFERENCES notification(id) ON UPDATE CASCADE NOT NULL,
    type post_notification_type NOT NULL,
    PRIMARY KEY (id_notification)
);

CREATE TABLE like_notification (
    id_notification INT REFERENCES notification(id) ON UPDATE CASCADE NOT NULL,
    type like_notification_type NOT NULL,
    PRIMARY KEY (id_notification)
);

CREATE TABLE message_notification (
    id_notification INT REFERENCES notification(id) ON UPDATE CASCADE NOT NULL,
    type message_notification_type NOT NULL,
    PRIMARY KEY (id_notification)
);

CREATE TABLE admin (
    id INT REFERENCES "user"(id) PRIMARY KEY
);


----------------------------------
-- Indexes
----------------------------------

CREATE INDEX idx_user_username_email ON "user" (username, email);

----------------------------------

CREATE INDEX idx_friendship_user1_user2_status ON friendship_connection (id_user1, id_user2, status);

----------------------------------

CREATE INDEX idx_notification_user_seen_created_at ON notification (id_user, seen, created_at);

----------------------------------

ALTER TABLE "user" 
ADD COLUMN user_search_tsv tsvector;

UPDATE "user" 
SET user_search_tsv = to_tsvector('english', username || ' ' || name);

CREATE INDEX idx_user_search 
ON "user" USING GIN (user_search_tsv);

CREATE TRIGGER trg_user_search_update 
BEFORE INSERT OR UPDATE ON "user" 
FOR EACH ROW EXECUTE FUNCTION 
tsvector_update_trigger(user_search_tsv, 'pg_catalog.english', username, name);

----------------------------------

ALTER TABLE post 
ADD COLUMN post_content_tsv tsvector;

UPDATE post 
SET post_content_tsv = to_tsvector('english', content);

CREATE INDEX idx_post_content_search 
ON post USING GIN (post_content_tsv);

CREATE TRIGGER trg_post_content_update 
BEFORE INSERT OR UPDATE ON post 
FOR EACH ROW EXECUTE FUNCTION 
tsvector_update_trigger(post_content_tsv, 'pg_catalog.english', content);

----------------------------------

ALTER TABLE comment 
ADD COLUMN comment_content_tsv tsvector;

UPDATE comment 
SET comment_content_tsv = to_tsvector('english', content);

CREATE INDEX idx_comment_content_search 
ON comment USING GIN (comment_content_tsv);

CREATE TRIGGER trg_comment_content_update 
BEFORE INSERT OR UPDATE ON comment 
FOR EACH ROW EXECUTE FUNCTION 
tsvector_update_trigger(comment_content_tsv, 'pg_catalog.english', content);

----------------------------------

ALTER TABLE "group" 
ADD COLUMN group_content_tsv tsvector;


UPDATE "group" 
SET group_content_tsv = to_tsvector('english', name || ' ' || description);


CREATE INDEX idx_group_content_search 
ON "group" USING GIN (group_content_tsv);


CREATE TRIGGER trg_group_content_update 
BEFORE INSERT OR UPDATE ON "group" 
FOR EACH ROW EXECUTE FUNCTION 
tsvector_update_trigger(group_content_tsv, 'pg_catalog.english', name, description);


----------------------------------
-- Triggers
----------------------------------

CREATE OR REPLACE FUNCTION trg_no_self_report_func()
RETURNS TRIGGER AS $$
BEGIN
    IF NEW.id_user = (SELECT id_user FROM post WHERE id = NEW.id_reported_post) THEN
        RAISE EXCEPTION 'User cannot report their own post';
    END IF;

    RETURN NEW; 
END;


$$ LANGUAGE plpgsql;

CREATE TRIGGER trg_no_self_report
BEFORE INSERT ON report
FOR EACH ROW
EXECUTE FUNCTION trg_no_self_report_func();

-----------------------

CREATE OR REPLACE FUNCTION trg_update_last_login_func()
RETURNS TRIGGER AS $$
BEGIN
    IF NEW.account_status = 'active' THEN
        NEW.last_login := CURRENT_TIMESTAMP; 
    END IF;

    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER trg_update_last_login
BEFORE UPDATE ON "user"
FOR EACH ROW
EXECUTE FUNCTION trg_update_last_login_func();

-----------------------


CREATE OR REPLACE FUNCTION trg_comment_on_post_notification_func()
RETURNS TRIGGER AS $$
DECLARE
    post_owner INT;
    notification_id INT;
BEGIN
    SELECT id_user INTO post_owner FROM post WHERE id = NEW.id_post;

    IF post_owner IS NOT NULL AND post_owner <> NEW.id_user THEN
        INSERT INTO notification (id_user, id_related_post, id_related_comment, seen, created_at)
        VALUES (post_owner, NEW.id_post, NEW.id, FALSE, CURRENT_TIMESTAMP)
        RETURNING id INTO notification_id; 

        INSERT INTO comment_notification (id_notification, type)
        VALUES (notification_id, 'commented_on_post'); 
    END IF;

    RETURN NEW; 
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER trg_comment_on_post_notification
AFTER INSERT ON comment
FOR EACH ROW
EXECUTE FUNCTION trg_comment_on_post_notification_func();


----------------------

-- Create the trigger function to transfer group ownership or delete the group
CREATE OR REPLACE FUNCTION trg_transfer_or_delete_group()
RETURNS TRIGGER AS $$
DECLARE
    new_owner_id INT;
BEGIN
    SELECT id INTO new_owner_id FROM "user" WHERE id != OLD.id LIMIT 1;
    IF new_owner_id IS NOT NULL THEN
        UPDATE "group" SET id_owner = new_owner_id WHERE id_owner = OLD.id;
    ELSE
        DELETE FROM "group" WHERE id_owner = OLD.id;
    END IF;

    RETURN OLD;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER before_user_delete
BEFORE DELETE ON "user"
FOR EACH ROW
EXECUTE FUNCTION trg_transfer_or_delete_group();

--------

-- Create the trigger function to enforce email domain
CREATE OR REPLACE FUNCTION trg_check_feup_email()
RETURNS TRIGGER AS $$
BEGIN
    IF NEW.email !~* '^[^@]+@up\.pt$' THEN
        RAISE EXCEPTION 'Email must be a FEUP institutional email (@up.pt)';
    END IF;
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER before_user_insert_or_update
BEFORE INSERT OR UPDATE ON "user"
FOR EACH ROW
EXECUTE FUNCTION trg_check_feup_email();


------------

-- Create the trigger function to enforce unique likes
CREATE OR REPLACE FUNCTION trg_check_unique_like()
RETURNS TRIGGER AS $$
BEGIN
    IF NEW.id_post IS NOT NULL THEN
        IF EXISTS (SELECT 1 FROM "like" WHERE id_user = NEW.id_user AND id_post = NEW.id_post) THEN
            RAISE EXCEPTION 'User has already liked this post';
        END IF;
    END IF;

    IF NEW.id_comment IS NOT NULL THEN
        IF EXISTS (SELECT 1 FROM "like" WHERE id_user = NEW.id_user AND id_comment = NEW.id_comment) THEN
            RAISE EXCEPTION 'User has already liked this comment';
        END IF;
    END IF;

    RETURN NEW;
END;
$$ LANGUAGE plpgsql;


CREATE TRIGGER before_like_insert
BEFORE INSERT ON "like"
FOR EACH ROW
EXECUTE FUNCTION trg_check_unique_like();

--------------------------------

-- Create the trigger function to delete related notifications
CREATE OR REPLACE FUNCTION trg_delete_related_notification()
RETURNS TRIGGER AS $$
BEGIN
    DELETE FROM notification WHERE id = OLD.id_notification;
    RETURN OLD;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER after_user_notification_delete
AFTER DELETE ON user_notification
FOR EACH ROW
EXECUTE FUNCTION trg_delete_related_notification();

CREATE TRIGGER after_comment_notification_delete
AFTER DELETE ON comment_notification
FOR EACH ROW
EXECUTE FUNCTION trg_delete_related_notification();

CREATE TRIGGER after_group_notification_delete
AFTER DELETE ON group_notification
FOR EACH ROW
EXECUTE FUNCTION trg_delete_related_notification();

CREATE TRIGGER after_post_notification_delete
AFTER DELETE ON post_notification
FOR EACH ROW
EXECUTE FUNCTION trg_delete_related_notification();

CREATE TRIGGER after_like_notification_delete
AFTER DELETE ON like_notification
FOR EACH ROW
EXECUTE FUNCTION trg_delete_related_notification();

CREATE TRIGGER after_message_notification_delete
AFTER DELETE ON message_notification
FOR EACH ROW
EXECUTE FUNCTION trg_delete_related_notification();

-----------------------------------------------

```

<br>


### A.2. Database population

```
SET search_path TO ebd;

-- Truncate tables
TRUNCATE "like" CASCADE;
TRUNCATE comment CASCADE;
TRUNCATE post CASCADE;
TRUNCATE group_membership CASCADE;
TRUNCATE "group" CASCADE;
TRUNCATE friendship_connection CASCADE;
TRUNCATE report CASCADE;
TRUNCATE notification CASCADE;
TRUNCATE user_notification CASCADE;
TRUNCATE comment_notification CASCADE;
TRUNCATE group_notification CASCADE;
TRUNCATE post_notification CASCADE;
TRUNCATE like_notification CASCADE;
TRUNCATE message_notification CASCADE;
TRUNCATE message CASCADE;
TRUNCATE admin CASCADE;
TRUNCATE "user" CASCADE;

-- Reset sequences
ALTER SEQUENCE user_id_seq RESTART WITH 1;
ALTER SEQUENCE group_id_seq RESTART WITH 1;
ALTER SEQUENCE post_id_seq RESTART WITH 1;
ALTER SEQUENCE comment_id_seq RESTART WITH 1;
ALTER SEQUENCE like_id_seq RESTART WITH 1;
ALTER SEQUENCE report_id_seq RESTART WITH 1;
ALTER SEQUENCE notification_id_seq RESTART WITH 1;
ALTER SEQUENCE message_id_seq RESTART WITH 1;

-- Insert users
INSERT INTO "user" (username, email, name, password, visibility, account_status)
VALUES 
('john_doe', 'john.doe@up.pt', 'John Doe', 'hashed_password1', 'public', 'active'),
('jane_smith', 'jane.smith@up.pt', 'Jane Smith', 'hashed_password2', 'private', 'active'),
('alice_jones', 'alice.jones@up.pt', 'Alice Jones', 'hashed_password3', 'public', 'offline'),
('bob_brown', 'bob.brown@up.pt', 'Bob Brown', 'hashed_password4', 'public', 'active'),
('carol_white', 'carol.white@up.pt', 'Carol White', 'hashed_password5', 'private', 'active'),
('dave_black', 'dave.black@up.pt', 'Dave Black', 'hashed_password6', 'public', 'active'),
('eve_green', 'eve.green@up.pt', 'Eve Green', 'hashed_password7', 'private', 'offline'),
('frank_blue', 'frank.blue@up.pt', 'Frank Blue', 'hashed_password8', 'public', 'deleted'),
('grace_yellow', 'grace.yellow@up.pt', 'Grace Yellow', 'hashed_password9', 'public', 'active'),
('hank_red', 'hank.red@up.pt', 'Hank Red', 'hashed_password10', 'private', 'blocked');

-- removed for brevity

----------------------
-- end
----------------------
```

<br>

---

## Revision history

Changes made to the first submission:
1. Item 1
1. ...


<br>

GROUP24116, 01/10/2024

* Alexandre Chouzende - 202207429@up.pt
* Rui Borges - 202207475@up.pt
* Pedro Paixão- 202008467@up.pt
* Tiago Torrado - 202206399@up.pt