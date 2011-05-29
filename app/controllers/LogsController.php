<?php
class LogsController extends Controller {
  
  function index(){
    
  }
  
  function before(){
    $this->loadModel('IP');
    $this->loadModel('URL');
    $this->loadModel('Request');
  }
  
  function parseLog(){
    static $months = array(
            'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 
            'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec');
       
    foreach ($this->models as $model) {
     // $model->truncate();
    }
    
    $file = "/Users/kix/access_log_sample";

    $handle = fopen($file, 'r');
    $pattern = '/(\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}) - - \[(\d{2})\/(\w+)\/(\d{4}):(\d{2}):(\d{2}):(\d{2}) \+(\d{2})(\d{2})\] \"(\w+) ([^ ]*) (.*)\" (\d{3}) (\d*)/';

    $items = array();
    while ($line = fgets($handle)) {
      $matches = array();
      preg_match_all($pattern, $line, $matches);
      
      $url = explode('?', $matches[11][0]);
     
      $time = mktime((int)$matches[5][0]-(int)$matches[8][0], 
                         (int)$matches[6][0]-(int)$matches[9][0], 
                         (int)$matches[7][0], array_search($matches[3][0], $months), 
                         (int)$matches[2][0], 
                         (int)$matches[4][0]);
            
      $ip_id = $this->IP->put(array('ip'=>$matches[1][0]));
      $url_id = $this->URL->put(array('url'=>$url[0]));
      
      $get = (isset($url[1])) ? $url[1] : false;
           
      $request = array('time'=>$time, 'ip_id'=>$ip_id, 'url_id'=>$url_id, 'get_params'=> $get, 'length'=>$matches[14][0], 'code'=>$matches[13][0]);
      
      $items[] = $request;
      
      $this->Request->put($request);
    }
  }
}