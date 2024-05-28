create table gem_detail_list
(
    id           int auto_increment
        primary key,
    item_list_id int     null,
    gem_class    tinyint null,
    gem_type     tinyint null
);

INSERT INTO game_data.gem_detail_list (id, item_list_id, gem_class, gem_type) VALUES (1, 3, 1, 3);
INSERT INTO game_data.gem_detail_list (id, item_list_id, gem_class, gem_type) VALUES (2, 4, 3, 3);
INSERT INTO game_data.gem_detail_list (id, item_list_id, gem_class, gem_type) VALUES (3, 5, 2, 3);
INSERT INTO game_data.gem_detail_list (id, item_list_id, gem_class, gem_type) VALUES (4, 6, 4, 3);
