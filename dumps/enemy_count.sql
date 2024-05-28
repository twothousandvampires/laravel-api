create table enemy_count
(
    enemy_id  int     null,
    distance  tinyint null,
    min_count tinyint null,
    max_count tinyint null
);

INSERT INTO game_serve.enemy_count (enemy_id, distance, min_count, max_count) VALUES (1, 0, 1, 2);
INSERT INTO game_serve.enemy_count (enemy_id, distance, min_count, max_count) VALUES (1, 1, 2, 4);
INSERT INTO game_serve.enemy_count (enemy_id, distance, min_count, max_count) VALUES (1, 2, 3, 5);
INSERT INTO game_serve.enemy_count (enemy_id, distance, min_count, max_count) VALUES (1, 3, 4, 6);
INSERT INTO game_serve.enemy_count (enemy_id, distance, min_count, max_count) VALUES (2, 0, 1, 2);
INSERT INTO game_serve.enemy_count (enemy_id, distance, min_count, max_count) VALUES (2, 1, 2, 3);
INSERT INTO game_serve.enemy_count (enemy_id, distance, min_count, max_count) VALUES (2, 2, 3, 4);
INSERT INTO game_serve.enemy_count (enemy_id, distance, min_count, max_count) VALUES (2, 3, 4, 5);
