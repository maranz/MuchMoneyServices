<?php
    class users
    {                
        public static function getList($conn, $appid, $msg){
            $qry = "call MZ_spUSERSl ('%s');";
            $qry = sprintf($qry,  $appid);            
            return helperSP::GetResult($conn, $qry, $msg);             
        }
        
        public function isValid($conn, $userid, $appid){
            $qry = "
select count(*) 
from MZ_USERS u 
inner join MZ_APPUSER a on 
    a.appid = '%s'
where u.USERID = '%s'             
and u.USERID = a.USERID;
";
            $qry = sprintf($qry, $appid, $userid);
            $result = $conn->query($qry);
            $rows = array();        
            if($result->num_rows >0) {                
                while($row = $result->fetch_array(MYSQLI_NUM)) {
                   $rows[] = $row;               
                }   
                $count = $rows[0][0]; 
                return ($count > 0); 
            }
            return FALSE;
        }
        
        public function getListUsersGrops($conn){
            $qry = "
select GROUPID
,NAME
,'group' as TYPE
from MZ_GROUP
where CDATE is null

union all

select USERID
,USER
,'user' as TYPE 
from MZ_USERS
where CDATE is null;
";
            $result = $conn->query($qry);
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