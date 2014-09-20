<?php
    include "config.php";
    include "helperSP.php";
    include "money.php";
    include "users.php";
    include "years.php";    
    include "itemcost.php";
    include "menu.php";
    include "message.php";
    include "projects.php";
    
    $msg = new message();        
             
    $param = $_GET['param'];
    
    /*
    echo $param;
    exit();
    */ 
      
    if ($param == ''){
        $msg->addError("Parametro 'param' non passato");
        echo $msg->getJSON();
        exit();
    }
    
    $params = json_decode($param, true);
    
    /*
    $action = "years";
    $appid = 'b4b6dff6-eef3-11e3-9555-001a92630969';
    $userid = '2026b34f-db8f-11e3-b734-001a92630969';
    */
    
    /*
    $action = 'itemcosts';
    $userid = '2026b34f-db8f-11e3-b734-001a92630969';
    $appid = 'b4b6dff6-eef3-11e3-9555-001a92630969';    
    
    $action = "itemcosts";
    $appid = "b4b6dff6-eef3-11e3-9555-001a92630969";
    $ctype = "U";
    $projectid = "1967f417-db8f-11e3-b734-001a92630969";
    $userid = "2026b34f-db8f-11e3-b734-001a926";
    */    
        
    $action = $params['action'];
    $userid = $params['userid'];
    $appid = $params['appid'];    
    
    if ($action == ''){        
        $msg->addError("Parametro 'action' non passato");
        echo $msg->getJSON();
        exit();
    }    
     
    if ($userid == ''){        
        $msg->addError("Parametro 'userid' non passato");
        echo $msg->getJSON();
        exit();
    }
    
    if ($appid == ''){        
        $msg->addError("Parametro 'appid' non passato");
        echo $msg->getJSON();
        exit();
    }
    
    try
    {
        $config = new config();
        $conn = new mysqli($config->DB_host, $config->DB_user, $config->DB_password, $config->DB_name);
          
        if (mysqli_connect_errno()) {
            $msg->addError("Errore connessione al DBMS: ".mysqli_connect_error());
            echo $msg->getJSON();
            exit();
        }
        else {
            $users = new users();
            $isValid = $users -> isValid($conn, $userid, $appid, $msg);
            if ($isValid != TRUE){
                $msg->addError("Utente non trovato o non abilitato all'accesso");
                echo $msg->getJSON();
                exit();
            }
            $rows = array();
            switch ($action) {
                case "users":
                    $rows = users::getList($conn, $appid, $msg);
                    break;
                case "years":
                    $rows = years::getList($conn, $appid, $msg);
                    break;
                case "projects":
                    $rows = projects::getList($conn, $userid, $appid, $msg);
                    break;
                case "startmenu":   
                    $list = new menu();
                    $rows = $list->getStartMenu($conn, $appid, $msg);
                    break;
                case "insertmoney":                                        
                    $useridOwner = $params['useridowner'];
                    if ($useridOwner == ''){        
                        $msg->addError("Parametro 'useridOwner' non passato");
                        echo $msg->getJSON();
                        exit();
                    }                  
                    $itemcostname = $params['itemcostname'];
                    if ($itemcostname == ''){        
                        $msg->addError("Parametro 'itemcostname' non passato");
                        echo $msg->getJSON();
                        exit();
                    }                    
                    $vdate = $params['vdate'];
                    if ($vdate == ''){        
                        $msg->addError("Parametro 'vdate' non passato");
                        echo $msg->getJSON();
                        exit();
                    }                    
                    $money = $params['money'];
                    if ($money == ''){        
                        $msg->addError("Parametro 'money' non passato");
                        echo $msg->getJSON();
                        exit();
                    }                    
                    $ownerid = $params['ownerid'];
                    if (trim($ownerid) == ''){
                        $projectid = null;
                    }
                    $ctype = $params['ctype'];
                    if ($ctype == ''){        
                        $msg->addError("Parametro 'ctype' non passato");
                        echo $msg->getJSON();
                        exit();
                    }
                    $rows = money::insert($conn
                                         ,$moneyid
                                         ,$useridOwner
                                         ,$ownerid
                                         ,$itemcostid
                                         ,$itemcostname
                                         ,$ctype
                                         ,$vdate
                                         ,$money
                                         ,$appid
                                         ,$msg);
                break;
                case "itemcosts":                    
                    $name = $params['name'];
                    if (trim($name) == ''){
                        $name = null;
                    }
                    $vdate = $params['vdate'];
                    if (trim($vdate) == ''){
                        $vdate = null;
                    }
                    $ctype = $params['ctype'];
                    if ($ctype == ''){        
                        $msg->addError("Parametro 'ctype' non passato");
                        echo $msg->getJSON();
                        exit();
                    }
                    $projectid = $params['projectid'];
                    if (trim($projectid) == ''){
                        $projectid = null;
                    }
                    $useridOwner = $params['useridowner'];
                    if (trim($useridOwner) == ''){
                        $useridOwner = null;
                    }
                    $rows = itemcost::getList($conn
                                             ,$name
                                             ,$appid                                    
                                             ,$vdate
                                             ,$ctype
                                             ,$useridOwner
                                             ,$projectid
                                             ,$msg);
                break;  
                case 'vismoney':
                    $year = $params['year'];
                    if (trim($year) == ''){
                        $msg->addError("Parametro 'year' non passato");
                        echo $msg->getJSON();
                        exit();
                    }
	                $projectid = $params['projectid'];
                    if (trim($projectid) == ''){
                        $projectid = null;
                    }
                    $rows = money::getList($conn
                                             ,$year
                                             ,$userid                                    
                                             ,$projectid
                                             ,$appid                                             
                                             ,$msg);
	            break;              
            }

            if (count($rows) > 0){                
                $msg->addResult($rows);
            }else{
                $msg->addError("'".$action."'"." not found element");
            }             
            echo $msg->getJSON();
        }
    } catch (Exception $e) {
       $msg->addError('Caught exception: ',  $e->getMessage(), "\n");
       echo $msg->getJSON();
    }    
     
    $conn->close();
?>