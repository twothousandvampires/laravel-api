create table gem_skills
(
    id          int auto_increment
        primary key,
    level       tinyint default 0 not null,
    exp_needed  int               not null,
    name        varchar(255)      not null,
    skill_type  tinyint           not null,
    item_id     int               not null,
    skill_class tinyint           null,
    constraint FK_6LIZHWJET
        foreign key (item_id) references items (id)
            on update cascade on delete cascade
);

INSERT INTO game_serve.gem_skills (id, level, exp_needed, name, skill_type, item_id, skill_class) VALUES (465, 1, 2000, 'fire coil', 1, 1787, 2);
INSERT INTO game_serve.gem_skills (id, level, exp_needed, name, skill_type, item_id, skill_class) VALUES (466, 1, 2000, 'luminous arc', 1, 1791, 2);
INSERT INTO game_serve.gem_skills (id, level, exp_needed, name, skill_type, item_id, skill_class) VALUES (467, 1, 2000, 'wild wind', 1, 1792, 2);
