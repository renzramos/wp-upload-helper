<?php
/*
* Name: WP Upload Helper
* Author: Renz R. Ramos
* Version: 1.0.0
* Created At: 2021-10-19
* Updated At: 2021-10-19
*/

class WPUploadHelper{

    private $upload_root_dir = '';
    private $upload_dir = '';
    private $upload_url = '';

    private $upload_folder_dir = '';
    private $upload_folder_url = '';

    private $upload_file;
    private $upload_filename;
    private $upload_file_type;
    private $upload_new_filename;

    private $upload_save_result;

    private $upload_wp_media = false;
    private $upload_wp_media_title;
    private $upload_wp_media_parent_id;
    private $upload_wp_media_attachment_id;

    public function __contruct(){

        $this->upload_root_dir = wp_upload_dir();
        $this->upload_dir = $this->upload_root_dir['basedir'];
        $this->upload_url = $this->upload_root_dir['baseurl'];

    }
    
    public function set_folder($folder = 'folder-name'){
        $this->upload_folder_dir = $this->upload_dir . $folder;
        $this->upload_folder_url = $this->upload_url . $folder;

        // Folder not exist, create it
        $this->check_create_folder_dir();
    }

    /*
    * Check/Create Folder Dir
    * Version: 1.0.0
    * Created At: 2021-10-19
    * Updated At: 2021-10-19
    */
    public function check_create_folder_dir(){

        // Folder not exist, create it
        if (!file_exists($this->upload_folder_dir)){
            mkdir($this->upload_folder_dir,0755,true);
        }

    }

    public function set_file($file){
        $this->upload_file = $file;
        $this->get_upload_filename();
    }

    public function set_filename($filename){
        $this->upload_new_filename = $filename . '.'. $this->get_upload_file_type();
    }

    public function get_upload_filename(){
        $this->upload_filename = basename($this->file["name"]) . '.'. $this->get_upload_file_type();
        return $this->upload_filename;
    }

    public function get_upload_file_type(){
        $this->upload_file_type = wp_check_filetype( basename( $this->get_upload_filename() ), null );
    }

    public function get_upload_file_path(){

        $destination = $this->$this->upload_folder_dir;

        // Check if there is new file name
        if ($this->upload_new_filename == ''){
            $destination.= '/' . $this->upload_filename();
        }else{
            $destination.= '/' . $this->upload_new_filename();
        }
        return $destination;
    }

    public function get_upload_file_url(){

        $destination = $this->$this->upload_folder_dir;

        // Check if there is new file name
        if ($this->upload_new_filename == ''){
            $destination.= '/' . $this->upload_filename();
        }else{
            $destination.= '/' . $this->upload_new_filename();
        }
        return $destination;    
    }

    public function save_file(){
        $save_file_response = array();
        if (move_uploaded_file($this->upload_file["tmp_name"], $this->get_upload_file_path() )){
            $save_file_response['status'] = true;
        }else{
            $save_file_response['status'] = false;
        }

        // Check Upload Media
        if ($this->upload_wp_media){
            $attachment = array(
                'guid'           => $this->get_upload_file_url(), 
                'post_mime_type' => $this->get_upload_file_type(),
                'post_title'     => $this->upload_wp_media_title,
                'post_content'   => '',
                'post_status'    => 'inherit'
            );
            $this->upload_wp_media_attachment_id = wp_insert_attachment( $attachment, $this->get_upload_file_path() , $this->upload_wp_media_parent_id );
        }
        return $save_file_response;
    }

    public function get_upload_wp_media_attachment_id(){
        return $this->upload_wp_media_attachment_id;
    }

    public function set_upload_media($status = false, $title = '', $parent_id = 0){
        $this->upload_wp_media = $status;
        $this->upload_wp_media_title = $title;
        $this->upload_wp_media_parent_id = $parent_id;
    }

    public function set_upload_media_featured($post_id = 0){
        if ($this->upload_wp_media){
            set_post_thumbnail($post_id,$this->upload_wp_media_attachment_id);
        }
    }
    
    public function set_upload_media_meta_data($post_id = 0,$meta_key = '', $meta_value = ''){
        update_post_meta($post_id,$meta_key,$meta_value);
    }
    public function validate_image_file($required_width = 0, $required_height = 0){
        $image_info = getimagesize($file["tmp_name"]);
        $image_width = $image_info[0];
        $image_height = $image_info[1];
        $image_status = false;
        if ($image_width == $required_width && $image_height == $required_height){
            $status = true;
        }
        return $status;
    }


    public function quick_upload($folder = '',$file = [],$filename = '', $required_width = 0, $required_height = 0,$upload_media = false, $upload_media_featured = 0){

        $response = array();
        $this->set_folder($folder);
        $this->set_file($file);
        $this->set_filename($filename);

        if ($required_height > 0 && $required_width > 0){
            if ($wp_upload_helper->validate_image_file(200,200)){
                
                $this->set_upload_media($upload_media);
                $this->upload_media_featured($upload_media_featured);
                $this->save();

                $response['status'] = 'saved';

            }else{
                $response['status'] = 'invalid_image_size';
            }
        }else{

            $this->set_upload_media($upload_media);
            $this->upload_media_featured($upload_media_featured);
            $this->save();

        }
        
       
    }
   
}


$wp_upload_helper = new WPUploadHelper();
$wp_upload_helper = $this->quick_upload('foler/folder',$_FILE['test'],'filename',200,200,true,99);



// // Basic Configuration
// $wp_upload_helper->set_folder('test-folder-2021/tester'); 
// $wp_upload_helper->set_file($_FILE['ddd']); 
// $wp_upload_helper->set_filename('test'); // without file type
// if ($wp_upload_helper->validate_image_file(200,200)){
    
//     // WordPress Media - 99 - post_id
//     $wp_upload_helper->set_upload_media(true,'File Title',99);
//     $wp_upload_helper->set_upload_media_featured(99);

//     // Save File
//     $save_file_result = $wp_upload_helper->save_file();

//     // Meta Data
//     $wp_upload_helper->set_upload_media_meta_data(99,'brand_logo_id',$wp_upload_helper->get_upload_wp_media_attachment_id());
//     $wp_upload_helper->set_upload_media_meta_data(99,'brand_logo_url',$wp_upload_helper->get_upload_file_url());

// }

