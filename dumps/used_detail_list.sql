create table used_detail_list
(
    id           int auto_increment
        primary key,
    used_type    tinyint null,
    charge       tinyint null,
    item_list_id int     null
);

INSERT INTO game_data.used_detail_list (id, used_type, charge, item_list_id) VALUES (1, 1, 1, 22);
