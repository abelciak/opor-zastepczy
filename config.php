<?php 
ini_set('display_errors', 0);
//Konfiguracja sesji w PHP
if(!isset($_SESSION)) 
    { 
        session_start(); 
	}	 
session_cache_limiter("must-revalidate");

mysql_connect("localhost","user","pass");
mysql_select_db("db");
mysql_query ('SET NAMES utf8');
?>