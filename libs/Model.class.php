<?php
/**
 * A generic model class
 * TODO: Method chaining, return self (e.g. Model->get()->paginate(10))
 */
class Model {
  var $name;
  var $tableName;
  var $dbo;
  
  var $hasAndBelongsToMany;
  
  /**
   * Creates a model instance
   * If given an ID, should really be ActiveRecord-like
   * Can use a different DBO than the whole app
   * @param integer $id 
   * @param DBO $dbo 
   */
  public function __construct($id=False, $dbo=False){
    $this->name = get_class($this);
    $this->tableName = get_class($this).'s';
    $this->dbo = new DBO_MySQL();
    $dboFields = $this->dbo->describe($this->tableName);
        
    if (!is_array($dboFields) || count($dboFields) == 0) {
      die("DB table $this->tableName has no fields or does not exist. " .print_r($dboFields, TRUE));
    } // TODO: Brutal death!
    
    $i = 0;
    
    foreach ($dboFields as $name=>$type) {
      if (!isset($this->fields[$name])){
        $this->fields[$name] = array(
            'Name' => $name,
            'Type' => $type['Type']
        );
        $this->fields[$name]['Null'] = (isset($type['Null'])) ? $type['Null'] : FALSE;
        ++$i;
      }
      if (!isset($this->fields[$name]['Desc'])){
        $this->fields[$name]['Desc'] = $name;
      }
    }
  }
  
  /* TODO: work to do
  public function putHABTM($modelName, $foreignId, $thisId){
    //$foreignModel = new $modelName();
    
    $tableName = $this->name .'s_'.$modelName.'s';
    $data = array(
        $modelName.'_id'  =>$foreignId,
        $this->name.'_id' =>$thisId
        );
    return $this->dbo->insert($tableName, $data);
  }
  
  public function getHABTM($modelName, $thisId){
    //$foreignModel = new $modelName();
    
    $tableName = $this->name .'s_'. $modelName.'s';
    $data = array(
        $this->name.'_id' =>$thisId
        );
    return $this->dbo->get($tableName, $data);
  }
  
  public function getBTM($modelName, $foreignId){
    $model = new $modelName();
    return $this->dbo->join($model->tableName, $this->tableName, $foreignId);
  }
  
  public function dropHABTM($modelName, $thisId){
    //$foreignModel = new $modelName();
    
    $tableName = $this->name .'s_'. $modelName.'s';
    $data = array(
      $this->name.'_id'   =>$thisId
      );
    return $this->dbo->drop($tableName, $data);
  }*/
  
  public function get($fields = Null){
    return $this->dbo->get($this->tableName, $fields); 
  }
  
  /**
   * Dirty pagination. Pagination should really be a chainable method, need to 
   * re-write model
   * @param type $perPage
   * @param type $pageNum 
   */
  public function paginate($perPage, $pageNum = 0){
    $this->dbo->paginate($perPage, $pageNum);
  }  
  
  public function validate($data){
    $ret = array();
    foreach($this->fields as $field){
      $name = $field['Name'];
      if(isset($data[$name])){
        $validationResult = Validator::field($field, $data[$name]);
        if ($validationResult) {
          $ret[$name] = $validationResult;
        }
      } elseif ($field['Type'] == 'file'
              ||$field['Type'] == 'id') {
        
      } else {
        $ret[$name] = 'Field not filled';
      }
      unset($data[$name]);
    }
    foreach ($data as $field=>$value){

    }
    if (count($ret) == 0) {
      return False;
    }
    return $ret;
  }
  
  public function truncate(){
    return $this->dbo->truncate($this->tableName);
  }
  
  public function put(array $fields){
    return $this->dbo->put($this->tableName, $fields);
  }
  
  public function drop(array $fields){
    return $this->dbo->drop($this->tableName, $fields);
  }
  
  public function count($fields = False){
    return $this->dbo->count($this->tableName, $fields);
  }
}
