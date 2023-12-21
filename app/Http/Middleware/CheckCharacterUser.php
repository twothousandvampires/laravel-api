<?php

namespace App\Http\Middleware;

use App\Models\Character;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class CheckCharacterUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     */
    public function handle(Request $request, Closure $next)
    {

        $user_id = Character::find($request->route('char_id'))->user_id;

        if($user_id == Auth::user()->id){
            return $next($request);
        }
        return new JsonResource(['msg'=> 'hui']);
    }
}
