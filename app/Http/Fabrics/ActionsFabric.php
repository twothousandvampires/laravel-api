<?php
namespace App\Http\Fabrics;

class ActionsFabric
{
    static private $actions_map = [
        'torch' => 'App\Http\Actions\Character\TorchAction',
        'rest' => 'App\Http\Actions\Character\RestAction',
        'create_item' => 'App\Http\Actions\Item\CreateItemAction',   
        'login' => 'App\Http\Actions\User\LoginAction', 
        'user' => 'App\Http\Actions\User\GetUserAction',
        'logout' => 'App\Http\Actions\User\LogoutAction',
        'registration' => 'App\Http\Actions\User\RegistrationAction',
        'delete_character' => 'App\Http\Actions\Character\DeleteCharacterAction',
        'create_character' => 'App\Http\Actions\Character\CreateCharacter',
        'change_items' => 'App\Http\Actions\Item\ChangeItemsAction',
        'set_started' => 'App\Http\Actions\Character\SetStarted',
        'move' => 'App\Http\Actions\Character\MoveAction',
        'win' => 'App\Http\Actions\Character\WinAction',
        'retreat' => 'App\Http\Actions\Character\RetreatAction',
        'set' => 'App\Http\Actions\Character\SetAction',
        'unlock_passives' => 'App\Http\Actions\Skill\UnlockPassivesAction',
        'learn_passive' => 'App\Http\Actions\Skill\LearnPassiveAction',
        'upgrade_passive' => 'App\Http\Actions\Skill\UpgradePassiveAction',
        'get_items_list' => 'App\Http\Actions\Item\GetItemsList',
        'delete_item' => 'App\Http\Actions\Item\DeleteItem',
        'delete_all_items' => 'App\Http\Actions\Item\DeleteAll',
        'use_item' => 'App\Http\Actions\Item\UseItem',
        'upgrade_item_quality' => 'App\Http\Actions\Craft\UpgradeQuality',
        'upgrade_effect' => 'App\Http\Actions\Craft\UpgradeEffect',
        'add_property' => 'App\Http\Actions\Craft\AddProperty',
        'synthesis' => 'App\Http\Actions\Craft\Synthesis',
        'disassemble' => 'App\Http\Actions\Craft\Disassemble',
        'learn_random_skill' => 'App\Http\Actions\Skill\LearnRandomSkill',
        'learn_skill' => 'App\Http\Actions\Skill\LearnSkill',
        'get_item_skills' => 'App\Http\Actions\Skill\GetSkillForItem',
        'upgrade_random_skill' => 'App\Http\Actions\Skill\UpgradeRandomSkill',
        'upgrade_skill' => 'App\Http\Actions\Skill\UpgradeSkill',
    ];

    static public function createAction($action_name)
    {
        if(isset(ActionsFabric::$actions_map[$action_name])){
            return new ActionsFabric::$actions_map[$action_name]();
        }
        else{
            return false;
        }
    }
}
