<?php

class Config_test {
  var $db = array(
    // using globals
    /*'host' => '127.0.0.1',
    'user' => 'logparse',
    'pass' => '',
    'base' => 'logparse'*/
  );
  var $debug = true;
  var $libs = array(
    
  );
}

class Config_prod {
  var $libs = Config_test::libs;
}

define ('HOST','127.0.0.1');
define ('USER','logparse');
define ('BASE','logparse');
define ('PASS','');