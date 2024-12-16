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
('hank_red', 'hank.red@up.pt', 'Hank Red', 'hashed_password10', 'private', 'blocked'),
('maria_purple', 'maria.p@up.pt', 'Maria Purple', 'hashed_password11', 'public', 'active'),
('joao_orange', 'joao.o@up.pt', 'Joao Orange', 'hashed_password12', 'public', 'active'),
('ana_brown', 'ana.brown@up.pt', 'Ana Brown', 'hashed_password13', 'public', 'active'),
('francisco_madureira', 'macaco.super_dragoes@up.pt', 'Francisco Madureira', 'hashed_password14', 'public', 'active'),
('marcelo_rebelo_sousa', 'mrs@up.pt', 'Marcelo Rebelo Sousa', 'hashed_password15', 'public', 'active'),
('antonio_costa', 'ac@up.pt', 'Antonio Costa', 'hashed_password16', 'public', 'active');


-- Insert groups
INSERT INTO "group" (name, description, visibility, id_owner)
VALUES 
('Hiking Enthusiasts', 'A group for people who love hiking.', 'public', 1),
('Book Club', 'Discussing our favorite books.', 'private', 2),
('Music Lovers', 'Sharing music recommendations.', 'public', 3),
('Movie Buffs', 'Discussing favorite movies.', 'public', 4),
('Photography Club', 'Sharing photography tips.', 'private', 5),
('Cooking Club', 'Sharing recipes and cooking tips.', 'public', 6),
('Travel Buddies', 'Planning trips together.', 'private', 7),
('Tech Geeks', 'Discussing latest in tech.', 'public', 8),
('Gaming Squad', 'Playing and discussing games.', 'private', 9),
('Art Enthusiasts', 'Discussing art and exhibitions.', 'public', 10),
('Super Dragões', 'Grupo de apoio ao FC Porto', 'public', 14);

-- Insert group memberships
INSERT INTO group_membership (id_user, id_group, group_status)
VALUES 
(1, 1, 'accepted'),
(2, 1, 'pending'),
(3, 2, 'accepted'),
(4, 3, 'accepted'),
(5, 4, 'accepted'),
(6, 5, 'pending'),
(7, 6, 'accepted'),
(8, 7, 'accepted'),
(9, 8, 'accepted'),
(10, 9, 'pending'),
(11, 10, 'accepted'),
(12, 11, 'accepted'),
(13, 11, 'accepted'),
(14, 11, 'accepted'),
(15, 11, 'accepted'),
(16, 11, 'accepted');

-- Insert posts
INSERT INTO post (content, visibility, id_user, id_group)
VALUES 
('Looking forward to our next hike!', 'public', 1, 1),
('Has anyone read the latest mystery novel?', 'private', 2, 2),
('What is your favorite band?', 'public', 3, 3),
('Best movie of the year?', 'public', 4, 4),
('Tips for beginner photographers?', 'private', 5, 5),
('Best recipes for quick meals?', 'public', 6, 6),
('Planning a trip to the mountains.', 'private', 7, 7),
('Latest tech trends for 2024.', 'public', 8, 8),
('Best multiplayer games this year.', 'private', 9, 9),
('Art exhibitions worth visiting.', 'public', 10, 10),
('Vamos ganhar!', 'public', 14, 11);

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
(2),
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


INSERT INTO notification (id_user, id_related_post, id_related_comment, id_related_group, seen)
VALUES 
(1, 1, NULL, 1, FALSE),
(2, 2, 1, NULL, FALSE),
(3, NULL, NULL, NULL, TRUE),
(4, 2, NULL, 2, FALSE),
(5, NULL, 2, NULL, FALSE),
(6, 5, NULL, 5, FALSE),
(7, 6, NULL, 6, FALSE),
(8, 7, NULL, 7, FALSE),
(9, 8, NULL, 8, FALSE),
(10, 9, NULL, 9, FALSE),
(11, 10, NULL, 10, FALSE),
(14, 11, NULL, 11, FALSE);

-- Insert specific user notifications
INSERT INTO user_notification (id_notification, type)
VALUES 
(1, 'received_friend_request'),
(3, 'match');

-- Insert specific comment notifications
INSERT INTO comment_notification (id_notification, type)
VALUES 
(5, 'commented_on_post');


-- Insert specific group notifications
INSERT INTO group_notification (id_notification, type)
VALUES 
(6, 'invited_to_group');

-- Insert Friendships
INSERT INTO friendship_connection (id_user1, id_user2, status)
VALUES 
(1, 2, 'pending'),
(1, 3, 'accepted'),
(1, 4, 'accepted'),
(1, 5, 'accepted'),
(1, 6, 'accepted'),
(1, 7, 'accepted'),
(1, 8, 'accepted'),
(1, 9, 'accepted'),
(1, 10, 'accepted'),
(1, 11, 'accepted'),
(1, 12, 'accepted'),
(1, 13, 'accepted'),
(1, 14, 'accepted'),
(1, 15, 'accepted'),
(1, 16, 'accepted');
