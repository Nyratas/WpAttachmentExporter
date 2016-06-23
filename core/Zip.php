<?php

namespace WpAttachmentExporter;

class Zip
{
      protected $zip;
      protected $dir = 'tmp';
      public $file = 'WpAttachmentExport.zip';
      public $src;
      public $error = false;

      const E_EMPTY = 20;
      const E_NO_OPEN = 21;
      const E_NO_SAVE = 22;
      const E_UNABLE_MKDIR = 23;

      function __construct($repository)
      {
            if(!count($repository)) $this->error = Zip::E_EMPTY;
            $this->makeDir();
            $this->makeFile();
            $this->addBatch($repository);
            $this->close();
      }

      protected function addBatch($a)
      {
            if($this->error) return false;
            foreach ($a as $file) {
                  $this->zip->addFile($file->src, $file->getLocal());
            }
      }

      protected function close()
      {
            if($this->zip){
                  $this->zip->close();
                  if(!file_exists($this->src)){
                        $this->error = Zip::E_NO_SAVE;
                        return false;
                  }
                  return true;
            }
            return false;
      }

      protected function makeDir()
      {
            $dir = realpath(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR);
            $this->dir = $dir . DIRECTORY_SEPARATOR . $this->dir;
            if(!is_dir($this->dir)){
                  if(!mkdir($this->dir, 0777)) $this->error = Zip::E_UNABLE_MKDIR;
            }
            $this->src = realpath($this->dir) . DIRECTORY_SEPARATOR . $this->file;
      }

      protected function makeFile()
      {
            if($this->error) return false;
            $this->zip = new \ZipArchive;
            if($this->zip->open($this->src, file_exists($this->src) ? \ZIPARCHIVE::OVERWRITE : \ZIPARCHIVE::CREATE) !== true) $this->error = Zip::E_NO_OPEN;
      }
}