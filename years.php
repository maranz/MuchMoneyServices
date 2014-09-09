<?php
    class years
    {                
        public static function getList($conn, $appid, $msg){
            $qry = "call MZ_spYEARSl ('%s');";
            $qry = sprintf($qry,  $appid);            
            return helperSP::GetResult($conn, $qry, $msg);             
        }  
    } 
?>