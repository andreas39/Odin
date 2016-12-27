<?php

/**
 * EduCore.class.php
 * Author:       Andreas
 * Data:         2015/12/10
 * Description:  Edu. Query Core
 * Input:
 *         sid       Student Id Num
 *         password  Password
 * Output:
 *         code      Status Code
 *         msg       Status Message
 *         data      Student Info Data
 */

include 'Snoopy.class.php';

class EduCore
{
  public    $loginStatus;
  protected $code;
  protected $msg;
  protected $cookies;
  protected $sid;
  protected $password;
  protected $identity    = 'student';
  protected $role        = 1;
  protected $checkUrl    = 'http://202.197.224.134:8083/jwgl/logincheck.jsp';
  protected $infoUrl     = 'http://202.197.224.134:8083/jwgl/index1.jsp';
  protected $studentData;

  public function __construct($sid = '', $password = ''){
    $this->sid      = $sid;
    $this->password = $password;
    $this->checkValid();
  }

  public function getData(){
    $jsonArray = array(
      'code' => $this->code,
      'msg'  => urlencode($this->msg),
      'data' => $this->studentData
    );
    $jsonData = json_encode($jsonArray);
    $jsonData = urldecode($jsonData);
    return $jsonData;
  }

  protected function setMsg($retVal){
    $retValToMsg = array(
      0 => '成功',
      1 => '密码错误',
      2 => '超时',
      3 => '网络故障',
      4 => '未知错误',
      65535 => '缺失参数'
    );
    $this->code = $retVal;
    $this->msg  = $retValToMsg[$retVal];
    return $this->code;
  }

  protected function checkValid(){
    if($this->sid == '' || $this->password == '') return $this->setMsg(65535);
    $snoopy    = new Snoopy();
    $postArray = array(
      'username' => $this->sid,
      'password' => $this->password,
      'identity' => 'student',
      'role'     => 1
    );
    $snoopy->submit($this->checkUrl, $postArray);
    if($snoopy->status != 200) return $this->setMsg(3);
    if($this->processCookies($snoopy->headers) != 0) return 0;
    $snoopy->rawheaders['Cookie'] = $this->cookies;
    $data = iconv('gbk', 'utf-8', $snoopy->results);
    if($this->checkIfError($data) != 0) return 0;
    if($this->getName($snoopy, $postArray) != 0) return 0;
  }

  protected function processCookies($data){
    preg_match('/JSESSIONID=([\w]+);/', $data[2], $sessionid);
    if(isset($sessionid[1])){
      $this->cookies = 'JSESSIONID=' . $sessionid[1] . ';';
      return 0;
    }
    else{
      return $this->setMsg(3);
    }
  }

  protected function checkIfError($data){
    if(preg_match('/用户名或密码错误/',$data)){
      return $this->setMsg(1);
    }
    else if(preg_match('/超时/',$data)){
      return $this->setMsg(2);
    }
    else if(preg_match('/跳转/',$data)){
      //OK
      return 0;
    }
    else{
      return $this->setMsg(4);
    }
  }

  protected function getName(&$snoopy, $postArray){
    $snoopy->submit($this->infoUrl, $postArray);
    if($snoopy->status != 200) return $this->setMsg(3);
    $data = iconv('gbk', 'utf-8', $snoopy->results);
    preg_match('/<font color=red>(.*?)同学/', $data, $name);
    if(isset($name[1]) && $name[1] != ''){
      $this->name         = $name[1];
      $this->loginStatus  = 1;
      return $this->setMsg(0);
    }
    else{
      return $this->setMsg(3);
    }
  }
}

?>