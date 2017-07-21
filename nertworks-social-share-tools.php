<?php   
    /* 
    Plugin Name: NertWorks All in One Social Share Tools 
    Plugin URI: http://www.nertworks.com 
    Description: Plugin for place social share icons all over the website.  
    Author: Nickolas Ormond
    Version: 1.0 
    Author URI: http://www.nertworks.com 
    */  	
//---------------Generic Functions
function nertworks_social_show_alert($message){
	echo '
	<script type="text/javascript">
	alert("'.$message.'");
	</script>
	';
	

}
//add meta for Facebook and Pinterest
function nertworks_social_add_meta() {
   //echo 'Here is my Post Id: '.get_the_id();
	if (get_option('nertworks_social_image_genie')!="disabled"){
		$blog_title = get_bloginfo();
		$thumbnail = wp_get_attachment_url( get_post_thumbnail_id($post->ID) );
		if ($thumbnail==NULL){
			$html=get_post_field('post_content', $post->ID);
			$thumbnail=nertworks_social_get_thumbnail($html);
		}
		if ($thumbnail==NULL){
			$thumbnail==get_header_image();
		}
		if ($thumbnail==NULL){
			$thumbnail=plugins_url( 'images/no_image.png' , __FILE__ ) ;
		}
		$title=get_the_title();
		$actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		echo '<meta property="og:image" content="'.$thumbnail.'"/>
		<meta property="og:title" content="'.$title.'"/>
		<meta property="og:url" content="'.$actual_link.'"/>
		<meta property="og:site_name" content="'.$blog_title.'"/>
		<meta property="og:type" content="blog"/>
	   ';
	}
   
   
}
add_action('wp_head', 'nertworks_social_add_meta');
//Get Thumbnail Function
function nertworks_social_get_thumbnail($html){
        $matches = array();
        
        // images
        $pattern = '/<img[^>]*src=\"?(?<src>[^\"]*)\"?[^>]*>/im';
        preg_match( $pattern, $html, $matches ); 
        if($matches['src']) {
            return $matches['src'];
        }
        
        // youtube
        $pattern = "/(http:\/\/www.youtube.com\/watch\?.*v=|http:\/\/www.youtube-nocookie.com\/.*v\/|http:\/\/www.youtube.com\/embed\/|http:\/\/www.youtube.com\/v\/)(?<id>[\w-_]+)/i";
        preg_match( $pattern, $html, $matches ); 
        if( $matches['id'] ) {
            return "http://img.youtube.com/vi/{$matches['id']}/0.jpg";
        }
        
        // vimeo
        $pattern = "/(http:\/\/vimeo.com\/|http:\/\/player.vimeo.com\/video\/|http:\/\/vimeo.com\/moogaloop.swf?.*clip_id=)(?<id>[\d]+)/i";
        preg_match( $pattern, $html, $matches ); 
        if( $vimeo_id = $matches['id'] ) {
            $hash = unserialize(file_get_contents("http://vimeo.com/api/v2/video/{$vimeo_id}.php"));
            return "{$hash[0]['thumbnail_medium']}";
        }
        
        // dailymotion
        // http://www.dailymotion.com/thumbnail/150x150/video/xexakq
        $pattern = "/(http:\/\/www.dailymotion.com\/swf\/video\/)(?<id>[\w\d]+)/i";
        preg_match( $pattern, $html, $matches ); 
        if( $matches['id'] ) {
            return "http://www.dailymotion.com/thumbnail/150x150/video/{$matches['id']}.jpg";
        }
        
        return null;
}
function nert_floating_addthis(){
				echo '<!-- AddThis Button BEGIN -->
				<div class="addthis_toolbox addthis_floating_style '.get_option('nertworks_social_addthis_floating_share_size').'" style="left:50px;top:50px;">
				<a class="addthis_button_preferred_1"></a>
				<a class="addthis_button_preferred_2"></a>
				<a class="addthis_button_preferred_3"></a>
				<a class="addthis_button_preferred_4"></a>
				<a class="addthis_button_compact"></a>
				</div>
				<script type="text/javascript">var addthis_config = {"data_track_addressbar":true};</script>
				<script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-52a4e4cc58321913"></script>
				<!-- AddThis Button END -->';	
				}

