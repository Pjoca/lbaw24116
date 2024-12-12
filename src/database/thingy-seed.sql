DROP SCHEMA IF EXISTS lbaw24116 CASCADE;
CREATE SCHEMA lbaw24116;
SET search_path TO lbaw24116;


-- Drop tables that depend on the types
DROP TABLE IF EXISTS "user" CASCADE;
DROP TABLE IF EXISTS friendship CASCADE;
DROP TABLE IF EXISTS friendship_request CASCADE;
DROP TABLE IF EXISTS "group" CASCADE;
DROP TABLE IF EXISTS group_membership CASCADE;
DROP TABLE IF EXISTS post CASCADE;
DROP TABLE IF EXISTS comment CASCADE;
DROP TABLE IF EXISTS "like" CASCADE;
DROP TABLE IF EXISTS report CASCADE;
DROP TABLE IF EXISTS notification CASCADE;
DROP TABLE IF EXISTS admin CASCADE;
DROP TABLE IF EXISTS message CASCADE;

-- Drop existing types if they exist
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
    image_url TEXT,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    name VARCHAR(100) NOT NULL,
    password VARCHAR(255) NOT NULL,
    visibility visibility_type CHECK (visibility IN ('public', 'private')) NOT NULL,
    account_status account_status_type NOT NULL,
    last_login DATE DEFAULT CURRENT_DATE
);

CREATE TABLE friendship (
    id SERIAL PRIMARY KEY,
    id_user1 INT REFERENCES "user"(id) ON DELETE CASCADE,
    id_user2 INT REFERENCES "user"(id) ON DELETE CASCADE,
    created_at DATE DEFAULT CURRENT_DATE
);

-- Friendship request table for pending or rejected requests
CREATE TABLE friendship_request (
    id SERIAL PRIMARY KEY,
    id_sender INT REFERENCES "user"(id) ON DELETE CASCADE,
    id_receiver INT REFERENCES "user"(id) ON DELETE CASCADE,
    status friendship_status_type NOT NULL,
    created_at DATE DEFAULT CURRENT_DATE
);

CREATE TABLE "group" (
    id SERIAL PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    image_url TEXT,
    description TEXT,
    visibility visibility_type CHECK (visibility IN ('public', 'private')) NOT NULL,
    created_at DATE DEFAULT CURRENT_DATE,
    id_owner INT REFERENCES "user"(id) NOT NULL
);

CREATE TABLE group_membership (
    id_user INT REFERENCES "user"(id) ON DELETE CASCADE,
    id_group INT REFERENCES "group"(id) ON DELETE CASCADE,
    joined_at DATE DEFAULT CURRENT_DATE,
    PRIMARY KEY (id_user, id_group)
);

CREATE TABLE group_membership_request (
    id SERIAL PRIMARY KEY,
    id_user INT REFERENCES "user"(id) ON DELETE CASCADE,
    id_group INT REFERENCES "group"(id) ON DELETE CASCADE,
    request_status group_membership_type NOT NULL DEFAULT 'pending', -- 'pending', 'accepted', 'rejected'
    requested_at DATE DEFAULT CURRENT_DATE,
    is_invite BOOLEAN DEFAULT FALSE -- Distinguishes invites from user requests
);


CREATE TABLE post (
    id SERIAL PRIMARY KEY,
    image_url TEXT,
    content TEXT NOT NULL,
    created_at DATE DEFAULT CURRENT_DATE,
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
    id_post INT NOT NULL REFERENCES post(id) ON DELETE CASCADE
);

CREATE TABLE "like" (
    id SERIAL PRIMARY KEY,
    created_at DATE DEFAULT CURRENT_DATE,
    id_user INT REFERENCES "user"(id),
    id_post INT REFERENCES post(id) ON DELETE CASCADE,
	id_comment INT REFERENCES comment(id) ON DELETE CASCADE,
    visibility visibility_type NOT NULL DEFAULT 'public'
);

CREATE TABLE report (
    id SERIAL PRIMARY KEY,
    description TEXT NOT NULL,
    status report_status_type NOT NULL DEFAULT 'pending',
    id_user INT REFERENCES "user"(id) NOT NULL,
    id_reported_post INT REFERENCES post(id) ON DELETE CASCADE,
    id_reported_comment INT REFERENCES comment(id) ON DELETE CASCADE
);

