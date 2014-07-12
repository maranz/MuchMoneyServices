<?php
    class helperSP {
        public static function Error ($conn, $msg){
            if (!($res = $conn->query("SELECT @ERR as ERR"))) {               
               $msg->addError("Fetch failed: (" . $conn->errno . ") " . $conn->error);
               return false;
            };         
            $row = $res->fetch_assoc();
            $ERR = $row["ERR"];
            if (!empty($ERR)){
                $msg->addError($ERR);
                return false;
            }
            return true;
        }
        
        public static function GetResultAndNext($conn, $qry, $msg){
            $result = $conn->query($qry); 
            if ($result === false) {                
                $msg->addError("CALL failed: (" . $conn->errno . ") " . $conn->error);
                return array();               
            };                                                
            $rows = array();                    
            if($result->num_rows >0) {                
                while($row = $result->fetch_array(MYSQLI_NUM)) {
                   $rows[] = $row;               
                }   
            }
            $result->close();
            $conn->next_result();
            return $rows;
        }
        
        public static function GetResult($conn, $qry, $msg){
            $result = $conn->query($qry); 
            if ($result === false) {                
                $msg->addError("CALL failed: (" . $conn->errno . ") " . $conn->error);
                return array();               
            };                                                
            $rows = array();                    
            if($result->num_rows >0) {                
                while($row = $result->fetch_array(MYSQLI_NUM)) {
                   $rows[] = $row;               
                }   
            }                      
            return $rows;
        }
    }
?>