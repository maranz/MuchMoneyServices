<?php
    class menu
    { 
        public function getStartMenu($conn, $appid, $msg){
            $qry = "call MZ_spMENUl ('%s');";
            $qry = sprintf($qry,  $appid);            
            return helperSP::GetResult($conn, $qry, $msg);
        }
    }
?>