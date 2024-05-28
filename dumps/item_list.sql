create table item_list
(
    id     int auto_increment
        primary key,
    name   varchar(255)      not null comment 'имя предмета',
    type   tinyint default 1 not null comment 'тип предмета',
    rarity tinyint default 1 not null comment 'редкость предмета'
);

INSERT INTO game_data.item_list (id, name, type, rarity) VALUES (1, 'elder ring', 1, 1);
INSERT INTO game_data.item_list (id, name, type, rarity) VALUES (2, 'majestic helm', 1, 1);
INSERT INTO game_data.item_list (id, name, type, rarity) VALUES (3, 'muddy gem', 2, 1);
INSERT INTO game_data.item_list (id, name, type, rarity) VALUES (4, 'acid gem', 2, 1);
INSERT INTO game_data.item_list (id, name, type, rarity) VALUES (5, 'slippery gem', 2, 1);
INSERT INTO game_data.item_list (id, name, type, rarity) VALUES (6, 'strange gem', 2, 2);
INSERT INTO game_data.item_list (id, name, type, rarity) VALUES (7, 'spectral skull', 1, 2);
INSERT INTO game_data.item_list (id, name, type, rarity) VALUES (8, 'strange diadem', 1, 2);
INSERT INTO game_data.item_list (id, name, type, rarity) VALUES (9, 'pale razor', 1, 1);
INSERT INTO game_data.item_list (id, name, type, rarity) VALUES (10, 'bone crusher', 1, 1);
INSERT INTO game_data.item_list (id, name, type, rarity) VALUES (11, 'giant scale', 1, 2);
INSERT INTO game_data.item_list (id, name, type, rarity) VALUES (12, 'waved stick', 1, 3);
INSERT INTO game_data.item_list (id, name, type, rarity) VALUES (13, 'jumping stars', 1, 2);
INSERT INTO game_data.item_list (id, name, type, rarity) VALUES (14, 'searching eye', 1, 2);
INSERT INTO game_data.item_list (id, name, type, rarity) VALUES (16, 'sprouted branch', 1, 3);
INSERT INTO game_data.item_list (id, name, type, rarity) VALUES (17, 'skull covered in wax', 1, 3);
INSERT INTO game_data.item_list (id, name, type, rarity) VALUES (18, 'Fu Inle armour', 1, 3);
INSERT INTO game_data.item_list (id, name, type, rarity) VALUES (19, 'bones in boots', 1, 1);
INSERT INTO game_data.item_list (id, name, type, rarity) VALUES (20, 'dream fragment', 1, 4);
INSERT INTO game_data.item_list (id, name, type, rarity) VALUES (22, 'battle mastery scroll', 3, 1);
INSERT INTO game_data.item_list (id, name, type, rarity) VALUES (23, 'drop of blood', 1, 1);
INSERT INTO game_data.item_list (id, name, type, rarity) VALUES (24, 'leaf fall', 1, 4);
INSERT INTO game_data.item_list (id, name, type, rarity) VALUES (25, 'endless flame', 1, 4);
INSERT INTO game_data.item_list (id, name, type, rarity) VALUES (26, 'drop of shadow', 1, 3);
