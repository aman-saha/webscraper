<?php
/*
    Created by Aman Saha
    @darknurd

*/
//get hashtags
    function gethashtags($data)
    {
        preg_match_all('/(^|[^a-z0-9_])#([a-z0-9_]+)/i', $data, $matched_hashtags);
        $hashtag = '';
        if(!empty($matched_hashtags[0]))
        {
            foreach($matched_hashtags[0] as $match)
            {
                $hashtag .=preg_replace("/[^a-z0-9]+/i", "", $match).',';
            }
        }
        return rtrim($hashtag);
    }
//generate salt string
    function generate_salt_string()
    {
        $blowfish_pre = '$2a$05$';
        $blowfish_end = '$';
        $allowed_chars =
        'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789./';
        $chars_len = 63;
        $salt_length = 21;
        $salt = "";
        for($i=0; $i<$salt_length; $i++)
        {
            $salt .= $allowed_chars[mt_rand(0,$chars_len)];
        }
        $bcrypt_salt = $blowfish_pre . $salt . $blowfish_end;
        return $bcrypt_salt;
    }
//hashing of password
    function password_hashing($password)
    {
        $hash_password = array();
        $salt_string = generate_salt_string();
        $hashed_password = crypt($password,$salt_string);
        $hash_password['salt_string'] = $salt_string;
        $hash_password['hash_value'] = $hashed_password;
        return $hash_password;
    }
//compare hash values
    function hash_compare($a, $b) { 
        if (!is_string($a) || !is_string($b)) { 
            return false; 
        } 
        
        $len = strlen($a); 
        if ($len !== strlen($b)) { 
            return false; 
        } 

        $status = 0; 
        for ($i = 0; $i < $len; $i++) { 
            $status |= ord($a[$i]) ^ ord($b[$i]); 
        } 
        return $status === 0; 
    } 

//------------------------------------------------------------------------------------------------------------------------------------------------//

