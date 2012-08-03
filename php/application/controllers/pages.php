<?php

class Pages extends CI_Controller {

    public function index() {
        //landing page when the url is  http://test-gardenshift.rhcloud.com/index.php/pages       
        $this->load->library('session');

        $username_session = $this->session->userdata('username');

        if ($username_session == '') {
            $this->load->view('pages/home.php');
        }
        else
            $this->mainPageLoader();
    }

    public function adduser() {
        // function to add a new user to the database
        $name = $_POST['username'];
        $this->load->library('session');
        $context = stream_context_create(array(
            'http' => array(
                'method' => 'GET',
                'header' => array("username: " . $this->session->userdata('username'), "secretKey: " . $this->session->userdata('secretKey'))
            )
                ));

        $json = file_get_contents('http://dev-gardenshift.rhcloud.com/Gardenshift/user_available/' . $name, false, $context);
        //   $json = file_get_contents('http://dev-gardenshift.rhcloud.com/Gardenshift/user_available/'.$name);
        $data['result'] = json_decode($json);

        if ($data['result'] == null)
            $data['status'] = '1';
        else
            $data['status'] = '0';

        $this->load->view('pages/adduser.php', $data);
    }

    public function authenticate() {
        //code to authenticate user 
        session_start();
        $this->load->library('session');

        $username = $_POST['username'];
        $password = $_POST['password'];
        $username_session = $this->session->userdata('username');
        if ($username != $username_session) {
            // Calls in Web service to check whether the provided credentials exist or not

            $url = 'http://dev-gardenshift.rhcloud.com/Gardenshift/authenticate';
            // The submitted form data, encoded as query-string-style
            // name-value pairs

            $body = 'username=' . $username . '&password=' . $password;
            $c = curl_init($url);
            curl_setopt($c, CURLOPT_POST, true);
            curl_setopt($c, CURLOPT_POSTFIELDS, $body);
            curl_setopt($c, CURLOPT_RETURNTRANSFER, true);

            $page = curl_exec($c);
            curl_close($c);


            // Calls in web service to display all the information of all the users in order to see which all crops are being grown



            if ($page != "false") {


                $this->session->set_userdata('username', $username);
                $this->session->set_userdata('secretKey', sha1($username . $page));
                //$_SESSION['username'] = $username;
                //$msgcount=count($usermessages);
                $this->mainPageLoader();
                // echo $page; 
            }
            else
                echo 'Invalid username or password';
        }
        else {
            $this->mainPageLoader();
        }
    }

    public function notification_populator() {
        //function to display notifications
        $this->load->library('session');
        $username = $this->session->userdata('username');
        $ch1 = curl_init("https://dev-gardenshift.rhcloud.com/Gardenshift/get_bulletin/" . $username);
        curl_setopt($ch1, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch1, CURLOPT_HTTPHEADER, array("username: " . $this->session->userdata('username'), "secretKey: " . $this->session->userdata('secretKey')));
        $json_res = curl_exec($ch1);
        curl_close($ch1);

        $json_array = json_decode($json_res);
        // print_r($json_array);
        if (count($json_array) != 0)
            $bulletin = $json_array->{'bulletin'};
        //print_r($bulletin);
        $bulletincount = count($bulletin);
        //print_r($bulletin);


        global $array1;
        for ($i = 0; $i < count($bulletin); $i++) {

            $array1[$i] = $bulletin[$i]->{'text'};
        }
        $ch2 = curl_init("https://dev-gardenshift.rhcloud.com/Gardenshift/get_bulletin_archive/" . $username);
        curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch2, CURLOPT_HTTPHEADER, array("username: " . $this->session->userdata('username'), "secretKey: " . $this->session->userdata('secretKey')));
        $json_res = curl_exec($ch2);
        curl_close($ch2);
        // echo $json_res;
        $json_array = json_decode($json_res);
        // print_r($json_array);
        if (count($json_array) != 0)
            $bulletin1 = $json_array->{'bulletin_archive'};

