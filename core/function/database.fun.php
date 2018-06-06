<?php

# ======================================================================================================= #

/**
 * Function wrap for static class method
 * Connect to DB
 *
 */

 function dbConnect() {
     $config = getConfig('database');

     if (!empty($config['host']) && !empty($config['user']) && !empty($config['password']) && !empty($config['database'])) {
         \App\M\Database::connect($config['host'], $config['user'], $config['password'], $config['database']);
     } else {
         debug('No Database config');
         die();
     }
 }

 /**
  * Function wrap for static class method
  * Close connection to DB
  *
  */

 function dbClose() {
      \App\M\Database::close();
      exit();
 }

 /**
  * Function wrap for static class method
  * Select few rows
  *
  */

 function dbArray($query, $parms = array()) {
     return \App\M\Database::getArray($query, $parms);
 }

 /**
  * Function wrap for static class method
  * Select one row
  *
  */

 function dbRow($query, $parms = array()) {
     return \App\M\Database::getRow($query, $parms);
 }

 /**
  * Function wrap for static class method
  * Select one value from one column
  *
  */

 function dbOne($query, $parms = array()) {
     return \App\M\Database::getOne($query, $parms);
 }

 /**
  * Function wrap for static class method
  * Quotes a string for use in a query
  *
  */

 function dbQuote($string) {
     return \App\M\Database::quote($string);
 }
