//
//  Author : Hilay Khatri
//  


function showAllPictures()
{
        // Populate albums div 
        
       
           
           $.ajax({
                type:"POST",
                url:"http://test-gardenshift.rhcloud.com/index.php/pages/get_feedback",
                success: function(response)
                {
                     
                var obj = jQuery.parseJSON(response);
  
                    
                  
                    msg ="<ol>";
                   
                            
                    for(i= obj.albums_picture.length -1 ; i>=0 ; i--)
                        {
                  
                                            msg += "<li>";
                                            msg += "<h4>" + obj.albums_picture[i].picture_caption + "</h4>";
                                            msg += "<a href='"+ obj.albums_picture[i].picture_url + "'>";
                                            msg += "<img src='" + obj.albums_picture[i].picture_url +"' /> </a>";
                                            msg += "</li>";
      
                          
                        }
                        
                        msg += "</ol>";
                      
                   
                    document.getElementById('showcase').innerHTML = msg;
                    
                   
                    
                    var tn1 = $('.mygallery').tn3({
                                                    skinDir:"skins",
                                                    imageClick:"fullscreen",
                                                    image:{
                                                    maxZoom:1.5,
                                                    crop:true,
                                                    clickEvent:"dblclick",
                                                    transitions:[{
                                                    type:"blinds"
                                                    },{
                                                    type:"grid"
                                                    },{
                                                    type:"grid",
                                                    duration:460,
                                                    easing:"easeInQuad",
                                                    gridX:1,
                                                    gridY:8,
                                                    // flat, diagonal, circle, random
                                                    sort:"random",
                                                    sortReverse:false,
                                                    diagonalStart:"bl",
                                                    // fade, scale
                                                    method:"scale",
                                                    partDuration:360,
                                                    partEasing:"easeOutSine",
                                                    partDirection:"left"
                                                    }]
                                                    }
                                       });

                     
                    
                     
                     }
                 
                
 
            });
    
    }
    
    
    
    function albumPictureUpload()
    
    {
        // Uploads the picture to the test-gardenshift.rhcloud.com/images/; all the pictures are named as: Username + timestamp + filename
        
        $("#uploadImageFormAlbum").submit();
    }