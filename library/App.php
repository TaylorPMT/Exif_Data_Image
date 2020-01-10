<?php

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
        else
        {
            return false;
        }
    }
function address($imageURL='')
    {  
       
        $imgLocation=get_image_location($imageURL);
        $imgLat=$imgLocation['latitude'];
        $imgLog=$imgLocation['longitude'];
        $geolocation=$imgLat.','.$imgLog;
    
       
        //json decode
        if(isset($imgLat,$imgLog))
        {
        //get file content map api key 
        $request =file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?latlng='.$geolocation.'&sensor=false&key=AIzaSyDJx44jiP9QHAeOec2C0aLW29jiL6OQyRU');
        
        $output=json_decode($request,true);
        $formatted_address= $output['results'][0]['formatted_address'];
        $street=$output['results'][0]['address_components'][1]['long_name'];
        $city=$output['results'][0]['address_components'][2]['long_name'];
        $province=$output['results'][0]['address_components'][3]['long_name'];
        return array('formatted_address'=>$formatted_address,'street'=>$street,'city'=>$city,'province'=>$province);
        }else
        {
            return false;
        }
    }
    function DECtoDMS($dec)
    {

        //Chuyển đổi độ 

        $vars = explode(".",$dec);
        $deg = $vars[0];
        $tempma = "0.".$vars[1];

        $tempma = $tempma * 3600;
        $min = floor($tempma / 60);
        $sec = $tempma - ($min*60);

        return array("deg"=>$deg,"min"=>$min,"sec"=>$sec);
    }
    function Redirect($url, $permanent = false)
    {
        if (headers_sent() === false)
        {
            header('Location: ' . $url, true, ($permanent === true) ? 301 : 302);
        }

        exit();
    }    

?>