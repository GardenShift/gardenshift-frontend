<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Crop extends CI_Controller {

    public function index() {
        //  executes when http://test-gardenshift.rhcloud.com/index.php/crop  is called. 
        echo 'hello';
    }

    public function mycrops($frmusername = '') {
        // code to display all the crops of the user whose username is in the path
        session_start();
        $this->load->library('session');
        $secretKey = $this->session->userdata('secretKey');
        $username = $this->session->userdata('username');
        if ($frmusername == $this->session->userdata('username')) {
            $ch = curl_init("https://dev-gardenshift.rhcloud.com/Gardenshift/user_details/" . $frmusername);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array("username: " . $username, "secretKey: " . $secretKey));
            $json_res = curl_exec($ch);
            curl_close($ch);
            $json_array = json_decode($json_res);

            $usercrops = $json_array->{'user_crops'};
            // print_r ($usercrops);
            $count = count($usercrops);
            $context = stream_context_create(array(
                'http' => array(
                    'method' => 'GET',
                    'header' => array("username: " . $username, "secretKey: " . $secretKey)
                )
                    ));

            $json_res1 = file_get_contents('https://dev-gardenshift.rhcloud.com/Gardenshift/crop_details/all', false, $context);

            $json_array = json_decode($json_res1);
            $cropcnt = count($json_array);
            global $array1;
            for ($i = 0; $i < $cropcnt; $i++) {

                $array1[$i] = $json_array[$i]->{'crop_name'};
            }
            $data['usercrops'] = $usercrops;
            $data['cropsarray'] = $array1;
            $data['username'] = $frmusername;

            $this->load->view('pages/crops_display', $data);
        } else {
            header('Location: http://test-gardenshift.rhcloud.com');
        }
    }

    public function allcrops() {

        // This function displays all the crops available in the crop database.  
        session_start();
        $this->load->library('session');
        $secretKey = $this->session->userdata('secretKey');
        $username = $this->session->userdata('username');
        if ($this->session->userdata('username') != '') {

            $ch = curl_init("https://dev-gardenshift.rhcloud.com/Gardenshift/crop_details/all");

            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            curl_setopt($ch, CURLOPT_HTTPHEADER, array("username: " . $username, "secretKey: " . $secretKey));
            $json_res = curl_exec($ch);

            curl_close($ch);
            $json_array_allcrops = json_decode($json_res);

            $data['allcrops'] = $json_array_allcrops;
            $this->load->view('pages/all_crops', $data);
        } else {
            header('Location: http://test-gardenshift.rhcloud.com');
        }
    }

    public function addusercrop() {

        // function to facilitate user to add a new crop to his garden
        $this->load->library('session');
        $username = $_POST['name'];
        $crop_name = $_POST['crop_name'];
        $quantity = $_POST['quantity'];
        $hdate = $_POST['hdate'];
        $comment = $_POST['comments'];
        session_start();
        $this->load->library('session');


        $ch = curl_init('https://dev-gardenshift.rhcloud.com/Gardenshift/create_usercrop');

        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "username=$username&name=$crop_name&quantity=$quantity&date=$hdate&comment=$comment");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("username: " . $this->session->userdata('username'), "secretKey: " . $this->session->userdata('secretKey')));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

        $result = curl_exec($ch);

        curl_close($ch);
        return $result;
    }

    public function addnewcrop() {

        //function to add a crop to the crops database
        session_start();
        $this->load->library('session');
        if ($this->session->userdata('username') != '') {

            $crop_name = $_POST['name'];
            $crop_description = $_POST['description'];
            // $crop_name = "spinach1";
            //   $crop_description = "dsadas";

            $ch = curl_init('https://dev-gardenshift.rhcloud.com/Gardenshift/create_crop');

            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, "name=$crop_name&description=$crop_description");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array("username: " . $this->session->userdata('username'), "secretKey: " . $this->session->userdata('secretKey')));
            $result = curl_exec($ch);
            echo $result;
            curl_close($ch);
            $this->load->view('pages/all_crops', $result);
        } else {
            header('Location: http://test-gardenshift.rhcloud.com');
        }
    }

}