CREATE TABLE notification (
    id SERIAL PRIMARY KEY,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    id_user INT REFERENCES "user"(id) NOT NULL,
    message_content TEXT,
    onclick_url TEXT
);

CREATE TABLE message (
    id SERIAL PRIMARY KEY,
    content TEXT NOT NULL,
    created_at DATE DEFAULT CURRENT_DATE,
    id_sender INT REFERENCES "user"(id),
    id_receiver INT REFERENCES "user"(id),
    visibility visibility_type NOT NULL DEFAULT 'public'
);

CREATE TABLE admin (
    id INT REFERENCES "user"(id) PRIMARY KEY
);


----------------------------------
-- Indexes
----------------------------------

CREATE INDEX idx_user_username_email ON "user" (username, email);

----------------------------------

CREATE INDEX idx_friendship_users ON friendship (id_user1, id_user2);

----------------------------------

ALTER TABLE "user" 
ADD COLUMN user_search_tsv tsvector;

UPDATE "user" 
SET user_search_tsv = to_tsvector('english', username || ' ' || name);

CREATE INDEX idx_user_search 
ON "user" USING GIN (user_search_tsv);

DO $$
BEGIN
    IF NOT EXISTS (
        SELECT 1
        FROM pg_trigger
        WHERE tgname = 'trg_user_search_update'
    ) THEN
        CREATE TRIGGER trg_user_search_update 
        BEFORE INSERT OR UPDATE ON "user" 
        FOR EACH ROW EXECUTE FUNCTION 
        tsvector_update_trigger(user_search_tsv, 'pg_catalog.english', username, name);
    END IF;
END $$;

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

CREATE OR REPLACE FUNCTION add_owner_to_membership()
RETURNS TRIGGER AS $$
BEGIN
    -- Insert the owner into the group_membership table
    INSERT INTO group_membership (id_user, id_group, joined_at)
    VALUES (NEW.id_owner, NEW.id, CURRENT_DATE);
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;


CREATE TRIGGER after_group_insert
AFTER INSERT ON "group"
FOR EACH ROW
EXECUTE FUNCTION add_owner_to_membership();

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

CREATE OR REPLACE FUNCTION trg_check_unique_friendship_request()
RETURNS TRIGGER AS $$
BEGIN
    -- Check if there's already a friendship
    IF EXISTS (
        SELECT 1 FROM friendship
        WHERE (id_user1 = NEW.id_sender AND id_user2 = NEW.id_receiver)
           OR (id_user1 = NEW.id_receiver AND id_user2 = NEW.id_sender)
    ) THEN
        RAISE EXCEPTION 'Friendship already exists between these users';
    END IF;

    -- Check if a similar request exists
    IF EXISTS (
        SELECT 1 FROM friendship_request
        WHERE (id_sender = NEW.id_sender AND id_receiver = NEW.id_receiver)
           OR (id_sender = NEW.id_receiver AND id_receiver = NEW.id_sender)
    ) THEN
        RAISE EXCEPTION 'Friendship request already exists between these users';
    END IF;

    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER trg_unique_friendship_request
BEFORE INSERT ON friendship_request
FOR EACH ROW
EXECUTE FUNCTION trg_check_unique_friendship_request();


------------

CREATE OR REPLACE FUNCTION trg_check_unique_group_request()
RETURNS TRIGGER AS $$
BEGIN
    IF EXISTS (
        SELECT 1 
        FROM group_membership_request 
        WHERE id_user = NEW.id_user AND id_group = NEW.id_group
    ) THEN
        RAISE EXCEPTION 'Group membership request already exists for this user and group';
    END IF;

    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER trg_unique_group_request
BEFORE INSERT ON group_membership_request
FOR EACH ROW
EXECUTE FUNCTION trg_check_unique_group_request();

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

-----------------------------------------------

-- Truncate tables
TRUNCATE "like" CASCADE;
TRUNCATE comment CASCADE;
TRUNCATE post CASCADE;
TRUNCATE group_membership CASCADE;
TRUNCATE "group" CASCADE;
TRUNCATE friendship CASCADE;
TRUNCATE friendship_request CASCADE;
TRUNCATE report CASCADE;
TRUNCATE notification CASCADE;
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

