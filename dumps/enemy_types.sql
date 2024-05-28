create table enemy_types
(
    id        int auto_increment
        primary key,
    type_name varchar(255) null
);

INSERT INTO game_serve.enemy_types (id, type_name) VALUES (1, 'undead');
