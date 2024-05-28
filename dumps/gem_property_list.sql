create table gem_property_list
(
    id          int auto_increment
        primary key,
    item_name   varchar(255) null,
    low         int          null,
    normal      int          null,
    good        int          null,
    masterpiece int          null,
    prop_name   varchar(255) null
);

INSERT INTO game_serve.gem_property_list (id, item_name, low, normal, good, masterpiece, prop_name) VALUES (1, 'slippery gem', 0, 0, 2, 5, 'reduce_mana_cost');
INSERT INTO game_serve.gem_property_list (id, item_name, low, normal, good, masterpiece, prop_name) VALUES (2, 'slippery gem', 2, 3, 4, 4, 'max_amp');
INSERT INTO game_serve.gem_property_list (id, item_name, low, normal, good, masterpiece, prop_name) VALUES (3, 'slippery gem', 500, 350, 200, 100, 'upgrade_amp_exp_cost');
