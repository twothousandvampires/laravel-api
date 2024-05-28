create table gem_classes
(
    id         int auto_increment
        primary key,
    class_name varchar(255) null
);

INSERT INTO game_data.gem_classes (id, class_name) VALUES (1, 'combat');
INSERT INTO game_data.gem_classes (id, class_name) VALUES (2, 'sorcery');
INSERT INTO game_data.gem_classes (id, class_name) VALUES (3, 'movement');
INSERT INTO game_data.gem_classes (id, class_name) VALUES (4, 'all');
