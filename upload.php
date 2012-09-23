<?php
$response = array('status'=>1, 'response'=>'File Successfully Uploaded!');
if(!empty($_FILES['images']['name'])){
	$allowed_types = array('image/jpeg', 'image/jpg', 'image/gif', 'image/png');
	
	$upload_error = $_FILES['images']['error'];
	$upload_type = $_FILES['images']['type'];
	$upload_tmp_name = $_FILES['images']['tmp_name'];
	$upload_size = $_FILES['images']['size'];
	$upload_name = 'uploads/' . time().$_FILES['images']['name'];
	$error_message = '';
	
	//check uploaded file
	if($upload_error > 0){
		$error_message = 'An unexpected error ocurred while uploading!';
	}else{
		if(!getimagesize($upload_tmp_name)){
			$error_message = 'The uploaded file was not an image!';
		}else{
			// Check filetype
			if(!in_array($upload_type, $allowed_types)){
				$error_message = 'The uploaded file is unsupported!';
			}else{
				// Check filesize
				if($upload_size > 500000){
					$error_message = 'The uploaded file exceeds maximum upload size for an image!';
				}else{
					// Check if the file exists
					if(file_exists($upload_name)){
						$error_message = 'A file with the same filename already exists!';
					}else{
						// Upload file
						if(!move_uploaded_file($upload_tmp_name, $upload_name)){
							$error_message = 'File destination is not writeable!';
						}
					}
				}
			}
		}
	}
	
	if($error_message != ''){
		$response['status'] = 0;
		$response['response'] = $error_message;
		$response['filename'] = $upload_name;
	}
	
}
echo json_encode($response);
?>