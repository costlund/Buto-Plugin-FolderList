<?php
class PluginFolderList{
  function __construct() {
    wfPlugin::includeonce('wf/yml');
    wfPlugin::enable('wf/table');
    wfPlugin::includeonce('download/safe');
  }
  private function set_root_dir($data){
    $root_dir = null;
    if($data->get('data/public')){
      $root_dir = wfGlobals::getWebDir();
    }else{
      $root_dir = wfGlobals::getAppDir();
    }
    return $root_dir;
  }
  public function widget_list($data){
    $data = $this->set_data($data);
    /**
     * 
     */
    if(!$data->get('data/tree')){
      /**
       * List
       */
      $widget = new PluginWfYml(__DIR__.'/widget/folder.yml');
      $widget->setByTag($data->get('data'));
    }else{
      /**
       * Tree
       */
      $data = $this->set_data_tree($data);
      $widget = new PluginWfYml(__DIR__.'/widget/tree.yml');
      $widget->setByTag(array('tree_element' => $data->get('data/tree_element')));
    }
    /**
     * 
     */
    wfDocument::renderElement($widget->get());
  }
  public function widget_download($data){
    $data = $this->set_data($data);
    $root_dir = $this->set_root_dir($data);
    /**
     * 
     */
    if(strstr(wfRequest::get('file'), '../')){
      throw new Exception(__CLASS__.' says: Param file error ('.wfRequest::get('file').')!');
    }
    /**
     * 
     */
    if(!$data->get('data/files/'.wfRequest::get('file'))){
      throw new Exception(__CLASS__.' says: File does not exist ('.wfRequest::get('file').')!');
    }
    /**
     * 
     */
    wfUser::setSession('plugin/download/safe/file', $root_dir.$data->get('data/path').'/'.wfRequest::get('file'));
    $download = new PluginDownloadSafe();
    $download->widget_safe();
    exit;
  }
  private function set_data($data){
    $data = new PluginWfArray($data);
    /**
     * Replace dir
     */
    $data->set('data/path', wfSettings::replaceDir($data->get('data/path')));
    /**
     * 
     */
    $root_dir = $this->set_root_dir($data);
    /**
     * Path exist
     */
    $data->set('data/path_exist', wfFilesystem::fileExist($root_dir.$data->get('data/path')));
    /**
     * Files
     */
    $files = wfFilesystem::getScandir($root_dir.$data->get('data/path'));
    $files2 = array();
    foreach ($files as $key => $value) {
      $type = mime_content_type($root_dir.$data->get('data/path').'/'.$value);
      if(filetype($root_dir.$data->get('data/path').'/'.$value)!='file'){
        continue;
      }
      $href = $data->get('data/url').'?file='.$value;
      $name = '<a href="'.$href.'" target="_blank">'.$value.'</a>';
      $files2[$value] = array(
          'name' => $name,
          'href' => $href,
          'size' => round(filesize($root_dir.$data->get('data/path').'/'.$value) / 1000, 2).' kb', 
          'created_at' => date('Y-m-d H:i:s', filemtime($root_dir.$data->get('data/path').'/'.$value)), 
          'type' => $type
          );
    }
    $data->set('data/files', $files2);
    return $data;
  }
  private function set_data_tree($data){
    function get_tree_name($k){
      /**
       * After last underscore.
       */
      $v = substr($k, strrpos($k, '_'));
      /**
       * Remove file extension.
       */
      $v = substr($v, 0,  strrpos($v, '.'));
      /**
       * Replace underscore (if any).
       */
      $v = str_replace('_', '', $v);
      /**
       * Remove sort prefix.
       */
      if(strpos($v, '@')){
        $v = substr($v, strpos($v, '@')+1);
      }
      /**
       * 
       */
      return $v;
    }
    $margin = 20;
    $element = array();
    foreach( $data->get('data/files') as $k => $v ){
      $margin_left = $margin * substr_count($k, '_');
      $element[] = wfDocument::createHtmlElement('a', get_tree_name($k), array(
        'href' => $data->get("data/files/$k/href"),
        'target' => '_blank',
        'style' => "margin-left:".$margin_left."px;display:block"
      ));
    }
    $data->set('data/tree_element', $element);
    return $data;
 }
}
