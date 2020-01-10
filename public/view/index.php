<!DOCTYPE html>
<html lang="en">
<head>
    
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <link href="./public/css/main.css" type="text/css" rel="stylesheet">

   
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.4.1/css/all.css" integrity="sha384-5sAR7xN1Nv6T6+dT2mhtzEpVJvfS3NScPQTrOxhwjIuvcA67KV2R5Jz6kr4abQsz" crossorigin="anonymous">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css?family=Roboto&display=swap" rel="stylesheet">
    <title>Ảnh</title>
</head>
<body>
    <header>
          <section class="clearfix ">
              <div class="container">
                  <div class="row">
                        <div class="col">
                            <h3 class="text-info">Exif Info 
                               
                            </h3>
                            
                        </div>
                        <div class="col">
                                    <ul class="nav justify-content-center">
                            <li class="nav-item">
                                <a class="nav-link active" href="#">Active</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#">Link</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#">Link</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link disabled" href="#" tabindex="-1" aria-disabled="true">Disabled</a>
                            </li>
                        </ul>
                        </div>
                  </div>
              </div>
          </section>
    </header>
    <main>
    <?php

    require_once '/xampp/htdocs/vietvang/meta_image/controllers/inforimage.php';
   /*$obj=new controllers\inforimage();
    $obj->getName();*/
 
    
use library\DB;

if(isset($_POST['submit'])){
    
        $obj=new DB();
        $a=$obj->saveimage();
      
    }

    
    
   
  
?>
            <section class="clearfix my-3">
                <div class="container">
                    <div class="row">
                        <div class="col">
                            <p class="text-center text-danger " style="font-size: 2rem;border-bottom: 1px solid; " >Analyze a File</p>
                        </div>
                    </div>
                    <div class="row">
                        
                   
                        <div class="col">
                           
                            <form action="?uploadfile"  method="POST" enctype="multipart/form-data">
                            <div class="form-group">
                               
                                    <input name="fileupload" id="fileupload"  type="file">
                                    <button type="submit" name="submit" class="btn btn-sm btn-success">UPLOAD FILE</button>
                            </div>
                            </form>
                        </div>
                     
                          
                           
                    </div>
                    <div class="row">
                            <p>Or just drag and drop a file here.
             
                            </p>
                        </div>
                    </div>
                </div>
            </section>
      
            <section class="clearfix">
                <div class ="container">
                    <div class ="row">
                    <?php
            $ob=new DB();
            $data=$ob->selectdata();
            $arr=array();
            while($row= mysqli_fetch_assoc($data))
            {
                    $arr[]=$row;
                   
            
            ?>
                        <div class="col image-list">
                          
                                <div class="card" style="width: 18rem;">
                                <img class="card-img-top image-size" src="./public/image/<?php echo $row['name_File'] ?>" alt="Card image cap">
                                <div class="card-body">
                     
                                    <h5 class="card-title name-device"><i class="fa fa-camera-retro"></i><?php echo $row['name_Device'] ?></h5>
                                    <p class="card-text"><i class="fas fa-file-image"></i> <?php echo $row['name_File']?></p>
                                    <p class="card-text"><i class="far fa-clock"></i><?php echo $row['time_Exif'] ?></p>
                                    <p class="card-text"><i class="fas fa-map"></i><?php echo $row['exif_address']?></p>
                                    <p class="card-text"><i class="fas fa-road"></i><?php echo $row['exif_street']?></p>
                                    <p class="card-text"><i class="fas fa-city"></i><?php echo $row['exif_city']?></p>
                                    <p class="card-text"><i class="fas fa-university"></i><?php echo $row['exif_province']?></p>
                                   
                                    
                                    <iframe src="https://maps.google.com/maps?q=<?php echo floatval($row['gps_Latitude'])?>,<?php echo floatval($row['gps_Longitude'])?>&z=18&output=embed" width="250" height="250" frameborder="0" style="border:0"></iframe>
                                   
                                   
                                </div>  
                     
                       
                        </div>
           
                        </div>
                        <?php 
            }
            ?>                         
                    </div>
                </div>
            </section>
    </main>
    <footer>
            <section class="clearfix">
                <div class="container">
                    <div class="row">
                        <div class="col">
                            <p class="title-footer">What is this site?   </p>
                            <span class="text-footer">Exif Info is a tool that allows you to upload a file, and will show you the (normally hidden) metadata that is embedded in that file. The tool focuses on displaying the metadata from Exif images (i.e. .jpeg files), but can extract the metadata from almost every common media format including images, movies, audio files, Microsoft Word documents, Adobe PDFs, and many more.
                            </span>
                        </div>
                        <div class="col">
                        <p class="title-footer">What is Exif?</p>
                            <span class="text-footer">
                            The Exchangeable image file format is a standard that defines the formats of image, audio, and metadata tags used by cameras, phones, and other digital recording devices.
                            </span>
                        </div>
                        <div class="col">
                        <p class="title-footer">What sort of metadata is commonly included?</p>
                        <span class="text-footer">Depending on the file type and the authoring tool (the application or the capturing device), different types of metadata are recorded. Examples include:</span>
                        </div>
                    </div>
                </div>
            </section>
    </footer>

</body>
<script src="js/bootstrap.min.js"></script>
<script src="js/jquery-3.4.1.slim.min.js"></script>
<script src="popper.min.js"></script>
</html>