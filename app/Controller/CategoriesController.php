<?php
/**
 * Created by JetBrains PhpStorm.
 * User: webonise
 * Date: 13/12/13
 * Time: 12:29 PM
 * To change this template use File | Settings | File Templates.
 */

class CategoriesController extends AppController {
    public $helpers = array ('Html' , 'Form','Session');
    public $component = array('Session');
    public function index() {

        if($this->request->is('post')) {
            $website = $this->request->data;
            //$linkList = array();
           // echo $url['WebCrawl']['url'];
            $url = $website['WebCrawl']['url'];
            if (!preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i",$url))
            {
                echo "Invalid URL";

            }
            else{
                 $linkList =   $this->getLinks($url);
                 //pr($linkList);
                 $deleteLink = array('67','133'.'134','135','136','137','138','139','140');
                 $categoryList = $this->arrayClean($linkList,$deleteLink );
                    //pr($categoryList);
                    foreach($categoryList as $val){

                        $value = parse_url($val);
                       // var_dump(explode('/',trim($value['path'],"/")));

                        $newArray = explode('/',trim($value['path'],"/"));
                        //pr($newArray);
                        $count = count($newArray) ;

                        if(($newArray[$count-1]) !='index.html'){
                            $insertData[]['name']=  rtrim($newArray[$count-1],".html");
                        }else {
                            $count = count($newArray) ;
                            $insertData[]['name'] = rtrim($newArray[$count-2],".html");
                        }




                    }
                 pr($insertData);
                //unset($insertData['Category'][0]);

                            $this->Category->create();
                            if($this->Category->saveMany($insertData)){
                            }
                            else{
                                echo mysql_error();
                            }

                //pr($this->Category->find('all'));

//                $data['Category']['name']='ga';




                }

            }






    }

    function getLinks($url){
        $ch = curl_init();
        $timeout = 5;
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        $html = curl_exec($ch);
        //pr($html);die;
        curl_close($ch);
        $dom = new DOMDocument();
        # The @ before the method call suppresses any warnings that
        # loadHTML might throw because of invalid HTML in the page.
        @$dom->loadHTML($html);
        $aLink = array();
        # Iterate over all the <a> tags
        foreach($dom->getElementsByTagName('a') as $link) {
            # Show the <a href>
           // echo $link->getAttribute('href');
            //echo "<br />";
            $aLink[] = $link->getAttribute('href');


        }

        return $aLink ;
    }

    function arrayClean( $array, $toDelete )
    {
        foreach($toDelete as $del) {
            unset($array[$del]);
        }
        return $array;
    }


}