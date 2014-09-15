<?php
    class projects
    {                
        public static function getList($conn, $userid, $appid, $msg){
            $qry = "call MZ_spPROJECTl ('%s', '%s');";
            $qry = sprintf($qry, $userid, $appid);            
            return helperSP::GetResult($conn, $qry, $msg);             
        }  
    } 
?>