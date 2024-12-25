select game_data.equip_detail_list.equip_class, game_data.equip_detail_list.equip_type, count(*) from game_data.item_list
join game_data.equip_detail_list on game_data.item_list.id = game_data.equip_detail_list.item_list_id
group by game_data.equip_detail_list.equip_class, game_data.equip_detail_list.equip_type