function get_social_code(){
	$page_title=get_the_title();
//----------------------------------Addthis	
	if (get_option('nertworks_social_bar_source')=="addthis"){
		
		
		//Addthis Facebook Like Button
		if (get_option('nertworks_social_addthis_floating_share')!="disabled"){
			add_action('get_footer', 'nert_floating_addthis');
		}
		if (get_option('nertworks_social_addthis_facebook_like')!="disabled"){
			$facebook_button='<li><a class="addthis_button_facebook_like" fb:like:layout="button_count"></a></li>';
		}
		if (get_option('nertworks_social_addthis_twitter_button')!="disabled"){
			$twitter_button='<li><a class="addthis_button_tweet"></a></li>';
		}
		if (get_option('nertworks_social_addthis_facebook_share')!="disabled"){
			$facebook_share='<li><a class="addthis_button_facebook"></a></li>';
		}
		if (get_option('nertworks_social_addthis_twitter_share_button')!="disabled"){
			$twitter_share='<li><a class="addthis_button_twitter"></a></li>';
		}
		if (get_option('nertworks_social_addthis_stumble_share')!="disabled"){
			$stumble_share='<li><a class="addthis_button_stumbleupon"></a></li>';
		}
		if (get_option('nertworks_social_addthis_gmail_share')!="disabled"){
			$gmail_share='<li><a class="addthis_button_gmail"></a></li>';
		}
		if (get_option('nertworks_social_addthis_myspace_share')!="disabled"){
			$myspace_share='<li><a class="addthis_button_myspace"></a></li>';
		}
		if (get_option('nertworks_social_addthis_plus_button')!="disabled"){
			$addthis_button='<li><a class="addthis_button_compact"></a></li>';
		}
		return '<!-- AddThis Button BEGIN -->
		<div id="socialcontainer" class="addthis_toolbox addthis_default_style '.get_option('nertworks_social_addthis_icon_size').'">
		'.$facebook_share.'
		'.$twitter_share.'
		'.$stumble_share.'
		'.$gmail_share.'
		'.$myspace_share.'
		'.$addthis_button.'
		'.$facebook_button.'
		'.$twitter_button.'
		</div>
		<script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#pubid=ra-4e717f7f2aee34a7"></script>
		<!-- AddThis Button END -->';	
	}

//-------------------------Custom
	if (get_option('nertworks_social_bar_source')=="custom"){
		
		if (get_option('nertworks_social_bar_custom_icon_size')!=NULL){
			$icon_size=get_option('nertworks_social_bar_custom_icon_size'); 
		}
		else {
			$icon_size=24;
		}
		
		if ((get_option('nertworks_social_custom_stumble_icon')=="stumble_round1")||(get_option('nertworks_social_custom_stumble_icon')=="")){
			$stumble_image='stumble.png';
			$stumble_image = '<img src="' . plugins_url( 'images/'.$stumble_image , __FILE__ ) . '" alt="Share On Stumble" width="'.$icon_size.'" class="hoverImages">';
		}
		if (get_option('nertworks_social_custom_stumble_icon')=="stumble_square"){
			$stumble_image='stumble_square.png';
			$stumble_image = '<img src="' . plugins_url( 'images/'.$stumble_image , __FILE__ ) . '" alt="Share On Stumble" width="'.$icon_size.'" class="hoverImages">';
		}
		if (get_option('nertworks_social_custom_stumble_icon')=="my_stumble_icon"){
			$stumble_image=get_option('nertworks_social_custom_stumble_icon_uploaded');
			$stumble_image = '<img src="'.$stumble_image.'" alt="Share On Stumble" width="'.$icon_size.'" class="hoverImages" >';
		}
		
		if ((get_option('nertworks_social_custom_twitter_icon')=="twitter_round1")||(get_option('nertworks_social_custom_twitter_icon')=="")){
			$twitter_image='twitter.png';
			$twitter_image='<img src="' . plugins_url( 'images/'.$twitter_image , __FILE__ ) . '" alt="Share On Twitter" width="'.$icon_size.'" class="hoverImages"/>';
		}
		if (get_option('nertworks_social_custom_twitter_icon')=="twitter_square"){
			$twitter_image='twitter_square.png';
			$twitter_image='<img src="' . plugins_url( 'images/'.$twitter_image , __FILE__ ) . '" alt="Share On Twitter" width="'.$icon_size.'" class="hoverImages"/>';
		}
		if (get_option('nertworks_social_custom_twitter_icon')=="my_twitter_icon"){
			$twitter_image=get_option('nertworks_social_custom_twitter_icon_uploaded');
			$twitter_image='<img src="' . $twitter_image . '" alt="Share On Twitter" width="'.$icon_size.'" class="hoverImages" class="hoverImages"/>';
		}
		
		if ((get_option('nertworks_social_custom_pinterest_icon')=="pinterest_round1")||(get_option('nertworks_social_custom_pinterest_icon')=="")){
			$pinterest_image='pinterest.png';
			$pinterest_image='<img src="' . plugins_url( 'images/'.$pinterest_image , __FILE__ ) . '" alt="Pin it on Pinterest"  width="'.$icon_size.'" class="hoverImages">';
		}
		if (get_option('nertworks_social_custom_pinterest_icon')=="pinterest_square"){
			$pinterest_image='pinterest_square.png';
			$pinterest_image='<img src="' . plugins_url( 'images/'.$pinterest_image , __FILE__ ) . '" alt="Pin it on Pinterest"  width="'.$icon_size.'" class="hoverImages">';
		}
		if (get_option('nertworks_social_custom_pinterest_icon')=="my_pinterest_icon"){
			$pinterest_image=get_option('nertworks_social_custom_pinterest_icon_uploaded');
			$pinterest_image='<img src="' . $pinterest_image . '" alt="Pin it on Pinterest"  width="'.$icon_size.'" class="hoverImages">';
		}
		
		if ((get_option('nertworks_social_custom_linkedin_icon')=="linkedin_round1")||(get_option('nertworks_social_custom_linkedin_icon')=="")){
			$linkedin_image='linkedin.png';
			$linkedin_image='<img src="' . plugins_url( 'images/'.$linkedin_image , __FILE__ ) . '" alt="Share on Linkedin"  width="'.$icon_size.'" class="hoverImages">';
		}
		if (get_option('nertworks_social_custom_linkedin_icon')=="linkedin_square"){
			$linkedin_image='linkedin_square.png';
			$linkedin_image='<img src="' . plugins_url( 'images/'.$linkedin_image , __FILE__ ) . '" alt="Share on Linkedin"  width="'.$icon_size.'" class="hoverImages">';
		}
		if (get_option('nertworks_social_custom_linkedin_icon')=="my_linkedin_icon"){
			$linkedin_image=get_option('nertworks_social_custom_linkedin_icon_uploaded');
			$linkedin_image='<img src="' . $linkedin_image . '" alt="Share on Linkedin"  width="'.$icon_size.'" class="hoverImages">';
		}

		if ((get_option('nertworks_social_custom_facebook_icon')=="facebook_round1")||(get_option('nertworks_social_custom_facebook_icon')=="")){
			$facebook_image='facebook.png';
			$facebook_image='<img src="' . plugins_url( 'images/'.$facebook_image , __FILE__ ) . '" alt="Share on facebook"  width="'.$icon_size.'" class="hoverImages">';
		}
		if (get_option('nertworks_social_custom_facebook_icon')=="facebook_square"){
			$facebook_image='facebook_square.png';
			$facebook_image='<img src="' . plugins_url( 'images/'.$facebook_image , __FILE__ ) . '" alt="Share on facebook"  width="'.$icon_size.'" class="hoverImages">';
		}
		if (get_option('nertworks_social_custom_facebook_icon')=="my_facebook_icon"){
			$facebook_image=get_option('nertworks_social_custom_facebook_icon_uploaded');
			$facebook_image='<img src="' . $facebook_image . '" alt="Share on Facebook"  width="'.$icon_size.'" class="hoverImages">';
		}
		
		
		//---------Gat Url
		$url = $_SERVER['REQUEST_URI'];
		$url_array = explode('?',$url);
		array_shift($url_array);
		$everything = implode('?', $url_array);
		//------------
		
		//------------
		if(has_post_thumbnail()) {
			$media=wp_get_attachment_url(get_the_post_thumbnail());
		}
		if ($media==NULL){
			$media=get_header_image();
		}
		if ($media==NULL){
			$media=plugins_url( 'images/no_image.png' , __FILE__ ) ;
		}
		$actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		return '<div id="socialcontainer">
		
		<li><a href="http://stumbleupon.com/submit?url='.urlencode($actual_link).'&title='.urlencode($page_title).'" target="_blank">
		'.$stumble_image.'</a></li>
		<li><a href="http://twitter.com/home?status='.urlencode($actual_link).'+'.urlencode($page_title).'" target="_blank">
		<li><a href="http://twitter.com/home?status='.urlencode($actual_link).'+'.urlencode($page_title).'" target="_blank">
		'.$twitter_image.'
		</a></li>
		<li><a href="http://pinterest.com/pin/create/bookmarklet/?media='.$media.'&url='.urlencode($actual_link).'&is_video=false&description='.urlencode($actual_link).'" target="_blank">
		'.$pinterest_image.'</a></li>
		<li><a href="http://linkedin.com/shareArticle?mini=true&url='.urlencode($actual_link).'&title='.urlencode($page_title).'&source='.urlencode($actual_link).'" target="_blank">
		'.$linkedin_image.'</a></li>
		<li><a href="https://www.facebook.com/sharer/sharer.php?u='.urlencode($actual_link).'" target="_blank">
		'.$facebook_image.'</a></li>
		</div>';
	}
//----------------------------------Addtoany	
	if (get_option('nertworks_social_bar_source')=="addtoany"){
		if (get_option('nertworks_social_bar_addtoany_facebook_like')=="enabled"){
			$facebook_like='<li><a class="a2a_button_facebook_like"></a></li>';
		}
		if (get_option('nertworks_social_bar_addtoany_twitter_button')!="disabled"){
			$twitter_button='<li><a class="a2a_button_twitter_tweet"></a></li>';
		}
		if (get_option('nertworks_social_bar_addtoany_google_button')!="disabled"){
			$google_button='<li><a class="a2a_button_google_plusone"></a></li>';
		}
		if (get_option('nertworks_social_bar_addtoany_facebook_share')!="disabled"){
			$facebook_share='<li><a class="a2a_button_facebook"></a></li>';
		}
		if (get_option('nertworks_social_bar_addtoany_twitter')!="disabled"){
			$twitter='<li><a class="a2a_button_twitter"></a></li>';
		}
		if (get_option('nertworks_social_bar_addtoany_google')!="disabled"){
			$google_plus='<li><a class="a2a_button_google_plus"></a></li>';
		}
		if (get_option('nertworks_social_bar_addtoany_pinterest')!="disabled"){
			$pinterest='<li><a class="a2a_button_pinterest"></a></li>';
		}
		if (get_option('nertworks_social_bar_addtoany_share_save')!="disabled"){
			$share_save='<li><a class="a2a_dd" href="http://www.addtoany.com/share_save"></a></li>';
		}
		return '<div id="socialcontainer" class="a2a_kit '.get_option('nertworks_social_addtoany_icon_size').' a2a_default_style">
		'.$facebook_like.'
		'.$twitter_button.'
		'.$google_button.'
		'.$facebook_share.'
		'.$twitter.'
		'.$google_plus.'
		'.$pinterest.'
		'.$share_save.'
		</div>

		<script type="text/javascript" src="http://static.addtoany.com/menu/page.js"></script>';
	}
//----------------------------------AddtoanyCustom
	if (get_option('nertworks_social_bar_source')=="addtoany_custom"){
		return '<div id="socialcontainer" class="a2a_kit">
		<li><a class="a2a_button_facebook">
			<img src="http://farm3.static.flickr.com/2777/4443689395_6e0a240ce9_o.png" border="0" alt="Facebook" width="62" height="78"/>
		</a></li>
		<li><a class="a2a_button_twitter">
			<img src="http://farm5.static.flickr.com/4015/4444459286_678f428d93_o.png" border="0" alt="Twitter" width="62" height="78"/>
		</a></li>
		<li><a class="a2a_button_stumbleupon">
			<img src="http://farm3.static.flickr.com/2703/4443689409_def0f35d18_o.png" border="0" alt="Stumbleupon" width="62" height="78"/>
		</a></li>
		<li><a class="a2a_dd" href="http://www.addtoany.com/share_save">
			<img src="http://static.addtoany.com/buttons/share_save_171_16.png" border="0" alt="Share"/>
		</a></li>';
		
		return '</div>
		<script type="text/javascript" src="http://static.addtoany.com/menu/page.js"></script>';
	}
	if (get_option('nertworks_social_bar_source')==""){
		return "The Source Value is somehow Blank  Go to the NertWorks Social Share Tools Settings Page to fix this.  ";
	
	}
}
//----------------------------------	
add_filter( 'the_content', 'show_nertworks_social_bar' );
function show_nertworks_social_bar( $content )
{
	if ( (is_front_page()||is_home()) && (get_option('nertworks_social_bar_home_page_question')!="no")) {
		if (get_option('nertworks_social_bar_placement')=="before"){
			return get_social_code() . $content;
		}
		if (get_option('nertworks_social_bar_placement')=="after"){
			return $content . get_social_code();
		}
		if (get_option('nertworks_social_bar_placement')==""){
			return $content. get_social_code();
		}
	}
	if ( is_single() && (get_option('nertworks_social_bar_single_posts_question')!="no")){	
		if (get_option('nertworks_social_bar_placement')=="before"){
			return get_social_code() . $content;
		}
		if (get_option('nertworks_social_bar_placement')=="after"){
			return $content . get_social_code();
		}
		if (get_option('nertworks_social_bar_placement')==""){
			return $content;
		}
	}
	if ( is_page() && (get_option('nertworks_social_bar_page_question')!="no")){	
		if (get_option('nertworks_social_bar_placement')=="before"){
			return get_social_code() . $content;
		}
		if (get_option('nertworks_social_bar_placement')=="after"){
			return $content . get_social_code();
		}
		if (get_option('nertworks_social_bar_placement')==""){
			return $content;
		}
	}
}

