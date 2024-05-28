create table equip_classes
(
    id         int auto_increment
        primary key,
    class_name varchar(255) null
);

create table equip_detail_list
(
    id           int auto_increment
        primary key,
    equip_type   tinyint null,
    equip_class  tinyint null,
    item_list_id int     null
);

create table equip_types
(
    id        int auto_increment
        primary key,
    type_name varchar(255) null
);

create table gem_classes
(
    id         int auto_increment
        primary key,
    class_name varchar(255) null
);

create table gem_detail_list
(
    id           int auto_increment
        primary key,
    item_list_id int     null,
    gem_class    tinyint null,
    gem_type     tinyint null
);

create table gem_types
(
    id        int auto_increment
        primary key,
    type_name varchar(255) null
);

create table item_list
(
    id     int auto_increment
        primary key,
    name   varchar(255)      not null comment 'имя предмета',
    type   tinyint default 1 not null comment 'тип предмета',
    rarity tinyint default 1 not null comment 'редкость предмета'
);

create table item_types
(
    item_type_name varchar(255) null comment 'тип предмета',
    id             int auto_increment
        primary key
);

create table used_detail_list
(
    id           int auto_increment
        primary key,
    used_type    tinyint null,
    charge       tinyint null,
    item_list_id int     null
);

create table used_types
(
    id        int auto_increment
        primary key,
    type_name varchar(255) null
);


