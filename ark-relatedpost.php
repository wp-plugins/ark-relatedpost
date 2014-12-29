<?php
/**
Plugin Name: ark-relatedpost
Author: Александр Каратаев
Plugin URI: http://blog.ddw.kz/novyj-plagin-vyvoda-svyazannyx-zapisej-ark-relatedpost.html
Description: Вывод связанных записей на основе тегов
Version: 2.4
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
'ark_imgurl' => '',
'ark_maxword' => '24',
'ark_bgcolor' => '#FFF',
'ark_nobgcolor' => '0',
'ark_bordercolor' => '#C7C7C7',
'ark_nobordercolor' => '0',
'ark_width' => '96',
'ark_title' => 'Материалы по теме:',
'ark_titlecolor' => '#215B9B',
'ark_titleshadow' => '1',
'ark_titleshadowcolor' => '#5DB6FA',
'ark_titleshadowx' => '1',
'ark_titleshadowy' => '1',
'ark_titleshadowr' => '1',
'ark_titlefontsize' => '20',
'ark_titletop' => '4',
'ark_textcolor' => '#000',
'ark_textfontsize' => '12',
'ark_orientation' => '0',
'ark_subtitlefontsize' => '14',
'ark_subtitlecolor' => '#3366DD',
'ark_subtitlesymbol' => '',
'ark_maxposts' => '5',
'ark_maxgposts' => '4',
'ark_source' => '0',
'ark_borderradius' => '10',
'ark_imgborderradius' => '4',
'ark_first' => '0',
'ark_hand' => '0',
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
	screen_icon('users');
    echo '<h2>Плагин&nbsp;ark-relatedpost&nbsp;2.4</h2><div style="clear: both;float:right; padding-right:20px;"><noindex><a rel="nofollow" href="http://blog.ddw.kz/podderzhka-proektov-avtora-etogo-bloga
" target="_blank"><img align="right" src="' . plugins_url( '/img/button-donate.png', __FILE__ ) . '" alt="Пожертвовать" border="0" /></a></noindex></div>';
?>	
<div class="wrap">
<h2>Настройки связанных записей</h2>
<?php // Пошла обработка запроса
if (isset($_POST['save'])) {
$ark_option = array(
'ark_imgsize' => $_POST['ark_imgsize'],
'ark_imgurl' => $_POST['ark_imgurl'],
'ark_maxword' => $_POST['ark_maxword'],
'ark_bgcolor' => $_POST['ark_bgcolor'],
'ark_nobgcolor' => $_POST['ark_nobgcolor'],
'ark_bordercolor' => $_POST['ark_bordercolor'],
'ark_nobordercolor' => $_POST['ark_nobordercolor'],
'ark_width' => $_POST['ark_width'],
'ark_titlecolor' => $_POST['ark_titlecolor'],
'ark_titleshadow' => $_POST['ark_titleshadow'],
'ark_titleshadowcolor' => $_POST['ark_titleshadowcolor'],
'ark_titleshadowx' => $_POST['ark_titleshadowx'],
'ark_titleshadowy' => $_POST['ark_titleshadowy'],
'ark_titleshadowr' => $_POST['ark_titleshadowr'],
'ark_textcolor' => $_POST['ark_textcolor'],
'ark_titlefontsize' => $_POST['ark_titlefontsize'],
'ark_titletop' => $_POST['ark_titletop'],
'ark_textfontsize' => $_POST['ark_textfontsize'],
'ark_orientation' => $_POST['ark_orientation'],
'ark_subtitlefontsize' => $_POST['ark_subtitlefontsize'],
'ark_subtitlecolor' => $_POST['ark_subtitlecolor'],
'ark_subtitlesymbol' => $_POST['ark_subtitlesymbol'],
'ark_maxposts' => $_POST['ark_maxposts'],
'ark_title' => $_POST['ark_title'],
'ark_maxgposts' => $_POST['ark_maxgposts'],
'ark_source' => $_POST['ark_source'],
'ark_borderradius' => $_POST['ark_borderradius'],
'ark_imgborderradius' => $_POST['ark_imgborderradius'],
'ark_first' => $_POST['ark_first'],
'ark_hand' => $_POST['ark_hand'],
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
<td><b>Фон блока:</b>&nbsp;&nbsp;Прозрачный <input type="checkbox" name="ark_nobgcolor" value="1" <?php if ($result['ark_nobgcolor'] == 1) { echo "checked"; } ?>/>&nbsp;или&nbsp;</td>
<td><input class="iris_color" name="ark_bgcolor" type="text" value="<?php echo $result['ark_bgcolor']; ?>" />
</td>
<td><i>Основной фон блока вывода связанных записей</i></td>
</tr><tr>
<td><b>Цвет рамки блока:</b>&nbsp;&nbsp;Убрать рамку <input type="checkbox" name="ark_nobordercolor" value="1" <?php if ($result['ark_nobordercolor'] == 1) { echo "checked"; } ?>/>&nbsp;или&nbsp;</td>
<td><input class="iris_color" name="ark_bordercolor" type="text" value="<?php echo $result['ark_bordercolor']; ?>" />
</td>
<td><i>Если рамка не нужна, установите флажок "Убрать рамку".</i></td>
</tr></table><table>
<tr>
<td>Ширина блока относительно родительского контейнера</td>
<td><input type="number" min="10" max="100" name="ark_width" size="3" value="<?php echo $result['ark_width']; ?>" /> <b>%</b>&nbsp;</td>
</tr>
<tr>
<td>Закругление углов блока (<i>ноль, если не надо</i>)</td>
<td><input type="number" min="0" max="20" name="ark_borderradius" value="<?php echo $result['ark_borderradius']; ?>" /> </td>
</tr><tr><td colspan="2"><br><hr></td></tr>
</table>
<br>
<table>
<tr>
<td>Заголовок блока <input type="text" name="ark_title" size="40" value="<?php echo $result['ark_title']; ?>" /></td>
<td>Отступ от верхнего края блока <input type="number" min="2" max="50" name="ark_titletop" size="2" value="<?php echo $result['ark_titletop']; ?>" /> <b>px</b>&nbsp;</td>
</tr></table><table><tr>
<td>Размер шрифта <input type="number" min="8" max="36" name="ark_titlefontsize" size="2" value="<?php echo $result['ark_titlefontsize']; ?>" /> <b>px</b>&nbsp;</td>
<td><input class="iris_color" name="ark_titlecolor" type="text" value="<?php echo $result['ark_titlecolor']; ?>" /></td>
</tr>
<tr>
<td>Эффект тени шрифта заголовка <input type="checkbox" name="ark_titleshadow" value="1" <?php if ($result['ark_titleshadow'] == 1) { echo "checked"; } ?>/> </td>
<td><input class="iris_color" name="ark_titleshadowcolor" type="text" value="<?php echo $result['ark_titleshadowcolor']; ?>" /></td>
</tr>
<tr><td colspan="2">
<b>Настройка тени:</b></td></tr><tr><td>&nbsp;сдвиг по X <input type="number" min="0" max="6" name="ark_titleshadowx" size="2" value="<?php echo $result['ark_titleshadowx']; ?>" /> <b>px</b>&nbsp;</td><td>
&nbsp;сдвиг по Y <input type="number" min="0" max="6" name="ark_titleshadowy" size="2" value="<?php echo $result['ark_titleshadowy']; ?>" /> <b>px</b>&nbsp;
&nbsp;размытие <input type="number" min="0" max="6" name="ark_titleshadowr" size="2" value="<?php echo $result['ark_titleshadowr']; ?>" /> <b>px</b>&nbsp;
</td></tr>
<tr><td colspan="2"><br><hr></td></tr>
</table>
<br>
<table>
<tr valign="top">
<td>Ширина миниатюры <input type="number" min="0" max="150" name="ark_imgsize" size="3" value="<?php echo $result['ark_imgsize']; ?>" /><b>px</b>&nbsp;</td>
<td><i>Чтобы не выводить миниатюру - установите ширину в ноль.</i></td>
</tr></table><table><tr>
<td><input type="url" name="ark_imgurl" size="50" placeholder="Введите URL своей картинки" value="<?php echo $result['ark_imgurl']; ?>" /></td></tr><tr>
<td><i>URL собственной картинки-заглушки, которая будет выводиться когда не найдена миниатюра. Может располагаться на стороннем хосте.</i></td>
</tr>
<tr>
<td>Закругление углов миниатюры (<i>ноль, если не надо</i>)&nbsp;<input type="number" min="0" max="20" name="ark_imgborderradius" value="<?php echo $result['ark_imgborderradius']; ?>" /><br><hr> </td>
</tr>
<tr>
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
<td>Ограничитель горизонтального вывода <input type="number" min="2" max="10" name="ark_maxgposts" size="3" value="<?php echo $result['ark_maxgposts']; ?>" /><br><i>Если записей выводится больше указанного количества, то они будут размещены с новой строки</i><br><hr></td>
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
</tr><tr><td colspan="2"><br><hr></td></tr>
</table>

<h3>Описание</h3>
<table>
<tr valign="top">
<td>Количество слов в описании <input type="number" min="0" max="50" name="ark_maxword" size="4" value="<?php echo $result['ark_maxword']; ?>" /></td>
<td><i>Чтобы не выводить описание - установите количество в ноль.</i></td>
</tr><tr>
<td>Размер шрифта <input type="number" min="8" max="18" name="ark_textfontsize" size="2" value="<?php echo $result['ark_textfontsize']; ?>" /> <b>px</b>&nbsp;</td>
<td>&nbsp;<input class="iris_color" name="ark_textcolor" type="text" value="<?php echo $result['ark_textcolor']; ?>" /></td>
</tr><tr><td colspan="2"><br><hr></td></tr>
</table>
<!--<h3>Дополнительные опции</h3>
<table><tr>
<td>Пытаться вставлять блок раньше других вставок в контент&nbsp;&nbsp;<input type="checkbox" name="ark_first" value="1" <?php if ($result['ark_first'] == 1) { echo "checked"; } ?>/><br><hr></td>
</tr><tr>
<td>Разместить блок самостоятельно&nbsp;&nbsp;<input type="checkbox" name="ark_hand" value="1" <?php if ($result['ark_hand'] == 1) { echo "checked"; } ?>/><br>В этом случае для работы плагина необходимо в <b>single.php</b> в нужное место вставить строку:<br><br>
	<?php $code = '<?php if (function_exists("ark_related_posts")) ark_related_posts(); ?>';
	highlight_string($code); ?></td>
</tr></table> -->
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
function ark_that_image($userimgurl) {
$image_id = get_post_thumbnail_id();
$image_url = wp_get_attachment_image_src($image_id);
$image_url = $image_url[0];
  if(empty($image_url)) {
	$image_url = ark_get_post_image();
	  if(empty($image_url)) {
		if (empty($userimgurl)) {
			$image_url = plugins_url( '/img/ark-noimage.png', __FILE__ );
			} else {
			$image_url = $userimgurl;
		}
	}
  }
return $image_url;
}	
function ark_get_post_image() {
  global $post, $posts;
  $first_img = '';
  ob_start();
  ob_end_clean();
  $output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches);
  $first_img = $matches [1] [0];
  return $first_img;
}
// Материалы по теме 	
function get_ark_related_posts() {	
$result = get_option('ark_relpost');
$arkImgSize = $result['ark_imgsize']; 
$arkMaxWord = $result['ark_maxword'];
$arkTitle = $result['ark_title'];
$arkWidth = $result['ark_width'];
$userimgurl = $result['ark_imgurl'];
$arkborderradius = 'border-radius: '.$result['ark_borderradius'].'px;
-moz-border-radius: '.$result['ark_borderradius'].'px '.$result['ark_borderradius'].'px '.$result['ark_borderradius'].'px '.$result['ark_borderradius'].'px;
-webkit-border-bottom-left-radius:'.$result['ark_borderradius'].'px;
-webkit-border-bottom-right-radius:'.$result['ark_borderradius'].'px;
-webkit-border-top-left-radius:'.$result['ark_borderradius'].'px;
-webkit-border-top-right-radius:'.$result['ark_borderradius'].'px;';
$arkimgborderradius = 'border-radius: '.$result['ark_imgborderradius'].'px;
-moz-border-radius: '.$result['ark_imgborderradius'].'px '.$result['ark_imgborderradius'].'px '.$result['ark_imgborderradius'].'px '.$result['ark_imgborderradius'].'px;
-webkit-border-bottom-left-radius:'.$result['ark_imgborderradius'].'px;
-webkit-border-bottom-right-radius:'.$result['ark_imgborderradius'].'px;
-webkit-border-top-left-radius:'.$result['ark_imgborderradius'].'px;
-webkit-border-top-right-radius:'.$result['ark_imgborderradius'].'px;';
if ($result['ark_titleshadow'] == 1) {$arktitleshadow = 'text-shadow:'. $result['ark_titleshadowx'] .'px '. $result['ark_titleshadowy'] .'px '. $result['ark_titleshadowr'] .'px '. $result['ark_titleshadowcolor'] .' !important;'; } else {$arktitleshadow = 'text-shadow: 0px 0px 0px !important;';}
if ($result['ark_nobgcolor'] == 1) {$arkbgcolor = 'background: none !important;'; } else {$arkbgcolor = 'background:' . $result['ark_bgcolor'] .' !important;';}
if ($result['ark_nobordercolor'] == 1) {$arkborder = 'border: 0px !important;'; $arkborderradius=''; } else {$arkborder = 'border: 1px solid ' . $result['ark_bordercolor'] .' !important;';}

$MaxGPosts = 0;
$CntPosts = 0;
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
		// Основной блок
		$arkrpbeg = '<div class="arkrelated" style="display:inline-block; '.$arkbgcolor . ' width:' . $result['ark_width'] .'%;'.$arkborder.$arkborderradius.'">';
		// Заголовок основного блока
		$arkrpbeg = $arkrpbeg . '<h3 style="margin-top: ' . $result['ark_titletop'] . 'px; color:' . $result['ark_titlecolor'] . '; font-size:' . $result['ark_titlefontsize'] . 'px; '.$arktitleshadow.'">' . $result['ark_title'] . '</h3>';
		$arkrpbeg = $arkrpbeg . '';
		$arkrpend = '</div>';
		$arksubtitlesymbol = trim($result['ark_subtitlesymbol']);
		if ($arksubtitlesymbol!= '') {
			$arksubtitlesymbol.='&nbsp;';
		}
		if ($result['ark_nobgcolor'] == 1) {$arkbgdivcolor = 'none'; } else {$arkbgdivcolor = $result['ark_bgcolor'] ;}
        while ($my_query->have_posts()) {
			$my_query->the_post();
			$arkpostlinks = get_permalink();
			if ($result['ark_orientation']==0) { 
			// Настройки вертикального вывода
				$arkrpbegpost = '<div  class="mtrans" style="'.$arkbgcolor .'width:100%; border:0px; cursor:pointer !important;">';
				$arkrpendpost = '</div>';
				$arkpbeforeimage='<div class="nohover" style="display:inline; float: left; border:0px; margin: 4px; width:' . ($result['ark_imgsize']+4) . 'px; padding-right:6px;">';
				$arkpafterimage='</div>';
				$arkimgalign = 'left';
				$arkdivstyle = 'float: left; border:0px; line-height: 110% !important; padding-top:10px; font-weight: normal !important; text-align: left !important;';
			} else {
				// Настройки горизонтального вывода
				if ($result['ark_maxgposts']>0) {
					$arkmaxprocwidth = intval(100/$result['ark_maxgposts'])-1 . '%;';
				} else {
					$arkmaxprocwidth = '25%;';
				}
				$MaxGPosts++;
				$CntPosts++;
				if ($MaxGPosts==1 && $CntPosts==1) {
					$arkdivtbl = '<table><tr><td class="mtrans" style="max-width:'.$arkmaxprocwidth.'" width="'.$arkmaxprocwidth.'" height="100%; min-height="100%; cursor:pointer !important;">';
				} else {
					$arkdivtbl = '<td class="mtrans" style="max-width:'.$arkmaxprocwidth.'" width="'.$arkmaxprocwidth.'" height="100%; min-height="100%; cursor:pointer !important;">';
				}
				$arkrpbegpost = '<div style="position:relative; top: 0px; z-index:9999999; border:0px; margin:2px; text-align:left !important; height:100%; min-height:100%;">';
				if ($MaxGPosts>$result['ark_maxgposts']) { 
					//$arkrpbegpost = '' . $arkrpbegpost; 
					$MaxGPosts=1; 
					$arkdivtbl = '</tr><tr><td class="mtrans" style="max-width:'.$arkmaxprocwidth.'" width="'.$arkmaxprocwidth.'" height="100%; min-height="100%; cursor:pointer !important;">';
					} 
				$arkrpendpost = '</div></td>';
				$arkpbeforeimage='<center class="nohover">';
				$arkpafterimage='</center>';
				$arkimgalign = 'top';
				$arkdivstyle = 'height: 100%; border:0px; line-height: 110% !important; font-weight: normal !important; text-align: left !important;';
			}
			// Конец настроек вывода
			// Определяем картинку
			$arkpimg = '<img class="nohover" width="' . $result['ark_imgsize'] . 'px" height="' . $result['ark_imgsize'] . 'px" align="'.$arkimgalign.'" style="'.$arkimgborderradius.'" src="' . ark_that_image($userimgurl) . '" />';	
			$arkpimg = $arkpbeforeimage . $arkpimg . $arkpafterimage;
		 if ($result['ark_imgsize']>0) {
			/*$arkrp = '<a class="nohover" href="'.$arkpostlinks.'">';*/
			$arkrp = $arkrp . $arkpimg;	
			/*$arkrp = $arkrp . '</a>' ;*/
		  
		  } else {
			$arkrp = '';
		  }
		  $arkpimg = $arkrp;
		  $arkrp = '';
		  // Конец определения картинки
		// Заголовок записи
			$arksubtitle = '<div class="nohover" style="'.$arkdivstyle.'"><span class="nohover" style="font-weight: bold; font-size:' . $result['ark_subtitlefontsize'] . 'px; color:' . $result['ark_subtitlecolor'] . ';">' . $arksubtitlesymbol . get_the_title() . '</span></div>';	
			//$arkrp = $arkrp .$arksubtitle;
		  // Вывод описания
		 if ($arkMaxWord > 0) {	
			$arkrp = $arkrp . '<div class="nohover" style="margin:5px 0px 5px 0px; color:' . $result['ark_textcolor'] . '; font-size:' . $result['ark_textfontsize'] . 'px; '.$arkdivstyle.'">';
		 	$arkrp = $arkrp . ark_content_rss('', TRUE, '', $arkMaxWord); 
			 $arkrp = $arkrp . '</div>';	
			$arkopis = $arkrp; 
		  } 
		  // Конец настроек вывода
		  if ($result['ark_orientation']==0) { 
			$outtmp = $outtmp . '<a class="nohover" href="'.$arkpostlinks.'">' . $arkrpbegpost . '<table class="nohover"><tr><td class="nohover">'. $arkpimg . '</td><td class="nohover" style="vertical-align: top;">'.$arksubtitle . $arkopis .'</td></tr></table>'. $arkrpendpost . '</a>';
			}else{
			$outtmp = $outtmp . $arkdivtbl . '<a class="nohover" style="vertical-align: top;" href="'.$arkpostlinks.'">' . $arkrpbegpost . $arkpimg . $arksubtitle . $arkopis . $arkrpendpost . '</a>';	
		}

		 $arkrpbegpost = '';
		  $arkrp = '';
		  $arkrpendpost = '';
		  
        }
		if ($result['ark_orientation']==0) {
			$outtmp = $outtmp . '';
		}
			$out = $arkrpbeg . $outtmp . '</tr></table>' . $arkrpend;	
			
			
			
		//echo $out;
		
		wp_reset_query();
		return  $out;
 }
// Материалы по теме End 
}

add_filter( 'the_content', 'ark_related_posts_auto', 999 );
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