create table equip_types
(
    id        int auto_increment
        primary key,
    type_name varchar(255) null
);

INSERT INTO game_data.equip_types (id, type_name) VALUES (1, 'weapon');
INSERT INTO game_data.equip_types (id, type_name) VALUES (2, 'armour');
INSERT INTO game_data.equip_types (id, type_name) VALUES (3, 'accessory');