-- Insert users with the default image
INSERT INTO "user" (username, email, name, password, visibility, account_status, image_url)
VALUES 
('john_doe', 'john.doe@up.pt', 'John Doe', '$2a$12$MhT/6twPk.2Hb63b.VXDZe1ceusL6iUyAWQ9Nq4978NWlLNz3zOf2', 'public', 'active', 'users/images/default.png'),
('jane_smith', 'jane.smith@up.pt', 'Jane Smith', '$2a$12$MhT/6twPk.2Hb63b.VXDZe1ceusL6iUyAWQ9Nq4978NWlLNz3zOf2', 'private', 'active', 'users/images/default.png'),
('alice_jones', 'alice.jones@up.pt', 'Alice Jones', 'hashed_password3', 'public', 'offline', 'users/images/default.png'),
('bob_brown', 'bob.brown@up.pt', 'Bob Brown', 'hashed_password4', 'public', 'active', 'users/images/default.png'),
('carol_white', 'carol.white@up.pt', 'Carol White', 'hashed_password5', 'private', 'active', 'users/images/default.png'),
('dave_black', 'dave.black@up.pt', 'Dave Black', 'hashed_password6', 'public', 'active', 'users/images/default.png'),
('eve_green', 'eve.green@up.pt', 'Eve Green', 'hashed_password7', 'private', 'offline', 'users/images/default.png'),
('frank_blue', 'frank.blue@up.pt', 'Frank Blue', 'hashed_password8', 'public', 'deleted', 'users/images/default.png'),
('grace_yellow', 'grace.yellow@up.pt', 'Grace Yellow', 'hashed_password9', 'public', 'active', 'users/images/default.png'),
('hank_red', 'hank.red@up.pt', 'Hank Red', 'hashed_password10', 'private', 'blocked', 'users/images/default.png'),
('maria_purple', 'maria.p@up.pt', 'Maria Purple', 'hashed_password11', 'public', 'active', 'users/images/default.png'),
('joao_orange', 'joao.o@up.pt', 'Joao Orange', 'hashed_password12', 'public', 'active', 'users/images/default.png'),
('ana_brown', 'ana.brown@up.pt', 'Ana Brown', 'hashed_password13', 'public', 'active', 'users/images/default.png'),
('francisco_madureira', 'macaco.super_dragoes@up.pt', 'Francisco Madureira', 'hashed_password14', 'public', 'active', 'users/images/default.png'),
('marcelo_rebelo_sousa', 'mrs@up.pt', 'Marcelo Rebelo Sousa', 'hashed_password15', 'public', 'active', 'users/images/default.png'),
('antonio_costa', 'ac@up.pt', 'Antonio Costa', 'hashed_password16', 'public', 'active', 'users/images/default.png');

-- Insert groups
INSERT INTO "group" (name, description, visibility, id_owner)
VALUES 
('Hiking Enthusiasts', 'A group for people who love hiking.', 'private', 1),
('Book Club', 'Discussing our favorite books.', 'private', 2),
('Music Lovers', 'Sharing music recommendations.', 'public', 3),
('Movie Buffs', 'Discussing favorite movies.', 'public', 4),
('Photography Club', 'Sharing photography tips.', 'private', 5),
('Cooking Club', 'Sharing recipes and cooking tips.', 'public', 6),
('Travel Buddies', 'Planning trips together.', 'private', 7),
('Tech Geeks', 'Discussing latest in tech.', 'public', 8),
('Gaming Squad', 'Playing and discussing games.', 'private', 9),
('Art Enthusiasts', 'Discussing art and exhibitions.', 'public', 10),
('Super Dragões', 'Grupo de apoio ao FC Porto', 'public', 14),
('Politica', 'Grupo de discussão política', 'public', 15),
('Benfica', 'Grupo de apoio ao Benfica', 'public', 14),
('Sporting', 'Grupo de apoio ao Sporting', 'public', 14),
('Portimonense', 'Grupo de apoio ao Portimonense', 'public', 14),
('Boavista', 'Grupo de apoio ao Boavista', 'public', 14),
('Braga', 'Grupo de apoio ao Braga', 'public', 14),
('Famalicão', 'Grupo de apoio ao Famalicão', 'public', 14),
('Gil Vicente', 'Grupo de apoio ao Gil Vicente', 'public', 14),
('Marítimo', 'Grupo de apoio ao Marítimo', 'public', 14),
('Moreirense', 'Grupo de apoio ao Moreirense', 'public', 14),
('Nacional', 'Grupo de apoio ao Nacional', 'public', 14),
('P. Ferreira', 'Grupo de apoio ao P. Ferreira', 'public', 14),
('Santa Clara', 'Grupo de apoio ao Santa Clara', 'public', 14),
('Tondela', 'Grupo de apoio ao Tondela', 'public', 14),
('V. Guimarães', 'Grupo de apoio ao V. Guimarães', 'public', 14),
('V. Setúbal', 'Grupo de apoio ao V. Setúbal', 'public', 14);

