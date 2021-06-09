<?php
/**
* Sponsored content plugin
*
* @package    
* @author     Ian Peralta <ian@hilltimes.com>
*/

class SponsoredContent{
    public $arrID = array();
    public $balancer = 4;

    public function __construct(){
        $this->arrID = $this->queryData();
    }

    /** 
    *   Get id for the frontpage display
    */
    public function getFrontPageSc(){
      
      // get the random index
      $postID = $this->randomIDFrontPage($this->arrID);
      array_push($_SESSION["arrKeysFront"], $postID);
      
      // Get the Template and generate the sponsored content
      if(!empty($this->arrID[$postID])){
        $sponsoreHTML = new SponsoredContentHTML($this->arrID[$postID]);
        return $sponsoreHTML->createHomepageSC();
      }else{
          return false;
      }
    }

    /** 
    *   Get id for the story page display
    */
    public function getSidebarSC(){
      
      // get the random index
      $postID = $this->randomIDListPage($this->arrID);
      array_push($_SESSION["arrKeysList"], $postID);

      // Get the Template and generate the sponsored content
      if(!empty($this->arrID[$postID])){
        $sponsoreHTML = new SponsoredContentHTML($this->arrID[$postID]);
        return $sponsoreHTML->createSidebarSC();
      }else{
        return false;
      }      
   }

    /** 
    *  Data query to get all active sponsored contents
    */
    public function queryData(){ 
          global $wpdb;
          $arrID = array(); 

          // quest all the sponsored contents
          $posts = $wpdb->get_results("SELECT * FROM ht_posts WHERE post_type = 'sc'");
          
          foreach($posts as $post ){
              // check the dates if they are active

              $sdate = strtotime( get_post_meta( $post->ID, 'start_date', true ) ); 
              $edate = strtotime( get_post_meta( $post->ID, 'end_date', true ) );   
              $now = strtotime("now");    
              
              if( ( $now >= $sdate ) && ( $edate >= $now ) ){

                    if( get_post_status( $post->ID ) === 'publish'){ 
                        array_push($arrID, $post->ID);
                    }
                    
              }
          }
          return $arrID;
    }
    
    /**
     *  Get random unique ID
    */
    public function randomIDFrontPage(){
      $arr = $this->arrID; 
      $keyRan = array_rand($arr);
      $cnt = count($arr) + $this->balancer;
  
      if(!in_array($keyRan, $_SESSION["arrKeysFront"])){
         $postID = $keyRan;
      }else{
          for($x = 0; $x <= $cnt; $x++){
              $keyRan = array_rand($arr);
              if(!in_array($keyRan, $_SESSION["arrKeysFront"])){
                 $postID = $keyRan;
                 break;
              }
          }
      }
      return $postID;
   }

   public function randomIDListPage(){
      $arr = $this->arrID; 
      $keyRan = array_rand($arr);
      $cnt = count($arr) + $this->balancer;
  
      if(!in_array($keyRan, $_SESSION["arrKeysList"])){
         $postID = $keyRan;
      }else{
          for($x = 0; $x <= $cnt; $x++){
              $keyRan = array_rand($arr);
              if(!in_array($keyRan, $_SESSION["arrKeysList"])){
                 $postID = $keyRan;
                 break;
              }
          }
      }
      return $postID;
   }
}
?>