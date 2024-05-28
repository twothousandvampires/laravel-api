create table equipment_properties_list
(
    id          tinyint auto_increment
        primary key,
    item_name   varchar(255)      not null,
    type        varchar(255)      not null,
    class       varchar(255)      not null,
    low         int               null,
    normal      int               not null,
    good        int               not null,
    masterpiece int               not null,
    stat        varchar(255)      not null,
    name        varchar(255)      not null,
    prop_type   tinyint           not null,
    sub_type    tinyint default 1 not null,
    inc_type    tinyint default 1 null
);

INSERT INTO game_serve.equipment_properties_list (id, item_name, type, class, low, normal, good, masterpiece, stat, name, prop_type, sub_type, inc_type) VALUES (22, 'elder ring', 'accessory', 'combat', 1, 2, 3, 6, 'min_attack_damage', 'attack min damage', 1, 1, 1);
INSERT INTO game_serve.equipment_properties_list (id, item_name, type, class, low, normal, good, masterpiece, stat, name, prop_type, sub_type, inc_type) VALUES (23, 'elder ring', 'accessory', 'combat', 1, 2, 3, 4, 'armour', 'armour', 1, 1, 1);
INSERT INTO game_serve.equipment_properties_list (id, item_name, type, class, low, normal, good, masterpiece, stat, name, prop_type, sub_type, inc_type) VALUES (24, 'majestic helm', 'armour', 'combat', 1, 2, 3, 6, 'armour', 'additional armour', 1, 1, 1);
INSERT INTO game_serve.equipment_properties_list (id, item_name, type, class, low, normal, good, masterpiece, stat, name, prop_type, sub_type, inc_type) VALUES (25, 'majestic helm', 'armour', 'combat', 400, 300, 200, 100, 'movement_speed', 'reduce movement speed', 1, 2, 1);
INSERT INTO game_serve.equipment_properties_list (id, item_name, type, class, low, normal, good, masterpiece, stat, name, prop_type, sub_type, inc_type) VALUES (26, 'spectral skull', 'armour', 'movement', 100, 150, 200, 300, 'energy_regeneration', 'increase energy regeneration', 1, 1, 1);
INSERT INTO game_serve.equipment_properties_list (id, item_name, type, class, low, normal, good, masterpiece, stat, name, prop_type, sub_type, inc_type) VALUES (27, 'strange diadem', 'armour', 'sorcery', 1, 2, 3, 6, 'min_spell_damage', 'increase min spell damage', 1, 1, 1);
INSERT INTO game_serve.equipment_properties_list (id, item_name, type, class, low, normal, good, masterpiece, stat, name, prop_type, sub_type, inc_type) VALUES (28, 'pale razor', 'weapon', 'combat', 1, 2, 3, 6, 'min_attack_damage', 'additional minimum attack damage', 1, 1, 1);
INSERT INTO game_serve.equipment_properties_list (id, item_name, type, class, low, normal, good, masterpiece, stat, name, prop_type, sub_type, inc_type) VALUES (29, 'pale razor', 'weaponp', 'combat', 2, 4, 6, 12, 'max_attack_damage', 'additional maximum attack damage', 1, 1, 1);
INSERT INTO game_serve.equipment_properties_list (id, item_name, type, class, low, normal, good, masterpiece, stat, name, prop_type, sub_type, inc_type) VALUES (30, 'bone crusher', 'weapon', 'combat', 400, 350, 300, 100, 'attack_speed', 'reduce attack speed', 1, 1, 2);
INSERT INTO game_serve.equipment_properties_list (id, item_name, type, class, low, normal, good, masterpiece, stat, name, prop_type, sub_type, inc_type) VALUES (31, 'bone crusher', 'weapon', 'combat', 2, 4, 6, 12, 'min_attack_damage', 'additional minimum attack damage', 1, 1, 1);
INSERT INTO game_serve.equipment_properties_list (id, item_name, type, class, low, normal, good, masterpiece, stat, name, prop_type, sub_type, inc_type) VALUES (32, 'bone crusher', 'weapon', 'combat', 4, 6, 8, 20, 'max_attack_damage', 'additional maximum attack damage', 1, 1, 1);
INSERT INTO game_serve.equipment_properties_list (id, item_name, type, class, low, normal, good, masterpiece, stat, name, prop_type, sub_type, inc_type) VALUES (33, 'giant scale', 'armour', 'combat', 1, 2, 3, 6, 'attack_block', 'additional chance to block attack', 1, 1, 1);
INSERT INTO game_serve.equipment_properties_list (id, item_name, type, class, low, normal, good, masterpiece, stat, name, prop_type, sub_type, inc_type) VALUES (34, 'giant scale', 'armour', 'combat', 200, 150, 100, 20, 'movement_speed', 'reduce movement speed', 1, 2, 1);
INSERT INTO game_serve.equipment_properties_list (id, item_name, type, class, low, normal, good, masterpiece, stat, name, prop_type, sub_type, inc_type) VALUES (35, 'waved stick', 'weapon', 'sorcery', 4, 6, 10, 30, 'max_spell_damage', 'increase max spell damage', 1, 1, 1);
INSERT INTO game_serve.equipment_properties_list (id, item_name, type, class, low, normal, good, masterpiece, stat, name, prop_type, sub_type, inc_type) VALUES (36, 'jumping stars', 'accessory', 'sorcery', 50, 100, 150, 300, 'cast_speed', 'increase cast speed', 1, 2, 2);
INSERT INTO game_serve.equipment_properties_list (id, item_name, type, class, low, normal, good, masterpiece, stat, name, prop_type, sub_type, inc_type) VALUES (37, 'jumping stars', 'accessory', 'sorcery', 2, 4, 6, 16, 'max_spell_damage', 'additional maximum spell damage', 1, 1, 1);
INSERT INTO game_serve.equipment_properties_list (id, item_name, type, class, low, normal, good, masterpiece, stat, name, prop_type, sub_type, inc_type) VALUES (38, 'searching eye', 'accessory', 'sorcery', 10, 20, 30, 50, 'spell_aoe', 'increase spell aoe', 1, 1, 1);
INSERT INTO game_serve.equipment_properties_list (id, item_name, type, class, low, normal, good, masterpiece, stat, name, prop_type, sub_type, inc_type) VALUES (39, 'sprouted branch', 'accessory', 'sorcery', 2, 5, 10, 15, 'resist', 'increase resist', 1, 1, 1);
INSERT INTO game_serve.equipment_properties_list (id, item_name, type, class, low, normal, good, masterpiece, stat, name, prop_type, sub_type, inc_type) VALUES (41, 'skull covered in wax', 'armour', 'sorcery', 10, 8, 6, 1, 'min_attack_damage', 'reduce attack damage', 1, 2, 1);
INSERT INTO game_serve.equipment_properties_list (id, item_name, type, class, low, normal, good, masterpiece, stat, name, prop_type, sub_type, inc_type) VALUES (42, 'skull covered in wax', 'armour', 'sorcery', 1, 3, 5, 10, 'attack_crit_chance', 'additional critical chance', 1, 1, 1);
INSERT INTO game_serve.equipment_properties_list (id, item_name, type, class, low, normal, good, masterpiece, stat, name, prop_type, sub_type, inc_type) VALUES (43, 'Fu Inle armour', 'armour', 'sorcery', 100, 150, 200, 400, 'cast_speed', 'increase cast speed', 1, 2, 2);
INSERT INTO game_serve.equipment_properties_list (id, item_name, type, class, low, normal, good, masterpiece, stat, name, prop_type, sub_type, inc_type) VALUES (44, 'Fu Inle armour', 'armour', 'sorcery', 10, 20, 30, 60, 'spell_crit_multiplier', 'additional spell multiplier', 1, 1, 1);
INSERT INTO game_serve.equipment_properties_list (id, item_name, type, class, low, normal, good, masterpiece, stat, name, prop_type, sub_type, inc_type) VALUES (46, 'bones in boots', 'armour', 'movement', 1, 2, 3, 5, 'min_attack_damage', 'increase attack damage', 1, 1, 1);
INSERT INTO game_serve.equipment_properties_list (id, item_name, type, class, low, normal, good, masterpiece, stat, name, prop_type, sub_type, inc_type) VALUES (47, 'dream fragment', 'accessory', 'sorcery', 1, 1, 1, 1, 'cannot_be_frozen', 'cannot be frozen', 3, 1, 1);
INSERT INTO game_serve.equipment_properties_list (id, item_name, type, class, low, normal, good, masterpiece, stat, name, prop_type, sub_type, inc_type) VALUES (48, 'dream fragment', 'accessory', 'sorcery', 20, 15, 10, 2, 'max_spell_damage', 'reduce max spell damage', 1, 2, 1);
INSERT INTO game_serve.equipment_properties_list (id, item_name, type, class, low, normal, good, masterpiece, stat, name, prop_type, sub_type, inc_type) VALUES (49, 'drop of blood', 'accessory', 'combat', 1, 2, 3, 5, 'max_life', 'add life', 1, 1, 1);
INSERT INTO game_serve.equipment_properties_list (id, item_name, type, class, low, normal, good, masterpiece, stat, name, prop_type, sub_type, inc_type) VALUES (51, 'elder ring', 'accessory', 'combat', 2, 4, 6, 12, 'max_attack_damage', 'attack max damage', 1, 1, 1);
INSERT INTO game_serve.equipment_properties_list (id, item_name, type, class, low, normal, good, masterpiece, stat, name, prop_type, sub_type, inc_type) VALUES (52, 'strange diadem', 'armour', 'sorcery', 2, 4, 6, 12, 'max_spell_damage', 'increase max spell damage', 1, 1, 1);
INSERT INTO game_serve.equipment_properties_list (id, item_name, type, class, low, normal, good, masterpiece, stat, name, prop_type, sub_type, inc_type) VALUES (53, 'leaf fall', 'armour', 'sorcery', 10, 20, 30, 60, 'leaf_armour_on_block', 'chance to create leaf armour when you block', 2, 1, 1);
INSERT INTO game_serve.equipment_properties_list (id, item_name, type, class, low, normal, good, masterpiece, stat, name, prop_type, sub_type, inc_type) VALUES (54, 'leaf fall', 'armour', 'sorcery', 1, 2, 3, 10, 'max_life', 'add life', 1, 1, 1);
INSERT INTO game_serve.equipment_properties_list (id, item_name, type, class, low, normal, good, masterpiece, stat, name, prop_type, sub_type, inc_type) VALUES (55, 'endless flame', 'accessory', 'sorcery', 5, 4, 3, 1, 'radiance', 'radiance', 2, 1, 1);
INSERT INTO game_serve.equipment_properties_list (id, item_name, type, class, low, normal, good, masterpiece, stat, name, prop_type, sub_type, inc_type) VALUES (56, 'drop of shadow', 'accessory', 'sorcery', 1, 2, 3, 8, 'max_mana', 'add maximum mana', 1, 1, 1);
