<?php
interface application_interface_view 		{
	public function addTpl($file,$position);
	public function addSubview(application_view_subview $subview);
	public function placeObj($position);
}
?>