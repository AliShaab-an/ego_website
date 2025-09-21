<?php 

    require_once __DIR__ . '/../models/ProductImages.php';


    class ProductImagesController{

        public function uploadImages($productId,$files){
            $uploadDir = __DIR__ . '/../../public/admin/uploads/';
            
            // Allowed file types
            $allowedTypes = ['image/jpeg','image/png','image/webp'];
            $maxSize = 2 * 1024 * 1024; //2MB limit

            foreach($files['name'] as $key=>$name){
                $tmpName = $files['tmp_name'][$key];
                $error = $files['error'][$key];
                $size = $files['size']['key'];
                $type = mime_content_type($tmpName);

                if($error === UPLOAD_ERR_OK){

                    // 1. Validate file type
                    if(!array($type,$allowedTypes)){
                        continue;
                    }

                    if($size > $maxSize){
                        continue;
                    }

                    $safeName = uniqid() .  "_" . basename($name);
                    $target = $uploadDir . $safeName;

                    if(move_uploaded_file($tmpName,$target)){
                        ProductImages::addImage($productId,"admin/uploads/" . $safeName);
                    }
                }
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