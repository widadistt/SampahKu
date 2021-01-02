<?php
    require 'db_connection.php';

    if(isset($_SESSION['login_id'])){
        header('Location: index.html');
        exit;
    }

    require 'google-api/vendor/autoload.php';

    // Creating new google client instance
    $client = new Google_Client();

    // Enter your Client ID
    $client->setClientId('784608908051-oolek9btnagbllhk4366uqufnpu9fr3q.apps.googleusercontent.com');
    // Enter your Client Secrect
    $client->setClientSecret('B3Vi6wN9DhLV2wnjWttx_HSx');
    // Enter the Redirect URL
    $client->setRedirectUri('http://localhost/SampahKu/login.php');

    // Adding those scopes which we want to get (email & profile Information)
    $client->addScope("email");
    $client->addScope("profile");


    if(isset($_GET['code'])):

        $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);

        if(!isset($token["error"])){

            $client->setAccessToken($token['access_token']);

            // getting profile information
            $google_oauth = new Google_Service_Oauth2($client);
            $google_account_info = $google_oauth->userinfo->get();
        
            // Storing data into database
            $id = mysqli_real_escape_string($db_connection, $google_account_info->id);
            $full_name = mysqli_real_escape_string($db_connection, trim($google_account_info->name));
            $email = mysqli_real_escape_string($db_connection, $google_account_info->email);
            $profile_pic = mysqli_real_escape_string($db_connection, $google_account_info->picture);

            // checking user already exists or not
            $get_user = mysqli_query($db_connection, "SELECT `google_id` FROM `users` WHERE `google_id`='$id'");
            if(mysqli_num_rows($get_user) > 0){

                $_SESSION['login_id'] = $id; 
                header('Location: index.html');
                exit;

            }
            else{

                // if user not exists we will insert the user
                $insert = mysqli_query($db_connection, "INSERT INTO `users`(`google_id`,`name`,`email`,`profile_image`) VALUES('$id','$full_name','$email','$profile_pic')");

                if($insert){
                    $_SESSION['login_id'] = $id; 
                    header('Location: index.html');
                    exit;
                }
                else{
                    echo "Sign up failed!(Something went wrong).";
                }

            }

        }
        else{
            header('Location: login.php');
            exit;
        }
        
    else: 
        // Google Login Url = $client->createAuthUrl(); 
    ?>

<?php endif; ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Sampahku</title>
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Allerta+Stencil">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        .w3-allerta {
            font-family: "Allerta Stencil", Sans-serif;
        }
    </style>


</head>

