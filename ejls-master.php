<?php
/**
 * @package Structured Data of JSON-LD
 * @version 3.0-dev
 */
/*
Plugin Name: Structured Data of JSON-LD
Plugin URI: http://wordpress.org/plugins/ejls-easy-json-ld-setter/
Description: Set Structured Data of "JSON-LD" to your WebSite.schema type that you can use is "Article","Person","WebSite" and "searchAction".
Author: Hidetaka Okamoto
Version: 3.0
Author URI: http://wp-kyoto.net/
*/

ejls_master();

function ejls_master(){
  add_action('wp_footer','ejls_insert_jsonld');
}

function ejls_insert_jsonld(){
  $Ejls_Cont = new EJLS_JSONLD_Content;
  $jsonld = $Ejls_Cont->get_jsonld();
  $html = ejls_make_html($jsonld);
  ejls_insert_html($html);
}

function ejls_insert_html($html){
  echo $html;
}

function ejls_make_html($jsonld){
  $html  = "<script type='application/ld+json'>";
  $html .= $jsonld;
  $html .= "</script>";
  return $html;
}

/**
 * Root Object of JSON-LD Content
 */
class EJLS_JSONLD_Content
{
  public $contentArray;

  function __construct(){
    $this->_set_jsonld();
  }

  private function _set_jsonld(){
    $this->contentArray = array(
      "@type"      => "Sample",
      "properties" => "dummy",
    );
  }

  public function get_jsonld(){
    return json_encode($this->contentArray, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT);
  }
}
