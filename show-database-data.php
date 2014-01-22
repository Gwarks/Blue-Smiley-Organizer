<?php

require 'class.base.php';
require 'class.html.php';

$base_instance=new base();
$html_instance=new html();

$userid=$base_instance->get_userid();

$category_id=isset($_REQUEST['category_id']) ? (int)$_REQUEST['category_id'] : exit;

if ($category_id) {

$query=" AND (category_id=$category_id) "; $param="category_id=$category_id";

$data=$base_instance->get_data("SELECT title FROM {$base_instance->entity['DATABASE']['CATEGORY']} WHERE ID=$category_id");
$category_title=$data[1]->title;

$category_title2='(Category '.$category_title.')';

} else { $category_title2=''; }

$html_instance->add_parameter(
array('ACTION'=>'show_content',
'ENTITY'=>'DATABASE',
'MAXHITS'=>40,
'WHERE'=>"WHERE user='$userid' $query",
'HEADER'=>'Data '.$category_title2.' &nbsp;&nbsp; <a href="add-database-data.php?category_id='.$category_id.'">[Add Data]</a>',
'INNER_TABLE_WIDTH'=>'80%',
'URL_PARAMETER'=>$param,
'HEAD'=>'<script language="JavaScript" type="text/javascript">function createRequestObject(){try {var requester=new XMLHttpRequest();}catch (error) {try{var requester=new ActiveXObject("Microsoft.XMLHTTP");}catch(error){return false;}}return requester;}var http=createRequestObject();function DelData(item){if(confirm("Delete Data?")){http.open(\'get\',\'delete-database-data.php?item=\'+item);http.onreadystatechange=handleResponse;http.send(null);}}function handleResponse(){if(http.readyState==4){var response=http.responseText;var update=new Array();if(response.indexOf(\'|\'!=-1)){res=response.split(\'|\');document.getElementById(res[0]).innerHTML=res[1];}}}</script>'
));

$data=$html_instance->get_items();

if (!$data) {

if (!$data) { $base_instance->show_message('No data added in this category','<a href="add-database-data.php?category_id='.$category_id.'">[Add Data]</a>'); }

}

