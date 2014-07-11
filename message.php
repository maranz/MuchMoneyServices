<?php
    class message
    {
        public $list; 
        
         function __construct() {
             $list = array();
         }
        
        public function addError($msg)
        {
            $item = array();
            $item['error'] = true;
            $item['msg'] = $msg;
            $this->list[] = $item;
        }
        
        public function getJSON()
        {
            return json_encode($this->list);
        }
    }
?>