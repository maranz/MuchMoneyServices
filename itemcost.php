<?php
    class itemcost
    {
         public static function getList($conn, $name, $appid, $vdate, $msg){
            $qry = "call MZ_spITEMCOSTIl ('%s', '%s', '%s');";
            $qry = sprintf($qry, $name, $appid, $vdate);            
            return helperSP::GetResult($conn, $qry, $msg);             
        }
    }
?>