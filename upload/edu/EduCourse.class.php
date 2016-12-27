<?php

/**
 * EduCourse.class.php
 * Author:       Andreas
 * Data:         2015/12/10
 * Description:  Get Course List
 * Input:
 *               sid       Student Id Num
 *               password  Password
 * Output:
 *               code      Status Code
 *               msg       Status Message
 *               data      Student Info Data
 */

include 'EduCore.class.php';

class EduCourse extends EduCore
{
  public function __construct($style = 1){
    $this->sid      =  $_REQUEST['sid'];
    $this->password =  $_REQUEST['password'];
    $this->style    =  $style;
    $this->checkValid();
    if($this->loginStatus == 1) $this->getCourse();
    exit($this->getData());
  }

  private function weekProcess($data){
    //周数解析三种情况：2-12双,13-14、1-8、3
    $weekDataArray = array();
    $weekData = '';
    if(preg_match('/,/', $data)){
      $explodeArray = explode(',', $data);
      foreach($explodeArray as $key => $value){
        if(preg_match('/-/', $value)){
          $weekDataArray = $this->weekXToBProcess($value, $weekDataArray);
        }
        else{
          array_push($weekDataArray, $value);
        }
      }
    }
    else if(preg_match('/-/', $data)){
      $weekDataArray = $this->weekXToBProcess($data, $weekDataArray);
    }
    else{
      array_push($weekDataArray, $data);
    }
    sort($weekDataArray);
    foreach($weekDataArray as $key => $value){
      $weekData = $weekData . $value . ',';
    }
    $weekData = substr($weekData, 0, -1);
    return $weekData;
  }

  private function weekXToBProcess($data, $weekDataArray){
    if(preg_match('/单/', $data)){
      $addNum = 2;
      $data = str_replace('单', '', $data);
    }
    else if(preg_match('/双/', $data)){
      $addNum = 2;
      $data = str_replace('双', '', $data);
    }
    else{
      $addNum = 1;
    }
    $explodeArray = explode('-', $data);
    $weekStart = $explodeArray[0];
    $weekEnd = $explodeArray[1];
    for($i = $weekStart; $i <= $weekEnd; $i += $addNum){
      array_push($weekDataArray, $i);
    }
    return $weekDataArray;
  }

  public function getCourse(){
    $studentData;
    $url                          =  'http://202.197.224.134:8083/jwgl/xk/xk1_kb_gr.jsp?xq=1&xkjc=&xklx=&xn1=2014&xq1=02&xkdl2=';
    $snoopy                       =  new Snoopy();
    $snoopy->rawheaders['Cookie'] =  $this->cookies;
    $snoopy->fetch($url);
    if($snoopy->status != 200) return $this->setMsg(3);
    $data = iconv('gbk', 'utf-8', $snoopy->results);
    $data = str_replace('<table width=100% border=0 cellpadding=0 cellspacing=0>', '<table>', $data);
    $data = str_replace('<td colspan=2>', '<td>', $data);
    for($i=1; $i<=5; $i++){
      for($j=1; $j<=7; $j++){
        $data = preg_replace('/<td valign=top>/', "<td day=$j section=$i>", $data, 1);
      }
    }
    $data = preg_replace('/第(.*?)周/', '第$1周,2节', $data);
    $data = preg_replace('/\((.*?)节\)\s+第(.*?)周,2节/', '第$2周,$1节', $data);
    $data = preg_replace('/\((.*?)节\)第(.*?)周,2节/', '第$2周,$1节', $data);
    $data .= '<td day=1 section=6>';
    // $i: section
    for($i=1; $i<=5; $i++){
      // $j: day
      for($j=1; $j<=7; $j++){
        if($j == 7){
          $nextSection = $i+1;
          $nextday     = 1;
        }
        else{
          $nextSection = $i;
          $nextday     = $j+1;
        }
        preg_match("/<td day=$j section=$i>[\s\S]*<td day=$nextday section=$nextSection>/", $data, $tempData);
        if(isset($tempData[0]))
          if(preg_match_all('/\<table\>\s+\<tr\>\s+\<td\>\s+(.*?)\s+\<\/td\>\s+\<\/tr\>\s+\<tr\>\s+\<td \>\s+(.*?)\s+\<\/td\>\s+\<td \>\s+(.*?)\s+\<\/td\>\s+\<\/tr\>\s+\<tr\>\s+\<td\>\s+第(.*?)周,(.*?)节\s+\<\/td\>\s+\<\/tr\>\s+\<\/table\>/', $tempData[0], $matches)){
            $num = count($matches[0]);
            for($k=0; $k<$num; $k++){
              $studentData[$j][$i][$k]['course']   = urlencode($matches[1][$k]);
              $studentData[$j][$i][$k]['location'] = urlencode($matches[2][$k]);
              $studentData[$j][$i][$k]['teacher']  = urlencode($matches[3][$k]);
              $studentData[$j][$i][$k]['week']     = urlencode($this->weekProcess($matches[4][$k]));
              $studentData[$j][$i][$k]['week_string']     = urlencode('第' . $matches[4][$k] . '周');
              if($this->style == 3)
                $studentData[$j][$i][$k]['day'] = urlencode($j);
              $studentData[$j][$i][$k]['section_start'] = urlencode($i * 2 - 1);
              $studentData[$j][$i][$k]['section_end']   = urlencode($i * 2 - 1 + $matches[5][$k] - 1);
            }
          }
      }
    }
    ksort($studentData);
    if($this->style == 1){
      $this->studentData = $studentData;
    }
    else if($this->style == 2){
      foreach($studentData as $day => $value){
        $i = 0;
        foreach($value as $section => $content){
          $myStudentData[$day][$i] = $content;
          $i++;
        }
      }
      $this->studentData = $myStudentData;
    }
    else if($this->style == 3){
      $i = 0;
      foreach($studentData as $day => $value){
        foreach($value as $section => $content){
          $myStudentData[$i] = $content;
          $i++;
        }
      }
      $this->studentData = $myStudentData;
    }
  }


}

?>