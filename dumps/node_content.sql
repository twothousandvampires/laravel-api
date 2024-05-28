create table node_content
(
    id           bigint auto_increment
        primary key,
    node_id      bigint  null,
    content_type tinyint null,
    content      json    null,
    constraint node_content_id_uindex
        unique (id),
    constraint node_content_nodes_id_fk
        foreign key (node_id) references nodes (id)
            on update cascade on delete cascade
);

create index node_id
    on node_content (node_id);

INSERT INTO world.node_content (id, node_id, content_type, content) VALUES (769, 4026, 1, null);
INSERT INTO world.node_content (id, node_id, content_type, content) VALUES (770, 4042, 1, '{"map": {"width": 584, "height": 584}, "enemy": {"item": "strange gem", "groups": [[{"num": 12, "name": "skeleton warrior"}], [{"num": 13, "name": "skeleton archer"}, {"num": 6, "name": "skeleton archer"}]], "total_exp": 34}}');
INSERT INTO world.node_content (id, node_id, content_type, content) VALUES (771, 4047, 1, null);
INSERT INTO world.node_content (id, node_id, content_type, content) VALUES (772, 4049, 1, null);
INSERT INTO world.node_content (id, node_id, content_type, content) VALUES (773, 4051, 1, '{"map": {"width": 531, "height": 531}, "enemy": {"item": "strange gem", "groups": [[{"num": 19, "name": "skeleton warrior"}, {"num": 12, "name": "skeleton warrior"}], [{"num": 20, "name": "skeleton archer"}]], "total_exp": 32}}');
INSERT INTO world.node_content (id, node_id, content_type, content) VALUES (774, 4056, 1, null);
INSERT INTO world.node_content (id, node_id, content_type, content) VALUES (775, 4080, 1, '{"map": {"width": 569, "height": 569}, "enemy": {"item": "slippery gem", "groups": [[{"num": 19, "name": "skeleton warrior"}, {"num": 33, "name": "skeleton warrior"}], [{"num": 34, "name": "skeleton archer"}]], "total_exp": 32}}');
INSERT INTO world.node_content (id, node_id, content_type, content) VALUES (776, 4082, 1, '{"map": {"width": 532, "height": 532}, "enemy": {"item": "acid gem", "groups": [[{"num": 19, "name": "skeleton warrior"}], [{"num": 20, "name": "skeleton archer"}]], "total_exp": 22}}');
INSERT INTO world.node_content (id, node_id, content_type, content) VALUES (777, 4091, 1, null);
INSERT INTO world.node_content (id, node_id, content_type, content) VALUES (778, 4094, 1, '{"map": {"width": 556, "height": 556}, "enemy": {"item": "acid gem", "groups": [[{"num": 19, "name": "skeleton warrior"}, {"num": 12, "name": "skeleton warrior"}], [{"num": 20, "name": "skeleton archer"}, {"num": 13, "name": "skeleton archer"}]], "total_exp": 44}}');
INSERT INTO world.node_content (id, node_id, content_type, content) VALUES (779, 4095, 1, '{"map": {"width": 593, "height": 593}, "enemy": {"item": "slippery gem", "groups": [[{"num": 19, "name": "skeleton warrior"}, {"num": 33, "name": "skeleton warrior"}], [{"num": 20, "name": "skeleton archer"}, {"num": 34, "name": "skeleton archer"}]], "total_exp": 44}}');
INSERT INTO world.node_content (id, node_id, content_type, content) VALUES (780, 4104, 1, '{"map": {"width": 535, "height": 535}, "enemy": {"item": "acid gem", "groups": [[{"num": 33, "name": "skeleton warrior"}], [{"num": 34, "name": "skeleton archer"}]], "total_exp": 22}}');
INSERT INTO world.node_content (id, node_id, content_type, content) VALUES (781, 4110, 1, '{"map": {"width": 585, "height": 585}, "enemy": {"item": "searching eye", "groups": [[{"num": 33, "name": "skeleton warrior"}, {"num": 12, "name": "skeleton warrior"}], [{"num": 34, "name": "skeleton archer"}]], "total_exp": 32}}');
INSERT INTO world.node_content (id, node_id, content_type, content) VALUES (782, 4111, 1, '{"map": {"width": 560, "height": 560}, "enemy": {"item": "acid gem", "groups": [[{"num": 19, "name": "skeleton warrior"}], [{"num": 20, "name": "skeleton archer"}, {"num": 6, "name": "skeleton archer"}]], "total_exp": 34}}');
INSERT INTO world.node_content (id, node_id, content_type, content) VALUES (783, 4118, 1, '{"map": {"width": 551, "height": 551}, "enemy": {"item": "muddy gem", "groups": [[{"num": 5, "name": "skeleton warrior"}, {"num": 26, "name": "skeleton warrior"}], [{"num": 27, "name": "skeleton archer"}, {"num": 6, "name": "skeleton archer"}]], "total_exp": 44}}');
INSERT INTO world.node_content (id, node_id, content_type, content) VALUES (784, 4123, 1, null);
INSERT INTO world.node_content (id, node_id, content_type, content) VALUES (785, 4150, 1, null);
INSERT INTO world.node_content (id, node_id, content_type, content) VALUES (786, 4152, 1, null);
INSERT INTO world.node_content (id, node_id, content_type, content) VALUES (787, 4155, 1, '{"map": {"width": 577, "height": 577}, "enemy": {"item": "muddy gem", "groups": [[{"num": 33, "name": "skeleton warrior"}, {"num": 19, "name": "skeleton warrior"}], [{"num": 20, "name": "skeleton archer"}]], "total_exp": 32}}');
INSERT INTO world.node_content (id, node_id, content_type, content) VALUES (788, 4156, 1, '{"map": {"width": 555, "height": 555}, "enemy": {"item": "slippery gem", "groups": [[{"num": 5, "name": "skeleton warrior"}], [{"num": 6, "name": "skeleton archer"}, {"num": 13, "name": "skeleton archer"}]], "total_exp": 34}}');
INSERT INTO world.node_content (id, node_id, content_type, content) VALUES (789, 4157, 1, null);
INSERT INTO world.node_content (id, node_id, content_type, content) VALUES (790, 4172, 1, '{"map": {"width": 503, "height": 503}, "enemy": {"item": "muddy gem", "groups": [[{"num": 19, "name": "skeleton warrior"}], [{"num": 20, "name": "skeleton archer"}, {"num": 34, "name": "skeleton archer"}]], "total_exp": 34}}');
INSERT INTO world.node_content (id, node_id, content_type, content) VALUES (791, 4175, 1, null);
INSERT INTO world.node_content (id, node_id, content_type, content) VALUES (792, 4188, 1, '{"map": {"width": 589, "height": 589}, "enemy": {"item": "acid gem", "groups": [[{"num": 26, "name": "skeleton warrior"}], [{"num": 27, "name": "skeleton archer"}, {"num": 13, "name": "skeleton archer"}]], "total_exp": 34}}');
INSERT INTO world.node_content (id, node_id, content_type, content) VALUES (793, 4192, 1, null);
INSERT INTO world.node_content (id, node_id, content_type, content) VALUES (794, 4193, 1, null);
INSERT INTO world.node_content (id, node_id, content_type, content) VALUES (795, 4200, 0, '{"map": {"width": 580, "height": 580}, "enemy": {"item": "strange gem", "groups": [[{"num": 5, "name": "skeleton warrior"}], [{"num": 6, "name": "skeleton archer"}, {"num": 27, "name": "skeleton archer"}]], "total_exp": 34}}');
INSERT INTO world.node_content (id, node_id, content_type, content) VALUES (796, 4202, 1, null);
INSERT INTO world.node_content (id, node_id, content_type, content) VALUES (797, 4211, 1, '{"map": {"width": 571, "height": 571}, "enemy": {"item": "acid gem", "groups": [[{"num": 26, "name": "skeleton warrior"}], [{"num": 27, "name": "skeleton archer"}]], "total_exp": 22}}');
INSERT INTO world.node_content (id, node_id, content_type, content) VALUES (798, 4228, 1, '{"map": {"width": 518, "height": 518}, "enemy": {"item": "jumping stars", "groups": [[{"num": 19, "name": "skeleton warrior"}], [{"num": 20, "name": "skeleton archer"}]], "total_exp": 22}}');
INSERT INTO world.node_content (id, node_id, content_type, content) VALUES (799, 4234, 1, null);
INSERT INTO world.node_content (id, node_id, content_type, content) VALUES (800, 4235, 1, null);
INSERT INTO world.node_content (id, node_id, content_type, content) VALUES (801, 4241, 1, null);
INSERT INTO world.node_content (id, node_id, content_type, content) VALUES (802, 4247, 1, '{"map": {"width": 518, "height": 518}, "enemy": {"item": "strange gem", "groups": [[{"num": 5, "name": "skeleton warrior"}, {"num": 26, "name": "skeleton warrior"}], [{"num": 27, "name": "skeleton archer"}]], "total_exp": 32}}');
INSERT INTO world.node_content (id, node_id, content_type, content) VALUES (803, 4249, 1, '{"map": {"width": 585, "height": 585}, "enemy": {"item": "searching eye", "groups": [[{"num": 5, "name": "skeleton warrior"}], [{"num": 6, "name": "skeleton archer"}, {"num": 13, "name": "skeleton archer"}]], "total_exp": 34}}');
INSERT INTO world.node_content (id, node_id, content_type, content) VALUES (804, 4255, 1, '{"map": {"width": 565, "height": 565}, "enemy": {"item": "searching eye", "groups": [[{"num": 12, "name": "skeleton warrior"}], [{"num": 13, "name": "skeleton archer"}, {"num": 27, "name": "skeleton archer"}]], "total_exp": 34}}');
INSERT INTO world.node_content (id, node_id, content_type, content) VALUES (805, 4268, 1, '{"map": {"width": 571, "height": 571}, "enemy": {"item": "acid gem", "groups": [[{"num": 26, "name": "skeleton warrior"}, {"num": 5, "name": "skeleton warrior"}], [{"num": 27, "name": "skeleton archer"}]], "total_exp": 32}}');
INSERT INTO world.node_content (id, node_id, content_type, content) VALUES (806, 4272, 1, '{"map": {"width": 597, "height": 597}, "enemy": {"item": "acid gem", "groups": [[{"num": 5, "name": "skeleton warrior"}], [{"num": 6, "name": "skeleton archer"}]], "total_exp": 22}}');
INSERT INTO world.node_content (id, node_id, content_type, content) VALUES (807, 4276, 1, null);
INSERT INTO world.node_content (id, node_id, content_type, content) VALUES (808, 4288, 1, null);
INSERT INTO world.node_content (id, node_id, content_type, content) VALUES (809, 4289, 1, null);
INSERT INTO world.node_content (id, node_id, content_type, content) VALUES (810, 4291, 1, null);
INSERT INTO world.node_content (id, node_id, content_type, content) VALUES (811, 4295, 1, null);
INSERT INTO world.node_content (id, node_id, content_type, content) VALUES (812, 4302, 1, null);
INSERT INTO world.node_content (id, node_id, content_type, content) VALUES (813, 4303, 1, null);
INSERT INTO world.node_content (id, node_id, content_type, content) VALUES (814, 4321, 1, '{"map": {"width": 595, "height": 595}, "enemy": {"item": "strange gem", "groups": [[{"num": 19, "name": "skeleton warrior"}], [{"num": 20, "name": "skeleton archer"}]], "total_exp": 22}}');
INSERT INTO world.node_content (id, node_id, content_type, content) VALUES (815, 4327, 0, '{"map": {"width": 560, "height": 560}, "enemy": {"item": "slippery gem", "groups": [[{"num": 12, "name": "skeleton warrior"}, {"num": 19, "name": "skeleton warrior"}], [{"num": 20, "name": "skeleton archer"}, {"num": 13, "name": "skeleton archer"}]], "total_exp": 44}}');
INSERT INTO world.node_content (id, node_id, content_type, content) VALUES (816, 4328, 1, null);
INSERT INTO world.node_content (id, node_id, content_type, content) VALUES (817, 4331, 1, '{"map": {"width": 529, "height": 529}, "enemy": {"item": "muddy gem", "groups": [[{"num": 26, "name": "skeleton warrior"}, {"num": 19, "name": "skeleton warrior"}], [{"num": 20, "name": "skeleton archer"}, {"num": 27, "name": "skeleton archer"}]], "total_exp": 44}}');
INSERT INTO world.node_content (id, node_id, content_type, content) VALUES (818, 4332, 1, '{"map": {"width": 538, "height": 538}, "enemy": {"item": "muddy gem", "groups": [[{"num": 5, "name": "skeleton warrior"}], [{"num": 6, "name": "skeleton archer"}]], "total_exp": 22}}');
INSERT INTO world.node_content (id, node_id, content_type, content) VALUES (819, 4337, 1, null);
INSERT INTO world.node_content (id, node_id, content_type, content) VALUES (820, 4347, 1, '{"map": {"width": 581, "height": 581}, "enemy": {"item": "slippery gem", "groups": [[{"num": 12, "name": "skeleton warrior"}], [{"num": 13, "name": "skeleton archer"}]], "total_exp": 22}}');
INSERT INTO world.node_content (id, node_id, content_type, content) VALUES (821, 4354, 1, null);
INSERT INTO world.node_content (id, node_id, content_type, content) VALUES (822, 4362, 1, null);
INSERT INTO world.node_content (id, node_id, content_type, content) VALUES (823, 4364, 1, null);
INSERT INTO world.node_content (id, node_id, content_type, content) VALUES (824, 4374, 1, '{"map": {"width": 520, "height": 520}, "enemy": {"item": "muddy gem", "groups": [[{"num": 19, "name": "skeleton warrior"}], [{"num": 20, "name": "skeleton archer"}, {"num": 13, "name": "skeleton archer"}]], "total_exp": 34}}');
INSERT INTO world.node_content (id, node_id, content_type, content) VALUES (825, 4378, 1, '{"map": {"width": 528, "height": 528}, "enemy": {"item": "muddy gem", "groups": [[{"num": 33, "name": "skeleton warrior"}, {"num": 26, "name": "skeleton warrior"}], [{"num": 27, "name": "skeleton archer"}, {"num": 34, "name": "skeleton archer"}]], "total_exp": 44}}');
INSERT INTO world.node_content (id, node_id, content_type, content) VALUES (826, 4380, 1, null);
INSERT INTO world.node_content (id, node_id, content_type, content) VALUES (827, 4389, 1, null);
INSERT INTO world.node_content (id, node_id, content_type, content) VALUES (828, 4392, 1, null);
INSERT INTO world.node_content (id, node_id, content_type, content) VALUES (829, 4399, 1, '{"map": {"width": 553, "height": 553}, "enemy": {"item": "slippery gem", "groups": [[{"num": 33, "name": "skeleton warrior"}], [{"num": 34, "name": "skeleton archer"}, {"num": 13, "name": "skeleton archer"}]], "total_exp": 34}}');
INSERT INTO world.node_content (id, node_id, content_type, content) VALUES (830, 4410, 1, null);
