<?php

use Symfony\Component\HttpFoundation\Request;
require_once __DIR__.'/../vendor/autoload.php';

$app = new Silex\Application();

$app['debug'] = true;

$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/../views',
    'twig.date.format' => 'd/m/Y',
    'twig.date.timezone' => 'Europe/Paris'
));

$app->register(new Silex\Provider\DoctrineServiceProvider(), array(
    'db.options' => array(
      'dbname' => 'todolist',
      'user' => 'root',
      'password' => '123',
      'host' => 'localhost',
      'driver' => 'pdo_mysql',
      'charset' => 'utf8mb4',
    ),
));

$app->get('/', function() {
  $html = '<h1>racine</h1>';
  return $html;
});

$app->get('/todos', function() use($app) {
  $sql = 'SELECT * FROM todo';
  $todos = $app['db']->fetchAll($sql);

    return $app['twig']->render('todos.html.twig', [
      'todos' => $todos,
    ]);
});

$app->match('/todo/create', function(Request $request) use ($app){
  $title = $request->get('title');
    return $app['twig']->render('todo-create.html.twig', []);
})->method('GET|POST');

$app->run();
