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
               // $this->getRecipe($url);
                 $linkList =   $this->getLinks($url);

                 //pr($linkList);
                $deleteLink = array('67','133'.'134','135','136','137','138','139','140');
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

                        unset($insertData[0]);
                            pr($insertData);
                    //unset($insertData[0]['name']);
                            $this->Category->create();
                            if($this->Category->saveMany($insertData)){
                                  $this->getRecipe($url);

                            }
                            else{
                                echo mysql_error();
                            }






                //recipe Insert
               /*  $this->Category->Recipe->create();
                 if($this->Category->Recipe->saveMany($RecipeList)){
                 }
                 else{
                     echo mysql_error();
                 }      */

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
        pr($html);
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

    function getRecipe($url){
       $ch = curl_init();
        $timeout = 5;
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        $html = curl_exec($ch);
        //pr($html);
        curl_close($ch);
        $dom_document = new DOMDocument();
        # The @ before the method call suppresses any warnings that
        # loadHTML might throw because of invalid HTML in the page.
        @$dom_document->loadHTML($html);

        //var_dump($dom_document);
        $dom_xpath = new DOMXpath($dom_document);
       // $anchorRegex = array("//td[@bgcolor='808C55']/div[@class='top-link']/a" ,"//td[@bgcolor='DCE8BD']/div[@class='flinks']/b/a");
        $anchorRegex = array("//td[@bgcolor='808C55']/div[@class='top-link']/a" );
        foreach($anchorRegex as $regex){
           $anchor[] = $dom_xpath->query($regex);
        }



        //var_dump($anchor);
          $position = 0 ;
        if (!is_null($anchor)) {

            foreach ($anchor as $linkShow) {
                     //echo $linkData ;
                foreach($linkShow as $a){
                $anchorTag[$position][] = $a->getAttribute("href");

            }
                $position++ ;
           }

        }
       // pr($anchorTag);

        //$completeAnchor = array_merge( $anchorTag[0] , $anchorTag[1]);
       // pr($completeAnchor);
        $completeAnchor =  $anchorTag[0];
        $valueCategory = $this->Category->find('all');
        //pr($valueCategory);

        //return $anchorTag ;
        $index = 0;
        $flag =0 ;
        foreach($valueCategory as $categoryUrl){
             $categoryDetails[$index]['name'] = $categoryUrl['Category']['name'];
             $categoryDetails[$index]['id'] = $categoryUrl['Category']['id'];
            $index++ ;
        }
        //pr($categoryDetails);
        foreach($completeAnchor as $val){
            $value = parse_url($val);
            // var_dump(explode('/',trim($value['path'],"/")));

            $newArray = explode('/',trim($value['path'],"/"));
            //pr($newArray);
            $count = count($newArray) ;

            if(($newArray[$count-1]) !='index.html'){
                $anchorData[$flag]['name']=  rtrim($newArray[$count-1],".html");
                $anchorData[$flag]['url'] = $val ;
            }else {
                $count = count($newArray) ;
                $anchorData[$flag]['name'] = rtrim($newArray[$count-2],".html");
                $anchorData[$flag]['url'] = $val ;
            }
            $flag++ ;
        }
            //pr($anchorData);
        foreach($categoryDetails as $catData){
            foreach ($anchorData as $linkData){
                if($catData['name'] == $linkData['name']){
                    $recipeValue[$catData['id']] = $this->getRecipeDetails($linkData['url']);
                   foreach($recipeValue[$catData['id']] as $content){

                        $recipeContent[$catData['id']][] = $this->createRecipe($content,$url,$catData['id']);

                    }
                }
            }
        }
           // $recipeValue['2'] = $this->getRecipeDetails("http://www.indianfoodforever.com/non-veg/chicken/");
               //pr($recipeValue);
             //   $recipeContent['2'][] = $this->createRecipe("http://www.indianfoodforever.com/gujarati/chakli.html",$url,'1');

            //pr($recipeContent);
        $key = 0 ;
        foreach($recipeContent as $recipeData){
            foreach($recipeData as $recipeInsertValue){

                $insertRecipe[$key]['name'] = $recipeInsertValue['name'];
                $insertRecipe[$key]['imagepath'] = $recipeInsertValue['image'];
                $insertRecipe[$key]['ingredients'] = $recipeInsertValue['ingredients'];
                $insertRecipe[$key]['categoryId'] = $recipeInsertValue['CategoryId'];
                $insertRecipe[$key]['preparation'] = $recipeInsertValue['preparation'];
                $insertRecipe[$key]['notes'] = 'no values' ;
                $key++;

            }
        }
        //return $insertRecipe ;
        //pr($insertRecipe);
        $this->Category->Recipe->create();
        if($this->Category->Recipe->saveMany($insertRecipe)){
            echo "confirm" ;
            $this->Session->setFlash(_('your Recipe has been saved.'));
             return $this->redirect(array('controller'=>'recipes' ,'action' => 'index'));
             }
        else{
            echo mysql_error();
        }


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

            }

        }
        else{
            echo "error is there" ;
        }
         return $recipeLink ;


    }


    function createRecipe($link,$url,$Id){
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
        var_dump($imageSrc);
           $imageValue = '';
        if(!is_null($imageSrc)) {
                foreach($imageSrc as $imageContent){

                if($imageContent->hasAttribute("src"))
                {

                 $imageValue = $url.$imageContent->getAttribute("src");
                }
             }
        }else{
            echo "here in image 1" ;
            $otherImage = $dom_get->query("//td/td[@valign='TOP']/img");
            //var_dump(
            if(!is_null($otherImage)){
                foreach($otherImage as $img){
                    if(!is_null($img->getAttribute("src"))){
                     $imageValue = $url.$img->getAttribute("src");
                    }
                    else{
                         $imageValue = "no image";
                    }
                }
             }
            else{
                $imageValue = "no image tag" ;
            }
           }
        $ingredients = $dom_get->query("//div[@class='text']");
        if(!is_null($ingredients)) {
            foreach($ingredients as $ingredientsContent){
                if(!is_null($ingredientsContent->nodeValue)){
                    $ingredientData = $ingredientsContent->nodeValue;
                }else{
                    $ingredientData  = "no content" ;
                }

            }

        }
        else {
            $ingredientData = "no content" ;
        }
        $procedure = " ";
        $preparation =  $dom_get->query("//td[@valign='TOP']/ul/li");
        foreach($preparation as $steps){
            $procedure .= $steps->nodeValue ;
        }

        //$imagePath = isset($imageValue)?$imageValue:"no image" ;
        if(!empty($imageValue)){
           $imagePath = $imageValue ;
        }else{
            $imagePath = "no image" ;
        }

        if(!empty($ingredientData)){
           $ingredientsShow = $ingredientData ;
        }else{
            $ingredientsShow  = "no ingredients" ;
        }


       $recipeDetails = array("name" => $recipeName , "image" => $imagePath , "CategoryId" => $Id, "ingredients" => $ingredientsShow , "preparation" => $procedure);

         return $recipeDetails ;

    }



}