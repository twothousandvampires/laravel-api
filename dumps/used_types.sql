create table used_types
(
    id        int auto_increment
        primary key,
    type_name varchar(255) null
);

INSERT INTO game_data.used_types (id, type_name) VALUES (1, 'scroll');
