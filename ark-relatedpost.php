<?php
/**
Plugin Name: ark-relatedpost
Author: Александр Каратаев
Plugin URI: http://blog.ddw.kz/novyj-plagin-vyvoda-svyazannyx-zapisej-ark-relatedpost.html
Description: Вывод связанных записей на основе тегов
Version: 2.0
Author URI: http://blog.ddw.kz
License: GPL2
*/
?>
<?php
/*  Copyright 2014  Александр Каратаев  (email : ddw2@yandex.ru)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
?>
<?php
register_activation_hook(__FILE__, 'ark_relatedpost_activation');
 
function ark_relatedpost_activation() {
// действие при активации
ark_init_option();
// регистрируем действие при удалении
register_uninstall_hook(__FILE__, 'ark_relatedpost_uninstall');
}
 
function ark_relatedpost_uninstall(){
//действие при удалении
delete_option( 'ark_relpost' ); 
}
// Админ панель
//Опции по умолчанию
function ark_init_option() {
$ark_option = array(
'ark_imgsize' => '70',
'ark_maxword' => '24',
'ark_bgcolor' => '#FFF',
'ark_bordercolor' => '#C7C7C7',
'ark_width' => '96',
'ark_title' => 'Материалы по теме:',
'ark_titlecolor' => '#215B9B',
'ark_titlefontsize' => '20',
'ark_textcolor' => '#000',
'ark_textfontsize' => '12',
'ark_orientation' => '0',
'ark_subtitlefontsize' => '14',
'ark_subtitlecolor' => '#3366DD',
'ark_subtitlesymbol' => '',
'ark_maxposts' => '5',
'ark_maxgposts' => '4',
'ark_source' => '0',
);
add_option('ark_relpost', $ark_option,'','no');
}
// Хук вставки в админ меню
add_action('admin_menu', 'ark_rp_add_pages');
// Акция предыдущено хука
function ark_rp_add_pages() {
    // Добавляем новое субменю в Options:
    add_options_page('ark_relatedpost', 'Связанные записи', 8, 'ark_rp_ostoptions', 'ark_rp_options_page');
}
/* Подключаем Iris Color Picker 
----------------------------------------------------------------- */
function add_admin_iris_scripts( $hook ){
	// подключаем IRIS
	wp_enqueue_script( 'wp-color-picker' );
	wp_enqueue_style( 'wp-color-picker' );

	// подключаем свой файл скрипта
	wp_enqueue_script('plugin-script', plugins_url('js/plugin-script.js', __FILE__), array('wp-color-picker'), false, 1 );
}
add_action( 'admin_enqueue_scripts', 'add_admin_iris_scripts' );
// Вывод страницы опций в субменю
function ark_rp_options_page() {
    echo "<h2>Плагин ark-relatedpost</h2>";
	screen_icon('users');
?>	
<div class="wrap">
<h2>Настройки связанных записей</h2>
<?php // Пошла обработка запроса
if (isset($_POST['save'])) {
$ark_option = array(
'ark_imgsize' => $_POST['ark_imgsize'],
'ark_maxword' => $_POST['ark_maxword'],
'ark_bgcolor' => $_POST['ark_bgcolor'],
'ark_bordercolor' => $_POST['ark_bordercolor'],
'ark_width' => $_POST['ark_width'],
'ark_titlecolor' => $_POST['ark_titlecolor'],
'ark_textcolor' => $_POST['ark_textcolor'],
'ark_titlefontsize' => $_POST['ark_titlefontsize'],
'ark_textfontsize' => $_POST['ark_textfontsize'],
'ark_orientation' => $_POST['ark_orientation'],
'ark_subtitlefontsize' => $_POST['ark_subtitlefontsize'],
'ark_subtitlecolor' => $_POST['ark_subtitlecolor'],
'ark_subtitlesymbol' => $_POST['ark_subtitlesymbol'],
'ark_maxposts' => $_POST['ark_maxposts'],
'ark_title' => $_POST['ark_title'],
'ark_maxgposts' => $_POST['ark_maxgposts'],
'ark_source' => $_POST['ark_source'],
);
update_option('ark_relpost', $ark_option);
echo '<div id="setting-error-settings_updated" class="updated settings-error"><p><b>'.__('Settings saved.').'</b></p></div>';
	
} else if ( isset($_POST['reset']) ) {   
      // При сбросе: удаляем записи опций из БД  
 	     delete_option( 'ark_relpost' ); 
		 ark_init_option();
  	  echo '<div id="message" class="updated fade"><p><strong>' . 
               'Настройки успешно восстановлены по умолчанию.' .
               '</strong></p></div>';
 
      } 
	  
?>
<form method="post">
<?php wp_nonce_field('update-options'); 
$result = get_option('ark_relpost');
?>
<h3>Источник связанных записей</h3>
<table>
<tr>
<td>
<input type="radio" name="ark_source" value="0" <?php if ($result['ark_source'] == 0) { echo "checked"; } ?> > Выбирать записи на основе тегов (меток) &nbsp;&nbsp;
<input type="radio" name="ark_source" value="1" <?php if ($result['ark_source'] == 1) { echo "checked"; } ?> > Выбирать записи на основе категорий
</td>
</tr>
</table>

<h3>Основной блок</h3>
<table>
<tr>
<td><b>Фон блока</b></td>
<td><input class="iris_color" name="ark_bgcolor" type="text" value="<?php echo $result['ark_bgcolor']; ?>" />
</td>
<td><i>Основной фон блока вывода связанных записей</i></td>
</tr><tr>
<td><b>Цвет рамки блока</b></td>
<td><input class="iris_color" name="ark_bordercolor" type="text" value="<?php echo $result['ark_bordercolor']; ?>" />
</td>
<td><i>Если рамка не нужна, установите цвет фона и цвет рамки одинаковыми.</i></td>
</tr></table><table>
<tr>
<td>Ширина блока относительно родительского контейнера</td>
<td><input type="number" min="10" max="100" name="ark_width" size="3" value="<?php echo $result['ark_width']; ?>" /> <b>%</b>&nbsp;</td>
</tr>
</table>
<br>
<table>
<tr>
<td>Заголовок блока <input type="text" name="ark_title" size="40" value="<?php echo $result['ark_title']; ?>" /></td>
</tr></table><table><tr>
<td>Размер шрифта <input type="number" min="8" max="36" name="ark_titlefontsize" size="2" value="<?php echo $result['ark_titlefontsize']; ?>" /> <b>px</b>&nbsp;</td>
<td><input class="iris_color" name="ark_titlecolor" type="text" value="<?php echo $result['ark_titlecolor']; ?>" /></td>
</tr>
</table>
<br>
<table>
<tr valign="top">
<td>Ширина миниатюры <input type="number" min="0" max="150" name="ark_imgsize" size="3" value="<?php echo $result['ark_imgsize']; ?>" /><b>px</b>&nbsp;</td>
<td><i>Чтобы не выводить миниатюру - установите ширину в ноль.</i></td>
</tr><tr>
<td colspan="2">&nbsp;Количество выводимых постов <input type="number" min="1" max="40" name="ark_maxposts" size="2" value="<?php echo $result['ark_maxposts']; ?>" /></td>
</tr></table>
<br>

<table>
<tr>
<td>Формат вывода связанных записей</td>
<td>
<input type="radio" name="ark_orientation" value="0" <?php if ($result['ark_orientation'] == 0) { echo "checked"; } ?> > Вертикально &nbsp;&nbsp;
<input type="radio" name="ark_orientation" value="1" <?php if ($result['ark_orientation'] == 1) { echo "checked"; } ?> > Горизонтально
</td>
</tr> </table>

<table>
<tr>
<td>Ограничитель горизонтального вывода <input type="number" min="2" max="10" name="ark_maxgposts" size="3" value="<?php echo $result['ark_maxgposts']; ?>" /><br><i>Если записей выводится больше указанного количества, то они будут размещены с новой строки</i></td>
</tr>  
</table>

<h3>Ссылка - название статьи</h3>
<table>
<tr valign="top">
<td>Размер шрифта <input type="number" min="8" max="36" name="ark_subtitlefontsize" size="2" value="<?php echo $result['ark_subtitlefontsize']; ?>" /> <b>px</b>&nbsp;</td>
<td>&nbsp;<input class="iris_color" name="ark_subtitlecolor" type="text" value="<?php echo $result['ark_subtitlecolor']; ?>" /></td>
</tr><tr>
<td>Свой символ перед заголовком статьи&nbsp;&nbsp;<input name="ark_subtitlesymbol" type="text" size="2" value="<?php echo $result['ark_subtitlesymbol']; ?>" /></td>
<td><i>Необязательно. Можно использовать по желанию, например: "☛", "☑", "●" или "▪"</i></td>
</tr>
</table>

<h3>Описание</h3>
<table>
<tr valign="top">
<td>Количество слов в описании <input type="number" name="ark_maxword" size="4" value="<?php echo $result['ark_maxword']; ?>" /></td>
<td><i>Чтобы не выводить описание - установите количество в ноль.</i></td>
</tr><tr>
<td>Размер шрифта <input type="number" min="8" max="18" name="ark_textfontsize" size="2" value="<?php echo $result['ark_textfontsize']; ?>" /> <b>px</b>&nbsp;</td>
<td>&nbsp;<input class="iris_color" name="ark_textcolor" type="text" value="<?php echo $result['ark_textcolor']; ?>" /></td>
</tr>

<tr valign="top">
<td></td>
</tr>
</table>

<p class="submit">
<input type="submit" name="save" class="button-primary" value="<?php _e('Save Changes') ?>" />
<input name="reset" type="submit" class="button-primary" value="<?php _e('Восстановить настройки по умолчанию') ?>" />
</p>

</form>
</div>	
<?php
}
// Функции плагина
// Стили
function set_style_arkrp() {
    // Регистрация стилей для плагина:
    wp_register_style( 'ark-relatedpost', plugins_url( '/css/ark-relatedpost.css', __FILE__ ), array(), '20131003', 'all' );
    wp_enqueue_style( 'ark-relatedpost' );
}
add_action( 'wp_enqueue_scripts', 'set_style_arkrp' );

