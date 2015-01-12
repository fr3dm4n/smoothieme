<?php
/**
 * User: Alfred Feldmeyer
 * Date: 12.01.2015
 * Time: 19:25
 */

// Define path to application directory
defined('APPLICATION_PATH')
|| define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../application'));

// Define application environment
defined('APPLICATION_ENV')
|| define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));


$stage_clear=0; //incrementiert mit jedem erfolgreichem test

//Ganz bewusst wird hier auf Zend verzichtet
$configs=parse_ini_file("../application/configs/application.ini");
$fruitDir=realpath($configs["backend.fruitpic.path"]);
$tmpDir=realpath($configs["backend.tmpdir.path"]);
//Db-Settings
$dbuser=$configs["resources.db.params.username"];
$dbhost=$configs["resources.db.params.host"];
$dbpw=$configs["resources.db.params.password"];
$dbname=$configs["resources.db.params.dbname"];
$dbport=$configs["resources.db.params.port"];

?>
<!doctype html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Installer</title>
    <script type="text/javascript" src="/js/dist/vendors.min.js"></script>
    <link href="/css/bootstrap.css" media="screen" rel="stylesheet" type="text/css">
    <style>
        body{
            padding-bottom: 200px
        }
        .scrollspy {
            position: relative;
            max-width: 600px;

            margin: 0 auto;
        }

        h2 {
            padding: 60px 0 0 0
        }

    </style>
    <script>
        $(document).ready(function () {
            $('body').scrollspy({target: '.sm-navbar-scrollspy',offset:60})
        });
    </script>

</head>
<body data-spy="scroll" data-target=".sm-navbar-scrollspy">
<nav id="navbar-example2" class="navbar navbar-default navbar-static navbar-fixed-top">
    <div class="container-fluid">
        <div class="navbar-header">
            <button class="navbar-toggle collapsed" type="button" data-toggle="collapse" data-target=".bs-example-js-navbar-scrollspy">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#">Smoothieme-Installer</a>
        </div>
        <div class="collapse navbar-collapse sm-navbar-scrollspy">
            <ul class="nav navbar-nav">
                <li class=""><a href="#one">1. techn. Umgebung</a></li>
                <li class=""><a href="#two">2. DB-Zugang</a></li>
                <li class=""><a href="#three">3. Logins</a></li>
                <li class=""><a href="#four">4. Cleanup</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="scrollspy">
    <h2 id="one">1. Überprüfung technischen Umgebung</h2>
    <?php if (!extension_loaded('imagick') && !class_exists("Imagick")) { ?>
        <div class="alert alert-danger" role="alert"><span class="glyphicon glyphicon-remove"></span> IMagick ist nicht installiert</div>
    <?php }else{ ?>
        <div class="alert alert-success" role="alert"><span class="glyphicon glyphicon-ok"></span> IMagick ist installiert</div>
    <?php } ?>

    <?php if (!extension_loaded('pdo') && !class_exists("PDO")) { ?>
        <div class="alert alert-danger" role="alert"><span class="glyphicon glyphicon-remove"></span> Pdo ist nicht installiert</div>
    <?php }else{ ?>
        <div class="alert alert-success" role="alert"><span class="glyphicon glyphicon-ok"></span> Pdo ist installiert</div>
    <?php } ?>

    <?php if (!defined('PDO::MYSQL_ATTR_LOCAL_INFILE')) { ?>
        <div class="alert alert-danger" role="alert"><span class="glyphicon glyphicon-remove"></span> Pdo_mysql ist nicht installiert</div>
    <?php }else{ ?>
        <div class="alert alert-success" role="alert"><span class="glyphicon glyphicon-ok"></span> Pdo_mysql ist installiert</div>
    <?php } ?>

    <?php if (!is_writeable($tmpDir)) { ?>
        <div class="alert alert-danger" role="alert"><span class="glyphicon glyphicon-remove"></span> <?php echo $tmpDir?> ist nicht beschreibbar</div>
    <?php }else{ ?>
        <div class="alert alert-success" role="alert"><span class="glyphicon glyphicon-ok"></span> <?php echo $tmpDir?> ist beschreibbar</div>
    <?php } ?>

    <?php if (!is_writeable($fruitDir)) { ?>
        <div class="alert alert-danger" role="alert"><span class="glyphicon glyphicon-remove"></span> <?php echo $fruitDir?> ist nicht beschreibbar</div>
    <?php }else{ ?>
        <div class="alert alert-success" role="alert"><span class="glyphicon glyphicon-ok"></span> <?php echo $fruitDir?> ist beschreibbar</div>
    <?php } ?>


    <a href="javascript:location.reload()" type="button" class="pull-right btn btn-info" aria-expanded="false">Erneut Prüfen
        <span class="glyphicon glyphicon-refresh"></span></a>
    <?php
    $stage_clear++;
    ?>
    <h2 id="two">2. Datenbank Zugang</h2>
    <?php
    if($stage_clear>0){
        try{
            $dbh = new pdo( 'mysql:host='.$dbhost.';port='.$dbport.';dbname='.$dbname,
                $dbuser,
                $dbpw,
                array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
        ?>
            <div class="alert alert-success" role="alert"><span class="glyphicon glyphicon-ok"></span> Verbindung zu Datenbank</div>
    <?php
        } catch(PDOException $ex){
            var_dump($ex)
            ?>
            <div class="alert alert-danger" role="alert"><span class="glyphicon glyphicon-remove"></span> Keine Verbindung zu Datenbank <?php echo $dbname ?></div>
            <div class="alert alert-info" role="alert">Bitte passen Sie die Einstellungen unter application/configs/application.ini an</div>

        <?php
        }
    }
    ?>
    <a href="javascript:location.reload()" type="button" class="pull-right btn btn-info" aria-expanded="false">Erneut Prüfen<span class="glyphicon glyphicon-refresh"></span></a>
    <h2 id="three">3. Admin-Login </h2>

    <p>
        Der voreingestellte Admin-Zugang ist
    </p>
    <table class="table" style="width: 200px; margin: auto">
        <tr>
            <td class="text-right"><b>Benutzer</b></td>
            <td>admin</td>
        </tr>
        <tr>
            <td  class="text-right"><b>Passwort</b></td>
            <td>123</td>
        </tr>
    </table>

    <h2 id="four">4. Cleanup </h2>

    <div class="alert alert-warning" role="alert">Bitte vergessen Sie nicht diese Datei aus dem Verzeichnis public/ zu entfernen!!!</div>

</div>

</body>
</html>