<body>
    <div class="w3-container">
        <h1 class="w3-center w3-allerta w3-xxlarge">Manage your trash carefully, live wisely</h1>
        <p class="w3-center w3-small"> 18218011 | Widad Istiqomah </p>
        <p>
            <input type="submit" value="Home" class="w3-btn w3-white w3-border w3-border-blue w3-margin" onclick="location.href = 'http://localhost/SampahKu/index.php'">
            <input type="submit" value="Landfills" id="getLandfills" class="w3-btn w3-blue w3-margin" >
            <input type="submit" value="Wastes" id="getWastes" class="w3-btn w3-blue w3-margin" >
            <input type="submit" value="Posts" id="getPosts" class="w3-btn w3-blue w3-margin">
            <a class="login-btn w3-btn w3-blue w3-margin login-btn" href="<?php echo $client->createAuthUrl(); ?>">Login</a>
        </p>
        <p class="w3-center w3-small"> Login to edit, add, or delete </p>
        <hr>

        <div class="w3-container w3-blue">
            <h5> Data </h5>
        </div>

        <div id="result" class="w3-container"></div>

        <hr>

        <div class="w3-container w3-blue">
            <h5> Form </h5>
        </div>

        <div id="form" class="w3-container"></div>

    </div>

    <script>
        document.getElementById('getLandfills').addEventListener('click', getLandfills);
        document.getElementById('getWastes').addEventListener('click', getWastes);
        document.getElementById('getPosts').addEventListener('click', getPosts);
        
        document.getElementById('formLandfills').addEventListener('click', formLandfills);
        document.getElementById('formWastes').addEventListener('click', formWastes);
        document.getElementById('formPosts').addEventListener('click', formPosts);

        document.getElementById('form').innerHTML.getElementById('postLandfills').addEventListener('submit', postLandfills);

        function getLandfills() {

            // fetch('https://sampahku-api.herokuapp.com/landfills')
            fetch('http://localhost/sampahku-api/landfills')
            .then(function (res) {
                return res.json();
            })
            .then(function (data) {
                let result = 
                `<div class="w3-container w3-center">
                    <h2 class="w3-center w3-allerta w3-xxlarge"> Landfills in Bandung </h2>
                </div>`;
                data.forEach((landfill) => {
                    const { id, name, phone_number, address } = landfill;
                    result +=
                    `
                    <div class="w3-panel w3-leftbar w3-border w3-round-small w3-border-blue w3-margin">
                        <h5> ${name} </h5>
                        <ul class="w3-ul">
                            <li> Phone-number: ${phone_number} </li>
                            <li> Address: ${address} </li>
                        </ul>
                        <input type="button" class="w3-button w3-blue w3-round w3-small w3-padding-small w3-section" style="width:60px" value="Edit" id="edit">
                        <input type="button" class="w3-button w3-blue w3-round w3-small w3-padding-small w3-section" style="width:60px" value="Delete" id ="delete">
                    </div>
                    `;
    
                    document.getElementById('result').innerHTML = result;
                });
            })

        }

        function getWastes() {

            // fetch('https://sampahku-api.herokuapp.com/wastes')
            fetch('http://localhost/sampahku-api/wastes')
                .then(function (res) {
                    return res.json();
                })
                .then(function (data) {
                    let result = `<h2 class="w3-center w3-allerta w3-xxlarge"> Waste and its category </h2>`;
                    data.forEach((waste) => {
                        const { id, name, category } = waste;
                        result +=
                            `<div class="w3-panel w3-leftbar w3-border w3-round-small w3-border-blue w3-margin">
                                <h5> Waste: ${name} </h5>
                                <ul class="w3-ul">
                                    <li> Category : ${category}</li>
                                </ul>
                                <input type="button" class="w3-button w3-blue w3-round w3-small w3-padding-small w3-section" style="width:60px" value="Edit" id="edit">
                                <input type="button" class="w3-button w3-blue w3-round w3-small w3-padding-small w3-section" style="width:60px" value="Delete" id ="delete">
                            </div>`;
                        document.getElementById('result').innerHTML = result;
                    });
                })

        }

        function getPosts() {

            // fetch('https://sampahku-api.herokuapp.com/posts')
            fetch('http://localhost/sampahku-api/posts')
                .then((res) => { return res.json() })
                .then((data) => {
                    let result = `<h2 class="w3-center w3-allerta w3-xxlarge"> Latest Post for You </h2>`;
                    data.forEach((post) => {
                        const {id, title, writer, content, published_date} = post;
                        result +=
                            `<div class="w3-panel w3-leftbar w3-border w3-round-small w3-border-blue w3-margin">
                                <h5> ${title} </h5>
                                <ul class="w3-ul">
                                    <li> Writer : ${writer}</li>
                                    <li> Published : ${published_date} </li>
                                    <li> ${content} </li>
                                </ul>
                                <input type="button" class="w3-button w3-blue w3-round w3-small w3-padding-small w3-section" style="width:60px" value="Edit" id="edit">
                                <input type="button" class="w3-button w3-blue w3-round w3-small w3-padding-small w3-section" style="width:60px" value="Delete" id ="delete">
                             </div>`;
                        document.getElementById('result').innerHTML = result;
                    });
                })

        }
    </script>

</body>

</html>