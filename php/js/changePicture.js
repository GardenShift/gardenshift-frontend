//
//  Author : Hilay Khatri
// 


function showProfilePicture()
{
    // Shows the profile picture of the user logged in 'profilePictureDiv' div tag
    $.ajax({
                type:"POST",
                url:"http://test-gardenshift.rhcloud.com/index.php/pages/get_feedback",
                success: function(response)
                {
                     

                    var obj = jQuery.parseJSON(response);
                    
                   
                    var urladdress = obj.picture;
                                    
                    var msg = "<image src=" + urladdress + " style='width: 100%' />";
                    msg+= "<button id='changePicture_btn' style='position:absolute; left:1%; top: 5%' onclick='showChangeProfileDialog()'> Change Picture </button>";
                    
                    document.getElementById('profilePictureDiv').innerHTML = msg;
                    
                    $("#changePicture_btn").hide();
                    
                }
    
    });
    
}


function changePicture()
{
    
    // Changes the profile picture by updating the database
    
   var key = $("#pictureURLtxt").val();
 
   
       if(key == "")
            {
                $("#uploadImageForm").submit();
                rerturn;
            }
       
     else
         {
            $.ajax({
                type:"POST",
                url:"http://test-gardenshift.rhcloud.com/index.php/pages/change_picture",
                data:{ "key" : key},
                success: function(response)
                {        
                showProfilePicture();
                }
            });
         }
        
        
       
}