//  Пропорциональное изменение картинок
function ark_that_image() {
$image_id = get_post_thumbnail_id();
$image_url = wp_get_attachment_image_src($image_id);
$image_url = $image_url[0];
  if(empty($image_url)) {
    //$image_url = "http://blog.ddw.kz/wp-content/uploads/2013/obg.png"; // Ссылка на заглушку
	$image_url = plugins_url( '/img/ark-noimage.png', __FILE__ );
  }
return $image_url;
}	
// Материалы по теме 	
function get_ark_related_posts() {	
$result = get_option('ark_relpost');
$arkImgSize = $result['ark_imgsize']; 
$arkMaxWord = $result['ark_maxword'];
$arkTitle = $result['ark_title'];
$arkWidth = $result['ark_width'];
$MaxGPosts = 0;
if ($result['ark_source']==0) {
	$tags = wp_get_post_tags(get_the_ID());
	if ($tags) {
	$tag_ids = array();
	foreach($tags as $individual_tag) $tag_ids[] = $individual_tag->term_id;
	$args=array(
	'tag__in' => $tag_ids, // Сортировка происходит по тегам (меткам)
	'orderby'=>rand, // Добавляем условие сортировки рандом (случайный подбор)
	'caller_get_posts'=>1, // Запрещаем повторение ссылок
	'post__not_in' => array(get_the_ID()),
	'showposts'=>$result['ark_maxposts'] // Цифра означает количество выводимых записей
	); 
	}
} else {
	$categories = get_the_category($post->ID);
	if ($categories) {
		$category_ids = array();
		foreach($categories as $individual_category) $category_ids[] = $individual_category->term_id;
		$args=array(
		'category__in' => $category_ids,
		'post__not_in' => array(get_the_ID()),
		'showposts'=>$result['ark_maxposts'],
		'caller_get_posts'=>1);
}
}
	$my_query = new wp_query($args);
	$outtmp = '';
	if( $my_query->have_posts() ) {
		$arkrpbeg = '<div class="arkrelated" style="background:' . $result['ark_bgcolor'] . '; width:' . $result['ark_width'] .'%; border-color:' . $result['ark_bordercolor'] . ';">';
		$arkrpbeg = $arkrpbeg . '<h3 style="color:' . $result['ark_titlecolor'] . '; font-size:' . $result['ark_titlefontsize'] . 'px;">' . $result['ark_title'] . '</h3>';
		$arkrpbeg = $arkrpbeg . '<table width="100%" border="0">';
		$arkrpend = '</table></div>';
		$arksubtitlesymbol = trim($result['ark_subtitlesymbol']);
		if ($arksubtitlesymbol!= '') {
			$arksubtitlesymbol.='&nbsp;';
		}
        while ($my_query->have_posts()) {
			$my_query->the_post();
			$arkpostlinks = get_permalink(); 
			$arksubtitle = '<b><a style="font-size:' . $result['ark_subtitlefontsize'] . 'px; color:' . $result['ark_subtitlecolor'] . ';" href="' . $arkpostlinks . '" rel="bookmark" title="Перейти на запись">' . $arksubtitlesymbol . get_the_title() . '</a></b>';
			if ($result['ark_orientation']==0) { 
				$arkrpbegpost = '<tr><td>';
				$arkrpendpost = '</td></tr>';
				$arkpafterimage='';
				$arkimgalign = 'left';
			} else {
				$MaxGPosts++;
				if ($MaxGPosts>$result['ark_maxgposts']) { 
					$arkrpbegpost = '</tr><tr><td style="vertical-align: top;"><center>'; 
					$MaxGPosts=0; 
				} else {
					$arkrpbegpost = '<td style="vertical-align: top;">';
				}
				if ($result['ark_maxgposts']>0) {
					$arkmaxprocwidth = (100/$result['ark_maxgposts']) + '%;';
				} else {
					$arkmaxprocwidth = '25%;';
				}
				$arkrpbegpost = $arkrpbegpost . '<table><tr valign="top"><td style="vertical-align: top; text-align:center; maxwidth:'.$arkmaxprocwidth.'" width="'.$arkmaxprocwidth.'" height="100%">';
				$arkrpendpost = '</td></tr></table></td>';
				$arkpafterimage='</center></td></tr><tr><td>';
				$arkimgalign = 'top';
				$arksubtitle = '<center>' . $arksubtitle . '</center>';
		  }
		  $arkpimg = '<img width="' . $result['ark_imgsize'] . 'px" height="' . $result['ark_imgsize'] . 'px" align="'.$arkimgalign.'" src="' . ark_that_image() . '" />';	
		 if ($result['ark_imgsize']>0) {
			$arkrp = '<a href="'.$arkpostlinks.'">';
			$arkrp = $arkrp . $arkpimg;	
			$arkrp = $arkrp . '</a>' . $arkpafterimage;
		  
		  } else {
			$arkrp = '';
		  }
		  $arkrp = $arkrp . $arksubtitle;
		 if ($arkMaxWord > 0) {	
			$arkrp = $arkrp . '<br /><font style="color:' . $result['ark_textcolor'] . '; font-size:' . $result['ark_textfontsize'] . 'px; line-height: 1.4;">';
		 	$arkrp = $arkrp . ark_content_rss('', TRUE, '', $arkMaxWord); 
			 $arkrp = $arkrp . '</font>';	
		  } 
		  $outtmp = $outtmp . $arkrpbegpost . $arkrp . $arkrpendpost;
		  $arkrpbegpost = '';
		  $arkrp = '';
		  $arkrpendpost = '';
		  
        }
		$out = $arkrpbeg . $outtmp . $arkrpend;	
		//echo $out;
		
		wp_reset_query();
		return  $out;
 }
// Материалы по теме End 
}
add_filter( 'the_content', 'ark_related_posts_auto', 100 );
function ark_related_posts_auto( $content ) {
	if (is_single ()) {
    $arkrelatedpost = get_ark_related_posts();
    $content = $content . $arkrelatedpost;
	}
    return $content;
}


