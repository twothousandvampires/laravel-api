create table gem_types
(
    id        int auto_increment
        primary key,
    type_name varchar(255) null
);

INSERT INTO game_data.gem_types (id, type_name) VALUES (1, 'active');
INSERT INTO game_data.gem_types (id, type_name) VALUES (2, 'passive');
INSERT INTO game_data.gem_types (id, type_name) VALUES (3, 'all');