register_activation_hook(__FILE__, 'nertworks_social_plugin_activate');
add_action('admin_init', 'nertworks_social_plugin_redirect');

function nertworks_social_plugin_activate() {
    add_option('nertworks_social_plugin_do_activation_redirect', true);
	update_option('nertworks_social_bar_source', 'addthis');
	update_option('nertworks_social_addthis_facebook_like', 'enabled');
	update_option('nertworks_social_addthis_twitter_button', 'enabled');
	update_option('nertworks_social_addthis_twitter_share_button', 'enabled');
	update_option('nertworks_social_addthis_facebook_share', 'enabled');
	update_option('nertworks_social_addthis_stumble_share', 'enabled');
	update_option('nertworks_social_addthis_gmail_share', 'enabled');
	update_option('nertworks_social_addthis_myspace_share', 'enabled');
	update_option('nertworks_social_addthis_plus_button', 'enabled');
	update_option('nertworks_social_addthis_facebook_like', 'enabled');
	update_option('nertworks_social_addthis_floating_share', 'enabled');
	update_option('nertworks_social_addthis_icon_size', 'addthis_32x32_style');
	//update_option('nertworks_social_addthis_floating_share_size', 'enabled');
	
	
	
	
}

function nertworks_social_plugin_redirect() {
    if (get_option('nertworks_social_plugin_do_activation_redirect', false)) {
        delete_option('nertworks_social_plugin_do_activation_redirect');
        if(!isset($_GET['activate-multi']))
        {
            wp_redirect("?page=nertworks-all-in-one-social-share-tools/nertworks-social-share-tools.php&tab=general_settings&nert_view=first_time");
        }
    }
}

