<?php
    class users
    {                
        public static function getList($conn, $appid, $msg){
            $qry = "call MZ_spUSERSl ('%s');";
            $qry = sprintf($qry,  $appid);            
            return helperSP::GetResult($conn, $qry, $msg);             
        }
        
        public function isValid($conn, $userid, $appid, $msg){            
            $qry = "call MZ_spUSERSc ('%s', '%s');";
            $qry = sprintf($qry, $userid, $appid);            
            $rows = helperSP::GetResultAndNext($conn, $qry, $msg);
            if (count($rows) > 0){
                $count = $rows[0][0]; 
                return ($count > 0); 
            }
            return FALSE;
        }
    } 
?>