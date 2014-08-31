<?php
    class itemcost
    {
         public static function getList($conn, $name, $appid, $vdate, $ctype, $userid, $projectid, $msg){
            $qry = "call MZ_spITEMCOSTIl ('%s', '%s', '%s', '%s', '%s', '%s');";
            $qry = sprintf($qry, $name, $appid, $vdate, $ctype, $userid, $projectid);            
            return helperSP::GetResult($conn, $qry, $msg);             
        }
    }
?>