function nertworks_social_tools_settings_page() {
?>
<div class="wrap">
<?php $logo=plugins_url('/images/nertworks_logo.png', __FILE__);?>
<a href="http://nertworks.com" target="_blank"><img src="<?php echo $logo; ?>" style="width:20%;"></a>
<h1><?php _e( 'Social Share Tools' ); ?></h1>
<?php settings_errors(); ?> 
<div class="about-text">
 <?php _e('A super easy way to add social share buttons to website until your heart\'s content' ); ?>
 </div>
<hr></hr>
<h2 class="nav-tab-wrapper">
<?php $tab=$_GET['tab']; 
if ($tab==NULL){
	$tab="general_settings";
}
?>	
 <a href="?page=nertworks-all-in-one-social-share-tools/nertworks-social-share-tools.php&tab=general_settings" class="nav-tab<?php if ($tab=="general_settings"){echo " nav-tab-active";}?>">
 <?php _e( 'General Settings' ); ?>
 </a><a href="?page=nertworks-all-in-one-social-share-tools/nertworks-social-share-tools.php&tab=image_genie_settings" class="nav-tab<?php if ($tab=="image_genie_settings"){echo " nav-tab-active";}?>">
 <?php _e( 'Meta Image Genie' ); ?>
 </a><a href="?page=nertworks-all-in-one-social-share-tools/nertworks-social-share-tools.php&tab=custom_icon_tool" class="nav-tab<?php if ($tab=="custom_icon_tool"){echo " nav-tab-active";}?>">
 <?php _e( 'My Custom Icons' ); ?>
 </a>
 <a href="?page=nertworks-all-in-one-social-share-tools/nertworks-social-share-tools.php&tab=extra_settings" class="nav-tab<?php if ($tab=="extra_settings"){echo " nav-tab-active";}?>">
 <?php _e( 'Extra Features' ); ?>
 </a>
 </h2>
 <!--Handle the Tabs-->
 <?php if ($tab=="general_settings"){ ?>
		 <div class="feature-section images-stagger-right">
		 <h3><?php _e( 'General Settings' ); ?></h3>
		 <form method="post" action="options.php">
			<?php settings_fields( 'nertworks-social-tools-settings-group' ); ?>
			<?php do_settings_sections( 'nertworks-social-tools-settings-group' ); ?>
		 
		<script type='text/javascript' src='http://code.jquery.com/jquery-1.6.4.js'></script>
		<script type='text/javascript'>//<![CDATA[ 
		$(window).load(function(){
		$(document).ready(function () {
			$('.group').hide();
			$('#<?php echo get_option('nertworks_social_bar_source'); ?>').fadeIn('slow');
			$('#nertworks_social_bar_source').change(function () {
				$('.group').hide();
				$('#'+$(this).val()).fadeIn('slow');
			})
		});
		});
		$(document).ajaxStart(function() {
		  $(".loading").show();
		});

		$(document).ajaxStop(function() {
		  $(".loading").hide();
		});
		//]]>  </script>
<script language="JavaScript">
jQuery(document).ready(function() {
jQuery('#upload_image_button').click(function() {
formfield = jQuery('#upload_image').attr('name');
tb_show('', 'media-upload.php?type=image&TB_iframe=true');
return false;
});

window.send_to_editor = function(html) {
imgurl = jQuery('img',html).attr('src');
jQuery('#upload_image').val(imgurl);
tb_remove();
}

});
</script>
<?php if ($_GET['nert_view']=="first_time"){
	echo '<div id="setting-error-settings_updated" class="updated settings-error"><p>Thanks for Activating this plugin.  Now you can setup which icons you would like to use and where.  Oh and don\'t forget to save</p> </div>';

}
?>
			<table class="form-table">
				<tr valign="top">
				<th scope="row"><strong>Before or After Content: </strong></th>
				<td>
				<select name="nertworks_social_bar_placement">
				<option value="before" <?php if (get_option('nertworks_social_bar_placement')=="before"){echo "selected";}?>>Before</option>
				<option value="after" <?php if ((get_option('nertworks_social_bar_placement')=="after")||(get_option('nertworks_social_bar_placement')=="")){echo "selected";}?>>After</option>
				</select>
				</td>
				</tr>
				<tr valign="top">
				<th scope="row"><strong>Mode: </strong></th>
				<td><select name="nertworks_social_bar_source" id="nertworks_social_bar_source">
				<option value="addthis" <?php if (get_option('nertworks_social_bar_source')=="addthis"){echo "selected";}?>>Addthis</option>
				<option value="addtoany" <?php if (get_option('nertworks_social_bar_source')=="addtoany"){echo "selected";}?>>Addtoany</option>
				<option value="addtoany_custom" <?php if (get_option('nertworks_social_bar_source')=="addtoany_custom"){echo "selected";}?>>Addtoany Custom</option>
				<option value="custom" <?php if (get_option('nertworks_social_bar_source')=="custom"){echo "selected";}?>>Custom</option>
				</select>
				</td>
				</tr>
				<tr valign="top">
				<th scope="row"><strong>Show on Home Page?: </strong></th>
				<td><select name="nertworks_social_bar_home_page_question">
				<option value="yes" <?php if (get_option('nertworks_social_bar_home_page_question')=="yes"){echo "selected";}?>>Yes</option>
				<option value="no" <?php if (get_option('nertworks_social_bar_home_page_question')=="no"){echo "selected";}?>>No</option>
				</select>
				</td>
				</tr>
				<tr valign="top">
				<th scope="row"><strong>Show on Single Posts?: </strong></th>
				<td><select name="nertworks_social_bar_single_posts_question">
				<option value="yes" <?php if (get_option('nertworks_social_bar_single_posts_question')=="yes"){echo "selected";}?>>Yes</option>
				<option value="no" <?php if (get_option('nertworks_social_bar_single_posts_question')=="no"){echo "selected";}?>>No</option>
				</select>
				</td>
				</tr>
				<tr valign="top">
				<th scope="row"><strong>Show on Pages?: </strong></th>
				<td><select name="nertworks_social_bar_page_question">
				<option value="yes" <?php if (get_option('nertworks_social_bar_page_question')=="yes"){echo "selected";}?>>Yes</option>
				<option value="no" <?php if (get_option('nertworks_social_bar_page_question')=="no"){echo "selected";}?>>No</option>
				</select>
				</td>
				</tr>
			</table>
		<hr></hr>
		<div id="activityDiv" class="loading">
			 <!--Hidden Divs-->
			<!--Addtoany Div-->
			<div id="addthis" class="group" >
			<table class="form-table">
				<h3>AddThis Social Bar</h3>
				<h4>Icon Size</h4>
				<label><input type="radio" name="nertworks_social_addthis_icon_size" id='4' value="" <?php if (get_option('nertworks_social_addthis_icon_size')==NULL){echo "checked";}?>/>16x16 Toolbox</label>
				<img src="<?php echo plugins_url('/images/addthis_default.png', __FILE__); ?>"><br />
				
				<label><input type="radio" name="nertworks_social_addthis_icon_size" id='5' value="addthis_32x32_style" <?php if (get_option('nertworks_social_addthis_icon_size')=="addthis_32x32_style"){echo "checked";}?>/>32x32 Toolbox</label>
				<img src="<?php echo plugins_url('/images/addthis_32.png', __FILE__); ?>"><br />
				<br />
				
				<tr valign="top">
				<th scope="row"><strong>Facebook Like: </strong></th>
				<td>
				<select name="nertworks_social_addthis_facebook_like">
					<option value="enabled" <?php if (get_option('nertworks_social_addthis_facebook_like')=="enabled"){echo "selected";}?>>Enabled</option>
					<option value="disabled" <?php if ((get_option('nertworks_social_addthis_facebook_like')=="disabled")||(get_option('nertworks_social_addthis_facebook_like')=="")){echo "selected";}?>>Disabled</option>
					</select>
				</td>
				</tr>
				<tr valign="top">
				<th scope="row"><strong>Twitter Pill: </strong></th>
				<td>
				<select name="nertworks_social_addthis_twitter_button">
					<option value="enabled" <?php if (get_option('nertworks_social_addthis_twitter_button')=="enabled"){echo "selected";}?>>Enabled</option>
					<option value="disabled" <?php if ((get_option('nertworks_social_addthis_twitter_button')=="disabled")||(get_option('nertworks_social_addthis_twitter_button')=="")){echo "selected";}?>>Disabled</option>
					</select>
				</td>
				</tr>
				
				<tr valign="top">
				<th scope="row"><strong>Facebook Share: </strong></th>
				<td>
				<select name="nertworks_social_addthis_facebook_share">
					<option value="enabled" <?php if (get_option('nertworks_social_addthis_facebook_share')=="enabled"){echo "selected";}?>>Enabled</option>
					<option value="disabled" <?php if ((get_option('nertworks_social_addthis_facebook_share')=="disabled")||(get_option('nertworks_social_addthis_facebook_share')=="")){echo "selected";}?>>Disabled</option>
					</select>
				</td>
				</tr>
				<tr valign="top">
				<th scope="row"><strong>Twitter Share: </strong></th>
				<td>
				<select name="nertworks_social_addthis_twitter_share_button">
					<option value="enabled" <?php if (get_option('nertworks_social_addthis_twitter_share_button')=="enabled"){echo "selected";}?>>Enabled</option>
					<option value="disabled" <?php if ((get_option('nertworks_social_addthis_twitter_share_button')=="disabled")||(get_option('nertworks_social_addthis_twitter_share_button')=="")){echo "selected";}?>>Disabled</option>
					</select>
				</td>
				</tr>
				<tr valign="top">
				<th scope="row"><strong>Stumble Share: </strong></th>
				<td>
				<select name="nertworks_social_addthis_stumble_share">
					<option value="enabled" <?php if (get_option('nertworks_social_addthis_stumble_share')=="enabled"){echo "selected";}?>>Enabled</option>
					<option value="disabled" <?php if ((get_option('nertworks_social_addthis_stumble_share')=="disabled")||(get_option('nertworks_social_addthis_stumble_share')=="")){echo "selected";}?>>Disabled</option>
					</select>
				</td>
				</tr>
				
				<tr valign="top">
				<th scope="row"><strong>Gmail Share: </strong></th>
				<td>
				<select name="nertworks_social_addthis_gmail_share">
					<option value="enabled" <?php if (get_option('nertworks_social_addthis_gmail_share')=="enabled"){echo "selected";}?>>Enabled</option>
					<option value="disabled" <?php if ((get_option('nertworks_social_addthis_gmail_share')=="disabled")||(get_option('nertworks_social_addthis_gmail_share')=="")){echo "selected";}?>>Disabled</option>
					</select>
				</td>
				</tr>
				
				<tr valign="top">
				<th scope="row"><strong>Myspace Share: </strong></th>
				<td>
				<select name="nertworks_social_addthis_myspace_share">
					<option value="enabled" <?php if (get_option('nertworks_social_addthis_myspace_share')=="enabled"){echo "selected";}?>>Enabled</option>
					<option value="disabled" <?php if ((get_option('nertworks_social_addthis_myspace_share')=="disabled")||(get_option('nertworks_social_addthis_myspace_share')=="")){echo "selected";}?>>Disabled</option>
					</select>
				</td>
				</tr>
				
				<tr valign="top">
				<th scope="row"><strong>Addthis Share: </strong></th>
				<td>
				<select name="nertworks_social_addthis_plus_button">
					<option value="enabled" <?php if (get_option('nertworks_social_addthis_plus_button')=="enabled"){echo "selected";}?>>Enabled</option>
					<option value="disabled" <?php if ((get_option('nertworks_social_addthis_plus_button')=="disabled")||(get_option('nertworks_social_addthis_plus_button')=="")){echo "selected";}?>>Disabled</option>
					</select>
				</td>
				</tr>
				<tr valign="top">
				<th scope="row"><strong>Floating Share Bar: </strong></th>
				<td>
				<select name="nertworks_social_addthis_floating_share">
					<option value="enabled" <?php if (get_option('nertworks_social_addthis_floating_share')=="enabled"){echo "selected";}?>>Enabled</option>
					<option value="disabled" <?php if ((get_option('nertworks_social_addthis_floating_share')=="disabled")||(get_option('nertworks_social_addthis_floating_share')=="")){echo "selected";}?>>Disabled</option>
					</select>
				</td>
				</tr>
				<tr valign="top">
				<th scope="row"><strong>Floating Share Bar Size: </strong></th>
				<td>
				<select name="nertworks_social_addthis_floating_share_size">
					<option value="addthis_16x16_style" <?php if (get_option('nertworks_social_addthis_floating_share_size')=="addthis_bar_large"){echo "selected";}?>>16x16</option>
					
					<option value="addthis_32x32_style" <?php if ((get_option('nertworks_social_addthis_floating_share_size')=="addthis_32x32_style")||(get_option('nertworks_social_addthis_floating_share_size')=="")){echo "selected";}?>>32x32</option>
					</select>
				</td>
				</tr>
			</table>
			</div>
			<!--Addtoany Div-->
			<div id="addtoany" class="group" > 
			<h3>Addtoany Social Share Bar</h3>
				<table class="form-table">
				<tr valign="top">
				<th scope="row"><strong>Default</strong></th>
				<td>
				<input type="radio" name="nertworks_social_addtoany_icon_size" id='4' value="" <?php if (get_option('nertworks_social_addtoany_icon_size')==""){echo "checked";}?>/>
				<img src="<?php echo plugins_url('/images/addtoany_default.png', __FILE__); ?>">
				</td>
				</tr>
				
				<tr valign="top">
				<th scope="row"><strong>32x32 Toolbox</strong></th>
				<td>
				<input type="radio" name="nertworks_social_addtoany_icon_size" id='4' value="a2a_kit_size_32" <?php if (get_option('nertworks_social_addtoany_icon_size')=="a2a_kit_size_32"){echo "checked";}?>/>
				<img src="<?php echo plugins_url('/images/addtoany_32.png', __FILE__); ?>">
				</td>
				</tr>
				
				<tr valign="top">
				<th scope="row"><strong>64x64 Toolbox</strong></th>
				<td>
				<input type="radio" name="nertworks_social_addtoany_icon_size" id='4' value="a2a_kit_size_64" <?php if (get_option('nertworks_social_addtoany_icon_size')=="a2a_kit_size_64"){echo "checked";}?>/>
				<img src="<?php echo plugins_url('/images/addtoany_64.png', __FILE__); ?>">
				</td>
				</tr>
				
				<tr valign="top">
				<th scope="row"><strong>Facebook Like: </strong></th>
				<td>
				<select name="nertworks_social_bar_addtoany_facebook_like">
					<option value="enabled" <?php if ((get_option('nertworks_social_bar_addtoany_facebook_like')=="enabled")||(get_option('nertworks_social_bar_addtoany_facebook_like')=="")){echo "selected";}?>>Enabled</option>
					<option value="disabled" <?php if (get_option('nertworks_social_bar_addtoany_facebook_like')=="disabled"){echo "selected";}?>>Disabled</option>
					</select>
				</td>
				</tr>
				<tr valign="top">
				<th scope="row"><strong>Twitter Pill: </strong></th>
				<td>
				<select name="nertworks_social_bar_addtoany_twitter_button">
					<option value="enabled" <?php if ((get_option('nertworks_social_bar_addtoany_twitter_button')=="enabled")||(get_option('nertworks_social_bar_addtoany_facebook_like')=="")){echo "selected";}?>>Enabled</option>
					<option value="disabled" <?php if (get_option('nertworks_social_bar_addtoany_twitter_button')=="disabled"){echo "selected";}?>>Disabled</option>
					</select>
				</td>
				</tr>
				<tr valign="top">
				<th scope="row"><strong>Google Plus: </strong></th>
				<td>
				<select name="nertworks_social_bar_addtoany_google_button">
					<option value="enabled" <?php if ((get_option('nertworks_social_bar_addtoany_google_button')=="enabled")||(get_option('nertworks_social_bar_addtoany_google_button')=="")){echo "selected";}?>>Enabled</option>
					<option value="disabled" <?php if (get_option('nertworks_social_bar_addtoany_google_button')=="disabled"){echo "selected";}?>>Disabled</option>
					</select>
				</td>
				</tr>
				
				<tr valign="top">
				<th scope="row"><strong>Facebook Share Button: </strong></th>
				<td>
				<select name="nertworks_social_bar_addtoany_facebook_share">
					<option value="enabled" <?php if ((get_option('nertworks_social_bar_addtoany_facebook_share')=="enabled")||(get_option('nertworks_social_bar_addtoany_facebook_share')=="")){echo "selected";}?>>Enabled</option>
					<option value="disabled" <?php if (get_option('nertworks_social_bar_addtoany_facebook_share')=="disabled"){echo "selected";}?>>Disabled</option>
				</select>
				</td>
				</tr>
				<tr valign="top">
				<th scope="row"><strong>Twitter Box: </strong></th>
				<td>
				<select name="nertworks_social_bar_addtoany_twitter">
					<option value="enabled" <?php if ((get_option('nertworks_social_bar_addtoany_twitter')=="enabled")||(get_option('nertworks_social_bar_addtoany_twitter')=="")){echo "selected";}?>>Enabled</option>
					<option value="disabled" <?php if (get_option('nertworks_social_bar_addtoany_twitter')=="disabled"){echo "selected";}?>>Disabled</option>
				</select>
				</td>
				</tr>
				<tr valign="top">
				<th scope="row"><strong>Google Plus Box: </strong></th>
				<td>
				<select name="nertworks_social_bar_addtoany_google">
					<option value="enabled" <?php if ((get_option('nertworks_social_bar_addtoany_google')=="enabled")||(get_option('nertworks_social_bar_addtoany_google')=="")){echo "selected";}?>>Enabled</option>
					<option value="disabled" <?php if (get_option('nertworks_social_bar_addtoany_google')=="disabled"){echo "selected";}?>>Disabled</option>
				</select>
				</td>
				</tr>
				<tr valign="top">
				<th scope="row"><strong>Pinterest Box: </strong></th>
				<td>
				<select name="nertworks_social_bar_addtoany_pinterest">
					<option value="enabled" <?php if ((get_option('nertworks_social_bar_addtoany_pinterest')=="enabled")||(get_option('nertworks_social_bar_addtoany_pinterest')=="")){echo "selected";}?>>Enabled</option>
					<option value="disabled" <?php if (get_option('nertworks_social_bar_addtoany_pinterest')=="disabled"){echo "selected";}?>>Disabled</option>
				</select>
				</td>
				</tr>
				<tr valign="top">
				<th scope="row"><strong>Share Save Box: </strong></th>
				<td>
				<select name="nertworks_social_bar_addtoany_share_save">
					<option value="enabled" <?php if ((get_option('nertworks_social_bar_addtoany_share_save')=="enabled")||(get_option('nertworks_social_bar_addtoany_share_save')=="")){echo "selected";}?>>Enabled</option>
					<option value="disabled" <?php if (get_option('nertworks_social_bar_addtoany_share_save')=="disabled"){echo "selected";}?>>Disabled</option>
				</select>
				</td>
				</tr>
				
				
				</table>				
			</div>
<!--AddtoAnyCustom -->
			<div id="addtoany_custom" class="group" > 
				<h3>Addtoany Custom Social Share Bar</h3>
				<img src="<?php echo plugins_url('/images/addtoany_custom.png', __FILE__); ?>"><br />
				
			</div>
<!--Custom Section -->
			<div id="custom" class="group" >
				<h3>Custom Social Share Bar</h3>
				<?php 
				if (get_option('nertworks_social_bar_custom_icon_size')!=NULL){
					$icon_size=get_option('nertworks_social_bar_custom_icon_size'); 
				}
				else {
					$icon_size=24;
				}
				?>
				
				
				<table class="form-table">
				<tr valign="top">
				<td><strong>Stumble Icon Choices</strong></td>
				<th scope="row"><strong>Disabled</strong></th>
				<td>	
					<input type="radio" name="nertworks_social_custom_stumble_icon" id='4' value="disabled" <?php if (get_option('nertworks_social_custom_stumble_icon')=="disabled"){echo "checked";}?>/>
				</td>
				</tr>
				
				<tr valign="top">
				<td></td>
				<th scope="row"><strong>Round</strong></th>
				<td>
					<input type="radio" name="nertworks_social_custom_stumble_icon" id='4' value="stumble_round1" <?php if ((get_option('nertworks_social_custom_stumble_icon')=="")||(get_option('nertworks_social_custom_stumble_icon')=="stumble_round1")){echo "checked";}?>/>
					<img src="<?php echo plugins_url('/images/stumble.png', __FILE__); ?>" width="<?php echo $icon_size; ?>">
				</td>
				</tr>
				<tr valign="top">
				<td></td>
				<th scope="row"><strong>Square</strong></th>
				<td>	
					<input type="radio" name="nertworks_social_custom_stumble_icon" id='4' value="stumble_square" <?php if (get_option('nertworks_social_custom_stumble_icon')=="stumble_square"){echo "checked";}?>/>
				<img src="<?php echo plugins_url('/images/stumble_square.png', __FILE__); ?>" width="<?php echo $icon_size; ?>">
				</td>
				</tr>
				
				<tr valign="top">
				<td></td>
				<th scope="row"><strong>Custom Stumble</strong></th>
				<td>	
				
				<?php if (get_option('nertworks_social_custom_stumble_icon_uploaded')!=NULL){?>
				<input type="radio" name="nertworks_social_custom_stumble_icon" id='4' value="my_stumble_icon" <?php if (get_option('nertworks_social_custom_stumble_icon')=="my_stumble_icon"){echo "checked";}?>/>
				<img src="<?php echo get_option('nertworks_social_custom_stumble_icon_uploaded'); ?>" width="<?php echo $icon_size; ?>">
				<?php } 
				else {
					echo '<a href="?page=nertworks-all-in-one-social-share-tools/nertworks-social-share-tools.php&tab=custom_icon_tool&button_type=nertworks_social_custom_stumble_icon_uploaded" class="button">+</a>';
				}?>
				</td>
				</tr>
				
				<!--Twitter-->
				<tr valign="top">
				<td><strong>Twitter Icon Choices</strong></td>
				<th scope="row"><strong>Disabled</strong></th>
				<td>	
					<input type="radio" name="nertworks_social_custom_twitter_icon" id='4' value="disabled" <?php if (get_option('nertworks_social_custom_twitter_icon')=="disabled"){echo "checked";}?>/>
				
				</td>
				</tr>
				
				<tr valign="top">
				<td></td>
				<th scope="row"><strong>Round</strong></th>
				<td>	
					<input type="radio" name="nertworks_social_custom_twitter_icon" id='4' value="twitter_round1" <?php if ((get_option('nertworks_social_custom_twitter_icon')=="")||(get_option('nertworks_social_custom_twitter_icon')=="twitter_round1")){echo "checked";}?>/>
					<img src="<?php echo plugins_url('/images/twitter.png', __FILE__); ?>" width="<?php echo $icon_size; ?>">
					</td>
				</tr>
				
				<tr valign="top">
				<td></td>
				<th scope="row"><strong>Square</strong></th>
				<td>	
					<input type="radio" name="nertworks_social_custom_twitter_icon" id='4' value="twitter_square" <?php if (get_option('nertworks_social_custom_twitter_icon')=="twitter_square"){echo "checked";}?>/>
				<img src="<?php echo plugins_url('/images/twitter_square.png', __FILE__); ?>" width="<?php echo $icon_size; ?>">
				</td>
				</tr>
				
				<tr valign="top">
				<td></td>
				<th scope="row"><strong>Custom Twitter</strong></th>
				<td>	
				
				<?php if (get_option('nertworks_social_custom_twitter_icon_uploaded')!=NULL){?>
				<input type="radio" name="nertworks_social_custom_twitter_icon" id='4' value="my_twitter_icon" <?php if (get_option('nertworks_social_custom_twitter_icon')=="my_twitter_icon"){echo "checked";}?>/>
				<img src="<?php echo get_option('nertworks_social_custom_twitter_icon_uploaded'); ?>" width="<?php echo $icon_size; ?>">
				<?php } 
				else {
					echo '<a href="?page=nertworks-all-in-one-social-share-tools/nertworks-social-share-tools.php&tab=custom_icon_tool&button_type=nertworks_social_custom_twitter_icon_uploaded" class="button">+</a>';
				}?>
				</td>
				</tr>
				
				<!--Pinterest-->
				<tr valign="top">
				<td><strong>Pinterest Icon Choices</strong></td>
				<th scope="row"><strong>Disabled</strong></th>
				<td>
				<input type="radio" name="nertworks_social_custom_pinterest_icon" id='4' value="disabled" <?php if (get_option('nertworks_social_custom_pinterest_icon')=="disabled"){echo "checked";}?>/>
				</td>
				</tr>
				
				<tr valign="top">
				<td></td>				
				<th scope="row"><strong>Round</strong></th>
				<td>	
					<input type="radio" name="nertworks_social_custom_pinterest_icon" id='4' value="pinterest_round1" <?php if ((get_option('nertworks_social_custom_pinterest_icon')=="")||(get_option('nertworks_social_custom_pinterest_icon')=="pinterest_round1")){echo "checked";}?>/>
				<img src="<?php echo plugins_url('/images/pinterest.png', __FILE__); ?>" width="<?php echo $icon_size; ?>">
				</td>
				</tr>
				
				<tr valign="top">
				<td></td>
				<th scope="row"><strong>Square</strong></th>
				<td>
				<input type="radio" name="nertworks_social_custom_pinterest_icon" id='4' value="pinterest_square" <?php if (get_option('nertworks_social_custom_pinterest_icon')=="pinterest_square"){echo "checked";}?>/>
				<img src="<?php echo plugins_url('/images/pinterest_square.png', __FILE__); ?>" width="<?php echo $icon_size; ?>">
				</td>
				</tr>
				
				<tr valign="top">
				<td></td>
				<th scope="row"><strong>Custom Pinterest</strong></th>
				<td>	
				
				<?php if (get_option('nertworks_social_custom_pinterest_icon_uploaded')!=NULL){?>
				<input type="radio" name="nertworks_social_custom_pinterest_icon" id='4' value="my_pinterest_icon" <?php if (get_option('nertworks_social_custom_pinterest_icon')=="my_pinterest_icon"){echo "checked";}?>/>
				<img src="<?php echo get_option('nertworks_social_custom_pinterest_icon_uploaded'); ?>" width="<?php echo $icon_size; ?>">
				<?php } 
				else {
					echo '<a href="?page=nertworks-all-in-one-social-share-tools/nertworks-social-share-tools.php&tab=custom_icon_tool&button_type=nertworks_social_custom_pinterest_icon_uploaded" class="button">+</a>';
				}?>
				</td>
				</tr>
				
				<!--Linkedin-->
				<tr valign="top">
				<td><strong>Linkedin Icon Choices</strong></td>
				<th scope="row"><strong>Disabled</strong></th>
				<td>
				<input type="radio" name="nertworks_social_custom_linkedin_icon" id='4' value="disabled" <?php if (get_option('nertworks_social_custom_linkedin_icon')=="disabled"){echo "checked";}?>/> 
				</td>
				</tr>
				<tr valign="top">
				<td></td>
				<th scope="row"><strong>Round</strong></th>
				<td>
				<input type="radio" name="nertworks_social_custom_linkedin_icon" id='4' value="linkedin_round1" <?php if ((get_option('nertworks_social_custom_linkedin_icon')=="")||(get_option('nertworks_social_custom_linkedin_icon')=="linkedin_round1")){echo "checked";}?>/>
				<img src="<?php echo plugins_url('/images/linkedin.png', __FILE__); ?>" width="<?php echo $icon_size; ?>">
					</td>
				</tr>
				
				<tr valign="top">
				<td></td>
				<th scope="row"><strong>Square</strong></th>
				<td>
				<input type="radio" name="nertworks_social_custom_linkedin_icon" id='4' value="linkedin_square" <?php if (get_option('nertworks_social_custom_linkedin_icon')=="linkedin_square"){echo "checked";}?>/> 
				<img src="<?php echo plugins_url('/images/linkedin_square.png', __FILE__); ?>" width="<?php echo $icon_size; ?>">
					</td>
				</tr>
				
				<tr valign="top">
				<td></td>
				<th scope="row"><strong>Custom Linkedin</strong></th>
				<td>	
				
				<?php if (get_option('nertworks_social_custom_linkedin_icon_uploaded')!=NULL){?>
				<input type="radio" name="nertworks_social_custom_linkedin_icon" id='4' value="my_linkedin_icon" <?php if (get_option('nertworks_social_custom_linkedin_icon')=="my_linkedin_icon"){echo "checked";}?>/>
				<img src="<?php echo get_option('nertworks_social_custom_linkedin_icon_uploaded'); ?>" width="<?php echo $icon_size; ?>">
				<?php } 
				else {
					echo '<a href="?page=nertworks-all-in-one-social-share-tools/nertworks-social-share-tools.php&tab=custom_icon_tool&button_type=nertworks_social_custom_linkedin_icon_uploaded" class="button">+</a>';
				}?>
				
				</td>
				
				</tr>
				
				<!--Facebook-->
				<tr valign="top">
				<td><strong>Facebook Icon Choices</strong></td>
				<th scope="row"><strong>Disabled</strong></th>
				<td>
				<input type="radio" name="nertworks_social_custom_facebook_icon" id='4' value="disabled" <?php if (get_option('nertworks_social_custom_facebook_icon')=="disabled"){echo "checked";}?>/> 
				</td>
				</tr>
				<tr valign="top">
				<td></td>
				<th scope="row"><strong>Round</strong></th>
				<td>
				<input type="radio" name="nertworks_social_custom_facebook_icon" id='4' value="facebook_round1" <?php if ((get_option('nertworks_social_custom_facebook_icon')=="")||(get_option('nertworks_social_custom_facebook_icon')=="facebook_round1")){echo "checked";}?>/>
				<img src="<?php echo plugins_url('/images/facebook.png', __FILE__); ?>" width="<?php echo $icon_size; ?>">
					</td>
				</tr>
				
				<tr valign="top">
				<td></td>
				<th scope="row"><strong>Square</strong></th>
				<td>
				<input type="radio" name="nertworks_social_custom_facebook_icon" id='4' value="facebook_square" <?php if (get_option('nertworks_social_custom_facebook_icon')=="facebook_square"){echo "checked";}?>/> 
				<img src="<?php echo plugins_url('/images/facebook_square.png', __FILE__); ?>" width="<?php echo $icon_size; ?>">
					</td>
				</tr>
				
				<tr valign="top">
				<td></td>
				<th scope="row"><strong>Custom Facebook</strong></th>
				<td>	
				
				<?php if (get_option('nertworks_social_custom_facebook_icon_uploaded')!=NULL){?>
				<input type="radio" name="nertworks_social_custom_facebook_icon" id='4' value="my_facebook_icon" <?php if (get_option('nertworks_social_custom_facebook_icon')=="my_facebook_icon"){echo "checked";}?>/>
				<img src="<?php echo get_option('nertworks_social_custom_facebook_icon_uploaded'); ?>" width="<?php echo $icon_size; ?>">
				<?php } 
				else {
					echo '<a href="?page=nertworks-all-in-one-social-share-tools/nertworks-social-share-tools.php&tab=custom_icon_tool&button_type=nertworks_social_custom_facebook_icon_uploaded" class="button">+</a>';
				}?>
				
				</td>
				
				</tr>			

			
				
				
				<tr valign="top">
				<th scope="row"><strong>Icon Size:</strong></th>
				<td>
				<select name="nertworks_social_bar_custom_icon_size">
						<option value="16" <?php if (get_option('nertworks_social_bar_custom_icon_size')=="16"){echo "selected";}?>>16</option>
						<option value="24" <?php if ((get_option('nertworks_social_bar_custom_icon_size')=="24")||(get_option('nertworks_social_bar_custom_icon_size')=="")){echo "selected";}?>>24</option>
						<option value="48" <?php if (get_option('nertworks_social_bar_custom_icon_size')=="48"){echo "selected";}?>>48</option>
						<option value="64" <?php if (get_option('nertworks_social_bar_custom_icon_size')=="64"){echo "selected";}?>>64</option>
						</select>
						</td>
				</tr>
				</table>
			</div>
		</div><!--activityDiv-->   
		<hr></hr>
		<?php submit_button(); ?>

		</form>
		<?php 
}
elseif ($tab=="image_genie_settings"){
	?>
	<h3><?php _e( 'Meta Image Genie Settings' ); ?></h3>
	<form method="post" action="options.php">
			<?php settings_fields( 'nertworks-social-tools-settings-group_advanced' ); ?>
			<?php do_settings_sections( 'nertworks-social-tools-settings-group_advanced' ); ?>
			<table class="form-table">
				<tr valign="top">
				<i>Meta Image Genie makes sharing content much easier by automatically placing images in your website's header for social networks to use.</i>
				
				</tr>
				<tr valign="top">
				<th scope="row"><strong>Enable Meta Image Genie: </strong></th>
				<td>
				<select name="nertworks_social_image_genie">
				<option value="enabled" <?php if ((get_option('nertworks_social_image_genie')=="enabled")||(get_option('nertworks_social_image_genie')=="")){echo "selected";}?>>Enabled</option>
				<option value="disabled" <?php if (get_option('nertworks_social_image_genie')=="disabled"){echo "selected";}?>>Disabled</option>
				</select>
				</td>
				</tr>
			</table>
		<hr></hr>
		<?php submit_button(); ?>

		</form>
<?php
}
elseif ($tab=="custom_icon_tool"){
?><h3><?php _e( 'Social Networks' ); ?></h3>
<script language="JavaScript">
jQuery(document).ready(function() {
jQuery('#upload_image_button').click(function() {
formfield = jQuery('#upload_image').attr('name');
tb_show('', 'media-upload.php?type=image&TB_iframe=true');
return false;
});

window.send_to_editor = function(html) {
imgurl = jQuery('img',html).attr('src');
jQuery('#upload_image').val(imgurl);
tb_remove();
}

});
</script>

<table class="form-table">
 
	
	<?php if ($_GET['button_type']!=NULL){?>
	<form method="post" action="options.php">
			
	
	<?php if ($_GET['button_type']=="nertworks_social_custom_stumble_icon_uploaded"){?>
		<tr valign="top">
		<th scope="row"><strong>Stumble Icon Management : </strong></th>
		<td><label for="upload_image">
		<?php settings_fields( 'nertworks-social-tools-settings-group-custom-icons-stumble' ); ?>
		<?php do_settings_sections( 'nertworks-social-tools-settings-group-custom-icons-stumble' ); ?>
		
		<input id="upload_image" type="text" size="36" name="<?php echo $_GET['button_type']; ?>" value="<?php echo $myimage; ?>" />
		<?php 
		$type="stumble";
		}?>
	<?php if ($_GET['button_type']=="nertworks_social_custom_twitter_icon_uploaded"){?>
		<tr valign="top">
		<th scope="row"><strong>Twitter Icon Management : </strong></th>
		<td><label for="upload_image">
		<?php settings_fields( 'nertworks-social-tools-settings-group-custom-icons-twitter' ); ?>
		<?php do_settings_sections( 'nertworks-social-tools-settings-group-custom-icons-twitter' ); ?>
		
		<input id="upload_image" type="text" size="36" name="<?php echo $_GET['button_type']; ?>" value="<?php echo $myimage; ?>" />
		<?php 
		$type="twitter";
		}?>
	<?php if ($_GET['button_type']=="nertworks_social_custom_pinterest_icon_uploaded"){?>
		<tr valign="top">
		<th scope="row"><strong>Pinterest Icon Management : </strong></th>
		<td><label for="upload_image">
		<?php settings_fields( 'nertworks-social-tools-settings-group-custom-icons-pinterest' ); ?>
		<?php do_settings_sections( 'nertworks-social-tools-settings-group-custom-icons-pinterest' ); ?>
		
		<input id="upload_image" type="text" size="36" name="<?php echo $_GET['button_type']; ?>" value="<?php echo $myimage; ?>" />
		<?php 
		$type="pinterest";
		}?>	
		<?php if ($_GET['button_type']=="nertworks_social_custom_linkedin_icon_uploaded"){?>
		<tr valign="top">
		<th scope="row"><strong>Linkedin Icon Management : </strong></th>
		<td><label for="upload_image">
		<?php settings_fields( 'nertworks-social-tools-settings-group-custom-icons-linkedin' ); ?>
		<?php do_settings_sections( 'nertworks-social-tools-settings-group-custom-icons-linkedin' ); ?>
		
		<input id="upload_image" type="text" size="36" name="<?php echo $_GET['button_type']; ?>" value="<?php echo $myimage; ?>" />
		<?php 
		$type="linkedin";
		}?>
		<?php if ($_GET['button_type']=="nertworks_social_custom_facebook_icon_uploaded"){?>
		<tr valign="top">
		<th scope="row"><strong>Facebook Icon Management : </strong></th>
		<td><label for="upload_image">
		<?php settings_fields( 'nertworks-social-tools-settings-group-custom-icons-facebook' ); ?>
		<?php do_settings_sections( 'nertworks-social-tools-settings-group-custom-icons-facebook' ); ?>
		
		<input id="upload_image" type="text" size="36" name="<?php echo $_GET['button_type']; ?>" value="<?php echo $myimage; ?>" />
		<?php 
		$type="facebook";
		}?>
		<input id="upload_image_button" type="button" value="Choose Icon" />
		<br />You can upload your own icon, choose from the gallery, or paste an image url
		</label>
	</td>
	</tr>
	
	<tr valign="top">
	<th scope="row"></th>
	<td><?php submit_button(); ?></td>
	
	
	</tr>
	
	<?php 
	if ($_GET['nert_option_removal']!=NULL){
		update_option($_GET['nert_option_removal'], '');
		update_option($_GET['short_type'], '');
		//$status= "Successfully removed option ";
		$actual_link = "?page=nertworks-all-in-one-social-share-tools/nertworks-social-share-tools.php&tab=custom_icon_tool&button_type=".$_GET['button_type'];
		//echo '<a href="'.$actual_link.'">Click me</a>';
		wp_redirect( $actual_link, $status );
	}
	if (get_option($_GET['button_type'])!=NULL){
		$current_icon='<img class="thumbnail" src="'.get_option($_GET['button_type']).'" width="128">';$remove_link='<a href="?page=nertworks-all-in-one-social-share-tools/nertworks-social-share-tools.php&tab=custom_icon_tool&nert_option_removal='.$_GET['button_type'].'&button_type='.$_GET['button_type'].'&short_type=nertworks_social_custom_'.$type.'_icon">Remove</a>';}
	else {
		$current_icon="You haven't uploaded a custom icon for this ".ucfirst($type)." yet";$remove_link="";
	}
	?>
	
	<tr valign="top">
	<th scope="row"><strong>Your Current <?php echo $type; ?> Icon: </strong></th>
	<td><?php echo $current_icon.$remove_link; ?></td>
	</tr>

	</form>
	<?php 
	}
	else {
	?>
	<tr valign="top">
	<th scope="row"></th>
	<td><img src="<?php echo plugins_url( 'images/stumble_logo.png' , __FILE__ ); ?>" width="200px"></td>
	<td><a href="?page=nertworks-all-in-one-social-share-tools/nertworks-social-share-tools.php&tab=custom_icon_tool&button_type=nertworks_social_custom_stumble_icon_uploaded" class="button"><img src="<?php echo plugins_url( 'images/wrench.png' , __FILE__ ); ?>" width="16px" title="Manage Your Stumble Icon"></a></td>
	</tr>
	
	<tr valign="top">
	<th scope="row"></th>
	<td><img src="<?php echo plugins_url( 'images/twitter_logo.png' , __FILE__ ); ?>" width="200px"></td>
	<td><a href="?page=nertworks-all-in-one-social-share-tools/nertworks-social-share-tools.php&tab=custom_icon_tool&button_type=nertworks_social_custom_twitter_icon_uploaded" class="button"><img src="<?php echo plugins_url( 'images/wrench.png' , __FILE__ ); ?>" width="16px" title="Manage Your Twitter Icon"></a> </td>
	
	</tr>
	
	<tr valign="top">
	<th scope="row"></th>
	<td><img src="<?php echo plugins_url( 'images/pinterest_logo.png' , __FILE__ ); ?>" width="200px"></td>
	<td><a href="?page=nertworks-all-in-one-social-share-tools/nertworks-social-share-tools.php&tab=custom_icon_tool&button_type=nertworks_social_custom_pinterest_icon_uploaded" class="button"><img src="<?php echo plugins_url( 'images/wrench.png' , __FILE__ ); ?>" width="16px" title="Manage Your Pinterest Icon"></a> </td>
	</tr>
	
	<tr valign="top">
	<th scope="row"></th>
	<td><img src="<?php echo plugins_url( 'images/linkedin_logo.png' , __FILE__ ); ?>" width="200px"></td>
	<td><a href="?page=nertworks-all-in-one-social-share-tools/nertworks-social-share-tools.php&tab=custom_icon_tool&button_type=nertworks_social_custom_linkedin_icon_uploaded" class="button"><img src="<?php echo plugins_url( 'images/wrench.png' , __FILE__ ); ?>" width="16px" title="Manage Your Linkedin Icon"></a> </td>
	</tr>
	<tr valign="top">
	<th scope="row"></th>
	<td><img src="<?php echo plugins_url( 'images/facebook_logo.png' , __FILE__ ); ?>" width="200px"></td>
	<td><a href="?page=nertworks-all-in-one-social-share-tools/nertworks-social-share-tools.php&tab=custom_icon_tool&button_type=nertworks_social_custom_facebook_icon_uploaded" class="button"><img src="<?php echo plugins_url( 'images/wrench.png' , __FILE__ ); ?>" width="16px" title="Manage Your Facebook Icon"></a> </td>
	</tr>
	
	<?php }
	?>
	
	


</table>
<?php
	//echo '<a href="http://nertworks.com/wp-admin/media-new.php" class="button">New Tab Upload Icon</a>';

}
elseif ($tab=="extra_settings"){
?><h3><?php _e( 'Extra Settings' ); ?></h3>
<?php
	echo "Extra Features coming soon.  Maybe some coffee will help speed things up.  ";
}
?>
</div><!--Tab End-->
<hr></hr>
<div id="donateDiv">

<i>Keep Nick and Allen awake with coffee to work on updates, features and bugs. Good coffee equals good code.</i> 
<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
<input type="hidden" name="cmd" value="_s-xclick">
<input type="hidden" name="hosted_button_id" value="D6FXJUCLE6RGY">
<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
</form>


<hr></hr>

</div><!--donateDiv-->

</div><!--wrap-->

<?php 
}
	