-- Insert group memberships
INSERT INTO group_membership (id_user, id_group, joined_at)
VALUES 
    (3, 2, CURRENT_DATE),
    (4, 3, CURRENT_DATE),
    (6, 5, CURRENT_DATE),
    (7, 6, CURRENT_DATE),
    (8, 7, CURRENT_DATE),
    (10, 9, CURRENT_DATE),
    (11, 10, CURRENT_DATE),
    (12, 11, CURRENT_DATE),
    (16, 11, CURRENT_DATE),
    (2,1, CURRENT_DATE),
    (3,1, CURRENT_DATE),
    (4,1, CURRENT_DATE),
    (5,1, CURRENT_DATE),
    (6,1, CURRENT_DATE),
    (7,1, CURRENT_DATE);


INSERT INTO group_membership_request (id_user, id_group, request_status, requested_at, is_invite)
VALUES 
    (5, 4, 'pending', CURRENT_DATE,FALSE),
    (9, 8, 'pending', CURRENT_DATE,FALSE),
    (13, 11, 'pending', CURRENT_DATE,FALSE),
    (14, 11, 'pending', CURRENT_DATE,FALSE),
    (15, 10, 'rejected', CURRENT_DATE,FALSE),
    (16, 2, 'pending', CURRENT_DATE,FALSE),
    (8,1,'pending',CURRENT_DATE,FALSE),
    (9,1,'pending',CURRENT_DATE,FALSE),
    (10,1,'pending',CURRENT_DATE,FALSE),
    (11,1,'pending',CURRENT_DATE,TRUE),
    (12,1,'pending',CURRENT_DATE,TRUE),
    (13,1,'pending',CURRENT_DATE,TRUE),
    (14,1,'pending',CURRENT_DATE,TRUE),
    (15,1,'pending',CURRENT_DATE,TRUE),
    (16,1,'pending',CURRENT_DATE,TRUE);

-- Insert posts
INSERT INTO post (content, visibility, id_user, id_group, image_url)
VALUES 
('Looking forward to our next hike!', 'public', 1, 1, 'posts/images/3fdH5IrFdTqI2FBiJaSS0w7QK36yr1hE5OFCyJuq.png'),
('Has anyone read the latest mystery novel?', 'private', 2, 2, 'posts/images/5QuUoSbIPnAu5TkCm12ap0ruM51qkg2AjfFOUOLu.png'),
('What is your favorite band?', 'public', 3, 3, 'posts/images/6GdnztpsMKDuEH8p0HWIWTBj4WpWHMnU6KrrIxr7.png'),
('Best movie of the year?', 'public', 4, 4, 'posts/images/OuyVuADuyoExsAYSZ4dAqgjfJcMyRs2ZbC7X2npA.png'),
('Tips for beginner photographers?', 'private', 5, 5, 'posts/images/ZQNwZHsGnVlyh0U05vzEMNxK4Vhg9vpOUFV6zuzU.png'),
('Best recipes for quick meals?', 'public', 6, 6, 'posts/images/wHZwjj14zP8ElKgW3DACQug9DvfB2CIw6OW8tWgT.png'),
('Planning a trip to the mountains.', 'private', 7, 7, 'posts/images/3fdH5IrFdTqI2FBiJaSS0w7QK36yr1hE5OFCyJuq.png'),
('Latest tech trends for 2024.', 'public', 8, 8, 'posts/images/5QuUoSbIPnAu5TkCm12ap0ruM51qkg2AjfFOUOLu.png'),
('Best multiplayer games this year.', 'private', 9, 9, 'posts/images/6GdnztpsMKDuEH8p0HWIWTBj4WpWHMnU6KrrIxr7.png'),
('Art exhibitions worth visiting.', 'public', 10, 10, 'posts/images/OuyVuADuyoExsAYSZ4dAqgjfJcMyRs2ZbC7X2npA.png'),
('Vamos ganhar!', 'public', 14, 11, 'posts/images/ZQNwZHsGnVlyh0U05vzEMNxK4Vhg9vpOUFV6zuzU.png');



