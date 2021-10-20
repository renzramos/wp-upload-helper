<?php

$wp_upload_helper = new WPUploadHelper();
$wp_upload_helper = $this->quick_upload('folder/folder',$_FILE['test'],'filename',200,200,true,99);
