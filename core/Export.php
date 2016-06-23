<?php

namespace WpAttachmentExporter;

class Export
{
      protected $repository;
      protected $archive;
      protected $url_home;
      protected $dir_home;
      protected $dir_uploads;
      public $log = [];

      const E_UNLINK = 30;
      const E_WPDB = 31;

      function __construct()
      {
            $this->setPaths();
            $this->repository = $this->cleanQuery( $this->performQuery() );
      }

      public function zip()
      {
            $this->archive = new Zip($this->repository);
            if($this->archive->error) {
                  $this->log('error', $this->archive->error);
                  return false;
            }
            $this->log('success', 0, $this->archive);
            return true;
      }

      public function clean()
      {
            if($this->archive && file_exists($this->archive->file)){
                  if(!unlink($this->archive->file)) $this->log('warning', Export::E_UNLINK);
                  else $this->log('success',1);
                  $this->archive = null;
            }
      }

      protected function performQuery()
      {
            global $wpdb;
            if(!is_object($wpdb)) {
                  $this->log('error',Export::E_WPDB);
                  return false;
            }
            $sql = 'SELECT ID, guid FROM ' . $wpdb->posts . ' WHERE post_type="attachment";';
            return $wpdb->get_results($sql);
      }

      protected function setPaths()
      {
            $a = wp_upload_dir();
            $this->url_home = home_url();
            $this->dir_home = realpath(ABSPATH);
            $this->dir_uploads = realpath($a['basedir']);
      }

      protected function cleanQuery($q)
      {
            if(!$q) return false;
            $a = [];
            foreach ($q as $o) {
                  if(strlen($o->guid)){
                        $o = new File($o->guid, $this->url_home, $this->dir_home, $this->dir_uploads);
                        if($o->error) $this->log('warning', $o->error, $o);
                        else array_push($a, $o);
                  }
            }
            return $a;
      }

      protected function log($type, $code, $arg = false)
      {
            $o = new \stdClass();
            $o->type = $type;
            $o->code = $code;
            $o->message = $this->getLogMessage($code,$arg);
            array_push($this->log, $o);
      }

      protected function getLogMessage($code, $arg)
      {
            switch ($code) {
                  case 0:
                        return 'Zip archive <em>' . $arg->file . '</em> successfully created.';
                        break;
                  case 1:
                        return 'Zip archive was successfully removed.';
                        break;
                  case File::E_NOT_EXISTS:
                        return 'File <em>' . $arg->src . '</em> does not exist. Skipping.';
                        break;
                  case Zip::E_EMPTY:
                        return 'There are no usable files to archive.';
                        break;
                  case Zip::E_NO_OPEN:
                        return 'Unable to write ZIP archive.';
                        break;
                  case Zip::E_NO_SAVE:
                        return 'Unable to save ZIP file.';
                        break;
                  case Zip::E_UNABLE_MKDIR:
                        return 'Unable to create temporary ZIP directory.';
                        break;
                  case Export::E_UNLINK:
                        return 'Unable to clean temporary ZIP directory.';
                        break;
                  case Export::E_WPDB:
                        return 'The Wordpress <em>$wpdb</em> object was not found.';
                        break;
                  default: return false; break;
            }
      }
}