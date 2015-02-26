<?php
define('DS', DIRECTORY_SEPARATOR);
define('ROOT', dirname(dirname(__FILE__)));

require_once (ROOT . DS . 'bootstrap.php');


use Doctrine\MongoDB\Connection;
use Doctrine\ODM\MongoDB\Configuration;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Mapping\Driver\AnnotationDriver;
use Documents\Game;
use Documents\User;
use Controllers\GameUpdateController;
use Controllers\QueryController;



$connection = new Connection();

$config = new Configuration();
$config->setProxyDir(__DIR__ . '/Proxies');
$config->setProxyNamespace('Proxies');
$config->setHydratorDir(__DIR__ . '/Hydrators');
$config->setHydratorNamespace('Hydrators');
$config->setDefaultDB('nogoodgames');
$config->setMetadataDriverImpl(AnnotationDriver::create(ROOT . DS .'app/model'));

AnnotationDriver::registerAnnotationClasses();

$dm = DocumentManager::create($connection, $config);
/*
$request = get_profile('WAR-666-MACHINE');
$data = get_profiledata_psnprofiles($request['body']);
$results = fetch_profiledata_psnprofiles($data);
$games_to_like = parse_profiledata_psnfprofiles($results);
var_dump($games_to_like);*/

$page = 1001;
$leaderboard = get_leaderboard($page);
//var_dump($leaderboard);
$users = parse_leaderboard($leaderboard['body']);
var_dump($users);

$page_start = 1001;
$page_finished = 1101;

for ($i = $page_start; $i <= $page_finished; $i++ ) {
    $leaderboard = get_leaderboard($i);
    if ($leaderboard['code'] != 200) {
        echo 'leaderboard page number '.$i.' has not been fetched with code '.$leaderboard['code'];
    } else {
        $users = parse_leaderboard($leaderboard['body']);
        foreach ($users as $user) {
            $request = get_profile($user);
            if ($request['code'] != 200){
                echo 'player ' .$user.' has not been fetched with code '.$request['code'];
            } else {
                //$player = new User();
                $data = get_profiledata_psnprofiles($request['body']);
                $results = fetch_profiledata_psnprofiles($data);
                $games_to_like = parse_profiledata_psnfprofiles($results);
                foreach ($games_to_like as $game_like) {
                    $game = new Game();
                    $finder = new QueryController($dm);

                    $query = $finder->findOneItem($game,'name',$game_like);

                    //make_like($player, $game);
                }

            }

        }
    }

}

//巴莎


?>