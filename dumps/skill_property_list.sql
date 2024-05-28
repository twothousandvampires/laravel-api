create table skill_property_list
(
    id          int auto_increment
        primary key,
    name        varchar(255) null,
    parent_name varchar(255) null,
    max_level   tinyint      null,
    type        varchar(255) null,
    exp_needed  int          null
);

INSERT INTO game_serve.skill_property_list (id, name, parent_name, max_level, type, exp_needed) VALUES (6, 'density', 'wandering clot', 20, '2', 1200);
INSERT INTO game_serve.skill_property_list (id, name, parent_name, max_level, type, exp_needed) VALUES (9, 'overload', 'wandering clot', 20, '2', 1200);
INSERT INTO game_serve.skill_property_list (id, name, parent_name, max_level, type, exp_needed) VALUES (10, 'more projectiles', 'wandering clot', 20, '2', 2000);
INSERT INTO game_serve.skill_property_list (id, name, parent_name, max_level, type, exp_needed) VALUES (11, 'intensity', 'wandering clot', 20, '2', 1400);
INSERT INTO game_serve.skill_property_list (id, name, parent_name, max_level, type, exp_needed) VALUES (12, 'endless energy', 'wandering clot', 20, '2', 2500);
INSERT INTO game_serve.skill_property_list (id, name, parent_name, max_level, type, exp_needed) VALUES (13, 'explosive', 'wandering clot', 20, '2', 3000);
