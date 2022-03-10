<?php

/** @var \Laravel\Lumen\Routing\Router $router */

use App\Http\Controllers\BookController;

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();

});

$router->group(['prefix' =>'/v1',], function() use($router) {

    $router->get('/books', 'BookController@index');
    $router->get('/books/{id}','BookController@findOne');
    $router->post('/books', 'BookController@create');
    $router->put('/books/{id}', 'BookController@update');

    $router->get('/authors', 'AuthorController@index');
    $router->get('/authors/{id}', 'AuthorController@findOne');
    $router->post('/authors', 'AuthorController@create');
    $router->put('/authors/{id}', 'AuthorController@update');

    $router->get('/characters', 'CharacterController@index');
    $router->get('/characters/{id}', 'CharacterController@findOne');
    $router->post('/characters', 'CharacterController@create');
    $router->put('/characters/{id}', 'CharacterController@update');

    $router->get('/users', 'UserController@index');
    $router->get('/users/{id}', 'UserController@findOne');
    $router->post('/users', 'UserController@create');
    $router->put('/users/{id}', 'UserController@update');

    $router->get('/comments', 'CommentController@index');
    $router->get('/comments/{id}', 'CommentController@findOne');
    $router->post('/comments', 'CommentController@create');
    $router->put('/comments/{id}', 'CommentController@update');
});
