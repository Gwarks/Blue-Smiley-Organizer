<?php

require 'class.base.php';
require 'class.html.php';

$base_instance=new base();
$html_instance=new html();

$userid=$base_instance->get_userid();

$category_id=isset($_GET['category_id']) ? $_GET['category_id'] : '';

$html_instance->add_parameter(
array('ACTION'=>'show_form',
'HEADER'=>'Link Search',
'FORM_ACTION'=>'show-links.php',
'BODY'=>'onLoad="javascript:document.form1.text_search.focus()"',
'TD_WIDTH'=>'35%',
'BUTTON_TEXT'=>'Search Links'
));

# build category select box

$select_box='&nbsp;<select name="category_id"><option>&lt;All&gt;';

$data=$base_instance->get_data("SELECT * FROM {$base_instance->entity['LINK']['CATEGORY']} WHERE user='$userid' ORDER BY title");

for ($index=1; $index <= sizeof($data); $index++) {

$category_title=$data[$index]->title;
$ID=$data[$index]->ID;

if ($ID==$category_id) { $select_box.="<option selected value=$ID>$category_title"; }
else { $select_box.="<option value=$ID>$category_title"; }

}

$select_box.='</select>';

#

$html_instance->add_form_field(array('TYPE'=>'text','NAME'=>'text_search','VALUE'=>'','SIZE'=>30,'TEXT'=>'Text'));

$html_instance->add_form_field(array('TYPE'=>'label','TEXT1'=>'Category:','TEXT2'=>"$select_box",'SECTIONS'=>2));

$html_instance->process();

?>