<?php
/**
Plugin Name: WStools.nl Klantbeoordelingen
Plugin URI: http://wstools.nl/
Description: Klantbeoordelingen module van WStools.nl
Version: 1.0
Author: WStools.nl
Author URI: http://wstools.nl/
*/
class WStools_klantbeoordelingen extends WP_Widget
{
  function WStools_klantbeoordelingen()
  {
    $widget_ops = array('classname' => 'WStools_klantbeoordelingen', 'description' => 'Toon uw klantbeoordelingen' );
    $this->WP_Widget('WStools_klantbeoordelingen', 'Klantbeoordelingen', $widget_ops);
  }
 
  function form($instance)
  {
    $instance = wp_parse_args( (array) $instance, array( 'title' => '' ) );
    $title = $instance['title'];
?>
  <p><label for="<?php echo $this->get_field_id('title'); ?>">Soort: 
  	<select name="<?php echo $this->get_field_name('title'); ?>">
		<option value="Box 1"<?php if(attribute_escape($title) == "Box 1") { ?> selected<?php } ?>>Box 1</option>
  		<option value="Box 2"<?php if(attribute_escape($title) == "Box 2") { ?> selected<?php } ?>>Box 2</option>
  	</select>
  </label>
  </p>
<?php
  }
 
  function update($new_instance, $old_instance)
  {
    $instance = $old_instance;
    $instance['title'] = $new_instance['title'];
    return $instance;
  }
 
  function widget($args, $instance)
  {
    extract($args, EXTR_SKIP);
    $title = empty($instance['title']) ? ' ' : apply_filters('widget_title', $instance['title']);
 	if(attribute_escape($title) == "") { $soort = "Box 1"; } else { $soort = attribute_escape($title);  }

	$host = $_SERVER['HTTP_HOST'];

	if($soort == "Box 1") {
		$linkbox1 = file_get_contents("http://beoordelingen.wstools.nl/give/".$host."/klantbeoordelingen-box01-link");
		$bron = file_get_contents($linkbox1."&wp=TRUE&check=".urlencode(str_replace("/","|",$_SERVER['REQUEST_URI'])));
		$bron = str_replace("{wppluginroot}",plugin_dir_url().'wstoolsnl-klantbeoordelingen/',$bron);
		if ($bron != "") {
		wp_enqueue_script('WSKlant', 'http://beoordelingen.wstools.nl/js/WS-klantbeoordelingen.js');
		wp_enqueue_style('WSKlantcss', plugin_dir_url().'wstoolsnl-klantbeoordelingen/css/WStools-box1.css');
		?>
        <div class="box-ws-in"><?php echo $bron; ?></div>
        <?php
		}
	} 
	if($soort == "Box 2") {
		$linkbox2 = file_get_contents("http://beoordelingen.wstools.nl/give/".$host."/klantbeoordelingen-box02-link");
		$linkbox2a = file_get_contents("http://beoordelingen.wstools.nl/give/".$host."/klantbeoordelingen-box02-linka");
		if ($linkbox2 != "") {
			wp_enqueue_style('WSKlant', plugin_dir_url().'wstoolsnl-klantbeoordelingen/css/WStools-box2.css');
		?>
        <div class="box2-wstools">
        	<div class="box2-top">klantbeoordelingen</div>
    		<iframe src="<?php echo $linkbox2; ?>" scrolling="no" width="100%" frameborder="0" /></iframe>
            <div class="box2-bottom">
                <div class="WStools-inc"><a href="<?php echo $linkbox2a; ?>" target="_blank" title="Gerealiseerd door WStools.nl"><img src="<?php echo plugin_dir_url().'wstoolsnl-klantbeoordelingen/'; ?>images/wstools-fullwit.png" alt="WStools" /></a></div>
            </div>
        </div>                
        <?php
		}
	}
  }
 
}
add_action( 'widgets_init', create_function('', 'return register_widget("WStools_klantbeoordelingen");') );



function WStools_klantbeoordeling_iframe(){
	$host = $_SERVER['HTTP_HOST'];
	$iframecode = file_get_contents("http://beoordelingen.wstools.nl/give/".$host."/klantbeoordelingen-iframe");
	wp_enqueue_script('WSFrame', plugin_dir_url().'wstoolsnl-klantbeoordelingen/js/WS-Frame.js');
	?>
	<iframe src="<?php echo $iframecode; ?>" id="WStools-kframe" scrolling="no" width="100%" height="200px" frameborder="0" onload="FrameManager.registerFrame(this)" /></iframe>
    <?php
}


add_shortcode( 'wstoolsnl-klantbeoordelingen', 'WStools_klantbeoordeling_iframe' );

?>