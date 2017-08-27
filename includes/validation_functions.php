<?php
/*
    Created by Aman Saha
    @darknurd

*/

//validation of name
    function valid_name($name)                 
    {
        global $error_in_name;
        if (!preg_match("/^[a-zA-Z ]*$/",$name)) 
        {
            $error_in_name = "Only letters and white space allowed";
             return 0; 
        }
           
        else
            return 1;
    }
//validation of name

//validation of e-mail
    function valid_email($email,$connection)                
    {
        global $error_in_email;
        $salt_string="@!";
        $email.=$salt_string;
        if(!empty($email))
        {
            $query = "SELECT email FROM users WHERE email='$email'";
            $result = mysqli_query($connection,$query);
            $email = chop($email,$salt_string);
            if(@mysqli_num_rows($result) == 0)
            {
                if (filter_var($email, FILTER_VALIDATE_EMAIL))
                {
                    return 1;
                }
                else
                {
                    $error_in_email="E-mail format error";
                }
            }
            else
            {
                $error_in_email="Email already exists.";
            }
        }
        else
        {
            $error_in_email="Please enter an E-mail";
        }
        return 0;
    }
//validation of e-mail

//validation of username
    function valid_username($username,$connection)        
    {
        global $error_in_username;
        $salt_string="@!";
        $username.=$salt_string;
        $query="SELECT username FROM users WHERE username='$username'";
        $result = mysqli_query($connection,$query);
        if(@mysqli_num_rows($result) == 0)
        {
            return 1;
        }
        else
        {
            $error_in_username="Username already taken.";
            return 0;
        }
    }
//validation of username

//validation of if username available
    function valid_new_username($new_username,$connection)        
    {
        $salt_string="@!";
        $new_username.=$salt_string;
        $query="SELECT username FROM users WHERE username='$new_username'";
        $check_username=mysqli_query($connection,$query);
        $check=@mysqli_num_rows($check_username);
        if($check==0)
        {
            return 1;
        }
        else
        {
            return 0;
        }
    }

//validation of if username available
    function valid_web($web,$connection)
    {
        global $error_in_web;
        if (!preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i",$website)) 
        {
            $error_in_web = "Invalid URL";
            return 0; 
        }
        return 1;
    }
//validation of password
    function valid_password($password,$confirm_password)         
    {
        global $error_in_password;
        if($password!=$confirm_password)
        {
            $error_in_password="Passwords do not match";
            return 0;
        }
        else
            return 1;
    }
//validation of password

?>