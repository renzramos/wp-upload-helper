<?php

// Basic Configuration
$wp_upload_helper->set_folder('test-folder-2021/tester'); 
$wp_upload_helper->set_file($_FILE['ddd']); 
$wp_upload_helper->set_filename('test'); // without file type
if ($wp_upload_helper->validate_image_file(200,200)){
    
    // WordPress Media - 99 - post_id
    $wp_upload_helper->set_upload_media(true,'File Title',99);
    $wp_upload_helper->set_upload_media_featured(99);

    // Save File
    $save_file_result = $wp_upload_helper->save_file();

    // Meta Data
    $wp_upload_helper->set_upload_media_meta_data(99,'brand_logo_id',$wp_upload_helper->get_upload_wp_media_attachment_id());
    $wp_upload_helper->set_upload_media_meta_data(99,'brand_logo_url',$wp_upload_helper->get_upload_file_url());

}

