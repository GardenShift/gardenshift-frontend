//
//  Author : Hilay Khatri
//  
//  When the user logs in, this script gets called to setup the initial envrionment 
//  
//  Div tags that need to be initialized as Dialog box are initialized here
// 

function main_init()
{
   $('#map_canvas').bind('tabsshow', function(event, ui) {
    if (ui.panel.id == "map_canvas") {
        resizeMap();
    }
});
    
         $('#searchField1').keypress(function(event) {
    if (event.keyCode == 13) {
        event.preventDefault();
    }
    
    });
    $.ajaxSetup ({  
        cache : false,
        async:false
    });
    

    var logout = document.getElementById('logout');
    logout.onclick = logout_f;

    var settings = document.getElementById('settings');
    settings.onclick = settings_f;
    
    var nearCrops = document.getElementById('nearByCrops');
    nearCrops.onclick = nearByCrops_f;
    
    var mycrops = document.getElementById('mycrops');
    mycrops.onclick = myCrops_f;
    
    var cropsFromDatabase = document.getElementById('allcrops');
    cropsFromDatabase.onclick = allcrops_f;
    
    

    function logout_f()
    {
        window.location = "http://test-gardenshift.rhcloud.com/index.php/pages/logout";
    }
    
      function allcrops_f()
    {
        window.location = "http://test-gardenshift.rhcloud.com/index.php/crop/allcrops";
    }
    


   $( "#userSettingsDialog" ).dialog({               
         
            modal: true,
            resizable: true,
            autoResize: true,
            autoOpen: false, 
            title: "Settings",
            overlay: {backgroundColor: "#0FF", opacity: 0.5},
            height: 'auto',
            width: 'auto',
            buttons: {
                    'Cancel': function() {
                        $(this).dialog('close');
                    },
               
                    'Update': function() {   
                        
                            
                   $("#userSettingsForm").submit();
             
                  }
            }}
);
    
    
    $( "#mapData" ).dialog({               
         
            modal: true,
            resizable: true,
            autoResize: true,
            autoOpen: false, 
            title: "Search",
            overlay: {backgroundColor: "#0FF", opacity: 0.5},
            height: 'auto',
            width: 'auto',
            buttons: {
                    'Cancel': function() {
                        $(this).dialog('close');
                        
                    },
               
                    'Show': function() {                  
                   $(this).dialog('close');
                   update_maps();
                  }
            }}
);
    
    
    $( "#feedbackPopUp" ).dialog({               
         
         
            resizable: true,
            autoResize: true,
            
            title: "Feedbacks",
            overlay: {backgroundColor: "#0FF", opacity: 0.5},
            autoOpen: false,
            height: 'auto',
            width: 'auto',
            buttons: {
                    'Ok': function() {
                        $(this).dialog('close');
                    }
               
                
                  }
            }
);
    
    $( "#friendsPopUp" ).dialog({               
         
         
            resizable: true,
            autoResize: true,
            
            title: "Friends",
            overlay: {backgroundColor: "#0FF", opacity: 0.5},
            autoOpen: false,
            height: 'auto',
            width: 'auto',
            buttons: {
                    'Ok': function() {
                        $(this).dialog('close');
                    }
               
                
                  }
            }
);
    
    $( "#pictureURL" ).dialog({               
         
         
            resizable: true,
            autoResize: true,       
            title: "Change Profile Picture",
            overlay: {backgroundColor: "#0FF", opacity: 0.5},
            autoOpen: false,
            height: 'auto',
            width: 'auto',
            buttons: {
                    'Close': function() {
                        $(this).dialog('close');
                    },
                    'Update': function() {
                        
                        
                        changePicture();
                        $(this).dialog('close');
                    }
               
                
                  }
            }
);
    
    $( "#addFeedbackPopUp" ).dialog({               
         
         
            resizable: true,
            autoResize: true,       
            title: "Add Feedback",
            overlay: {backgroundColor: "#0FF", opacity: 0.5},
            autoOpen: false,
            height: 'auto',
            width: 'auto',
            buttons: {
                    'Close': function() {
                        $(this).dialog('close');
                    },
                    'Add': function() {
                        addFeedback();
                        $(this).dialog('close');
                    }
               
                
                  }
            }
);
    
    
  
    
$( "#errorUsername" ).dialog({               
         
         
           
            resizable: false,
            autoResize: false,
            autoOpen: false, 
            title: "Message",
            overlay: {backgroundColor: "#0FF", opacity: 0.5},
            height: 'auto',
            width: 'auto',
            buttons: {
                    'Close': function() {
                        $(this).dialog('close');   
                    }
                               
                  }
            }
);  
    

$( "#albumPictureURL" ).dialog({               
         
         
            resizable: true,
            autoResize: true,       
            title: "Add picture",
            overlay: {backgroundColor: "#0FF", opacity: 0.5},
            autoOpen: false,
            height: 'auto',
            width: 'auto',
            buttons: {
                    'Close': function() {
                        $(this).dialog('close');
                    },
                    'Update': function() {
                        albumPictureUpload();
                        $(this).dialog('close');
                    }
               
                
                  }
            }
);
    
    

    
    
    $( "#errorUsername" ).dialog('close');
    $( "#userSettingsDialog" ).dialog('close');
    $( "#mapData" ).dialog('close');
    $( "#feedbackPopUp" ).dialog('close');
    $( "#GoogleMapArea" ).dialog('close');
      
}