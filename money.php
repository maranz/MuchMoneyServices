<?php
    class money
    {
        public static function insert($conn
                                     ,&$moneyid
                                     ,$userid
                                     ,$groupid
                                     ,$itemcostid
                                     ,$itemcostname
                                     ,$vdata
                                     ,$money
                                     ,$appid
                                     ,$msg){                                                     
            $grpid = null;
            if (!empty($groupid)){
                $grpid = "'".$groupid."'";
            }
			else{
				$grpid = "null";
			};
            $qry = "call MZ_spMONEYi (
                @MONEYID,
                '%s',
                 %s, 
                @ITEMCOSTID,  
                '%s',
                '%s',
                 %s,
                '%s', 
                @ERR
            );";
            $qry = sprintf($qry,  $userid, $grpid, $itemcostname, $vdata, $money, $appid);            
            helperSP::GetResult($conn, $qry, $msg);            
            $row = helperSP::GetResult($conn, "SELECT @MONEYID as MONEYID, @ITEMCOSTID as ITEMCOSTID, "."'".$itemcostname."' as ITEMCOSTNAME", $msg);
            return $row;
            /*         
            if (!($res = $conn->query("SELECT @MONEYID as MONEYID"))) {                              
               $msg->addError("Fetch failed: (" . $conn->errno . ") " . $conn->error);
               return false;               
            };         
            $row = $res->fetch_assoc();
            $mnyID = $row["MONEYID"];
            if (!empty($MONEYID)){
                $moneyid = $mnyID; 
            }
            return $result;             
            */
        }
    }
?>