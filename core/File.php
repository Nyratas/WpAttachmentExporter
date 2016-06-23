<?php

namespace WpAttachmentExport;

class File
{
      public $name;
      public $src;
      public $dir;
      public $guid;
      public $error = false;

      const E_NOT_EXISTS = 10;

      function __construct($url, $url_home, $dir_home, $dir_uploads)
      {
            $this->guid = $url;
            $this->src = $this->getSrc($url_home, $dir_home);
            $this->extractFile($dir_uploads);
            $this->error = $this->checkFile();
      }

      public function getLocal()
      {
            if(strlen($this->dir)) return $this->dir . DIRECTORY_SEPARATOR . $this->name;
            return $this->name;
      }

      protected function getSrc($url, $dir)
      {
            return $this->cleanDir(str_replace($url, $dir, $this->guid));
      }

      protected function cleanDir($s)
      {
            return str_replace('/', DIRECTORY_SEPARATOR, $s);
      }

      protected function extractFile($dir)
      {
            $this->dir = '';
            $a = explode(DIRECTORY_SEPARATOR, str_replace($dir, null, $this->src));
            foreach ($a as $i => $str) {
                  if($i == count($a) - 1) $this->name = $str;
                  elseif(strlen($str)){
                        if(strlen($this->dir)) $this->dir .= DIRECTORY_SEPARATOR;
                        $this->dir .= $str;
                  }
            }
      }

      protected function checkFile()
      {
            if(!file_exists($this->src)) return File::E_NOT_EXISTS;
            return false;
      }
}