create table characters
(
    id                     bigint auto_increment
        primary key,
    user_id                bigint unsigned        not null,
    name                   varchar(255)           not null,
    x                      smallint  default 0    not null,
    y                      smallint  default 0    not null,
    life                   smallint  default 40   not null,
    max_life               smallint  default 40   not null,
    energy                 smallint  default 100  not null,
    min_attack_damage      smallint  default 2    not null,
    max_attack_damage      smallint  default 4    not null,
    energy_regeneration    int       default 1000 not null,
    exp                    int       default 0    not null,
    min_spell_damage       int       default 0    null,
    max_spell_damage       int       default 0    null,
    armour                 mediumint default 0    not null,
    attack_speed           float     default 1400 not null,
    attack_range           tinyint   default 40   not null,
    attack_crit_chance     tinyint   default 4    not null,
    attack_crit_multiplier tinyint   default 120  not null,
    spell_aoe              smallint  default 0    not null,
    mana_cost              smallint  default 0    not null,
    spell_crit_chance      tinyint   default 4    not null,
    spell_crit_multiplier  smallint  default 120  not null,
    evade                  tinyint   default 2    not null,
    resist                 tinyint   default 1    not null,
    movement_speed         int       default 1500 not null,
    cast_speed             smallint  default 2000 not null,
    will                   smallint  default 20   not null,
    attack_block           tinyint   default 50   not null,
    spell_block            tinyint   default 25   not null,
    torch                  int       default 20   not null,
    mana                   int       default 20   null,
    max_mana               int       default 20   null,
    dead                   tinyint   default 0    null,
    min_add_damage         int       default 0    null,
    max_add_damage         int       default 0    null,
    speed                  tinyint   default 4    null,
    constraint characters_ibfk_1
        foreign key (user_id) references users (id)
            on update cascade on delete cascade
);

create index user_id
    on characters (user_id);

INSERT INTO game_serve.characters (id, user_id, name, x, y, life, max_life, energy, min_attack_damage, max_attack_damage, energy_regeneration, exp, min_spell_damage, max_spell_damage, armour, attack_speed, attack_range, attack_crit_chance, attack_crit_multiplier, spell_aoe, mana_cost, spell_crit_chance, spell_crit_multiplier, evade, resist, movement_speed, cast_speed, will, attack_block, spell_block, torch, mana, max_mana, dead, min_add_damage, max_add_damage, speed) VALUES (279, 1, '124', -24, -10, 27, 40, 100, 4, 8, 1000, 78, 3, 12, 0, 1400, 40, 4, 120, 0, 0, 4, 120, 2, 1, 1500, 2055, 20, 50, 25, 20, 20, 20, 0, 0, 0, 4);