function nertworks_social_admin_scripts() {
wp_enqueue_script('media-upload');
wp_enqueue_script('thickbox');
wp_enqueue_script('jquery');
}

function nertworks_social_admin_styles() {
wp_enqueue_style('thickbox');
}

add_action('admin_print_scripts', 'nertworks_social_admin_scripts');
add_action('admin_print_styles', 'nertworks_social_admin_styles');
	
//Adding the CSS File
add_action( 'wp_enqueue_scripts', 'nertworks_social_tools_stylesheet' );

/**
 * Enqueue plugin style-file
 */
function nertworks_social_tools_stylesheet() {
    // Respects SSL, Style.css is relative to the current file
    wp_register_style( 'prefix-style', plugins_url('style.css', __FILE__) );
    wp_enqueue_style( 'prefix-style' );
}

if ( is_admin() ){ // admin actions
// create custom plugin settings menu
	add_action('admin_menu', 'nertworks_social_tool_menu');
}

function nertworks_social_tool_menu() {
	//create new top-level menu
	add_menu_page('NW Social Tools', 'NW Social Tools', 'administrator', __FILE__, 'nertworks_social_tools_settings_page',plugins_url('/images/icon16.png', __FILE__));

	//call register settings function
	add_action( 'admin_init', 'register_nertworks_social_settings' );
}
function register_nertworks_social_settings() {
	//register our settings
	register_setting( 'nertworks-social-tools-settings-group', 'nertworks_social_bar_placement' );
	register_setting( 'nertworks-social-tools-settings-group', 'nertworks_social_bar_source' );
	register_setting( 'nertworks-social-tools-settings-group', 'nertworks_social_bar_home_page_question' );
	register_setting( 'nertworks-social-tools-settings-group', 'nertworks_social_bar_single_posts_question' );
	register_setting( 'nertworks-social-tools-settings-group', 'nertworks_social_bar_page_question' );
	register_setting( 'nertworks-social-tools-settings-group', 'nertworks_social_addthis_icon_size' );
	register_setting( 'nertworks-social-tools-settings-group', 'nertworks_social_addtoany_icon_size' );
	
	register_setting( 'nertworks-social-tools-settings-group', 'nertworks_social_custom_stumble_icon' );
	register_setting( 'nertworks-social-tools-settings-group', 'nertworks_social_custom_twitter_icon' );
	register_setting( 'nertworks-social-tools-settings-group', 'nertworks_social_custom_pinterest_icon' );
	register_setting( 'nertworks-social-tools-settings-group', 'nertworks_social_custom_linkedin_icon' );
	register_setting( 'nertworks-social-tools-settings-group', 'nertworks_social_custom_facebook_icon' );
	register_setting( 'nertworks-social-tools-settings-group', 'nertworks_social_bar_custom_icon_size' );
	
	register_setting( 'nertworks-social-tools-settings-group', 'nertworks_social_addthis_facebook_like' );
	register_setting( 'nertworks-social-tools-settings-group', 'nertworks_social_addthis_twitter_button' );
	register_setting( 'nertworks-social-tools-settings-group', 'nertworks_social_addthis_twitter_share_button' );
	register_setting( 'nertworks-social-tools-settings-group', 'nertworks_social_addthis_facebook_share' );
	register_setting( 'nertworks-social-tools-settings-group', 'nertworks_social_addthis_stumble_share' );
	register_setting( 'nertworks-social-tools-settings-group', 'nertworks_social_addthis_gmail_share' );
	register_setting( 'nertworks-social-tools-settings-group', 'nertworks_social_addthis_myspace_share' );
	register_setting( 'nertworks-social-tools-settings-group', 'nertworks_social_addthis_plus_button' );
	register_setting( 'nertworks-social-tools-settings-group', 'nertworks_social_addthis_floating_share' );
	register_setting( 'nertworks-social-tools-settings-group', 'nertworks_social_addthis_floating_share_size' );
	
	register_setting( 'nertworks-social-tools-settings-group', 'nertworks_social_bar_addtoany_facebook_like' );
	register_setting( 'nertworks-social-tools-settings-group', 'nertworks_social_bar_addtoany_twitter_button' );
	register_setting( 'nertworks-social-tools-settings-group', 'nertworks_social_bar_addtoany_google_button' );	
	register_setting( 'nertworks-social-tools-settings-group', 'nertworks_social_bar_addtoany_facebook_share' );
	register_setting( 'nertworks-social-tools-settings-group', 'nertworks_social_bar_addtoany_twitter' );
	register_setting( 'nertworks-social-tools-settings-group', 'nertworks_social_bar_addtoany_google' );
	register_setting( 'nertworks-social-tools-settings-group', 'nertworks_social_bar_addtoany_pinterest' );
	register_setting( 'nertworks-social-tools-settings-group', 'nertworks_social_bar_addtoany_share_save' );
	
	register_setting( 'nertworks-social-tools-settings-group_advanced', 'nertworks_social_image_genie' );
	
	register_setting( 'nertworks-social-tools-settings-group-custom-icons-stumble', 'nertworks_social_custom_stumble_icon_uploaded' );
	register_setting( 'nertworks-social-tools-settings-group-custom-icons-twitter', 'nertworks_social_custom_twitter_icon_uploaded' );
	register_setting( 'nertworks-social-tools-settings-group-custom-icons-pinterest', 'nertworks_social_custom_pinterest_icon_uploaded' );
	register_setting( 'nertworks-social-tools-settings-group-custom-icons-linkedin', 'nertworks_social_custom_linkedin_icon_uploaded' );
	register_setting( 'nertworks-social-tools-settings-group-custom-icons-facebook', 'nertworks_social_custom_facebook_icon_uploaded' );
	
	
}
?>