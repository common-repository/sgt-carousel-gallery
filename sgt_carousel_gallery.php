<?php 

/*
Plugin Name: SGT Carousel Gallery
Plugin URI: http://www.sgt-arts.com/blog/sgt-carousel-gallery
Description: 3d gallery using css transforms
Version: 1.2
Author: Stephanos Tsoucas
Author URI: http://www.sgt-arts.com
License: GPLv2
*/

	add_shortcode('sgt_gallery', 'sgt_carousel_gallery');
	
	add_action('after_setup_theme', 'sgt_gallery_setup');

/*This registers the possible image sizes that images will use*/
function sgt_gallery_setup(){
	$sizes= get_intermediate_image_sizes();
	add_image_size('sgt_small_square', 150, 150, true);
	add_image_size('sgt_medium_square', 250, 250, true);
	add_image_size('sgt_small_landscape', 200, 100, true);
	add_image_size('sgt_medium_landscape', 300, 150, true);
	add_image_size('sgt_large_landscape', 400, 250, true);
}

/*This function outputs the html, css, and javascript into the body of the page or post in which
the shortcode is invoked. */
 function sgt_carousel_gallery( $atts ){
	 
	extract( shortcode_atts( array(
		'width' => -1,
		'height' => -1,
		'radius' => -1,
		'size' => 'sgt_medium_landscape',
	), $atts ) );
	 
	global $post;  
  
    $args = array(  
        'post_parent'    => $post->ID,            // For the current post  
        'post_type'      => 'attachment',        // Get all post attachments  
        'post_mime_type' => 'image',             // Only grab images  
        'order'          => 'ASC',               // List in ascending order  
        'orderby'        => 'menu_order',        // List them in their menu order  
        'numberposts'    => -1,                  // Show all attachments  
        'post_status'    => null,                // For any post status  
    );  
	
	// Retrieve the items that match our query; in this case, images attached to the current post.  
$attachments = get_posts($args);  
  
    // If any images are attached to the current post, do the following:  
    if ($attachments){
		$numPanels= sizeof($attachments);
		
		global $_wp_additional_image_sizes;		
		$width= $_wp_additional_image_sizes[$size]['width'];
		$height= $_wp_additional_image_sizes[$size]['height'];
		
		if ($radius == -1) $radius= round(($width + 24)/(2*tan(M_PI/$numPanels)));
		
		$sideWidth= $width;
		$panelWidth= $sideWidth - 24;
		$degInc= 360/$numPanels;
		ob_start();
		?> 
        <script type="text/javascript" src="<?php echo plugins_url('sgt-carousel-gallery/modernizr.custom.48564.js', _FILE_) ?>"></script>
        
        <style type="text/css">
		
		.arrowLeft{
			position:absolute;
			height:0px;
			width:0px;
			border-top:10px solid transparent;
			border-bottom:10px solid transparent;
			border-right:20px solid #2c3e50;
			top:5px;
			right:20px;
		}
		
		.arrowRight{
			position:absolute;
			height:0px;
			width:0px;
			border-top:10px solid transparent;
			border-bottom:10px solid transparent;
			border-left:20px solid #2c3e50;
			top:5px;
			left:20px;
		}
		
		#captionsWrapper{
			min-height:20px;
			height:100%;
			overflow:scroll;
			position:relative;
			width:100%;
		}
	
		#container {
			display:block;
			width: 100%;
			height: <?php echo ($height + 20) ?>px;
			overflow:hidden;
			position: relative;
			margin-top:20px;
			margin:auto;
			-webkit-perspective: 1100px;
			-moz-perspective: 1100px;
			-o-perspective: 1100px;
			perspective: 1100px;
			top:10px;
			}

		.controlPanel {
			display:block;
			margin-top:10px;
			position:relative;
			width:100%;
			bottom:0px;
			min-height:30px;
			height:auto;
			overflow:auto;
			background:#fff;
			text-align:center;
		}
		
		.controlPanelFull{
			display:block;
			margin-top:1%;
			margin-left:1%;
			margin-right:1%;
			position:absolute;
			width:98%;
			bottom:1%;
			min-height:30px;
			height:auto;
			overflow:auto;
			background:#fff;
			z-index:500001;
		}

		#carousel {
			width: <?php echo ($width + 24)?>px;
			height: 170px;
			left:0px;
			right:0px;
			margin:auto;
			position: absolute;
			transform-style: preserve-3d;
			transform: translateZ(<?php echo (-$radius)?>px) rotateY(0deg);
			-webkit-transform-style: preserve-3d;
			-webkit-transform: translateZ(<?php echo (-$radius)?>px) rotateY(0deg);
			-webkit-transition: -webkit-transform 1s;
			transform-style: preserve-3d;
			transition: transform 1s;
			z-index:100;
}

	
		#carousel figure {
			display: block;
			position: absolute;
			width:<?php echo ($width + 28);?>px;
			height: auto;
			margin:0px;
			top:0px;
			left:0px;
			text-align:center;
}

		#carousel figure img{
			border:2px solid transparent;
			display:block;
			margin:auto;
		}
		
		#carousel figure img:hover{
			border-color:#666;
		}
		
		#fullGrey{
			opacity:0.65;
			background-color:#333;
			position:fixed;
			margin:auto; 
			top:0px;
			left:0px;
			width:100%;
			height:100%;
			z-index:500000;
		}
		
		/*Div to go full screen and display selected image*/
		#fullScreen {
			display:none;
			position:fixed;
			margin:auto; 
			overflow:hidden;
			top:0px;
			left:0px;
			width:100%;
			height:100%;
			text-align:center;
			z-index:500000;
		}
		
		#fullScreenImg{
			display:inline;
			background-color:#fff;
			//float:left;
			width:auto;
			max-width:69%;
			max-height:75%;
			margin-top:1%;
			overflow:hidden;
			z-index:500001;
		}
		
		#fullScreenImgDiv {
			background:#000;
			display:inline-block;
			top:10px;
			overflow:hidden;
			position:relative;
			width:95vw;
			height:95vh;
			z-index:500001;
		}
		
		#full-text{
			margin-top:30px;
			dislay:inline;
			width:96%;
			height:100%;
			overflow:scroll;
			margin-left:2%;
			margin-right:2%;
			padding-bottom:10px;
		}
		
		.imgInfo{
			
			-webkit-box-shadow: 1px 1px 2px rgba(153, 155, 195, 0.75);
			-moz-box-shadow:    1px 1px 2px rgba(153, 155, 195, 0.75);
			box-shadow:         1px 1px 2px rgba(153, 155, 195, 0.75);
			
			height:auto;
			width:100%;
			position:relative;
			display:inline-block;
			bottom:0px;
			margin-left:auto;
			margin-right:auto;
			margin-bottom:10px;	
			padding-top:5px;
			padding-bottom:5px;
			text-align:center;
		}
		
		#imgCaptionWrapper {
			display:inline;
			width:auto;
			overflow:scroll;
			position:relative;
			margin-left:auto;
			margin-right:auto;
			text-align:center;
		}
		
		#imgInfoWrapper{
			width:100%;
			height:auto;
			position:relative;
			overflow-x:visible;
			overflow-y:visible;
		}
		
		.imgCounter{
			position:relative;
		}
		
		.sgt_captions{
			display:block;
			position:relative;
			height:auto;
			overflow:auto;
			width:auto;
			font-size:1.2em;
			font-weight:bold;
			margin-left:5px;
		}
		
		#sgt_descriptions{
			background:#fff;
			display:inline;
			position:relative;
			margin-top:1%;
			margin-right:1%;
			max-height:50%;
			width:28%;
			font-size:1em;
			float:right;
		    overflow:scroll;
			z-index:500001;
		}
		
		.toggleLeft {
			height:25px;
			opacity:0.2;
			width:45%;
			position:absolute;
			top:0px;
			right:50%;
			transition: opacity .25s ease-in-out;
   			-moz-transition: opacity .25s ease-in-out;
   			-webkit-transition: opacity .25s ease-in-out;
			z-index:250;
		}
		
		.toggleRight {
			height:25px;
			opacity:0.2;
			width:45%;
			position:absolute;
			top:0px;
			left:50%;
			
			transition: opacity .25s ease-in-out;
   			-moz-transition: opacity .25s ease-in-out;
   			-webkit-transition: opacity .25s ease-in-out;
			z-index:250;
		}
		
		.toggleLeft:hover, .toggleRight:hover{
			opacity:1;
		}
		
		#warningBox{
			height:auto;
			width:100%;
			margin-left:auto;
			margin-right:auto;
			margin-top:10px;
			margin-bottom:10px;
			position:relative;
			display:inline-block;
			background-color:#eee;
		}

