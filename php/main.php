<?php
session_start();

require('../../data/config.php');
require('lib/medoo.min.php');

$database = new medoo([
    'database_type' => 'mysql',
    'database_name' => 'bvswdebate',
    'server' => SERVER_IP,
    'username' => SERVER_USER,
    'password' => SERVER_PASS]);

require('util.php');

require('pages/home.php');
require('pages/profile.php');
require('pages/tournaments.php');
require('pages/signin.php');
require('pages/members.php');
require('pages/error.php');

if(!array_key_exists('loggedin', $_SESSION)) {
    $_SESSION['loggedin'] = false;
}

if(array_key_exists('page', $_POST)) {
    switch($_POST['page']) {
        case 'home':
            get_home();
            break;
        case 'profile':
            if($_SESSION['loggedin']) {
                get_profile(false);
            } else {
                get_error(403);
            }
            break;
        case 'tournaments':
            get_tournaments();
            break;
        case 'members':
            if($_SESSION['loggedin']) {
                get_members();
            } else {
                get_error(403);
            }
            break;
        case 'signin':
            get_sign_in();
            break;
        case 'signout':
            session_unset();
            session_destroy();
            $_SESSION['loggedin'] = false;
            echo 'refresh';
            break;
        default:
            if($_POST['page'] != '') {
                get_error(404);
            } else {
                get_home();
            }
            break;
    }
} else if(array_key_exists('util', $_POST)) {
    switch($_POST['util']) {
        case 'add_tournament':
            Util::add_tournament($_POST['attr']['name'], $_POST['attr']['date'],
                                $_POST['attr']['location']);
            break;
        case 'name':
            echo Util::getUser($_SESSION['email'])[$_POST['attr']['type']];
            break;
        case 'sign_in':
            Util::sign_in($_POST['attr']['email'], $_POST['attr']['pass']);
            break;
        case 'add_user':
            Util::add_user($_POST['attr']);
            break;
        case 'sign_out':
            session_unset();
            session_destroy();
            $_SESSION['loggedin'] = false;
            break;
        case 'logged_in':
            Util::logged_in();
            break;
        default:
            echo '-1'; // failure
            break;
    }
}

?>