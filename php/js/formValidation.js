//
//  Author : Hilay Khatri
//  
//  This file stores all the validation script used before submitting the form
// 


jQuery(function(){
               
                // For Creating a new user Account
                
                jQuery("#email").validate({
                    expression: "if (VAL.match(/^[^\\W][a-zA-Z0-9\\_\\-\\.]+([a-zA-Z0-9\\_\\-\\.]+)*\\@[a-zA-Z0-9_]+(\\.[a-zA-Z0-9_]+)*\\.[a-zA-Z]{2,4}$/)) return true; else return false;",
                    message: "Please enter a valid Email"
                });
                
                jQuery("#password_add").validate({
                    expression: "if (VAL.length > 5 && VAL) return true; else return false;",
                    message: "Password is too short"
                
                });
                
                 jQuery("#confirmPassword_add").validate({
                    expression: "if ((VAL == jQuery('#password_add').val()) && VAL) return true; else return false;",
                    message: "Password doesn't match"
                 });
                 
                 jQuery("#username_add").validate({
                    expression: "if (VAL.length > 5 && VAL) return true; else return false;",
                    message: "Username is too short"         
           
                });
                
                
                // For Updating user Account
                
                jQuery("#user_email").validate({
                    expression: "if (VAL.match(/^[^\\W][a-zA-Z0-9\\_\\-\\.]+([a-zA-Z0-9\\_\\-\\.]+)*\\@[a-zA-Z0-9_]+(\\.[a-zA-Z0-9_]+)*\\.[a-zA-Z]{2,4}$/)) return true; else return false;",
                    message: "Please enter a valid Email"
                });
                
                jQuery("#user_password").validate({
                    expression: "if (VAL.length > 5 && VAL) return true; else return false;",
                    message: "Password is too short"
                
                });
                
                jQuery("#user_name").validate({
                    expression: "if (VAL.match(/^[A-Za-z ]*$/) && VAL) return true; else return false;",
                    message: "Please enter a valid Name"
                
                });
                
           
                jQuery("#user_zipcode").validate({
                    expression: "if (VAL.match(/^[0-9]*$/) && VAL.length == 5) return true; else return false;",
                    message: "Please enter a valid Zipcode"
                });
                
                
                // For Updating the Map Data
                
                jQuery("#crop_zipcode").validate({
                    expression: "if (VAL.match(/^[0-9]*$/) && VAL.length == 5) return true; else return false;",
                    message: "Please enter a valid Zipcode"
                });
                
                
                jQuery("#crop_name").validate({
                    expression: "if (VAL.match(/^[A-Za-z ]*$/) && VAL) return true; else return false;",
                    message: "Please enter a valid Name"
                
                });
                
                jQuery("#crop_distance").validate({
                    expression: "if (VAL < 11 && VAL) return true; else return false;",
                    message: "Please enter a smaller radius"
                
                });
            })
                 
          