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
    id_notification INT REFERENCES notification(id) NOT NULL,
    type user_notification_type NOT NULL,
    PRIMARY KEY (id_notification)
);

CREATE TABLE comment_notification (
    id_notification INT REFERENCES notification(id) NOT NULL,
    type comment_notification_type NOT NULL,
    PRIMARY KEY (id_notification)
);

CREATE TABLE group_notification (
    id_notification INT REFERENCES notification(id) NOT NULL,
    type group_notification_type NOT NULL,
    PRIMARY KEY (id_notification)
);

CREATE TABLE post_notification (
    id_notification INT REFERENCES notification(id) NOT NULL,
    type post_notification_type NOT NULL,
    PRIMARY KEY (id_notification)
);

CREATE TABLE like_notification (
    id_notification INT REFERENCES notification(id) NOT NULL,
    type like_notification_type NOT NULL,
    PRIMARY KEY (id_notification)
);

CREATE TABLE message_notification (
    id_notification INT REFERENCES notification(id) NOT NULL,
    type message_notification_type NOT NULL,
    PRIMARY KEY (id_notification)
);

CREATE TABLE admin (
    id INT REFERENCES "user"(id) PRIMARY KEY
);



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
-- TEST TRIGGERS

-- Create a user
INSERT INTO "user" (username, email, name, password, visibility, account_status)
VALUES ('student1', 'student1@up.pt', 'Student One', 'password', 'public', 'active');
