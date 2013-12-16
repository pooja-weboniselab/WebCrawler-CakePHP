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
                 //$linkList =   $this->getLinks($url);
                $linkList =   $this->getRecipe($url);
                 //pr($linkList);
                /* $deleteLink = array('67','133'.'134','135','136','137','138','139','140');
                 $categoryList = $this->arrayClean($linkList,$deleteLink );




                    //pr($categoryList);
                    foreach($categoryList as $val){
                       // $recipeList = $this->getLink($val);
                        //var_dump($recipeList);

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



                   */
            }

            }






    }

    function getLinks($url){
        /*$ch = curl_init();
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

        return $aLink ;*/
    }

    function arrayClean( $array, $toDelete )
    {
        foreach($toDelete as $del) {
            unset($array[$del]);
        }
        return $array;
    }

    function getRecipe($url){
        $ch = curl_init();
        $timeout = 5;
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        $html = curl_exec($ch);
        //pr($html);die;
        curl_close($ch);
        $dom_document = new DOMDocument();
        # The @ before the method call suppresses any warnings that
        # loadHTML might throw because of invalid HTML in the page.
        @$dom_document->loadHTML($html);

        // var_dump($dom_document);
        $dom_xpath = new DOMXpath($dom_document);
        $anchor = $dom_xpath->query("//td[@bgcolor='808C55']/div[@class='top-link']/a");
        //var_dump($$anchor);
        if (!is_null($anchor)) {

            foreach ($anchor as $a) {
                $anchorTag[] = $a->getAttribute("href");

            }

        }
          //pr($anchorTag);
        //return $anchorTag ;
         foreach($anchorTag as $val){
             $recipeValue[$val] = $this->getRecipeDetails($val);
             foreach($recipeValue[$val] as $content){

                 $recipeContent[$val][] = $this->createRecipe($content,$url);

             }
         }

        /* $otherAnchor = $dom_xpath->query("//td[@bgcolor='DCE8BD']/div[@class='flink']/a");
         if(!is_null($otherAnchor)){
             foreach($otherAnchor as $ah){
                 $otherAnchorTag = $ah->getAttribute("href");
             }
         }*/

         //$lastAnchor = $dom_xpath->query("")
        //pr($recipeValue);
         pr($recipeContent);


    }

    function getRecipeDetails($url){

       // echo "here" ;
        $chNew = curl_init();
        $timeout = 5;
        curl_setopt($chNew, CURLOPT_URL, $url);
        curl_setopt($chNew, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($chNew, CURLOPT_CONNECTTIMEOUT, $timeout);
        $html = curl_exec($chNew);
        //pr($html);die;
        curl_close($chNew);
        $dom_value = new DOMDocument();
        # The @ before the method call suppresses any warnings that
        # loadHTML might throw because of invalid HTML in the page.
        @$dom_value->loadHTML($html);
        $recipeLink = array();
         //var_dump($dom_value);

        $dom_get = new DOMXpath($dom_value);
        $anchorDetails = $dom_get->query("//td[@valign='TOP']/div[@class='mainlinks']/a");

        if (!is_null($anchorDetails)) {

            foreach ($anchorDetails as $data) {
                $recipeLink[] = $data->getAttribute("href");
                echo "here";
            }

        }
        else{
            echo "error is there" ;
        }
         return $recipeLink ;


    }


    function createRecipe($link,$url){
        $chNew = curl_init();
        $timeout = 5;
        curl_setopt($chNew, CURLOPT_URL, $link);
        curl_setopt($chNew, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($chNew, CURLOPT_CONNECTTIMEOUT, $timeout);
        $html = curl_exec($chNew);
        //pr($html);die;
        curl_close($chNew);
        $dom_value = new DOMDocument();
        # The @ before the method call suppresses any warnings that
        # loadHTML might throw because of invalid HTML in the page.
        @$dom_value->loadHTML($html);
        $headerValue = $dom_value->getElementsByTagName('h1');
        $recipeName =  $headerValue->item(0)->nodeValue;
        $dom_get = new DOMXpath($dom_value);
        $imageSrc = $dom_get->query("//div[@align='CENTER']/img");
        if(!is_null($imageSrc)) {
            foreach($imageSrc as $imageContent){
                $imageValue = $url.$imageContent->getAttribute("src");
            }

        }else{
            $otherImage = $dom_get->query("//td[@valign='TOP']/img");
            if(!is_null($otherImage)){
                foreach($otherImage as $img){
                    $imageValue = $url.$img->getAttribute("src");
                }
            }else{
                $imageValue = "no image";
            }

        }
        $ingredients = $dom_get->query("//div[@class='text']");
        if(!is_null($ingredients)) {
            foreach($ingredients as $ingredientsContent){
                $ingredientData = $ingredientsContent->nodeValue;
            }

        }
        $procedure = " ";
        $preparation =  $dom_get->query("//td[@valign='TOP']/ul/li");
        foreach($preparation as $steps){
            $procedure .= $steps->nodeValue ;
        }

        $recipeDetails = array("name" => $recipeName , "image" => $imageValue , "ingredients" => $ingredientData , "preparation" => $procedure);

         return $recipeDetails ;

    }



}