<?php

if(!empty(get_sso_user_detail())) {
    add_action('init','imis_sso_login');
}

function imis_sso_login()
{

    $user_detail = get_sso_user_detail();
    $json_decode = json_decode($user_detail);

    $user_id = $json_decode->{'Items'}->{'$values'}[0]->Id;

    // User id exist or not
    if (!empty($user_id)) :

        $firstname  = $json_decode->{'Items'}->{'$values'}[0]->PersonName->FirstName;
        $lastname   = $json_decode->{'Items'}->{'$values'}[0]->PersonName->LastName;
        $email      = $json_decode->{'Items'}->{'$values'}[0]->Emails->{'$values'}[0]->Address;
        $MEMBER_TYPE_DESCRIPTION = $json_decode->{'Items'}->{'$values'}[0]->AdditionalAttributes->{'$values'}[0]->Value;

    endif;

    // check user email 
    if (!empty($user_id)) : $user_email = get_user_by('email', $email); endif;


    $home_url = esc_url(home_url('/'));

    // If user email found
    if (!empty($user_email)) :
        $user = get_user_by('login', $email);

        if (isset($user->user_login)) :
            $username   = $user->user_login;

            $home_url = esc_url(home_url('/'));
            $login_data = array();
            $login_data['user_login']      = $username;
            $login_data['user_password']   = $username;

            $user_verify = wp_signon($login_data, false);

            if(is_wp_error($user_verify) === false): 

                update_user_meta($user->ID,'is_login','true');
                update_user_meta($user->ID,'is_login_time',date('Y-m-d_H:i:s'));
                // echo json_encode(array('result' => 'success', 'redirect' => get_permalink(get_page_by_path('user-profile')) ));
                wp_redirect('user-profile');

            endif;



        endif;

    elseif (!empty($user_id)) :

        $role            = 'acte_user';
        $username        = $email;
        $userId          = username_exists($username);
        $admin_email     = get_settings('admin_email');
        $user_member_ID  = (string)$user_id;
        $user_member_type_description = (string)$MEMBER_TYPE_DESCRIPTION;

        if (!$userId and email_exists($email) == false) :

            $user_create_id = wp_create_user($username, $username, $email);
            $newUser = new WP_User($user_create_id);
            $newUser->set_role($role);

            update_user_meta($user_create_id, 'first_name', $firstname);

            update_user_meta($user_create_id, 'last_name', $lastname);

            update_user_meta($user_create_id, 'iweb_member_id', $user_member_ID);

            update_user_meta($user_create_id, 'iweb_member_type_description', $user_member_type_description);

            $user = get_user_by('login', $email);

            if (!empty($user)) :

                $username = $user->user_login;

                $home_url = esc_url(home_url('/'));
                $login_data = array();
                $login_data['user_login']      = $username;
                $login_data['user_password']   = $username;

                $user_verify = wp_signon($login_data, false);

                if(is_wp_error($user_verify) === false): 

                    update_user_meta($user->ID, 'is_login', 'true');
                    update_user_meta($user->ID, 'is_login_time', date('Y-m-d_H:i:s'));
                    // echo json_encode(array('result' => 'success', 'redirect' => get_permalink(get_page_by_path('user-profile'))));
                    wp_redirect('user-profile');

                endif;

            else :
                // echo json_encode(array('result' => 'fail', 'message' => 'You are not member of this site.'));
                wp_redirect($home_url);
            endif;

        else :
            // echo json_encode(array('result' => 'fail', 'message' => 'Email is already registered. Please try another email address.'));
            wp_redirect($home_url);
        endif;

    else :
        // echo json_encode(array('result' => 'fail', 'message' => 'Please check username or password.', 'redirect' => $home_url));
        wp_redirect($home_url);
    endif;
}



// Get refresh token

add_action("init", "get_access_token");

function get_access_token()
{

    $refresh_token = $_POST['refresh_token'];

    if (isset($refresh_token)) :

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://acte01.isgazurecloud.com/asi.scheduler_imis/token',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_POSTFIELDS => 'grant_type=refresh_token&client_id=SSOAS&client_secret=ZPTTW38tyuz79y2nsztxghe3ySED45hmwseta35a35t&refresh_token=' . $refresh_token,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/x-www-form-urlencoded',
                'Cookie: __RequestVerificationToken_L0FzaS5TY2hlZHVsZXJfaU1JUw2=L2BkbkHkyzhnefA--9x5sYPnj6zLms7gmM9719CByCUhPnA3PjjNrqD4N0Eqa7Z3-bLJ4ZLbbHvsHI6qbM6_EJj1nyW476Gm8n05ILNrauQ1'
            ),
        ));

        $curl_token = curl_exec($curl);

        curl_close($curl);
        return $curl_token;

    endif;
}

// Authenticate and get user detail

add_action("init", "get_sso_user_detail");

function get_sso_user_detail()
{

    $json_access_token = json_decode(get_access_token());

    // echo '<script>console.log('.get_access_token().')</script>';

    //CURLOPT_URL => 'https://acte01.isgazurecloud.com/asi.scheduler_imis/api/Party',

    if (isset($json_access_token->access_token)) :

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://acte01.isgazurecloud.com/asi.scheduler_imis/api/Party?Email=startsWith:' . $json_access_token->userName,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer ' . $json_access_token->access_token . '',
                'Cookie: __RequestVerificationToken_L0FzaS5TY2hlZHVsZXJfaU1JUw2=L2BkbkHkyzhnefA--9x5sYPnj6zLms7gmM9719CByCUhPnA3PjjNrqD4N0Eqa7Z3-bLJ4ZLbbHvsHI6qbM6_EJj1nyW476Gm8n05ILNrauQ1'
            ),
        ));

        $user_list = curl_exec($curl);

        curl_close($curl);
        // echo '<script>console.log('.$user_list.')</script>';
        return $user_list;

    endif;
}