<?php $degrees= 0;
	$opacity= 1;
	for ($i= 1; $i <= $numPanels; $i++) {
	echo("#carousel figure:nth-child(" . $i . ") { -webkit-transform: rotateY(" . $degrees . "deg) translateZ(" . $radius . "px );
	transform: rotateY(" . $degrees . "deg) translateZ(" . $radius . "px); opacity:" . $opacity ."}\n");
	$opacity= 0.6;
	$degrees += $degInc;
	}?>
		</style>
        <div id="imgInfoWrapper">
        <section id="container">
  <div id="carousel">
  <?php 
	  $caption;
	  $description;
  	foreach($attachments as $attachment){
		$caption = $attachment->post_excerpt;
		$description= $attachment->post_content;
		$imageAttributes= wp_get_attachment_image_src($attachment->ID, 'large');
	  echo "<figure data-fullImage=\"" . $imageAttributes[0] . "\"
	  data-description=\"" . $description . "\"data-caption=\"" . $caption . "\">" . wp_get_attachment_image($attachment->ID, $size) ."</figure>";
	  }
  ?>
    </div>
    	
</section>
		<div class="controlPanel">
		<div class="toggleLeft" onclick="rotateLeft()"><div class="arrowLeft"></div></div>
        <div class="imgCounter"><?php echo 1?>/<?php echo $numPanels?></div>
  		<div class="toggleRight" onclick="rotateRight()"><div class="arrowRight"></div></div>
        <div id="captionsWrapper"><div class="sgt_captions"><?php echo $attachments[0]->post_excerpt;?></div></div>
    </div>
    	</div>
