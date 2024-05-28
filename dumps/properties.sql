create table properties
(
    id        int auto_increment
        primary key,
    item_id   int               not null,
    name      varchar(255)      not null,
    value     int               not null,
    stat      varchar(255)      not null,
    prop_type tinyint           not null,
    inc_type  tinyint default 1 null,
    sub_type  tinyint default 1 null,
    constraint properties_items_id_fk
        foreign key (item_id) references items (id)
            on update cascade on delete cascade
);

create index item_id
    on properties (item_id);

INSERT INTO game_serve.properties (id, item_id, name, value, stat, prop_type, inc_type, sub_type) VALUES (1678, 1785, 'increase cast speed', 200, 'cast_speed', 1, 1, 1);
INSERT INTO game_serve.properties (id, item_id, name, value, stat, prop_type, inc_type, sub_type) VALUES (1679, 1785, 'additional spell multiplier', 30, 'spell_crit_multiplier', 1, 1, 1);
INSERT INTO game_serve.properties (id, item_id, name, value, stat, prop_type, inc_type, sub_type) VALUES (1680, 1786, 'additional minimum attack damage', 2, 'min_attack_damage', 1, 1, 1);
INSERT INTO game_serve.properties (id, item_id, name, value, stat, prop_type, inc_type, sub_type) VALUES (1681, 1786, 'additional maximum attack damage', 4, 'max_attack_damage', 1, 1, 1);
INSERT INTO game_serve.properties (id, item_id, name, value, stat, prop_type, inc_type, sub_type) VALUES (1682, 1788, 'increase max spell damage', 4, 'max_spell_damage', 1, 1, 1);
INSERT INTO game_serve.properties (id, item_id, name, value, stat, prop_type, inc_type, sub_type) VALUES (1683, 1789, 'increase cast speed', 50, 'cast_speed', 1, 1, 1);
INSERT INTO game_serve.properties (id, item_id, name, value, stat, prop_type, inc_type, sub_type) VALUES (1684, 1789, 'additional maximum spell damage', 2, 'max_spell_damage', 1, 1, 1);
INSERT INTO game_serve.properties (id, item_id, name, value, stat, prop_type, inc_type, sub_type) VALUES (1685, 1790, 'increase min spell damage', 3, 'min_spell_damage', 1, 1, 1);
INSERT INTO game_serve.properties (id, item_id, name, value, stat, prop_type, inc_type, sub_type) VALUES (1686, 1790, 'increase max spell damage', 6, 'max_spell_damage', 1, 1, 1);
