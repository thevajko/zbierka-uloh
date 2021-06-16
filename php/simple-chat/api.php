<?php

switch (@$_GET['method']) {

    case 'login':
        break;

    case 'logout':
        break;

    case 'get-messages':
        break;

    case 'post-message':
        break;

    default:
        header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found");
        break;
}