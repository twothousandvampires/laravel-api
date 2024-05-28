create table item_types
(
    item_type_name varchar(255) null comment 'тип предмета',
    id             int auto_increment
        primary key
);

INSERT INTO game_data.item_types (item_type_name, id) VALUES ('equip', 1);
INSERT INTO game_data.item_types (item_type_name, id) VALUES ('gem', 2);
INSERT INTO game_data.item_types (item_type_name, id) VALUES ('used', 3);