-- Insert comments
INSERT INTO comment (content, visibility, id_user, id_post)
VALUES 
('Count me in!', 'public', 2, 1),
('I loved it!', 'private', 1, 2),
('My favorite band is XYZ!', 'public', 3, 3),
('The best movie is ABC!', 'public', 4, 4),
('Try using natural light.', 'private', 5, 5),
('I recommend quick pasta.', 'public', 6, 6),
('Let’s plan for next weekend.', 'private', 7, 7),
('AI is taking over!', 'public', 8, 8),
('Let’s play together!', 'private', 9, 9),
('The modern art exhibition is amazing!', 'public', 10, 10),
('Vamos ganhar!', 'public', 14, 11);

-- Insert likes
INSERT INTO "like" (id_user, id_post)
VALUES 
(2, 1),
(1, 2),
(3, 3),
(4, 4),
(5, 5),
(6, 6),
(7, 7),
(8, 8),
(9, 9),
(10, 10),
(11,10),
(12,10);

-- Insert reports
INSERT INTO report (description, status, id_user, id_reported_post)
VALUES 
('Inappropriate content', 'pending', 3, 1),
('Spam', 'reviewed', 4, 2),
('Hate speech', 'resolved', 5, 3),
('Harassment', 'pending', 6, 4),
('Misinformation', 'pending', 7, 5),
('Inappropriate image', 'reviewed', 8, 6),
('Phishing attempt', 'resolved', 9, 7),
('Copyright infringement', 'pending', 10, 8),
('Fake news', 'reviewed', 1, 9),
('Offensive language', 'resolved', 2, 10);

-- Insert admin
INSERT INTO admin (id)
VALUES 
(1),
(3);

-- Insert messages
INSERT INTO message (content, id_sender, id_receiver, visibility)
VALUES 
('Hello, how are you?', 1, 2, 'public'),
('What’s up?', 2, 3, 'public'),
('Long time no see!', 3, 4, 'public'),
('Let’s catch up!', 4, 5, 'public'),
('How’s it going?', 5, 6, 'public'),
('Any plans for the weekend?', 6, 7, 'public'),
('Are you coming to the party?', 7, 8, 'public'),
('Happy Birthday!', 8, 9, 'public'),
('Congrats on your new job!', 9, 10, 'public'),
('See you soon!', 10, 1, 'public'),
('Se houver penalti contra o benfica amanhã pago um jantar a todos os membros do grupo', 14, 11, 'public');


INSERT INTO friendship (id_user1, id_user2, created_at)
VALUES 
(1, 3, CURRENT_DATE),
(1, 4, CURRENT_DATE),
(1, 5, CURRENT_DATE),
(1, 6, CURRENT_DATE),
(1, 7, CURRENT_DATE),
(1, 8, CURRENT_DATE),
(1, 9, CURRENT_DATE),
(1, 10, CURRENT_DATE),
(1, 11, CURRENT_DATE),
(1, 12, CURRENT_DATE),
(1, 13, CURRENT_DATE),
(1, 14, CURRENT_DATE),
(1, 15, CURRENT_DATE),
(1, 16, CURRENT_DATE),
(2, 3, CURRENT_DATE),
(2, 4, CURRENT_DATE),
(2, 5, CURRENT_DATE),
(2, 6, CURRENT_DATE),
(2, 7, CURRENT_DATE),
(2, 8, CURRENT_DATE),
(2, 9, CURRENT_DATE),
(2, 10, CURRENT_DATE),
(2, 11, CURRENT_DATE),
(2, 12, CURRENT_DATE),
(2, 13, CURRENT_DATE),
(2, 14, CURRENT_DATE),
(2, 15, CURRENT_DATE),
(2, 16, CURRENT_DATE);

INSERT INTO friendship_request (id_sender, id_receiver, status, created_at)
VALUES 
(1, 2, 'pending', CURRENT_DATE);
-------