else {

$all_text='<table width="100%" border cellspacing=0 cellpadding=5 class="pastel"><tr><td><strong>Date</strong></td>';

# get title of number fields

$data_number=$base_instance->get_data("SELECT title,ID FROM {$base_instance->entity['DATABASE']['NUMBER_FIELDS']} WHERE category_id=$category_id ORDER BY ID");

$total_number_fields=sizeof($data_number);

for ($index=1; $index <= $total_number_fields; $index++) {

$all_text.='<td><strong>'.$data_number[$index]->title.'</strong></td>';

}

# get title of text fields

$data_text=$base_instance->get_data("SELECT * FROM {$base_instance->entity['DATABASE']['TEXT_FIELDS']} WHERE category_id=$category_id ORDER BY ID");

$total_text_fields=sizeof($data_text);

for ($index=1; $index <= $total_text_fields; $index++) {

$all_text.='<td><strong>'.$data_text[$index]->title.'</strong></td>';

}

# get title of select fields

$data_select=$base_instance->get_data("SELECT * FROM {$base_instance->entity['DATABASE']['SELECT_FIELDS']} WHERE category_id=$category_id ORDER BY ID");

$total_select_fields=sizeof($data_select);

for ($index=1; $index <= $total_select_fields; $index++) {

$all_text.='<td><strong>'.$data_select[$index]->title.'</strong></td>';

}

# get title of checkbox fields

$data_checkbox=$base_instance->get_data("SELECT * FROM {$base_instance->entity['DATABASE']['CHECKBOX_FIELDS']} WHERE category_id=$category_id ORDER BY ID");

$total_checkbox_fields=sizeof($data_checkbox);

for ($index=1; $index <= $total_checkbox_fields; $index++) {

$all_text.='<td><strong>'.$data_checkbox[$index]->title.'</strong></td>';

}

#

$all_text.='<td>&nbsp;</td></tr>';

#

for ($index=1; $index <= sizeof($data); $index++) {

$ID=$data[$index]->ID;
$datetime=$data[$index]->datetime;
$title=$data[$index]->title;
$text=$data[$index]->text;

$datetime_converted=$base_instance->convert_date($datetime);

$values='';

# get values of number fields

for ($index1=1; $index1 <= sizeof($data_number); $index1++) {

$number_field_id=$data_number[$index1]->ID;

$data2=$base_instance->get_data("SELECT value FROM {$base_instance->entity['DATABASE']['NUMBER_VALUES']} WHERE data_id=$ID AND category_id=$category_id AND number_field_id=$number_field_id");

if (empty($data2)) { $values.='<td>&nbsp;</td>'; } else {

for ($index2=1; $index2 <= sizeof($data2); $index2++) {

$value=$data2[$index2]->value;

$values.='<td>'.$value.'</td>';

}

}

}

# get values of text fields

for ($index1=1; $index1 <= sizeof($data_text); $index1++) {

$text_field_id=$data_text[$index1]->ID;

$data2=$base_instance->get_data("SELECT value FROM {$base_instance->entity['DATABASE']['TEXT_VALUES']} WHERE data_id=$ID AND category_id=$category_id AND text_field_id=$text_field_id");

if (empty($data2)) { $values.='<td>&nbsp;</td>'; } else {

for ($index2=1; $index2 <= sizeof($data2); $index2++) {

$value=$data2[$index2]->value;

if (empty($value)) { $values.='<td>&nbsp;</td>'; } else { $values.='<td>'.nl2br($value).'</td>'; }

}

}

}

# get values of select fields

for ($index1=1; $index1 <= sizeof($data_select); $index1++) {

$select_field_id=$data_select[$index1]->ID;

$data2=$base_instance->get_data("SELECT value FROM {$base_instance->entity['DATABASE']['SELECT_VALUES']} WHERE data_id=$ID AND category_id=$category_id AND select_field_id=$select_field_id");

if (empty($data2)) { $values.='<td>&nbsp;</td>'; } else {

for ($index2=1; $index2 <= sizeof($data2); $index2++) {

$value=$data2[$index2]->value;

$data_title=$base_instance->get_data("SELECT title FROM {$base_instance->entity['DATABASE']['SELECT_ITEMS']} WHERE ID=$value");
$select_title=$data_title[1]->title;

$values.='<td>'.$select_title.'</td>';

}

}

}

# get values of checkbox fields

for ($index1=1; $index1 <= sizeof($data_checkbox); $index1++) {

$checkbox_field_id=$data_checkbox[$index1]->ID;

$data2=$base_instance->get_data("SELECT value,checkbox_field_id FROM {$base_instance->entity['DATABASE']['CHECKBOX_VALUES']} WHERE data_id=$ID AND category_id=$category_id AND checkbox_field_id=$checkbox_field_id");

$values.='<td>';

if (empty($data2)) { $values.='&nbsp;'; } else {

for ($index2=1; $index2 <= sizeof($data2); $index2++) {

$value=$data2[$index2]->value;

$data_title=$base_instance->get_data("SELECT title FROM {$base_instance->entity['DATABASE']['CHECKBOX_ITEMS']} WHERE ID=$value");
$checkbox_title=$data_title[1]->title;

$values.=$checkbox_title.', ';

}

$values=substr($values,0,-2);

}

$values.='</td>';

}

#

if ($title or $text) { $comments=nl2br('<strong>'.$title.'</strong><br>'.$text); } else { $comments=''; }

$all_text.='<tr><td valign="top" width="80"><div id="item'.$ID.'">'.$datetime_converted.'<br>'.$comments.'</div></td>'.$values.'

<td width="75" valign="top" bgcolor="#fbfbfb">

<a href="javascript:DelData(\''.$ID.'\')">[Del]</a> &nbsp; <a href="edit-database-data.php?data_id='.$ID.'">[Edit]</a><p>

</td></tr>';

}

$all_text.='</table>';

}

$content_array[1]=array('MAIN'=>$all_text);

$html_instance->content=$content_array;

$html_instance->process();

?>