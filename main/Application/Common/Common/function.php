<?php
function _uid_mask($userId){
    return "u{$userId}";
}

function list_dir_fileName($dir){
  $array=array();
  //1、先打开要操作的目录，并用一个变量指向它
  //打开当前目录下的目录pic下的子目录common。
  $handler = opendir($dir);
  //2、循环的读取目录下的所有文件
  /*其中$filename = readdir($handler)是每次循环的时候将读取的文件名赋值给$filename，为了不陷于死循环，所以还要让$filename !== false。一定要用!==，因为如果某个文件名如果叫’0′，或者某些被系统认为是代表false，用!=就会停止循环*/
  while( ($filename = readdir($handler)) !== false ) 
  {
     // 3、目录下都会有两个文件，名字为’.'和‘..’，不要对他们进行操作
     if($filename != '.' && $filename != '..')
     {
        // 4、进行处理
         array_push($array,$filename);
     }
  }
  //5、关闭目录
  closedir($handler);
  return $array;
 }