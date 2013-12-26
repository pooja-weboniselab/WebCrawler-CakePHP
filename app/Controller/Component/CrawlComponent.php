<?php
/**
 * Created by JetBrains PhpStorm.
 * User: webonise
 * Date: 18/12/13
 * Time: 12:58 PM
 * To change this template use File | Settings | File Templates.
 */
App::uses('Component', 'Controller');

class CrawlComponent extends Component {

    public function fetchLink($url){
        $ch = curl_init();
        $timeout = 5;
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        $html = curl_exec($ch);
        //pr($html);
        curl_close($ch);
        $dom = new DOMDocument();
        # The @ before the method call suppresses any warnings that
        # loadHTML might throw because of invalid HTML in the page.
         @$dom->loadHTML($html);
        return $dom ;
    }

     public function domQuery($url,$regex){
         $dom_document = $this->fetchLink($url);
         $dom_xpath = new DOMXpath($dom_document);
         $content = $dom_xpath->query($regex);
         return $content ;
     }


}