<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
define('DS', DIRECTORY_SEPARATOR);
define('ROOT', dirname(dirname(__FILE__)));

require_once (ROOT . DS . 'bootstrap.php');


use Doctrine\MongoDB\Connection;
use Doctrine\ODM\MongoDB\Configuration;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Mapping\Driver\AnnotationDriver;
use Documents\Game;
use Documents\User;
use Views\UserView;
use Documents\Assessment;
use Controllers\GameUpdateController;
use Controllers\QueryController;
use Controllers\AssessmentUpdateController;
use Controllers\UserUpdateController;
use Controllers\UserViewController;
use Controllers\PredictionController;

define("KEY", "i9Cdde75L4q7ToM5L2vurkVp1uqZg20NXGHC8Hu8rNQijozvPuBh1ndTcNhdmH54");
define("ML_SERVER_ADR", "localhost");



$connection = new Connection();

$config = new Configuration();
$config->setProxyDir(ROOT.DS.'app/Proxies');
$config->setProxyNamespace('Proxies');
$config->setHydratorDir(ROOT.DS.'app/Hydrators');
$config->setHydratorNamespace('Hydrators');
$config->setDefaultDB('nogoodgames');
$config->setMetadataDriverImpl(AnnotationDriver::create(ROOT . DS .'app/model'));

AnnotationDriver::registerAnnotationClasses();

session_start();
?>

<!DOCTYPE html>
<html xml:lang="en" lang="en">

<head>
    <title>NoGoodGamez - let ai recommend you PS3/PS4 games you would like</title>
    <link rel="stylesheet" type="text/css" href="css/main.css">
    <meta charset="UTF-8">

</head>
<!-- This website is built using prediction.io, doctrine mongo odm, Jtinder, pls see my github for sc-->
<body>


<header>
    <hgroup>
        <h1>NoGoodGamez</h1>
        <h2>let ai recommend you PS3/PS4 games based on games you've liked before</h2>
    </hgroup>
    <p class="Byline">press like <strong id="liker">&#x2715</strong> or dislike <strong id="disliker">&#x025CB</strong> buttons below to assess the game</p>
</header>

<div class="wrap">


    <div id="tinderslide">
        <ul id="gamelist">
<?php

$current_ssid = session_id();

$prophet = new PredictionController(KEY,
    'http://'.ML_SERVER_ADR.':7070',
    'http://'.ML_SERVER_ADR.':8000');

$dm = DocumentManager::create($connection, $config);
$user = new User();
$query = new QueryController($dm);
$response = $query->findOneItem($user,'session',$current_ssid);
$view = new UserView();
$game = new Game();
$assmnt = new Assessment();


if($response === NULL){
    $updater = new UserUpdateController($user);
    $updater->updateRealUser($current_ssid);
    $dm->persist($user);
    $dm->flush();
    $cont = new UserViewController($user, $view, $game, $assmnt, $prophet);
    echo $cont->generateNewUserView($dm);
} else {
    $cont = new UserViewController($user, $view, $game, $assmnt, $prophet);
    echo $cont->generateExistingUserView($dm, "pane1");
}


?>
</ul>
        </div>
    </div>

    <div class="actions">
        <a href="#" class="dislike"><i>&#x025CB</i></a>
        <a href="#" class="like"><i>&#x2715</i></a>
    </div>

    <div>
        <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
        <!-- isnogood -->
        <ins class="adsbygoogle"
             style="display:block"
             data-ad-client="ca-pub-2595161812671986"
             data-ad-slot="6327134752"
             data-ad-format="auto"></ins>
        <script>
            (adsbygoogle = window.adsbygoogle || []).push({});
        </script>
    </div>
    <div id="FatFooter">

        <aside>
            <div class="LeftFloatBox">
                <img src="img/PredictionIO-logo.png">
            </div>

            <div class="LeftFloatBox">
                Built with <a href="http://prediction.io">Prediction.io</a> technology
            </div>
        </aside>
        <aside>

            <div class="RightFloatBox">
                <img src="img/gamespot.png">
            </div>

            <div class="RightFloatBox">
                Built using <a href="http://gamespot.com">gamespot</a> data
            </div>
        </aside>

        <footer>
            <p>Created by pashadude a.k.a. パシャ尺八
                &nbsp; &bull; &nbsp; Copyright © 2015
                &nbsp; &bull; &nbsp;

            </p>
        </footer>
    </div>



    <script type="text/javascript" src="js/jquery.min.js"></script>
    <script type="text/javascript" src="js/jquery.transform2d.js"></script>
    <script type="text/javascript" src="js/jquery.jTinder.js"></script>
    <script type="text/javascript" src="js/main.js"></script>
    <!-- Go to www.addthis.com/dashboard to customize your tools -->
    <script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-551068b619c7f381" async="async"></script>

</body>
</html>
