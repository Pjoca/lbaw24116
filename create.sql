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

-- Create types
CREATE TYPE visibility_type AS ENUM ('public', 'private', 'group');
CREATE TYPE account_status_type AS ENUM ('active', 'deleted', 'blocked');
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
    visibility visibility_type NOT NULL,
    account_status account_status_type NOT NULL,
    last_login DATE DEFAULT CURRENT_DATE
);

CREATE TABLE friendship_connection (
    id_user1 INT REFERENCES "user"(id) ON DELETE CASCADE,
    id_user2 INT REFERENCES "user"(id) ON DELETE CASCADE,
    status friendship_status_type NOT NULL,
    created_at DATE DEFAULT CURRENT_DATE,
    visibility visibility_type NOT NULL,
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
    status visibility_type NOT NULL,
    PRIMARY KEY (id_user, id_group)
);

CREATE TABLE post (
    id SERIAL PRIMARY KEY,
    content TEXT NOT NULL,
    edited_at DATE DEFAULT CURRENT_DATE,
    visibility visibility_type NOT NULL,
    id_user INT REFERENCES "user"(id) NOT NULL,
    id_group INT REFERENCES "group"(id)
);

CREATE TABLE comment (
    id SERIAL PRIMARY KEY,
    content TEXT NOT NULL,
    edited_at DATE DEFAULT CURRENT_DATE,
    deleted_at DATE CHECK (deleted_at >= CURRENT_DATE),
    id_user INT REFERENCES "user"(id) NOT NULL,
    id_post INT REFERENCES post(id) NOT NULL
);

CREATE TABLE "like" (
    id SERIAL PRIMARY KEY,
    created_at DATE DEFAULT CURRENT_DATE,
    id_user INT REFERENCES "user"(id) NOT NULL,
    id_post INT REFERENCES post(id)
);

CREATE TABLE report (
    id SERIAL PRIMARY KEY,
    description TEXT NOT NULL,
    status report_status_type NOT NULL,
    id_user INT REFERENCES "user"(id) NOT NULL,
    id_reported_post INT REFERENCES post(id),
    id_reported_comment INT REFERENCES comment(id)
);

CREATE TABLE notification (
    id SERIAL PRIMARY KEY,
    seen BOOLEAN DEFAULT FALSE,
    id_user INT REFERENCES "user"(id) NOT NULL,
    id_related_post INT REFERENCES post(id),
    id_related_comment INT REFERENCES comment(id),
    id_related_group INT REFERENCES "group"(id)
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