//preventing HTML and SQL Injection
    function mysql_entities_fix_string($data)
    {
        // Fix &entity\n;
        $data = str_replace(array('&amp;','&lt;','&gt;'), array('&amp;amp;','&amp;lt;','&amp;gt;'), $data);
        $data = preg_replace('/(&#*\w+)[\x00-\x20]+;/u', '$1;', $data);
        $data = preg_replace('/(&#x*[0-9A-F]+);*/iu', '$1;', $data);
        $data = html_entity_decode($data, ENT_COMPAT, 'UTF-8');

        // Remove any attribute starting with "on" or xmlns
        $data = preg_replace('#(<[^>]+?[\x00-\x20"\'])(?:on|xmlns)[^>]*+>#iu', '$1>', $data);

        // Remove javascript: and vbscript: protocols
        $data = preg_replace('#([a-z]*)[\x00-\x20]*=[\x00-\x20]*([`\'"]*)[\x00-\x20]*j[\x00-\x20]*a[\x00-\x20]*v[\x00-\x20]*a[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2nojavascript...', $data);
        $data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*v[\x00-\x20]*b[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2novbscript...', $data);
        $data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*-moz-binding[\x00-\x20]*:#u', '$1=$2nomozbinding...', $data);

        // Only works in IE: <span style="width: expression(alert('Ping!'));"></span>
        $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?expression[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
        $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?behaviour[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
        $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:*[^>]*+>#iu', '$1>', $data);

        // Remove namespaced elements (we do not need them)
        $data = preg_replace('#</*\w+:\w[^>]*+>#i', '', $data);

        do
        {
            // Remove really unwanted tags
            $old_data = $data;
            $data = preg_replace('#</*(?:applet|b(?:ase|gsound|link)|embed|frame(?:set)?|i(?:frame|layer)|l(?:ayer|ink)|meta|object|s(?:cript|tyle)|title|xml)[^>]*+>#i', '', $data);
        }
        while ($old_data !== $data);
        $data = trim($data);
        $data = stripslashes($data);
        $data=htmlspecialchars($data);
        return $data;
    }
//preventing HTML and SQL Injection
//------------------------------------------------------------------------------------------------------------------------------------------------//

//controlling the current user attempting login
    function attempt_login($username,$password,$connection)
    {
        $user = array();
        $blowfish_pre = '$2a$05$';
        $blowfish_end = '$';
        $salt_string="@!";
        $username.=$salt_string;
        $query="SELECT user_id,name,email,salt_string,password FROM users WHERE username='$username'";
        $result = mysqli_query($connection,$query);
        if(@mysqli_num_rows($result) == 1)
        {
            $row = mysqli_fetch_assoc($result);
            $hashed_password = crypt($password,$row['salt_string']);
            if(hash_compare($hashed_password,$row['password']))
            {
                $user['user_id']    = $row['user_id'];
                $user['username']   = $username;
                $user['name']       = chop($row['name'],$salt_string);
                $user['email']      = chop($row['email'],$salt_string);
                $user['ip_addr']    = $row['ip_addr'];
                $user['salt_string'] = $row['salt_string'];
                $user['success']    = true;
            }
            else
            {
                $user['success'] = false;
            }
        }
        else
        {
            $user['success'] = false;
        }
        return $user;
    }

//------------------------------------------------------------------------------------------------------------------------------------------------//

//to destroy current user session
    function destroySession()  
    {    
            session_start();
            session_destroy();
    }

//checking if user remained logged in
    function logged_in() 
    {
        return isset($_SESSION['current_username']);
    }


//to redirect to a new location
    function redirect_to($new_location) 
    {
        header("Location: " . $new_location);
        exit; 
    }
        
    //find name of user
    function find_name_by_id($user_id,$connection)
    {
        $salt_string = "@!";
        $query = "SELECT name FROM users WHERE user_id = $user_id";
        $result = mysqli_query($connection,$query);
        if($result)
        {
            $row = mysqli_fetch_assoc($result);
            return chop($row['name'],$salt_string);
        }
    }

    //find email of user
    function find_email_by_id($user_id,$connection)
    {
        $query="SELECT email FROM users WHERE user_id='$user_id'";
        $result=mysqli_query($connection,$query);
        if($result)
        {
            $row=mysqli_fetch_assoc($result);
            return chop($row['email'],$salt_string);
        }
    }

    //finds username of user
    function find_username_by_id($user_id,$connection)                           
    {
        $salt_string="@!";
        $query="SELECT username FROM users WHERE user_id=$user_id";
        $result=mysqli_query($connection,$query);
        if($result)
        {
            $row=mysqli_fetch_assoc($result);
            return chop($row['username'],$salt_string);
        }
    }

    function find_user_by_id($username,$connection)                           
    {
        $salt_string="@!";
        $username.= $salt_string;
        $query="SELECT user_id FROM users WHERE username = '$username'";
        $result=mysqli_query($connection,$query);
        if($result)
        {
            $row=mysqli_fetch_assoc($result);
            return $row['user_id'];
        }
    }

    //update username
    function update_username($user_id,$new_username,$connection)
    {
        if(!empty($new_username) && valid_username($new_username,$connection))
        {
            $salt_string="@!";
            $new_username.=$salt_string;

            $query = "UPDATE users SET username = '$new_username' WHERE user_id = '$user_id' LIMIT 1";
            $result = mysqli_query($connection, $query);
            $query = "UPDATE user_details SET username = '$new_username' WHERE user_id = '$user_id' LIMIT 1";
            $result = mysqli_query($connection, $query);
            if($result)
            {
                $_SESSION['current_username'] = chop($new_username,$salt_string);
                return 1;
            }
            else
                return 0;
        }
        else
            return 2;
    }

//update bio of user
     function update_bio($user_id,$bio,$connection)
    {
        if(!empty($bio))
        { 
            $salt_string="@!";
            $current_username.=$salt_string;
            $query = "UPDATE user_details SET bio = '$bio' WHERE user_id = '$user_id' LIMIT 1";
            $result = mysqli_query($connection, $query);

            if($result)
                return true;
            else
                return false;
        }
        else
            return true;
    }

//update birthday of user
    function update_birthday($user_id,$bday,$connection)
    {
        if(!empty($bday))
        {
            $salt_string="@!";
            $current_username.=$salt_string;
            $query = "UPDATE user_details SET bday = '$bday' WHERE user_id = '$user_id' LIMIT 1";
            $result = mysqli_query($connection, $query);

            if($result)
                return true;
            else
                return false;
        }
        else
            return true;
    }
        
//update new password of user
    function update_password($username,$confirm_password,$connection)         
    {
        if(!empty($confirm_password))
        {
            $salt_string="@!";
            $username.=$salt_string;
            $confirm_hashed_password=password_hashing($confirm_password);
            $query="UPDATE users SET password='$confirm_hashed_password' WHERE username='$username' LIMIT 1";
            $result = mysqli_query($connection,$query);
            if($result)
                return true;
            else
                return false;
        }
        else
            return true;
    }
//update location of user
    function update_location($user_id,$location,$connection)         
    {
        if(!empty($location))
        {
            $salt_string="@!";
            $username.=$salt_string;
            $query="UPDATE user_details SET location='$location' WHERE user_id='$user_id' LIMIT 1";
            $result = mysqli_query($connection,$query);
            if($result)
                return true;
            else
                return false;
       }
        else
            return true;
    }

    function update_gender($user_id,$gender,$connection)         
    {
        if(!empty($gender))
        {
            $salt_string="@!";
            $username.=$salt_string;
            $query="UPDATE user_details SET gender='$gender' WHERE user_id='$user_id' LIMIT 1";
            $result = mysqli_query($connection,$query);
            if($result)
                return true;
            else
                return false;
       }
        else
            return true;
    }

    function update_web($user_id,$web,$connection)         
    {
        if(!empty($web))
        {
            $salt_string="@!";
            $username.=$salt_string;
            $query="UPDATE user_details SET web='$web' WHERE user_id='$user_id' LIMIT 1";
            $result = mysqli_query($connection,$query);
            if($result)
                return true;
            else
                return false;
       }
        else
            return true;
    }
//---------------------------------------------------------------------------------------------------------//
    function deactivate_account($user_id,$connection)
    {
        $query = "UPDATE users SET status=0 WHERE user_id = '$user_id' ";
        $result = mysqli_query($connection,$query);
        if($result)
            return 1;
        else
            return 0;
    }

    function isFollowing($user_id,$friend_id,$connection)
    {
        $query = "SELECT id FROM friends WHERE friend_id_1=$user_id AND friend_id_2=$friend_id";
        $result_1 = mysqli_query($connection,$query);
        //$query = "SELECT id FROM friends WHERE friend_id_1=$friend_id AND friend_id_2=$user_id";
        //$result_2 = mysqli_query($connection,$query);
        if($result_1)
            if(mysqli_num_rows($result_1)==1)
                return true;
        return false;
    }

    function isBlocked($user_id,$friend_id,$connection)
    {
        $query = "SELECT * FROM block_list WHERE blocker_id = $user_id AND blocked_id = $friend_id OR blocker_id = $friend_id AND blocked_id = $user_id";
        $result = mysqli_query($connection,$connection);
        if($result && mysqli_num_rows($result))
            return true;
        return false;
    }

    function isLiked($user_id,$post_id,$connection)
    {
        $query = "SELECT * FROM likes WHERE post_id = $post_id AND user_id = $user_id";
        $result_like = mysqli_query($connection,$query);
        $like_status = false;       
        if(mysqli_num_rows($result_like)==1)
            $like_status = true;
        
        return $like_status;
    }

    function send_notification_post($post_id,$friend_id,$user_id,$msg,$connection)
    {
        $start_date = date("Y-m-d");
        $start_time = date("h:ia");
        $query = "INSERT INTO notification(post_id,user_id,friend_id,msg,start_date,start_time) VALUES($post_id,$user_id,$friend_id,'$msg','$start_date','$start_time')";
        $result = mysqli_query($connection,$query);
        if($result)
            return true;
        return false;
    } //for like and report system

    function send_notification_user($friend_id,$user_id,$msg,$connection)
    {
        $start_date = date("Y-m-d");
        $start_time = date("h:ia");
        $query = "INSERT INTO notification(user_id,friend_id,msg,start_date,start_time) VALUES($user_id,$friend_id,'$msg','$start_date','$start_time')";
        $result = mysqli_query($connection,$query);
        if($result)
            return true;
        return false;
    }

    /*function isRoasting($user_id,$friend_id,$connection)
    {
       $query = "SELECT id FROM roastee_event WHERE friend_id_1=$user_id AND friend_id_2=$friend_id";
        $result_1 = mysqli_query($connection,$query);
        $query = "SELECT id FROM roastee_event WHERE friend_id_1=$friend_id AND friend_id_2=$user_id";
        $result_2 = mysqli_query($connection,$query);
        if($result_1 && $result_2)
            if(@mysqli_num_rows($result_1)==1 || @mysqli_num_rows($result_2)==1)
                return true;
        return false;
    }

    function roastee_request_sent($user_id,$friend_id,$connection)
    {
        $query = "SELECT id FROM roastee_requests WHERE requester=$user_id AND accepter=$friend_id";
        $result_1 = mysqli_query($connection,$query);
        if(@mysqli_num_rows($result_1)==1)
            return true;
        return false;
    }

    function roastee_request_accept($user_id,$friend_id,$connection)
    {
        $query = "SELECT id FROM roastee_requests WHERE requester=$friend_id AND accepter=$user_id";
        $result_1 = mysqli_query($connection,$query);
        if(@mysqli_num_rows($result_1)==1)
            return true;
        return false;
    }*/

    function isOnline($user_id,$connection)
    {
        $query = "SELECT online FROM users WHERE user_id = $user_id ";
        $result = mysqli_query($connection,$query);
        if($result)
        {
            $row = mysqli_fetch_assoc($result);
            if($row['online'])
                return true;
            else
                return false;
        }
        return false;
    }

    function isFriendOfFriend($user_id,$friend_of_friend_id,$connection)
    {
        $query = "SELECT friend_id_1,friend_id_2 FROM friends WHERE friend_id_1 IN (SELECT
            CASE
                WHEN friend_id_1 = $friend_of_friend_id THEN friend_id_2
                ELSE friend_id_1
            END WHERE friend_id_1 = $friend_of_friend_id OR friend_id_2 = $friend_of_friend_id) OR friend_id_2 IN (SELECT
            CASE
                WHEN friend_id_1 = $friend_of_friend_id THEN friend_id_2
                ELSE friend_id_1
            END WHERE friend_id_1 = $friend_of_friend_id OR friend_id_2 = $friend_of_friend_id)";
        $result = mysqli_query($connection,$query);
        if (mysqli_num_rows($result)>0)
            return true;
        return false;

    }
?>