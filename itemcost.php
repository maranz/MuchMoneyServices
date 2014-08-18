<?php
    class itemcost
    {
         public static function getList($conn, $name, $appid, $vdate, $ctype, $msg){
            $qry = "call MZ_spITEMCOSTIl ('%s', '%s', '%s', '%s');";
            $qry = sprintf($qry, $name, $appid, $vdate, $ctype);            
            return helperSP::GetResult($conn, $qry, $msg);             
        }
    }
?>