<?php 
date_default_timezone_set ( 'Asia/Shanghai' );
set_include_path ( '/var/www/maildelivery' . PATH_SEPARATOR . '/var/www/maildelivery/library' . PATH_SEPARATOR . '/var/www/maildelivery/app/models/' . PATH_SEPARATOR . get_include_path ());
//Set Zend Framework  load class automatically
require_once 'Zend/Loader/Autoloader.php';
Zend_Loader_Autoloader::getInstance ()->setFallbackAutoloader ( true );
Zend_Session::start ();

$registry = Zend_Registry::getInstance ();

$basic = new Zend_Config_Ini ( '/var/www/maildelivery/app/config/basic.ini', null, true );
Zend_Registry::set ( 'basic', $basic );
$dbAdapter = Zend_Db::factory ( $basic->general->db->adapter, $basic->general->db->toArray () );
$dbAdapter->query ( "SET NAMES {$basic->general->db->charset}" );
Zend_Db_Table::setDefaultAdapter ( $dbAdapter );
Zend_Registry::set ( 'dbAdapter', $dbAdapter );
Zend_Registry::set ( 'db_username', $basic->general->db->username );
Zend_Registry::set ( 'db_password', $basic->general->db->password );
Zend_Registry::set ( 'db_dbname', $basic->general->db->dbname );
Zend_Registry::set ( 'dbprefix', $basic->general->db->prefix );
exec("ps -ef|grep ExecutionPlan.php|grep -v grep", $return_array, $status);
$running_count = count($return_array);
if ($running_count > 1) {
    exit;
}
$date=date('Y-m-d H:i:s',time());
$where=" checkpass = 1 or checkpass = 3 and draft = 0  and sendtype = 2";		
$select_cycle1 = "select id,cycle_type,cycle_time,cycle_end_time from mr_task where ".$where." and cycle_type = 1";
$result_cycle1 = $dbAdapter->fetchAll($select_cycle1);

$select_cycle2 = "select id,cycle_type,cycle_week,cycle_time,cycle_end_time from mr_task where ".$where." and cycle_type = 2";
$result_cycle2 = $dbAdapter->fetchAll($select_cycle2);
            
$select_cycle3 = "select id,cycle_type,cycle_month,cycle_time,cycle_end_time from mr_task where ".$where." and cycle_type = 3";
$result_cycle3 = $dbAdapter->fetchAll($select_cycle3);
//cycle
//everyday
foreach($result_cycle1 as $val1){
    $today1=date('Y-m-d',time()).' '.$val1['cycle_time'];
    if($val1['cycle_end_time'] != '0000-00-00' && $today1 >= $date){
        if($val1['status'] != 2){
            $rows=$dbAdapter->update('mr_task',array('status' => 2,'sendtime' => $today1),'id='.$val1['id']);
        }else{
            $rows=$dbAdapter->update('mr_task',array('sendtime' => $today1),'id='.$val1['id']);
        }
    }else{
        if($val1['cycle_end_time'] == '0000-00-00' && $today1 >= $date){
            $rows=$dbAdapter->update('mr_task',array('sendtime' => $today1),'id='.$val1['id']);
        }else{
            $rows=$dbAdapter->update('mr_task',array('sendtime' => ''),'id='.$val1['id']);
        }
    }
}

//week
foreach($result_cycle2 as $val2){
    $w=date('w',time());
    if($w == 0){
        $w =7;
    }
/* 			if($w == '0'){
        $week = '67';
    }elseif($w == '6'){
        $week = '15';
    }else{
        $week=$w;
    } */
    $today2=date('Y-m-d',time()).' '.$val2['cycle_time'];
    if($val2['cycle_end_time'] != '0000-00-00' && $today2 >= $date){
/* 				if($week == $val2['cycle_week']){
            if($val2['status'] != 2){
                $rows=$dbAdapter->update('mr_task',array('status' => 2,'sendtime' => $today2),'id='.$val2['id']);
            }else{
                $rows=$dbAdapter->update('mr_task',array('sendtime' => $today2),'id='.$val2['id']);
            }
        }else{
            $rows=$dbAdapter->update('mr_task',array('sendtime' => ''),'id='.$val2['id']);
        } */
        if($w == 6 or $w == 7){
            if($val2['cycle_week'] == $w or $val2['cycle_week'] == 67){
                if($val2['status'] != 2){
                    $rows=$dbAdapter->update('mr_task',array('status' => 2,'sendtime' => $today2),'id='.$val2['id']);
                }else{
                    $rows=$dbAdapter->update('mr_task',array('sendtime' => $today2),'id='.$val2['id']);
                }
            }
        }else{
            if($val2['cycle_week'] == $w or $val2['cycle_week'] == 15){
                if($val2['status'] != 2){
                    $rows=$dbAdapter->update('mr_task',array('status' => 2,'sendtime' => $today2),'id='.$val2['id']);
                }else{
                    $rows=$dbAdapter->update('mr_task',array('sendtime' => $today2),'id='.$val2['id']);
                }
            }
        }
        if($rows == 1){
            return true;
        }
    }else{
        if($val2['cycle_end_time'] == '0000-00-00' && $today2 >= $date){
            $rows=$dbAdapter->update('mr_task',array('sendtime' => $today2),'id='.$val2['id']);
        }else{
            $rows=$dbAdapter->update('mr_task',array('sendtime' => ''),'id='.$val2['id']);
        }
        if($rows == 1){
            return true;
        }
    }
}
//month
foreach($result_cycle3 as $val3){
    $month=date('j',time());
    $today3=date('Y-m-d',time()).' '.$val3['cycle_time'];
    //$last_month=date('t',strtotime('last month',$date));
    $last_month=date('t');
    if($month == $last_month){
        switch ($last_month) {
            case '28':
                $month = $val3['cycle_month'];
                break;
            case '29':
                $month = $val3['cycle_month'];
                break;
            case '30':
                $month = $val3['cycle_month'];
                break;
            case '31':
                $month = $val3['cycle_month'];
                break;
                
        }
    }
    
    if($val3['cycle_end_time'] != '' && $today3 >= $date){
        if($month == $val3['cycle_month']){
            if($val3['status'] != 2){
                $rows=$dbAdapter->update('mr_task',array('status' => 2,'sendtime' => $today3),'id='.$val3['id']);
            }else{
                $rows=$dbAdapter->update('mr_task',array('sendtime' => $today3),'id='.$val3['id']);
            }
        }else{
            $rows=$dbAdapter->update('mr_task',array('sendtime' => ''),'id='.$val3['id']);
        }
        if($rows == 1){
            return true;
        } 					
    }else{
        if($val3['cycle_end_time'] == '' && $today3 >= $date){
            $rows=$dbAdapter->update('mr_task',array('sendtime' => $today3),'id='.$val3['id']);
        }else{
            $rows=$dbAdapter->update('mr_task',array('sendtime' => ''),'id='.$val3['id']);
        }
        if($rows == 1){
            return true;
        }
    }
}