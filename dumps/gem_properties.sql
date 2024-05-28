create table gem_properties
(
    id        int auto_increment
        primary key,
    item_id   int          null,
    prop_name varchar(255) null,
    value     int          null,
    constraint gem_properties_items_id_fk
        foreign key (item_id) references items (id)
            on update cascade on delete cascade
);

INSERT INTO game_serve.gem_properties (id, item_id, prop_name, value) VALUES (368, 1791, 'reduce_mana_cost', 0);
INSERT INTO game_serve.gem_properties (id, item_id, prop_name, value) VALUES (369, 1791, 'max_amp', 2);
INSERT INTO game_serve.gem_properties (id, item_id, prop_name, value) VALUES (370, 1791, 'upgrade_amp_exp_cost', 500);
INSERT INTO game_serve.gem_properties (id, item_id, prop_name, value) VALUES (371, 1792, 'reduce_mana_cost', 5);
INSERT INTO game_serve.gem_properties (id, item_id, prop_name, value) VALUES (372, 1792, 'max_amp', 4);
INSERT INTO game_serve.gem_properties (id, item_id, prop_name, value) VALUES (373, 1792, 'upgrade_amp_exp_cost', 100);
