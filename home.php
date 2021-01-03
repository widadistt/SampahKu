<?php
    require 'db_connection.php';

    if(!isset($_SESSION['login_id'])){
        header('Location: login.php');
        exit;
    }

    $id = $_SESSION['login_id'];

    $get_user = mysqli_query($db_connection, "SELECT * FROM `users` WHERE `google_id`='$id'");

    if(mysqli_num_rows($get_user) > 0){
        $user = mysqli_fetch_assoc($get_user);
    }
    else{
        header('Location: logout.php');
        exit;
    }
?>

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
        <p class="w3-center"> By 18218011 | Widad Istiqomah </p>
        <p class="w3-center w3-small"><?php echo "Hi ", $user['name'], " | ", $user['email'], "!";?></p>
        <p>
            <input type="submit" value="Home" class="w3-btn w3-white w3-border w3-border-blue w3-margin" onclick="location.href = 'https://sampahku.herokuapp.com/home.php'">
            <input type="submit" value="Landfills" id="getLandfills" class="w3-btn w3-blue w3-margin" onclick="location.href = 'https://sampahku.herokuapp.com/landfills.php'">
            <input type="submit" value="Wastes" id="getWastes" class="w3-btn w3-blue w3-margin" onclick="location.href = 'https://sampahku.herokuapp.com/wastes.php'">
            <input type="submit" value="Posts" id="getPosts" class="w3-btn w3-blue w3-margin" onclick="location.href = 'https://sampahku.herokuapp.com/posts.php'">
            <input type="submit" value="Log out" class="w3-btn w3-blue w3-margin login-btn" onclick="location.href = 'https://sampahku.herokuapp.com/logout.php'">
        </p>
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

            fetch('https://sampahku-api.herokuapp.com/landfills')
            // fetch('http://localhost/sampahku-api/landfills')
            .then(function (res) {
                return res.json();
            })
            .then(function (data) {
                let result = 
                `<div class="w3-container w3-center">
                    <h2 class="w3-center w3-allerta w3-xxlarge"> Landfills in Bandung </h2>
                    <input type="button" class="w3-button w3-blue w3-round w3-padding-small w3-section" style="width:60px" value="Add" id="formAddLandfills">
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

            fetch('https://sampahku-api.herokuapp.com/wastes')
            // fetch('http://localhost/sampahku-api/wastes')
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

            fetch('https://sampahku-api.herokuapp.com/posts')
            // fetch('http://localhost/sampahku-api/posts')
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

        function postLandfills(event) {
            event.preventDefault();

            let name = document.getElementById('lf_name').value;
            let phone = document.getElementById('lf_phone').value;
            let address = document.getElementById('lf_addr').value;

            ((name || address) || phone) === "" ?
            alert('Please enter all details') :
            fetch('https://sampahku-api.herokuapp.com/landfills', {
                method: 'POST',
                headers: new Headers(),
                body: JSON.stringify({ name: name, phone_number: phone, address: address })
            }).then((res) => res.json())
                .then((data) => {alert('Data Sent')} )
                .catch((err) => console.log(err))
        }

        addLandfill.addEventListener('submit', (e) => {
            e.preventDefault();
            console.log('submited');
        })
    </script>

</body>

</html>