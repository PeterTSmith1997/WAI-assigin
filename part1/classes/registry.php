<?php
/**
 * Create an abstract Registry
 *
 * @author John Rooksby (based on work by Rob Davis)
 * @version 8.1
 *  
 */ 
Abstract Class Registry {
    private function __construct() {}
    abstract protected function get($key);
    abstract protected function set($key, $value);
}
?>
