//
//  Author : Hilay Khatri
// 


function showAvailableCrops()
    {
        // Populate crops table for all the available crops that a user can trade with
        
           
          
           
           $.ajax({
                type:"POST",
                url:"http://test-gardenshift.rhcloud.com/index.php/pages/get_crops",
                success: function(response)
                {
                     
              
                    var obj = jQuery.parseJSON(response);
                    
                   
                    var msg = "<table cellpadding='0' cellspacing='0px' border='0' id='userCropsTable' width='100%'>";
                            msg += "<thead><tr>";
                            msg += "<th>Username</th>";
                            msg += "<th>Crop</th>";
                            msg += "<th>Quantity</th>";
                            msg += "<th>Harvestation Date</th>";
                            msg += "<th>Email</th>";
                            msg += "<th>Zipcode</th>";
                            msg += "<th>Comments</th></thead><tbody>";
                            
                            
                    for(i=0; i< obj.length; i++)
                        {
                           
                            if(!isNaN(obj[i].user_crops.length))
                                {
                                    for(j=0; j< obj[i].user_crops.length; j++)
                                        {
                                            
                                            msg += "<tr>";
                                            msg += "<td>" + obj[i].username + "</td>";
                                            msg += "<td>" + obj[i].user_crops[j].crop_name + "</td>";
                                            msg += "<td>" + obj[i].user_crops[j].crop_expected_quantity + "</td>";
                                            msg += "<td>" + obj[i].user_crops[j].crop_harvest_date + "</td>";
                                            msg += "<td>" + obj[i].email + "</td>";
                                            msg += "<td>" + obj[i].zipcode + "</td>";
                                            msg += "<td>" + obj[i].user_crops[j].comments + "</td>";
                                            msg += "</tr>"; 
                                            
                                            
                                        }
                                    
                                }
                                
                              
                        }
                        
                        
                     msg += "</tbody></table>";
                     
                    document.getElementById('showCropsAll').innerHTML = msg;
                     
                         
                    $("#userCropsTable").dataTable( {
                                    
                                    "bPaginate": false,
                                    "bScrollCollapse": true,
                                    "bJQueryUI": true,
                                    "sPaginationType": "full_numbers",
                                    "bAutoWidth": false
                            });
                            
                   
                   
                     
                 
                            
                         
                            
                     
                     }
                
 
            });
            
            
      
  
             $( "#showCropsAll" ).dialog('open');
    
    }
    
    
    
   
   
   function showAllCrops()
    {
        // Populate table for all the user grown crops
        
           $.ajax({
                type:"POST",
                url:"http://test-gardenshift.rhcloud.com/index.php/pages/get_recent_crops",
                success: function(response)
                {
                     

                    var obj = jQuery.parseJSON(response);
                    
                   
                    var msg = "<table cellpadding='0' style='width: 100px;' cellspacing='0' border='0' id='userCropsTableTab' width='50%'>";
                            msg += "<thead><tr>";
                           
                            msg += "<th>Crop</th>";
                            msg += "<th>Quantity</th>";
                            msg += "<th>Harvestation Date</th>";
                            
                            msg += "<th>Comments</th></thead><tbody>";
                            
                            
                     
                            if(!isNaN(obj.user_crops.length))
                                {
                                    for(j=0; j< obj.user_crops.length; j++)
                                        {
                                            msg += "<tr>";
                                          
                                            msg += "<td>" + obj.user_crops[j].crop_name + "</td>";
                                            msg += "<td>" + obj.user_crops[j].crop_expected_quantity + "</td>";
                                            msg += "<td>" + obj.user_crops[j].crop_harvest_date + "</td>";
                                           
                                            msg += "<td>" + obj.user_crops[j].comments + "</td>";
                                            msg += "</tr>";                                          
                                        }
                                    
                                }
                       
                        
                     msg += "</tbody></table>";
                   
                     document.getElementById('UserCropsTab').innerHTML = msg;
                     
                     $("#userCropsTableTab").dataTable( {
                                    "sScrollY": "200px",
                                    "bPaginate": false,
                                    "bScrollCollapse": true,
                                    "bJQueryUI": true,
                                    "sPaginationType": "full_numbers",
                                    "bAutoWidth" : true
                            });
                     
                     }
                
 
            });
  
  
    
    }
    
    
    function showRecentCrops()
    {
        // Populate recent crops div with the most recent 3 crops grown
        
           $.ajax({
                type:"POST",
                url:"http://test-gardenshift.rhcloud.com/index.php/pages/get_recent_crops",
                success: function(response)
                {
                     

                    var obj = jQuery.parseJSON(response);
  
                    var msg = "<ul>";
 
                            
                    for(i= obj.user_crops.length - 1; i>=0 ; i--)
                        {
                            
                            if(!isNaN(obj.user_crops.length))
                                {
                                            msg += "<li>";
                                            msg += "<h3>" + obj.user_crops[i].crop_name + "</h3>";
                                            msg += "<p style = 'color:green;' >"  + obj.user_crops[i].crop_harvest_date + "</p>";
                                            msg += "</li>";                                          
                                                
                                }
                                
                            if(obj.user_crops.length - i == 3 )
                                break;
                                    
                                
                                
                        }
                        
                        
                     msg += "</ul>";
                   
                     if(obj.user_crops.length > 0)
                        document.getElementById('CropsDiv').innerHTML = msg;
                     else
                         document.getElementById('CropsDiv').innerHTML = "No Recent Crops";
                     
                    
                     
                     }
                
 
            });
  
  
    
    }