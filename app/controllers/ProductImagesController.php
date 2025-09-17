<?php 

    require_once __DIR__ . '/../models/ProductImages.php';


    class ProductImagesController{

        public function uploadImages($productId,$files){
            $uploadDir = __DIR__ . '/../../public/admin/uploads/';

            foreach($files['name'] as $key=>$name){
                $tempName = $files['temp_name'][$key];
                $error = $files['error'][$key];

                if($error === UPLOAD_ERR_OK){
                    $safeName = uniqid() .  "_" . basename($name);
                    $target = $uploadDir . $safeName;

                    if(move_uploaded_file($tempName,$target)){
                        ProductImages::addImage($productId,"uploads/" . $safeName);
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