function ark_content_rss($more_link_text='(more...)', $stripteaser=0, $more_file='', $cut = 0, $encode_html = 0) {
	_deprecated_function( __FUNCTION__, '2.9', 'the_content_feed' );
	$content = get_the_content($more_link_text, $stripteaser);
	$content = apply_filters('ark_content_rss', $content);
	if ( $cut && !$encode_html )
		$encode_html = 2;
	if ( 1== $encode_html ) {
		$content = esc_html($content);
		$cut = 0;
	} elseif ( 0 == $encode_html ) {
		$content = make_url_footnote($content);
	} elseif ( 2 == $encode_html ) {
		$content = strip_tags($content);
	}
	if ( $cut ) {
		$blah = explode(' ', $content);
		if ( count($blah) > $cut ) {
			$k = $cut;
			$use_dotdotdot = 1;
		} else {
			$k = count($blah);
			$use_dotdotdot = 0;
		}

		/** @todo Check performance, might be faster to use array slice instead. */
		for ( $i=0; $i<$k; $i++ )
			$excerpt .= $blah[$i].' ';
		$excerpt .= ($use_dotdotdot) ? '...' : '';
		$content = $excerpt;
	}
	$content = preg_replace("#\[.*?\]#","",$content);
	$content = str_replace(']]>', ']]&gt;', $content);
	return $content;
}
?>