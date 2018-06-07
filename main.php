<?php
/**
 * Created by PhpStorm.
 * User: ZuperZero
 * Date: 6/7/2018
 * Time: 07:46 PM
 */

if (isset($_GET['bar'])){
    $test = $_GET['bar'];
    echo "$test";
}
else{
    echo "error";
}
?>