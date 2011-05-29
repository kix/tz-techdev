<?php
class RequestsController extends Controller {
  
  var $perPage = 50;

  function before(){
    $this->pages = ($this->Request->count() / $this->perPage) + 1;
    $this->request = $_REQUEST;
  }
  
  function after(){
    
  }
  
  function page($pageNum){
    $this->page = $pageNum - 1;
    $this->index();
  }
  
  function index(){
    $count = $this->Request->count();
  
    $this->Request->paginate($this->perPage, $this->page);
    $this->from = $this->page * $this->perPage + 1;
    $this->to = ($count < $this->perPage * ($this->page + 1)) ? $count : $this->from + $this->perPage;
    $this->requests = $this->Request->get();
    
    $this->render();
  }
}