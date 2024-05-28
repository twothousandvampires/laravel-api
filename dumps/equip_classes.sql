create table equip_classes
(
    id         int auto_increment
        primary key,
    class_name varchar(255) null
);

INSERT INTO game_data.equip_classes (id, class_name) VALUES (1, 'combat');
INSERT INTO game_data.equip_classes (id, class_name) VALUES (2, 'sorcery');
INSERT INTO game_data.equip_classes (id, class_name) VALUES (3, 'movement');
