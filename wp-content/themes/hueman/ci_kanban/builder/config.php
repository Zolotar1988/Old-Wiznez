<?php
# Show errors
error_reporting(E_ALL);
ini_set('display_errors', '1');
define('BASEPATH', 'system');



# Include files
include('./functions.php');



# Initialize
$config             = new stdClass();



# Version of the framework
$config->version    = 'v5';



# The components desired for the build
$config->desiredComponents  = array(
    'account',
    'connections',
    'frontpage',
    'search',
    'profile',
    'listings',
    'dashboard',
);



# The different views for components which shall have routes attached
$config->componentViews                     = array();
$config->componentViews['account']          = array(null => 'logout', null => 'manage');
$config->componentViews['connections']      = array();
$config->componentViews['frontpage']        = array();
$config->componentViews['search']           = array(null => 'users', null => 'listings');
$config->componentViews['profile']          = array('(:num)' => 'index', null => 'edit');
$config->componentViews['listings']         = array('(:num)' => 'index', null => 'create', '(:num)' => 'apply', null => 'applied', null => 'applications', null => 'invitations');
$config->componentViews['dashboard']        = array(null => 'index');



# The components desired for the build
$config->desiredModules  = array(
//    'dashboard' => ''
);