        //print_r($bulletin);
        global $array2;
        for ($i = 0; $i < count($bulletin1) && $i <= 10; $i++) {

            $array2[$i] = $bulletin1[$i]->{'text'};
        }
        $notarray_read = $array2;
        $notarray = $array1;

        $i = 0;
        for ($i; $i < $bulletincount && $i <= 10; $i++) {
            echo '<li><strong>';
            if ($i == 0) {
                echo '<img class="corner_inset_left" alt="" src="../../images/corner_inset_left.png"/>';
            }
            echo '<a href="';
            if (strpos($notarray[$bulletincount - $i - 1], 'message') == TRUE) {

                echo 'http://test-gardenshift.rhcloud.com/index.php/message/mymessages/' . $this->session->userdata('username') . '">';
            } else if (strpos($notarray[$bulletincount - $i - 1], 'Feedback') == TRUE) {
                echo 'javascript:void(0);" onclick="showAllFeedback()" >';
            } else if (strpos($notarray[$bulletincount - $i - 1], 'friend') == TRUE) {
                echo 'javascript:void(0);" onclick="showPendingFriendRequests()" >';
            }

            else
                echo '#">';

            echo $notarray[$bulletincount - $i - 1];
            echo'</a>';
//                       if($i==0){
//                                echo '<img class="corner_right" alt="" src="../../images/corner_right.png"/>';
//                            }
            echo ' </strong></li>';
        }

        for ($i; $i < count($notarray_read) && $i <= 10; $i++) {
            echo '<li>';
            if ($i == 0) {
                echo '<img class="corner_inset_left" alt="" src="../../images/corner_inset_left.png"/>';
            }
            echo '<a href="';
            if (strpos($notarray_read[count($notarray_read) - $i - 1], 'message') == TRUE) {

                echo 'http://test-gardenshift.rhcloud.com/index.php/message/mymessages/' . $this->session->userdata('username') . '">';
            } else if (strpos($notarray_read[count($notarray_read) - $i - 1], 'Feedback') == TRUE) {

                echo 'javascript:void(0);" onclick="showAllFeedback()" >';
            } else if (strpos($notarray_read[count($notarray_read) - $i - 1], 'friend') == TRUE) {

                echo 'javascript:void(0);" onclick="showPendingFriendRequests()" >';
            }
            else
                echo '#">';


            echo $notarray_read[count($notarray_read) - $i - 1];
            echo'</a>';
//                        if($i==0){
//                                echo '<img class="corner_right" alt="" src="../../images/corner_right.png"/>';
//                            }
            echo ' </li>';
        }

