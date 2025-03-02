<?php

require_once dirname(__FILE__) . '/../../../videos/configuration.php';

class Live_restreams_logs extends ObjectYPT {

    protected $id,$restreamer,$m3u8,$logFile,$json,$live_transmitions_history_id,$live_restreams_id;
    
    static function getSearchFieldsNames() {
        return array('restreamer','m3u8','logFile','json');
    }

    static function getTableName() {
        return 'live_restreams_logs';
    }    
     
    function setId($id) {
        $this->id = intval($id);
    } 
 
    function setRestreamer($restreamer) {
        if(!isValidURL($restreamer)){
            return false;
        }
        $this->restreamer = $restreamer;
    } 
 
    function setM3u8($m3u8) {
        if(!isValidURL($m3u8)){
            return false;
        }
        $this->m3u8 = $m3u8;
    } 
 
    function setLogFile($logFile) {
        $logFile = basename($logFile);
        $this->logFile = $logFile;
    } 
 
    function setJson($json) {
        $this->json = $json;
    } 
 
    function setLive_transmitions_history_id($live_transmitions_history_id) {
        $this->live_transmitions_history_id = intval($live_transmitions_history_id);
    } 
 
    function setLive_restreams_id($live_restreams_id) {
        $this->live_restreams_id = intval($live_restreams_id);
    } 
    
     
    function getId() {
        return intval($this->id);
    }  
 
    function getRestreamer() {
        return $this->restreamer;
    }  
 
    function getM3u8() {
        return $this->m3u8;
    }  
 
    function getLogFile() {
        return $this->logFile;
    }  
 
    function getJson() {
        return $this->json;
    }  
 
    function getLive_transmitions_history_id() {
        return intval($this->live_transmitions_history_id);
    }  
 
    function getLive_restreams_id() {
        return intval($this->live_restreams_id);
    }  

        
    static function getLatest($live_transmitions_history_id, $live_restreams_id){
        global $global;
        
        if (!static::isTableInstalled()) {
            return false;
        }
        
        global $global;
        $sql = "SELECT * FROM " . static::getTableName() . " WHERE  live_transmitions_history_id = ? AND live_restreams_id = ? ORDER BY id DESC LIMIT 1";
        // I had to add this because the about from customize plugin was not loading on the about page http://127.0.0.1/AVideo/about
        $res = sqlDAL::readSql($sql, 'ii', array($live_transmitions_history_id, $live_restreams_id));
        $data = sqlDAL::fetchAssoc($res);
        sqlDAL::close($res);
        if ($res) {
            $row = $data;
        } else {
            $row = false;
        }
        return $row;
        
    }
    
    static function getLogURL($live_restreams_logs_id){
        $rlog = new Live_restreams_logs($live_restreams_logs_id);
        
        $restreamer = $rlog->getRestreamer();
        if(!isValidURL($restreamer)){
            return false;
        }
        
        $url = $restreamer;
        $url = addQueryStringParameter($url, 'logFile', $rlog->getLogFile());
        
        return $url;
    }
    
    static function getToken($action, $live_restreams_logs_id){
        $obj = new stdClass();
        $obj->action = $action;
        $obj->live_restreams_logs_id = $live_restreams_logs_id;
        $obj->time = time();
        
        $string = encryptString(json_encode($obj));
        return $string;
    }
    
    static function verifyToken($token, $secondsValid = 3600){
        $string = decryptString($token);
        if(!empty($string)){
            $obj = json_decode($string);
            if(!empty($obj)){
                if($obj->time > strtotime("-{$secondsValid} seconds")){
                    return $obj;
                }
            }
        }
        return false;
    }
    
}
