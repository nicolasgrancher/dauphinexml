<?php
function __autoload($className){
	require_once("./resources/classes/".$className.".php");
}
