
<?php

class Message extends CI_Controller {

    public function mymessages($frmusername = '') {

        // function to display all the messages of a user. 
        $this->load->library('session');

        if ($frmusername == $this->session->userdata('username')) {
            $ch = curl_init("https://dev-gardenshift.rhcloud.com/Gardenshift/get_notification_unread/" . $frmusername);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array("username: " . $this->session->userdata('username'), "secretKey: " . $this->session->userdata('secretKey')));
            $json_res = curl_exec($ch);
            curl_close($ch);

            $json_array = json_decode($json_res);
            if (count($json_array) != 0)
                $unreadmsgs = $json_array->{'notifications_unread'};
            //$msgcount=count($usermessages);
            $data['unreadmsgs'] = $unreadmsgs;

            $ch1 = curl_init("https://dev-gardenshift.rhcloud.com/Gardenshift/get_notification_read/" . $frmusername);
            curl_setopt($ch1, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch1, CURLOPT_HTTPHEADER, array("username: " . $this->session->userdata('username'), "secretKey: " . $this->session->userdata('secretKey')));
            $json_res1 = curl_exec($ch1);
            curl_close($ch1);

            $json_array1 = json_decode($json_res1);
            // print_r($json_array1);


            $readmsgs = $json_array1->{'notifications_read'};
            //$msgcount=count($usermessages);
            $data['readmsgs'] = $readmsgs;
            $data['username'] = $frmusername;
            $this->load->view('pages/messages', $data);
        }
        else {
            header('Location: http://test-gardenshift.rhcloud.com');
        }
    }

    public function updatenotif() {

        //function to update the messages from unread to read when the user views the message
        $name = $_POST['name'];
        session_start();
        $this->load->library('session');

        if ($name == $this->session->userdata('username')) {

            $timestamp = $_POST['timestamp'];
            $context = stream_context_create(array(
                'http' => array(
                    'method' => 'GET',
                    'header' => array("username: " . $this->session->userdata('username'), "secretKey: " . $this->session->userdata('secretKey'))
                )
                    ));

            $res = file_get_contents('https://dev-gardenshift.rhcloud.com/Gardenshift/update_notification_to_read/' . $name . '/' . $timestamp, false, $context);
            //    $res=file_get_contents('https://dev-gardenshift.rhcloud.com/Gardenshift/update_notification_to_read/'.$name.'/'.$timestamp);
            echo $res;
        } else {
            header('Location: http://test-gardenshift.rhcloud.com');
        }
    }

    public function deletenotif() {

        //function to delete a unread message from user's inbox.
        $name = $_POST['name'];
        session_start();
        $this->load->library('session');

        if ($name == $this->session->userdata('username')) {
            $timestamp = $_POST['timestamp'];
            $context = stream_context_create(array(
                'http' => array(
                    'method' => 'GET',
                    'header' => array("username: " . $this->session->userdata('username'), "secretKey: " . $this->session->userdata('secretKey'))
                )
                    ));

            $res = file_get_contents('https://dev-gardenshift.rhcloud.com/Gardenshift/delete_notification_unread/' . $name . '/' . $timestamp, false, $context);
            //   $res=file_get_contents('https://dev-gardenshift.rhcloud.com/Gardenshift/delete_notification_unread/'.$name.'/'.$timestamp);
            echo $res;
        } else {
            header('Location: http://test-gardenshift.rhcloud.com');
        }
    }

    public function deletenotif_read() {

        //function to delete a read message from user's inbox.
        $name = $_POST['name'];
        session_start();
        $this->load->library('session');

        if ($name == $this->session->userdata('username')) {
            $timestamp = $_POST['timestamp'];
            $context = stream_context_create(array(
                'http' => array(
                    'method' => 'GET',
                    'header' => array("username: " . $this->session->userdata('username'), "secretKey: " . $this->session->userdata('secretKey'))
                )
                    ));

            $res = file_get_contents('https://dev-gardenshift.rhcloud.com/Gardenshift/delete_notification_read/' . $name . '/' . $timestamp, false, $context);
            //   $res=file_get_contents('https://dev-gardenshift.rhcloud.com/Gardenshift/delete_notification_read/'.$name.'/'.$timestamp);
            echo $res;
        } else {
            header('Location: http://test-gardenshift.rhcloud.com');
        }
    }

    public function sendreply() {

        //function to facilitate the user to reply to a message.
        $username = $_POST['username'];
        $type = $_POST['type'];
        $from = $_POST['from'];
        $text = $_POST['text'];
        $url = 'http://dev-gardenshift.rhcloud.com/Gardenshift/send_notification';

        session_start();
        $this->load->library('session');

        if ($from == $this->session->userdata('username')) {
            $body = 'username=' . $username . '&type=' . $type . '&from=' . $from . '&text=' . $text;
            $c = curl_init($url);
            curl_setopt($c, CURLOPT_POST, true);
            curl_setopt($c, CURLOPT_POSTFIELDS, $body);
            curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($c, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($c, CURLOPT_HTTPHEADER, array("username: " . $this->session->userdata('username'), "secretKey: " . $this->session->userdata('secretKey')));

            $page = curl_exec($c);
            curl_close($c);
            $url1 = 'http://dev-gardenshift.rhcloud.com/Gardenshift/add_bulletin';
            $body1 = 'username=' . $username . '&text= ' . $from . ' sent you a message';
            $c1 = curl_init($url1);
            curl_setopt($c1, CURLOPT_POST, true);
            curl_setopt($c1, CURLOPT_POSTFIELDS, $body1);
            curl_setopt($c1, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($c1, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($c1, CURLOPT_HTTPHEADER, array("username: " . $this->session->userdata('username'), "secretKey: " . $this->session->userdata('secretKey')));
            $page = curl_exec($c1);
            curl_close($c1);
        } else {
            header('Location: http://test-gardenshift.rhcloud.com');
        }
    }

    public function send_new_message() {

        //function to facilitate the user to send a new message 
        $username = $_POST['username'];
        $type = $_POST['type'];
        $from = $_POST['from'];
        $text = $_POST['text'];
        $url = 'http://dev-gardenshift.rhcloud.com/Gardenshift/send_notification';

        session_start();
        $this->load->library('session');

        if ($from == $this->session->userdata('username')) {
            $body = 'username=' . $username . '&type=' . $type . '&from=' . $from . '&text=' . $text;
            $c = curl_init($url);
            curl_setopt($c, CURLOPT_POST, true);
            curl_setopt($c, CURLOPT_POSTFIELDS, $body);
            curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($c, CURLOPT_HTTPHEADER, array("username: " . $this->session->userdata('username'), "secretKey: " . $this->session->userdata('secretKey')));
            $page = curl_exec($c);
            curl_close($c);
            $url1 = 'http://dev-gardenshift.rhcloud.com/Gardenshift/add_bulletin';
            $body1 = 'username=' . $username . '&text= ' . $from . ' sent you a message';
            $c1 = curl_init($url1);
            curl_setopt($c1, CURLOPT_POST, true);
            curl_setopt($c1, CURLOPT_POSTFIELDS, $body1);
            curl_setopt($c1, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($c1, CURLOPT_HTTPHEADER, array("username: " . $this->session->userdata('username'), "secretKey: " . $this->session->userdata('secretKey')));
            $page = curl_exec($c1);
            curl_close($c1);
        } else {
            header('Location: http://test-gardenshift.rhcloud.com');
        }
    }

}

?>