        echo '<li style="background:#0395CC; padding-left:0px; margin:0px 0px; padding: 5px 0px; "><center><strong><a id="morenotifs" style="color:#172322;">View more notifications...</a></strong></center></li>';
    }

    public function logout() {
        //logs the user out
        $this->load->library('session');
        $context = stream_context_create(array(
            'http' => array(
                'method' => 'GET',
                'header' => array("username: " . $this->session->userdata('username'), "secretKey: " . $this->session->userdata('secretKey'))
            )
                ));

        $json = file_get_contents('http://dev-gardenshift.rhcloud.com/Gardenshift/logout/' . $this->session->userdata('username'), false, $context);
        $this->session->set_userdata('username', '');
        $this->session->set_userdata('secretKey', '');

        $this->load->view('pages/home.php');
    }

    public function get_userdata() {
        //get all the user data
        $this->load->library('session');
        $username = $this->session->userdata('username');
        $context = stream_context_create(array(
            'http' => array(
                'method' => 'GET',
                'header' => array("username: " . $this->session->userdata('username'), "secretKey: " . $this->session->userdata('secretKey'))
            )
                ));

        $json = file_get_contents('http://dev-gardenshift.rhcloud.com/Gardenshift/user_available/' . $username, false, $context);
        //$json = file_get_contents('http://dev-gardenshift.rhcloud.com/Gardenshift/user_available/'.$username);
        $result = json_decode($json);

        echo $json;
    }

    public function post_userdata() {

        // Need to add functionality in web service to change password
        $this->load->library('session');

        $username = $this->session->userdata('username');

        $password = $_POST['password'];
        $email = $_POST['email'];
        $zip = $_POST['zipcode'];
        $name = $_POST['name'];



        $url = 'http://dev-gardenshift.rhcloud.com/Gardenshift/updateuser';
        // The submitted form data, encoded as query-string-style
        // name-value pairs

        $body = 'username=' . $username . '&email=' . $email . '&zip=' . $zip . '&name=' . $name . '&password=' . $password;

        $c = curl_init($url);
        curl_setopt($c, CURLOPT_POST, true);
        curl_setopt($c, CURLOPT_POSTFIELDS, $body);
        curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($c, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($c, CURLOPT_HTTPHEADER, array("username: " . $this->session->userdata('username'), "secretKey: " . $this->session->userdata('secretKey')));
        $page = curl_exec($c);
        curl_close($c);

        $this->mainPageLoader();
    }

    public function get_crops() {
        $this->load->library('session');
        $context = stream_context_create(array(
            'http' => array(
                'method' => 'GET',
                'header' => array("username: " . $this->session->userdata('username'), "secretKey: " . $this->session->userdata('secretKey'))
            )
                ));

        $crops = file_get_contents('http://dev-gardenshift.rhcloud.com/Gardenshift/user_details/all', false, $context);
        //    $crops = file_get_contents('http://dev-gardenshift.rhcloud.com/Gardenshift/user_details/all');

        echo $crops;
    }

    public function get_mapdata() {

        $this->load->library('session');
        $zipcode = $_POST['crop_zipcode'];
        $distance = $_POST['crop_distance'];
        $crop = $_POST['crop_name'];
        $context = stream_context_create(array(
            'http' => array(
                'method' => 'GET',
                'header' => array("username: " . $this->session->userdata('username'), "secretKey: " . $this->session->userdata('secretKey'))
            )
                ));

        $crops = file_get_contents('http://dev-gardenshift.rhcloud.com/Gardenshift/search/' . $zipcode . '/' . $distance . '/' . $crop, false, $context);
        // $crops = file_get_contents('http://dev-gardenshift.rhcloud.com/Gardenshift/search/'.$zipcode.'/'.$distance.'/'.$crop);

        echo $crops;
    }

    public function get_feedback() {


        $this->load->library('session');

        $username = $this->session->userdata('username');
        $context = stream_context_create(array(
            'http' => array(
                'method' => 'GET',
                'header' => array("username: " . $this->session->userdata('username'), "secretKey: " . $this->session->userdata('secretKey'))
            )
                ));

        $feedback = file_get_contents('http://dev-gardenshift.rhcloud.com/Gardenshift/user_available/' . $username, false, $context);
        // $feedback = file_get_contents('http://dev-gardenshift.rhcloud.com/Gardenshift/user_available/'.$username);

        echo $feedback;
    }

    public function get_recent_crops() {


        $this->load->library('session');

        $username = $this->session->userdata('username');
        $context = stream_context_create(array(
            'http' => array(
                'method' => 'GET',
                'header' => array("username: " . $this->session->userdata('username'), "secretKey: " . $this->session->userdata('secretKey'))
            )
                ));

        $crops = file_get_contents('http://dev-gardenshift.rhcloud.com/Gardenshift/user_available/' . $username, false, $context);
        //   $crops = file_get_contents('http://dev-gardenshift.rhcloud.com/Gardenshift/user_available/'.$username);

        echo $crops;
    }

    public function post_status() {


        // Need to add functionality in web service to change password
        $this->load->library('session');

        $username = $this->session->userdata('username');
        $status = $_POST['status'];

        $url = 'http://dev-gardenshift.rhcloud.com/Gardenshift/status/';
        // The submitted form data, encoded as query-string-style
        // name-value pairs

        $body = 'username=' . $username . '&status_txt=' . $status;

        $c = curl_init($url);
        curl_setopt($c, CURLOPT_POST, true);
        curl_setopt($c, CURLOPT_POSTFIELDS, $body);
        curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($c, CURLOPT_HTTPHEADER, array("username: " . $this->session->userdata('username'), "secretKey: " . $this->session->userdata('secretKey')));
        $page = curl_exec($c);
        curl_close($c);
    }

    public function visit_user() {

        $guest = $_POST['name'];

        $this->load->library('session');
        $curruser = $this->session->userdata('username');

        $this->session->set_userdata('guest', $guest);
        $context = stream_context_create(array(
            'http' => array(
                'method' => 'GET',
                'header' => array("username: " . $curruser, "secretKey: " . $this->session->userdata('secretKey'))
            )
                ));

        $guest_details = file_get_contents('http://dev-gardenshift.rhcloud.com/Gardenshift/user_available/' . $guest, false, $context);
        // $guest_details = file_get_contents('http://dev-gardenshift.rhcloud.com/Gardenshift/user_available/'.$guest);

        echo $guest_details;
    }

    public function delete_status() {


        $date = $_POST['key'];

        $this->load->library('session');

        $username = $this->session->userdata('username');
        $context = stream_context_create(array(
            'http' => array(
                'method' => 'GET',
                'header' => array("username: " . $this->session->userdata('username'), "secretKey: " . $this->session->userdata('secretKey'))
            )
                ));

        $result = file_get_contents('http://dev-gardenshift.rhcloud.com/Gardenshift/delete_status/' . $username . '/' . rawurlencode($date), false, $context);
        //  $result = file_get_contents('http://dev-gardenshift.rhcloud.com/Gardenshift/delete_status/'.$username.'/'.  rawurlencode($date));
    }

    public function get_all_username() {
        $this->load->library('session');
        $context = stream_context_create(array(
            'http' => array(
                'method' => 'GET',
                'header' => array("username: " . $this->session->userdata('username'), "secretKey: " . $this->session->userdata('secretKey'))
            )
                ));

        $users = file_get_contents('http://dev-gardenshift.rhcloud.com/Gardenshift/user_details/all', false, $context);
        //  $users = file_get_contents('http://dev-gardenshift.rhcloud.com/Gardenshift/user_details/all');

        echo $users;
    }

    public function add_friends() {


        // Need to add functionality in web service to change password
        $this->load->library('session');

        $username = $this->session->userdata('username');
        $key = $_POST['key'];

        $url = 'http://dev-gardenshift.rhcloud.com/Gardenshift/add_friends/';

        // The submitted form data, encoded as query-string-style
        // name-value pairs

        $body = 'username=' . $username . '&friend_name=' . $key;

        $c = curl_init($url);
        curl_setopt($c, CURLOPT_POST, true);
        curl_setopt($c, CURLOPT_POSTFIELDS, $body);
        curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($c, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($c, CURLOPT_HTTPHEADER, array("username: " . $this->session->userdata('username'), "secretKey: " . $this->session->userdata('secretKey')));
        $page = curl_exec($c);
        curl_close($c);


        $url1 = 'http://dev-gardenshift.rhcloud.com/Gardenshift/add_bulletin';
        $body1 = 'username=' . $key . '&text= ' . $username . ' sent you a friend request';
        $c1 = curl_init($url1);
        curl_setopt($c1, CURLOPT_POST, true);
        curl_setopt($c1, CURLOPT_POSTFIELDS, $body1);
        curl_setopt($c1, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($c1, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($c1, CURLOPT_HTTPHEADER, array("username: " . $this->session->userdata('username'), "secretKey: " . $this->session->userdata('secretKey')));
        $page = curl_exec($c1);
        curl_close($c1);
    }

    public function accept_friends() {


        // Need to add functionality in web service to change password
        $this->load->library('session');

        $username = $this->session->userdata('username');
        $key = $_POST['key'];

        $url = 'http://dev-gardenshift.rhcloud.com/Gardenshift/accept_friends/';

        // The submitted form data, encoded as query-string-style
        // name-value pairs

        $body = 'username=' . $username . '&friend_name=' . $key;

        $c = curl_init($url);
        curl_setopt($c, CURLOPT_POST, true);
        curl_setopt($c, CURLOPT_POSTFIELDS, $body);
        curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($c, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($c, CURLOPT_HTTPHEADER, array("username: " . $this->session->userdata('username'), "secretKey: " . $this->session->userdata('secretKey')));
        $page = curl_exec($c);
        curl_close($c);
    }

    public function change_picture() {


        // Need to add functionality in web service to change password
        $this->load->library('session');

        $username = $this->session->userdata('username');
        $key = $_POST['key'];

        $url = 'http://dev-gardenshift.rhcloud.com/Gardenshift/change_picture/';

        // The submitted form data, encoded as query-string-style
        // name-value pairs

        $body = 'username=' . $username . '&url=' . rawurlencode($key);

        $c = curl_init($url);
        curl_setopt($c, CURLOPT_POST, true);
        curl_setopt($c, CURLOPT_POSTFIELDS, $body);
        curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($c, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($c, CURLOPT_HTTPHEADER, array("username: " . $this->session->userdata('username'), "secretKey: " . $this->session->userdata('secretKey')));
        $page = curl_exec($c);
        curl_close($c);

        echo $page;
    }

    public function add_feedback() {


        // Need to add functionality in web service to change password
        $this->load->library('session');

        $username = $this->session->userdata('username');
        $to = $_POST['to'];
        $msg = $_POST['msg'];

        $url = 'http://dev-gardenshift.rhcloud.com/Gardenshift/add_feedback/';

        // The submitted form data, encoded as query-string-style
        // name-value pairs

        $url1 = 'http://dev-gardenshift.rhcloud.com/Gardenshift/add_bulletin';
        $body1 = 'username=' . $to . '&text= ' . $username . ' sent you a Feedback';
        $c1 = curl_init($url1);
        curl_setopt($c1, CURLOPT_POST, true);
        curl_setopt($c1, CURLOPT_POSTFIELDS, $body1);
        curl_setopt($c1, CURLOPT_HTTPHEADER, array("username: " . $this->session->userdata('username'), "secretKey: " . $this->session->userdata('secretKey')));
        curl_setopt($c1, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($c1, CURLOPT_FOLLOWLOCATION, 1);

        $page = curl_exec($c1);
        curl_close($c1);

        $body = 'from=' . $username . '&to=' . $to . '&status_txt=' . $msg;

        $c = curl_init($url);
        curl_setopt($c, CURLOPT_POST, true);
        curl_setopt($c, CURLOPT_POSTFIELDS, $body);
        curl_setopt($c, CURLOPT_HTTPHEADER, array("username: " . $this->session->userdata('username'), "secretKey: " . $this->session->userdata('secretKey')));
        curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($c, CURLOPT_FOLLOWLOCATION, 1);

        $page = curl_exec($c);
        curl_close($c);

        echo $page;
    }

    public function mainPageLoader() {

        $this->load->library('session');
        $username = $this->session->userdata('username');
        $secretKey = $this->session->userdata('secretKey');
        if ($this->session->userdata('username') != '') {

            $ch = curl_init("https://dev-gardenshift.rhcloud.com/Gardenshift/get_notification_unread/" . $username);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array("username: " . $this->session->userdata('username'), "secretKey: " . $this->session->userdata('secretKey')));
            $json_res = curl_exec($ch);
            curl_close($ch);

            $json_array = json_decode($json_res);
            if (count($json_array) != 0)
                $unreadmsgs = $json_array->{'notifications_unread'};
            $data['msgcount'] = count($unreadmsgs);
            //$msgcount=count($usermessages);


            $ch1 = curl_init("https://dev-gardenshift.rhcloud.com/Gardenshift/get_bulletin/" . $username);
            curl_setopt($ch1, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch1, CURLOPT_HTTPHEADER, array("username: " . $username, "secretKey: " . $secretKey));
            $json_res = curl_exec($ch1);
            curl_close($ch1);
            // echo $json_res;
            $json_array = json_decode($json_res);
            // print_r($json_array);
            if (count($json_array) != 0)
                $bulletin = $json_array->{'bulletin'};
            //print_r($bulletin);
            $data['bulletincount'] = count($bulletin);
            //print_r($bulletin);
            global $array1;
            for ($i = 0; $i < count($bulletin); $i++) {

                $array1[$i] = $bulletin[$i]->{'text'};
            }
            $ch2 = curl_init("https://dev-gardenshift.rhcloud.com/Gardenshift/get_bulletin_archive/" . $username);
            curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch2, CURLOPT_HTTPHEADER, array("username: " . $username, "secretKey: " . $secretKey));
            $json_res = curl_exec($ch2);
            curl_close($ch2);
            // echo $json_res;
            $json_array = json_decode($json_res);
            // print_r($json_array);
            if (count($json_array) != 0)
                $bulletin1 = $json_array->{'bulletin_archive'};

            //print_r($bulletin);
            global $array2;
            for ($i = 0; $i < count($bulletin1) && $i <= 10; $i++) {

                $array2[$i] = $bulletin1[$i]->{'text'};
            }
            $data['notarray_read'] = $array2;
            $data['notarray'] = $array1;
            $this->load->view('pages/main.php', $data);
        } else {
            header('Location: http://test-gardenshift.rhcloud.com/');
        }
    }

    public function get_bulletin_count() {


        $this->load->library('session');

        $username = $this->session->userdata('username');

        $ch1 = curl_init("https://dev-gardenshift.rhcloud.com/Gardenshift/get_bulletin/" . $username);
        curl_setopt($ch1, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch1, CURLOPT_HTTPHEADER, array("username: " . $this->session->userdata('username'), "secretKey: " . $this->session->userdata('secretKey')));
        $json_res = curl_exec($ch1);
        curl_close($ch1);
        // echo $json_res;
        $json_array = json_decode($json_res);
        // print_r($json_array);
        if (count($json_array) != 0)
            $bulletin = $json_array->{'bulletin'};
        if (count($bulletin) != 0)
            echo count($bulletin);
        return count($bulletin);
    }

    public function flush_bulletin() {


        $this->load->library('session');

        $username = $this->session->userdata('username');

        $ch1 = curl_init("https://dev-gardenshift.rhcloud.com/Gardenshift/flush_bulletin/" . $username);
        curl_setopt($ch1, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch1, CURLOPT_HTTPHEADER, array("username: " . $this->session->userdata('username'), "secretKey: " . $this->session->userdata('secretKey')));
        $json_res = curl_exec($ch1);
        curl_close($ch1);
        // echo $json_res;
        $json_array = json_decode($json_res);
    }

    public function auto_login_onsignup() {
        $this->load->library('session');

        $username_session = $this->session->userdata('username');

        if ($username_session == '') {
            $username = $_POST['username'];
            $password = $_POST['password'];
            $email = $_POST['email'];



            $url1 = 'http://dev-gardenshift.rhcloud.com/Gardenshift/adduser/';
            // The submitted form data, encoded as query-string-style
            // name-value pairs

            $body1 = 'username=' . $username . '&password=' . $password . '&email=' . $email;
            $c1 = curl_init($url1);
            curl_setopt($c1, CURLOPT_POST, true);
            curl_setopt($c1, CURLOPT_POSTFIELDS, $body1);
            curl_setopt($c1, CURLOPT_RETURNTRANSFER, true);

            $page1 = curl_exec($c1);
            curl_close($c1);
        }

        // Calls in Web service to check whether the provided credentials exist or not



        $this->authenticate();
    }

    public function upload_file() {

        $this->load->library('session');

        $username = $this->session->userdata('username');

        $timestamp = time();

        $filename = basename($_FILES['uploaded']['name']);

        $replaced_name = str_replace(' ', '_', $filename);



        $target = getcwd() . "/images/" . $username . "_" . $timestamp . "_" . $replaced_name;


        $ok = 1;


        //This is our size condition 
        if ($uploaded_size > 3500000) {
            echo "Your file is too large.<br>";
            $ok = 0;
        }

        //This is our limit file type condition 
        if ($uploaded_type == "text/php") {
            echo "No PHP files<br>";
            $ok = 0;
        }

        //Here we check that $ok was not set to 0 by an error 
        if ($ok == 0) {
            Echo "Sorry your file was not uploaded";
        }

        //If everything is ok we try to upload it 
        else {
            if (move_uploaded_file($_FILES['uploaded']['tmp_name'], $target)) {
                $key = "/images/" . $username . "_" . $timestamp . "_" . $replaced_name;

                $url = 'http://dev-gardenshift.rhcloud.com/Gardenshift/change_picture/';

                // The submitted form data, encoded as query-string-style
                // name-value pairs

                $body = 'username=' . $username . '&url=' . $key;

                $c = curl_init($url);
                curl_setopt($c, CURLOPT_POST, true);
                curl_setopt($c, CURLOPT_POSTFIELDS, $body);
                curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($c, CURLOPT_FOLLOWLOCATION, 1);
                curl_setopt($c, CURLOPT_HTTPHEADER, array("username: " . $this->session->userdata('username'), "secretKey: " . $this->session->userdata('secretKey')));
                $page = curl_exec($c);
                curl_close($c);

                $this->mainPageLoader();
            } else {
                echo "Sorry, there was a problem uploading your file.";
            }
        }
    }

    public function upload_file_to_album() {

        $this->load->library('session');

        $username = $this->session->userdata('username');

        $timestamp = time();

        $filename = basename($_FILES['uploaded']['name']);

        $replaced_name = str_replace(' ', '_', $filename);



        $target = getcwd() . "/images/" . $username . "_" . $timestamp . "_" . $replaced_name;

        $ok = 1;

        $caption = $_POST['picture_caption'];


        //This is our size condition 
        if ($uploaded_size > 3500000) {
            echo "Your file is too large.<br>";
            $ok = 0;
        }

        //This is our limit file type condition 
        if ($uploaded_type == "text/php") {
            echo "No PHP files<br>";
            $ok = 0;
        }

        //Here we check that $ok was not set to 0 by an error 
        if ($ok == 0) {
            Echo "Sorry your file was not uploaded";
        }

        //If everything is ok we try to upload it 
        else {
            if (move_uploaded_file($_FILES['uploaded']['tmp_name'], $target)) {
                $key = "/images/" . $username . "_" . $timestamp . "_" . $replaced_name;

                $url = 'http://dev-gardenshift.rhcloud.com/Gardenshift/add_picture/';

                // The submitted form data, encoded as query-string-style
                // name-value pairs

                $body = 'username=' . $username . '&picture_url=' . $key . '&picture_caption=' . $caption;
                ;

                $c = curl_init($url);
                curl_setopt($c, CURLOPT_POST, true);
                curl_setopt($c, CURLOPT_POSTFIELDS, $body);
                curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
                curl_setopt($c, CURLOPT_HTTPHEADER, array("username: " . $this->session->userdata('username'), "secretKey: " . $this->session->userdata('secretKey')));
                $page = curl_exec($c);
                curl_close($c);

                $this->mainPageLoader();
            } else {
                echo "Sorry, there was a problem uploading your file.";
            }
        }
    }

    public function picture_page() {

        $this->load->view('pages/pictures.php');
    }

}