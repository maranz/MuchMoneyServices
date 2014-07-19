<?php
    class message
    {
        public $errors;
        public $result; 
        
        function __construct() {
             $errors = array();
             $result = array();
        }
        
        public function addError($msg)
        {
            $item = array();
            $item['error'] = true;
            $item['msg'] = $msg;
            $this->errors[] = $item;
        }
        
        public function addResult($rows)
        {
            $this->result = $rows;
        }
        
        public function getJSON()
        {
            $list = array();
            if (count($this->errors) > 0){
                $list = $this->errors;
            }
            else {
                $list = $this->result;
            }       
            header("Content-Type: application/json; charset=utf-8");
            $json = json_encode($list);
            return isset($_GET['callback']) ? $_GET['callback'].'('.$json.')' : $json;
        }
    }
?>