<script type="text/javascript">
var el= document.getElementById("carousel");
var figures= el.children;
var captions= document.getElementsByClassName("sgt_captions");

/*Object to keep track of which side of the carousel is facing forward, */
var carousel= {element: el, numPanels: <?php echo $numPanels?>, index:0, theta: 0, degInc: <?php echo $degInc;?>};
var imgCounter= document.getElementsByClassName("imgCounter");

/*Create fullscreen element for gallery*/
var fullScreen= document.createElement("div");
fullScreen.style.display= "none";
fullScreen.setAttribute("id", "fullScreen");
fullScreen.innerHTML= "<div id='fullGrey' onClick='unFullScreen()'></div><div id='fullScreenImgDiv'><div id='imgCaptionWrapper'><img id='fullScreenImg' src='" + figures[0].getAttribute("data-fullImage") + "'/>" + "<div id='sgt_descriptions'><div id='captionsWrapper'><div class='sgt_captions'><?php echo $attachments[0]->post_excerpt;?></div></div><div id='full-text'>" +  figures[0].getAttribute("data-description") + "</div></div></div><div class='controlPanelFull'><div class='toggleLeft' onClick='rotateLeft()'><div class='arrowLeft'></div></div><div class='imgCounter'><?php echo 1?>/<?php echo $numPanels?></div><div class='toggleRight' onClick='rotateRight()'><div class='arrowRight'></div></div></div></div>";
figures[0].onclick= function(){
	fullScreenImage();
}
var body= document.getElementsByTagName("body")[0];
body.insertBefore(fullScreen, body.childNodes[0]);

var fullImage= document.getElementById("fullScreenImg");
var fullText= document.getElementById("full-text");

function fullScreenImage(){
	fullScreen.style.display="block";
}

function unFullScreen(){
	fullScreen.style.display= "none";
}

function rotateRight(){
	carousel.index++;
	carousel.theta -= carousel.degInc;
	
	var oldFace= figures[modNumber((carousel.index - 1),carousel.numPanels)];
	rotateHelper(oldFace);
}

function rotateLeft(){
	carousel.index--;
	carousel.theta += carousel.degInc;
	
	var oldFace= figures[((carousel.index + 1)%carousel.numPanels)];
	rotateHelper(oldFace);
}


function rotateHelper(oldFace){
	carousel.index= modNumber(carousel.index, carousel.numPanels);
	var imgNumber= carousel.index + 1;
	changeImgCounter(imgNumber);
	
	var face= figures[carousel.index];
	face.style.opacity= '1';
	face.style.filter= 'alpha(opacity=100)';
	face.onclick= function(){
	fullScreenImage();
	}
	
	changeCaptions(face);
	
	oldFace.style.opacity= '0.6';
	oldFace.style.filter= 'alpha(opacity=100)';
	oldFace.onclick= null;
	
	fullImage.src= face.getAttribute("data-fullImage");
	fullText.innerHTML= face.getAttribute("data-description");
	
	var s= "translateZ(" + <?php echo -$radius?> + "px) rotateY(" + carousel.theta + "deg)";
	carousel.element.style.webkitTransform= s;
	carousel.element.style.transform= s;
}

function changeImgCounter(imgNumber){
	for (var i= 0; i < imgCounter.length; i++) {
		var counter= imgCounter[i];
		counter.innerHTML= imgNumber + "/" + carousel.numPanels;
	}
}

function changeCaptions(face){
	for (var i= 0; i < captions.length; i++) {
	captions[i].innerHTML= face.getAttribute("data-caption");
	}
}

function modNumber(n, m){
	return ((n%m)+m)%m;
}

function eighty_pc() {
    var height = $(window).height();
    var eighty = (80 * height) / 100;
    thirtypc = parseInt(eighty) + 'px';
    $("fullScreenImgDiv").css('height', eighty);
}

/*jquery(document).ready(function() {
    thirty_pc();
    jquery(window).bind('resize', thirty_pc);
});*/



</script>
<script type="text/javascript">
		/*Include Modernizr to detect if browser can handle css3d transforms*/
        if (!Modernizr.csstransforms3d) {
			
			var container= document.getElementById("imgInfoWrapper");
			container.innerHTML= "<div id='warningBox'>Unfortunately, your browser does not support the 3d css transforms necessary for this gallery to run. Try using <a href='http://www.apple.com/safari/'>Safari</a>, <a href='http://www.google.com/chrome'>Chrome</a>, or <a href='http://www.mozilla.org/firefox'>Firefox</a> to view this page.</div> ";
		}
        
        </script>
		
		<?php
	}
	
	$string= ob_get_contents();
	ob_end_clean();
	return $string;

 }
?>