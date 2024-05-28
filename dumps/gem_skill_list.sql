create table gem_skill_list
(
    id         int auto_increment
        primary key,
    exp_needed int               not null,
    name       varchar(255)      not null,
    gem_class  tinyint           not null,
    gem_type   tinyint           null,
    active     tinyint default 1 not null
);

INSERT INTO game_serve.gem_skill_list (id, exp_needed, name, gem_class, gem_type, active) VALUES (1, 2000, 'stone skin', 1, 2, 0);
INSERT INTO game_serve.gem_skill_list (id, exp_needed, name, gem_class, gem_type, active) VALUES (5, 2000, 'luminous arc', 2, 1, 0);
INSERT INTO game_serve.gem_skill_list (id, exp_needed, name, gem_class, gem_type, active) VALUES (8, 2000, 'excited body', 3, 1, 0);
INSERT INTO game_serve.gem_skill_list (id, exp_needed, name, gem_class, gem_type, active) VALUES (9, 2000, 'wandering clot', 2, 1, 0);
INSERT INTO game_serve.gem_skill_list (id, exp_needed, name, gem_class, gem_type, active) VALUES (10, 2000, 'falling rocks', 2, 1, 0);
INSERT INTO game_serve.gem_skill_list (id, exp_needed, name, gem_class, gem_type, active) VALUES (11, 2000, 'wild wind', 2, 1, 0);
INSERT INTO game_serve.gem_skill_list (id, exp_needed, name, gem_class, gem_type, active) VALUES (12, 2000, 'fire coil', 2, 1, 0);
INSERT INTO game_serve.gem_skill_list (id, exp_needed, name, gem_class, gem_type, active) VALUES (13, 1000, 'icy nova', 2, 1, 1);
