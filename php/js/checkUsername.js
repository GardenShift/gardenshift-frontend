//
//  Author : Hilay Khatri
// 


function checkUsername(name)
{
   // This function checks whether the username already exists in the database or not
   
        $.ajax({
            type:"POST",
            url:"http://test-gardenshift.rhcloud.com/index.php/pages/adduser",
            data:{"username" : name},
            success: function(response)
            {
                if(name.length > 5)
                  {
                        if(response == 0)
                            {                              
                            $("#errormsg").css("color","red");
                            $("#errormsg").html("Username already taken");                                                    
                            }
                        else
                            {
                            $("#errormsg").html("Username available");
                            $("#errormsg").css("color","green");
                            }
                  
                  }
                  else $("#errormsg").html("");
            }
        });
    
  
}


function userExists(name)
{
    // This function displays a pop up message if the search string in the search userbox doesn't match a username in the database'
        $.ajax({
                type:"POST",
                url:"http://test-gardenshift.rhcloud.com/index.php/pages/adduser",
                data:{"username" : name},
                success: function(response)
                {
                    
                            if(response == 0)
                                {     
                                        viewProfile();
                                }
                                else {
                                    document.getElementById('errorUsername').innerHTML = "Username does not Exists";
                                    $("#errorUsername").dialog('open');
                                     }
                }
            });
    
                                
}
