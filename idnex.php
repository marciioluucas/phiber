<?php
/**
 * Created by PhpStorm.
 * User: marci
 * Date: 13/04/2017
 * Time: 13:59
 */

require_once 'test/Usuario.php';
require 'Phiber.php';
include_once 'PhiberAutoload.php';
$u = new Usuario();
print_r(Phiber::openPersist()->update($u,["nome"=>"marcio"]));


