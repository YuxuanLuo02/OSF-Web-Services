<?php

/** @defgroup WsScones SCONES Web Service */
//@{

/*! @file \StructuredDynamics\osf\ws\scones\index.php
    @brief Entry point of a query for the SCONES web service
 */
 
include_once("../../../SplClassLoader.php");   
 
use \StructuredDynamics\osf\ws\scones\Scones;

// Don't display errors to the users. Set it to "On" to see errors for debugging purposes.
ini_set("display_errors", "Off"); 

ini_set("memory_limit", "256M");
set_time_limit(2700);

if ($_SERVER['REQUEST_METHOD'] != 'POST') 
{
  header("HTTP/1.1 405 Method Not Allowed");  
  die;
}

// Interface to use for this query
$interface = "default";

if(isset($_POST['interface']))
{
  $interface = $_POST['interface'];
}

// Version of the requested interface to use for this query
$version = "";

if(isset($_POST['version']))
{
  $version = $_POST['version'];
}

// Document content (in non-binary form)
$document = "";

if(isset($_POST['document']))
{
  $document = $_POST['document'];
}

/* 
  Functionality of the Scones endpoint to use: plain, noun.
*/
$type = "plain";

if(isset($_POST['type']))
{
  $type = $_POST['type'];
}

$stemming = "false";

if(isset($_POST['stemming']))
{
  $stemming = $_POST['stemming'];
}


$ws_scones = new Scones($document, $type, $stemming, $interface, $version);

$ws_scones->ws_conneg((isset($_SERVER['HTTP_ACCEPT']) ? $_SERVER['HTTP_ACCEPT'] : ""), 
                      (isset($_SERVER['HTTP_ACCEPT_CHARSET']) ? $_SERVER['HTTP_ACCEPT_CHARSET'] : ""), 
                      (isset($_SERVER['HTTP_ACCEPT_ENCODING']) ? $_SERVER['HTTP_ACCEPT_ENCODING'] : ""), 
                      (isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) ? $_SERVER['HTTP_ACCEPT_LANGUAGE'] : "")); 

$ws_scones->process();

$ws_scones->ws_respond($ws_scones->ws_serialize());

//@}

?>