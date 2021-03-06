<?php

/** @defgroup WsCrud Crud Web Service */
//@{

/*! @file \StructuredDynamics\osf\ws\crud\read\index.php
    @brief Entry point of a query for the Crud Read web service
 */

// Auto-load classes
include_once("../../../../SplClassLoader.php"); 
 
use \StructuredDynamics\osf\ws\crud\read\CrudRead;

// Don't display errors to the users. Set it to "On" to see errors for debugging purposes.
ini_set("display_errors", "Off"); 

ini_set("memory_limit", "64M");

// Interface to use for this query
$interface = "default";

if(isset($_GET['interface']))
{
  $interface = $_GET['interface'];
}
elseif(isset($_POST['interface']))
{
  $interface = $_POST['interface'];
}      

// Version of the requested interface to use for this query
$version = "";

if(isset($_GET['version']))
{
  $version = $_GET['version'];
}
elseif(isset($_POST['version']))
{
  $version = $_POST['version'];
}

// URI of the resource to get its description
$uri = "";

if(isset($_GET['uri']))
{
  $uri = $_GET['uri'];
}
elseif(isset($_POST['uri']))
{
  $uri = $_POST['uri'];
}

// URI of the crud to get the description of
$dataset = "";

if(isset($_GET['dataset']))
{
  $dataset = $_GET['dataset'];
}
elseif(isset($_POST['dataset']))
{
  $dataset = $_POST['dataset'];
}

// Include the reference of the resources that links to this resource
$include_linksback = "";

if(isset($_GET['include_linksback']))
{
  $include_linksback = $_GET['include_linksback'];
}
elseif(isset($_POST['include_linksback']))
{
  $include_linksback = $_POST['include_linksback'];
}

// Language of the record to return
$lang = "en";

if(isset($_GET['lang']))
{
  $lang = $_GET['lang'];
}
elseif(isset($_POST['lang']))
{
  $lang = $_POST['lang'];
}

// Include the reference of the resources that links to this resource
$include_reification = "";

if(isset($_GET['include_reification']))
{
  $include_reification = $_GET['include_reification'];
}
elseif(isset($_POST['include_reification']))
{
  $include_reification = $_POST['include_reification'];
}

// Include attribute/values of the attributes defined in this list
$include_attributes_list = "";

if(isset($_GET['include_attributes_list']))
{
  $include_attributes_list = $_GET['include_attributes_list'];
}
elseif(isset($_POST['include_attributes_list']))
{
  $include_attributes_list = $_POST['include_attributes_list'];
}        

$ws_cr = new CrudRead($uri, $dataset, $include_linksback, $include_reification, 
                      $include_attributes_list, $interface, $version, $lang);

$ws_cr->ws_conneg((isset($_SERVER['HTTP_ACCEPT']) ? $_SERVER['HTTP_ACCEPT'] : ""), 
                  (isset($_SERVER['HTTP_ACCEPT_CHARSET']) ? $_SERVER['HTTP_ACCEPT_CHARSET'] : ""), 
                  (isset($_SERVER['HTTP_ACCEPT_ENCODING']) ? $_SERVER['HTTP_ACCEPT_ENCODING'] : ""), 
                  (isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) ? $_SERVER['HTTP_ACCEPT_LANGUAGE'] : "")); 

$ws_cr->process();

$ws_cr->ws_respond($ws_cr->ws_serialize());

//@}

?>