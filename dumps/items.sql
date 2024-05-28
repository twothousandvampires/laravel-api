create table items
(
    id         int auto_increment
        primary key,
    char_id    bigint       null,
    slot       tinyint      null,
    type       varchar(255) not null,
    name       varchar(255) not null,
    rarity     tinyint      not null,
    created_at datetime     null,
    updated_at datetime     null,
    equiped    tinyint      null,
    constraint items_ibfk_1
        foreign key (char_id) references characters (id)
            on update cascade on delete cascade
);

create index char_id
    on items (char_id);

INSERT INTO game_serve.items (id, char_id, slot, type, name, rarity, created_at, updated_at, equiped) VALUES (1785, 279, 9, '1', 'Fu Inle armour', 3, '2024-04-16 19:56:38', '2024-04-16 19:56:38', null);
INSERT INTO game_serve.items (id, char_id, slot, type, name, rarity, created_at, updated_at, equiped) VALUES (1786, 279, 0, '1', 'pale razor', 1, '2024-04-16 19:57:26', '2024-04-16 19:57:31', null);
INSERT INTO game_serve.items (id, char_id, slot, type, name, rarity, created_at, updated_at, equiped) VALUES (1787, 279, 10, '2', 'strange gem', 2, '2024-04-16 19:58:42', '2024-04-16 19:58:42', null);
INSERT INTO game_serve.items (id, char_id, slot, type, name, rarity, created_at, updated_at, equiped) VALUES (1788, 279, 3, '1', 'waved stick', 3, '2024-04-16 19:59:00', '2024-04-16 19:59:08', null);
INSERT INTO game_serve.items (id, char_id, slot, type, name, rarity, created_at, updated_at, equiped) VALUES (1789, 279, 5, '1', 'jumping stars', 2, '2024-04-16 19:59:16', '2024-04-16 19:59:27', null);
INSERT INTO game_serve.items (id, char_id, slot, type, name, rarity, created_at, updated_at, equiped) VALUES (1790, 279, 4, '1', 'strange diadem', 2, '2024-04-16 19:59:33', '2024-04-16 19:59:40', null);
INSERT INTO game_serve.items (id, char_id, slot, type, name, rarity, created_at, updated_at, equiped) VALUES (1791, 279, 30, '2', 'slippery gem', 1, '2024-04-16 19:59:54', '2024-04-16 19:59:58', null);
INSERT INTO game_serve.items (id, char_id, slot, type, name, rarity, created_at, updated_at, equiped) VALUES (1792, 279, 11, '2', 'slippery gem', 1, '2024-04-16 20:00:33', '2024-04-16 20:00:33', null);
