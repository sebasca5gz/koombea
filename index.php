<?php
session_start();
require_once 'autoload.php';
require_once 'config.php';
$core = new core();
$core->setTitle('Koombea - Prueba técnica');
$core->setModule('home');
$core->load();