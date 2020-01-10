<?php
namespace library;

use controllers\inforimage;
require_once './library/App.php';
class DB{
    public $con;
    protected $servername = "localhost";
    protected $username = "root";
    protected $password = "";
    protected $dbname = "db_image";
    function __construct(){
        $this->con = mysqli_connect($this->servername, $this->username, $this->password);
        mysqli_select_db($this->con, $this->dbname);
        mysqli_query($this->con, "SET NAMES 'utf8'");
    }
   //start funcition 
   public  function  saveimage()
    {   
        $target_dir    = "public/image/";
            //Vị trí file lưu tạm trong server
            $target_file   = $target_dir . basename($_FILES["fileupload"]["name"]);
            $allowUpload   = true;
            //Lấy phần mở rộng của file
            $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
            $maxfilesize   = 80000000; //(bytes)
            ////Những loại file được phép upload
           // $allowtypes    = array('jpg', 'png', 'jpeg', 'gif');
           $allowtypes    = array('jpg');


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
                echo "<br> Trang web chỉ hỗ Trợ định dạng JPG";
                $allowUpload = false;
            }

            // Check if $uploadOk is set to 0 by an error
            if ($allowUpload) {
                //move file tới folder 
                if (move_uploaded_file($_FILES["fileupload"]["tmp_name"], $target_file))
                {
                    echo "<br>File ". basename( $_FILES["fileupload"]["name"]).
                    "Đã upload thành công";
                    //lưu tên hình ảnh upload 
                    $nameimg=basename( $_FILES["fileupload"]["name"]);	
                    $imageURL='./public/image/'.$nameimg.'';
                    echo "<br>". $imageURL;
                    $getInfo=get_info_image($imageURL);
                    //gọi hàm 
                    if($getInfo!=false && $getInfo['make']!='' && $getInfo['datetime']!='')
                    {
                      
                        $makeExif=$getInfo['make'];
                        $timeExif=$getInfo['datetime'];
                        
                    }else
                    {   $timeExif='Unknown';
                        $makeExif='Unknown';
                    }
                    //end 
                    //gọi hàm 
                    if(get_image_location($imageURL)!=false)
                    {       $get_Location=get_image_location($imageURL);
                            $gps_Latitude=$get_Location['latitude'];
                            $gps_Longtitude=$get_Location['longitude'];
                      
                    }else
                    {
                            $gps_Latitude='Unknown';
                            $gps_Longtitude='Unknown';
                    }
                    //end
                    //start
                    if(address($imageURL)!=false)
                    {
                        $address=address($imageURL);
                        $formatted_address=$address['formatted_address'];
                        $street=$address['street'];
                        $city=$address['city'];
                        $province=$address['province'];

                    }
                    else
                    {
                        $formatted_address='Unknown';
                        $street='Unknown';
                        $city='Unknown';
                        $province='Unknown';
                    }
                    //end
                
                    //sql insert
                   $sql="INSERT INTO full_data_image 
                   (id_data,name_Device,time_Exif,name_File,exif_address,exif_street,exif_city,exif_province,gps_Latitude,gps_Longitude) VALUES
                   ('','$makeExif','$timeExif','$nameimg','$formatted_address','$street','$city','$province','$gps_Latitude','$gps_Longtitude') ";
                    echo "<br>". $sql;
                    if(!$this->con)
                    {
                        die("Connect failed".mysqli_connect_error());
                    }
                    if(mysqli_query($this->con,$sql))
                    {       
                            
                            echo "<br> thêm thành công";
                            //header('Location: ./index.php');
                            //redirect
                            Redirect('./index.php',false);
                    }
                    //end sql insert
            
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
      
    }
    //end function
   function selectdata()
   {
   
       $sql="SELECT * FROM full_data_image" ;
       $result=mysqli_query($this->con,$sql);
       return $result;
   }
  


  
}
