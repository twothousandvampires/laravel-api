create table skill_property
(
    id         int auto_increment
        primary key,
    name       varchar(255) null,
    skill_id   int          null,
    exp_needed int          null,
    type       varchar(255) null,
    level      int          null,
    max_level  tinyint      null,
    constraint skill_property_gem_skills_id_fk
        foreign key (skill_id) references gem_skills (id)
            on update cascade on delete cascade
);

