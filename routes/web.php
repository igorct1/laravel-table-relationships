<?php

use App\Models\{
    Comment,
    User,
    Preference,
    Course,
    Module,
    Permission,
    Image,
};
use Illuminate\Support\Facades\Route;

Route::get('/one-to-many-polymorphic', function() {
    Course::create(['name' => 'Laravel Relationships',]);
    $course = Course::first();
    // $comment = $course->comments()->create([
    //     'subject' => 'blablablaa',
    //     'content' => 'blablabla2',
    // ]);

    // dd($course->comments);

    $comment = Comment::first();
    dd($comment->commentable->name); // Laravel Relationships
});
Route::get('/one-to-one-polymorphic', function () {
    $user = User::find(1);
    $data = ['path' => 'path/newPath'];
    //deletar
    // $user->image->delete();
    // se já existir alguma foto e o usuario passar novos dados, atualizar
    if($user->image){
        $user->image()->update($data);
    } else { // se não existir, criar
        // $user->image()->save(
        //     new Image(
        //         ['path' => 'path/nome-image.png']),
        // );
        // segunda opção de create, mas a de cima é melhor.
        $user->image()->create(['path' => 'path/nome-image2.png']);
    }
    dd($user->image);
});

Route::get('/many-to-many-pivot', function(){
    $user = User::with('permissions')->find(1);
    //adicionar nova permissão setando um valor false para a coluna active
    // $user->permissions()->attach([
    //     4 => ['active' => false],
    //     3 => ['active' => false],
    // ]);
    // listar todas os pivots
    foreach ($user->permissions as $permission) {
        echo "{$permission->name} <br>";
        // return $permission->pivot->active; // 1
    }
    $user->refresh();
    // return $user->permissions;
});

Route::get('/many-to-many', function(){
    $user = User::with('permissions')->find(1);
    // $permission = Permission::first();
    // $user->permissions()->save($permission);
    // $user->permissions()->saveMany([
    //     Permission::find(1),
    //     // Permission::find(2),
    // ]);
    // $user->permissions()->attach([1]);
    // $user->permissions()->detach([1]);

    $user->refresh();
    dd($user->permissions);
});

Route::get('/one-to-many', function () {
    // $course = Course::create([
    //     'name' => 'Curso de Laravel',
    // ]);

    $course = Course::with('modules.lessons')->first();
    echo $course->name;
    echo "<br>";
    foreach ($course->modules as $module) {
        echo "Module: {$module->name} <br>";
        foreach($module->lessons as $lesson){
            echo "lesson: {$lesson->name} <br>";
            echo "lesson: {$lesson->video} <br>";
        }
    }
    // $data = [
    //     'name' => 'Módulo x1'
    // ];
    // $course->modules()->create($data);
    // $mod1 = Module::find(1);
    // $modules = $course->modules()->get();
    // $mod1->lessons()->create([
    //     'name' => 'Routing and Controller',
    //     'video' => 'youtube.com/laradaily/routing-controller',
    // ]);
    // dd($mod1->lessons);
});

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
    $user->preference->delete();
    dd($user->preference);
});

Route::get('/', function () {
    return view('welcome');
});
