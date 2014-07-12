<?php    
    include "config.php";
    include "helperSP.php";
    include "moneyout.php";
    include "users.php";
    include "itemcost.php";
    include "menu.php";
    include "message.php";    
    
    $action = $_REQUEST['action'];
    $userid = $_REQUEST['userid'];
    $appid = $_REQUEST['appid'];
   
    $msg = new message();
     
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
                case "startmenu":                    
                    $list = new menu();
                    $rows = $list->getStartMenu($conn, $appid, $msg);
                    break;
                case "insertmoneyout":
                    $useridOwner = $_REQUEST['useridowner'];
                    if ($useridOwner == ''){        
                        $msg->addError("Parametro 'useridOwner' non passato");    
                        echo $msg->getJSON();
                        exit();
                    }
                    $itemcostname = $_REQUEST['itemcostname'];
                    if ($itemcostname == ''){        
                        $msg->addError("Parametro 'itemcostname' non passato");    
                        echo $msg->getJSON();
                        exit();
                    }
                    $vdate = $_REQUEST['vdate'];
                    if ($vdate == ''){        
                        $msg->addError("Parametro 'vdate' non passato");    
                        echo $msg->getJSON();
                        exit();
                    }
                    $money = $_REQUEST['money'];
                    if ($money == ''){        
                        $msg->addError("Parametro 'money' non passato");    
                        echo $msg->getJSON();
                        exit();
                    }
                    $groupid = $_REQUEST['groupid'];
                    if (trim($groupid) == ''){
                        $groupid = null;
                    }
                    $rows = moneyout::insert($conn
                                            ,$moneyid
                                            ,$useridOwner
                                            ,$groupid
                                            ,$itemcostname
                                            ,$vdate
                                            ,$money
                                            ,$appid
                                            ,$msg);
                break;
                case "itemcosts":
                    $name = $_REQUEST['name'];
                    $vdate = $_REQUEST['vdate'];
                    
                    $name="";
                    $vdate="";
                    
                    $rows = itemcost::getList($conn
                                             ,$name
                                             ,$appid                                    
                                             ,$vdate
                                             ,$msg
                                             ); 
                break;
            }
            
            if (count($rows) > 0){
                header("Content-type:application/json");
                echo json_encode($rows);
            }
            else {
                $msg->addError("not found element");
                echo $msg->getJSON();
            }    
        }
    } catch (Exception $e) {
       $msg->addError('Caught exception: ',  $e->getMessage(), "\n");
       echo $msg->getJSON();
    }    
     
    $conn->close();
?>