<?php 
/**
* Sponsored content html
*
* @package    Sponsored Content
* @author     Ian Peralta <ian@hilltimes.com>
*/

class SponsoredContentHTML{
      public $postid;
      public $home;
      public $single;
      
      public function __construct($id)
      {
         $this->postid = $id; 
         $this->home = 'DefaultHome';
         $this->sidebar = 'DefaultSidebar';
         $this->single = 'DefaultSingle';
      }

      /**
       *  Build the content by replacing alias in the template
       * 
       */

      public function createHomepageSC()
      { 
        $template = $this->home;
        $strContentSC = $this->getTemplate($template);
        $arrContent = $this->getData(); 

        $strContentSC = str_replace('###URL###',  $arrContent["url"], $strContentSC);
        $strContentSC = str_replace('###FEATURED###',  $arrContent["featuredImage"], $strContentSC);
        $strContentSC = str_replace('###TITLE###', $arrContent["title"], $strContentSC);         
        $strContentSC = str_replace('###AUTHOR###', $arrContent["author"], $strContentSC);
        return $strContentSC;
      }

      public function createSidebarSC()
      {  
        $template = $this->sidebar; 
        $strContentSC = $this->getTemplate($template);
        $arrContent = $this->getData(); 

        $strContentSC = str_replace('###URL###',  $arrContent["url"], $strContentSC);
        $strContentSC = str_replace('###LISTIMG###',  $arrContent["featuredImage"], $strContentSC);
        $strContentSC = str_replace('###SIDEBARETITLE###', $arrContent["title"], $strContentSC);         
        $strContentSC = str_replace('###LISTAUTHOR###', $arrContent["author"], $strContentSC);
        return $strContentSC;
      }

      /**
       *  Generate the data using post id
       * 
       */
      public function getData(){  
        
        $posts = get_post($this->postid); 

        $scContents['company'] = $sponsored_company = get_post_meta(  $this->postid, 'sponsored_company', true );
        $scContents['excerpt'] = get_post_meta(  $this->postid, 'ad_excerpt', true );
        $scContents['url'] = get_post_meta(  $this->postid, 'ad_url', true );
        $scContents['title'] = $posts->post_title;
        $scContents['author'] = get_post_meta(  $this->postid, 'sp_author', true );
        $scContents['featuredImage'] = wp_get_attachment_image_src( get_post_meta( $this->postid, 'featured_image_url', true), 'thumbnail' )[0];
        $scContents['coverImage'] = get_post_meta(  $this->postid, 'featured_image_url', true);
        return $scContents;
      }

      /**
       *  get the templates
       */
      public function getTemplate($template){
         $strFilename = __DIR__ . '/templates/'. $template .'.html';
         return file_get_contents($strFilename);  
      }

}
?>