<?php 

    require_once __DIR__ . '/../models/ProductImages.php';


    class ProductImagesController{

        public function uploadImages($productId,$files){
            file_put_contents(__DIR__ . '/../../logs/debug.log', "UploadImages called with files:\n" . print_r($files, true), FILE_APPEND);

            $uploadDir = __DIR__ . '/../../public/admin/uploads/';
            
            // Allowed file types
            $allowedTypes = ['image/jpeg','image/png','image/webp'];
            $maxSize = 2 * 1024 * 1024; //2MB limit
            try{
                foreach($files['name'] as $key=>$name){
                $tmpName = $files['tmp_name'][$key];
                $error = $files['error'][$key];
                $size = $files['size'][$key];
                $type = mime_content_type($tmpName);

                if($error === UPLOAD_ERR_OK){

                    // 1. Validate file type
                    if(!in_array($type,$allowedTypes)){
                        continue;
                    }

                    if($size > $maxSize){
                        continue;
                    }

                    $safeName = uniqid() .  "_" . basename($name);
                    $target = $uploadDir . $safeName;

                    file_put_contents(__DIR__ . '/../../logs/debug.log', "Processing file: {$name}, tmp: {$tmpName}, type: {$type}, size: {$size}\n", FILE_APPEND);

                    if(move_uploaded_file($tmpName,$target)){
                        file_put_contents(__DIR__ . '/../../logs/debug.log', "Image moved successfully: {$target}\n", FILE_APPEND);
                        ProductImages::addImage($productId,"admin/uploads/" . $safeName);
                        
                    }else{
                        throw new Exception("Failed to move uploaded file $name");
                    }
                }else{
                    throw new Exception("Upload error code: $error for file $name");
                }
            }
                return ['status' => 'success', 'message' => 'Images uploaded successfully'];
            }catch(Exception $e){
                return ['status' => 'error', 'message' => $e->getMessage()];
            }   
        }

        public function deleteImage($id){
            $image = ProductImages::getById($id);
            if($image){
                unlink(__DIR__ . '/../../public/' . $image['image_path']);
                ProductImages::deleteImage($id);
            }
        }
    }