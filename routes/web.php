<?php

use App\Models\{
    User,
    Preference,
};
use Illuminate\Support\Facades\Route;

Route::get('/one-to-one', function(){
    $user = User::with('preference')->first();
    $data = [
        'background_color' => '#fff',
    ];
    if($user->preference){
        $user->preference->update($data);
    }else {
        $user->preference()->create($data);
        // $preference = new Preference($data);
        // $user->preference()->save($preference);
    }
    $user->refresh();
    //deletar preferencia
    $user->preference->delete(['background_color']);
    dd($user->preference);
});


Route::get('/', function () {
    return view('welcome');
});
