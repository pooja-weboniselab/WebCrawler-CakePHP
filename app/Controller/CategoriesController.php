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
    public $components = array('Session','Crawl');
    public function index() {
        if($this->request->is('post')) {
            $website = $this->request->data;
            $url = $website['WebCrawl']['url'];
            if (!preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i",$url)){
                echo "Invalid URL";
            }
            else{
              $linkList =   $this->getLinks($url);
              $this->getRecipe($url);
              $deleteLink = array('67','133'.'134','135','136','137','138','139','140');
              $categoryList = $this->arrayClean($linkList,$deleteLink );
              $insertData = $this->customizeData($categoryList);
              unset($insertData[0]);
              pr($insertData);
              if($this->Category->saveData($insertData)){
              //$this->getRecipe($url);

              }
              else{
              echo mysql_error();
              }
            }
        }
    }

        public function getLinks($url){
            $dom = $this->Crawl->fetchLink($url);
            $aLink = array();
            # Iterate over all the <a> tags
            foreach($dom->getElementsByTagName('a') as $link) {
                # Show the <a href>
                $aLink[] = $link->getAttribute('href');
            }
            return $aLink ;
        }

        function arrayClean( $array, $toDelete ){
            foreach($toDelete as $del) {
                unset($array[$del]);
            }
            return $array;
        }

        function getRecipe($url){
            $anchorRegex = array("//td[@bgcolor='808C55']/div[@class='top-link']/a" ,"//td[@bgcolor='DCE8BD']/div[@class='flinks']/b/a");
            foreach($anchorRegex as $regex){
               $anchor[] = $this->Crawl->domQuery($url,$regex);
            }
            $position = 0 ;
            if (!is_null($anchor)) {
               foreach ($anchor as $linkShow) {
                    foreach($linkShow as $a) {
                    $anchorTag[$position][] = $a->getAttribute("href");
                }
                    $position++ ;
               }
            }
            //pr($anchorTag);
            $completeAnchor = array_merge( $anchorTag[0] , $anchorTag[1]);

            $valueCategory = $this->Category->fetchCategory();
               // pr($valueCategory);
            //return $anchorTag ;
            $index = 0;
            $flag =0 ;
            foreach($valueCategory as $categoryUrl){
                 $categoryDetails[$index]['name'] = $categoryUrl['Category']['name'];
                 $categoryDetails[$index]['id'] = $categoryUrl['Category']['id'];
                $index++ ;
            }
            //pr($categoryDetails);

            $showData = $this->customizeData($completeAnchor);
            $flag = 0 ;
            //pr($showData);
            foreach($showData as $value){

                $anchorData[$flag]['name'] = $value['name'] ;
                $anchorData[$flag]['url'] = $completeAnchor[$flag];
                $flag++ ;
            }
            pr($anchorData);
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
            pr($insertRecipe);
            if($this->Category->saveRecipe($insertRecipe)){
                echo "confirm" ;
                $this->Session->setFlash(_('your Recipe has been saved.'));
                return $this->redirect(array('controller'=>'recipes' ,'action' => 'index'));
            }
            else{
                echo mysql_error();
            }



        }

        private function getRecipeDetails($url){
            // echo "here" ;
            $regex = "//td[@valign='TOP']/div[@class='mainlinks']/a" ;
            $anchorDetails = $this->Crawl->domQuery($url,$regex);
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

        private function createRecipe($link,$url,$Id){
            $dom_value =  $this->Crawl->fetchLink($link);
            $headerValue = $dom_value->getElementsByTagName('h1');
            $recipeName =  $headerValue->item(0)->nodeValue;
            $regex = "//div[@align='CENTER']/img" ;
            $imageSrc = $this->Crawl->domQuery($link,$regex);
            //var_dump($imageSrc);
            $imageValue = '';
            if(!is_null($imageSrc)) {
                foreach($imageSrc as $imageContent){
                    if($imageContent->hasAttribute("src")){
                        $imageValue = $url.$imageContent->getAttribute("src");
                    }
                }
            }else{
                echo "here in image 1" ;
                $newRegex = "//td/td[@valign='TOP']/img" ;
                $otherImage = $this->Crawl->domQuery($link,$newRegex);
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
            $ingredientsRegex = "//div[@class='text']" ;
            $ingredients = $this->Crawl->domQuery($link,$ingredientsRegex);
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
            $prepareRegex = "//td[@valign='TOP']/ul/li" ;
            $preparation = $this->Crawl->domQuery($prepareRegex);
            foreach($preparation as $steps){
                $procedure .= $steps->nodeValue ;
            }
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

     private function customizeData($array){
         $flag = 0 ;
         foreach($array as $val){

             $value = parse_url($val);
             $newArray = explode('/',trim($value['path'],"/"));
             $count = count($newArray) ;
             if(($newArray[$count-1]) !='index.html'){
                 $outputData[$flag]['name']=  rtrim($newArray[$count-1],".html");
             }
             else {

                 $outputData[$flag]['name'] = rtrim($newArray[$count-2],".html");

             }
             $flag++ ;
         }
         //pr($outputData);
         return $outputData ;

     }



}