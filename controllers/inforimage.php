<?php
namespace controllers;

use library\connectdb;

require_once "/xampp/htdocs/vietvang/meta_image/library/db.php";

class inforimage {
    
    function gps2Num($coordPart){
        $parts = explode('/', $coordPart);
        if(count($parts) <= 0)
        return 0;
        if(count($parts) == 1)
        return $parts[0];
        return floatval($parts[0]) / floatval($parts[1]);
    }
    function get_image_location($image = ''){
        $exif = exif_read_data($image, 0, true);
        if($exif && isset($exif['GPS'])){
                //GPS Latitude tag if the image was taken in the northern or southern hemisphere. 
                //nếu hình ảnh chụp ở bán cầu bắc
            $GPSLatitudeRef = $exif['GPS']['GPSLatitudeRef'];
            $GPSLatitude    = $exif['GPS']['GPSLatitude'];
                //GPS Longitude Ref tells whether the picture was taken in the eastern or western hemisphere.
                //hình ảnh chụp ở đông hay tây
            $GPSLongitudeRef= $exif['GPS']['GPSLongitudeRef'];
            $GPSLongitude   = $exif['GPS']['GPSLongitude'];
            
            $lat_degrees = count($GPSLatitude) > 0 ? gps2Num($GPSLatitude[0]) : 0;
            $lat_minutes = count($GPSLatitude) > 1 ? gps2Num($GPSLatitude[1]) : 0;
            $lat_seconds = count($GPSLatitude) > 2 ? gps2Num($GPSLatitude[2]) : 0;
            
            $lon_degrees = count($GPSLongitude) > 0 ? gps2Num($GPSLongitude[0]) : 0;
            $lon_minutes = count($GPSLongitude) > 1 ? gps2Num($GPSLongitude[1]) : 0;
            $lon_seconds = count($GPSLongitude) > 2 ? gps2Num($GPSLongitude[2]) : 0;
            
            $lat_direction = ($GPSLatitudeRef == 'W' or $GPSLatitudeRef == 'S') ? -1 : 1;
            $lon_direction = ($GPSLongitudeRef == 'W' or $GPSLongitudeRef == 'S') ? -1 : 1;
            
            $latitude = $lat_direction * ($lat_degrees + ($lat_minutes / 60) + ($lat_seconds / (60*60)));
            $longitude = $lon_direction * ($lon_degrees + ($lon_minutes / 60) + ($lon_seconds / (60*60)));
    
            return array('latitude'=>$latitude, 'longitude'=>$longitude);
        }
        else{
            return false;
        }
    }
    //info image
    function get_info_image($image=''){
            $exif = exif_read_data($image, 0, true);
            if($exif && isset($exif['IFD0']))
            {
                $make=$exif['IFD0']['Make'];
                $datetime=$exif['IFD0']['DateTime'];
            return array('make'=>$make,'datetime'=>$datetime);
            }
        }
    function address($imgLat,$imgLog)
        {  
           // $imageURL='../public/image/phongcanh.jpg';
           // $imgLocation=get_image_location($imageURL);
            //$imgLat=$imgLocation['latitude'];
            //$imgLog=$imgLocation['longitude'];
            $geolocation=$imgLat.','.$imgLog;
            //get file content map api key 
            $request =file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?latlng='.$geolocation.'&sensor=false&key=AIzaSyDJx44jiP9QHAeOec2C0aLW29jiL6OQyRU');
            $output=json_decode($request,true);
            //json decode
           
            $formatted_address= $output['results'][0]['formatted_address'];
            $street=$output['results'][0]['address_components'][1]['long_name'];
            $city=$output['results'][0]['address_components'][2]['long_name'];
            $province=$output['results'][0]['address_components'][3]['long_name'];
            return array('formatted_address'=>$formatted_address,'street'=>$street,'city'=>$city,'province'=>$province);
            
        }
       public function uploadfile()
        {   
            $target_dir    = "public/image/";
            //Vị trí file lưu tạm trong server
            $target_file   = $target_dir . basename($_FILES["fileupload"]["name"]);
            $allowUpload   = true;
            //Lấy phần mở rộng của file
            $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
            $maxfilesize   = 80000000; //(bytes)
            ////Những loại file được phép upload
            $allowtypes    = array('jpg', 'png', 'jpeg', 'gif');


            if(isset($_POST["ok"])) {
                //Kiểm tra xem có phải là ảnh
                $check = getimagesize($_FILES["fileupload"]["tmp_name"]);
                if($check !== false) {
                    echo "Đây là file ảnh - " . $check["mime"] . ".";
                    $allowUpload = true;
                } else {
                    echo "Không phải file ảnh.";
                    $allowUpload = false;
                }
            }

            // Kiểm tra nếu file đã tồn tại thì không cho phép ghi đè
            if (file_exists($target_file)) {
                echo "File đã tồn tại.";
                $allowUpload = false;
            }
            // Kiểm tra kích thước file upload cho vượt quá giới hạn cho phép
            if ($_FILES["fileupload"]["size"] > $maxfilesize)
            {
                echo "Không được upload ảnh lớn hơn $maxfilesize (bytes).";
                $allowUpload = false;
            }


            // Kiểm tra kiểu file
            if (!in_array($imageFileType,$allowtypes ))
            {
                echo "<br>Chỉ được upload các định dạng JPG, PNG, JPEG, GIF";
                $allowUpload = false;
            }

            // Check if $uploadOk is set to 0 by an error
            if ($allowUpload) {
                if (move_uploaded_file($_FILES["fileupload"]["tmp_name"], $target_file))
                {
                    echo "<br>File ". basename( $_FILES["fileupload"]["name"]).
                    "Đã upload thành công";
                }
                else
                {
                    echo "<br>Có lỗi xảy ra khi upload file.";
                }
            }
            else
            {
                echo "<br>Không upload được file!";
            }
            $namehinh=basename( $_FILES["fileupload"]["name"]);	
          
            return $namehinh;
            
        } 
   
                        
       
    
}
