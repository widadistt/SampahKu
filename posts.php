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
    <style>
        .w3-allerta {
            font-family: "Allerta Stencil", Sans-serif;
        }
    </style>


</head>

<body>
    <div class="w3-container">
        <h1 class="w3-center w3-allerta w3-xxlarge">Manage your trash carefully, live wisely</h1>
        <p class="w3-center"> 18218011 | Widad Istiqomah </p>
        <p class="w3-center w3-small"><?php echo "Hi ", $user['name'], " | ", $user['email'], "!";?></p>
        <p>
            <input type="submit" value="Home" id="getLandfills" class="w3-btn w3-blue w3-margin" onclick="location.href = 'http://localhost/SampahKu/home.php'">
            <input type="submit" value="Landfills" id="getLandfills" class="w3-btn w3-blue w3-margin" onclick="location.href = 'http://localhost/SampahKu/landfills.php'";>
            <input type="submit" value="Wastes" id="getWastes" class="w3-btn w3-blue w3-margin" onclick="location.href = 'http://localhost/SampahKu/wastes.php'">
            <input type="submit" value="Posts" id="getPosts" class="w3-btn w3-white w3-border w3-border-blue w3-margin" onclick="location.href = 'http://localhost/SampahKu/posts.php'">
            <input type="submit" value="Log out" class="w3-btn w3-blue w3-margin login-btn" onclick="location.href = 'http://localhost/SampahKu/logout.php'">
        </p>
        <hr>

        <div class="w3-container w3-blue">
            <h5> Data </h5>
        </div>

        <div id="result" class="w3-container content">
            <script>
                // fetch('https://sampahku-api.herokuapp.com/posts')
                fetch('http://localhost/sampahku-api/posts')
                    .then((res) => { return res.json() })
                    .then((data) => {
                        let result = `<h2 class="w3-center w3-allerta w3-xxlarge"> Latest Post for You </h2>`;
                        data.forEach((post) => {
                            const {id, title, writer, content, published_date} = post;
                            result +=
                                `<div class="w3-panel w3-leftbar w3-border w3-round-small w3-border-blue w3-margin id="content" data-id=${id}>
                                    <h5 class="title"> ${title} </h5>
                                    <ul class="w3-ul">
                                        <li class="writer"> Writer: ${writer}</li>
                                        <li> Published: ${published_date} </li>
                                        <li class="content"> ${content} </li>
                                    </ul>
                                    <input type="button" class="w3-button w3-blue w3-round w3-small w3-padding-small w3-section" style="width:60px" value="Edit" id="edit">
                                    <input type="button" class="w3-button w3-blue w3-round w3-small w3-padding-small w3-section" style="width:60px" value="Delete" id ="delete">
                                </div>`;
                            document.getElementById('result').innerHTML = result;
                        });
                    })
            </script>
        </div>

        <hr>

        <div class="w3-container w3-blue">
            <h5> Form </h5>
        </div>

        <div id="form" class="w3-container">
            <form id="post" class="w3-container w3-margin">
                <p>
                    <input type="text" name="" placeholder="Post's title" class="w3-input" id="title">
                </p>
                <p>
                    <input type="text" name="" placeholder="Post's writer" class="w3-input" id="writer">
                </p>
                <p>
                    <textarea name="" placeholder="Post's content" class="w3-input" id="content"></textarea>
                </p>
                <input type="submit" value="SEND POST" class="btn w3-btn w3-blue">
            </form>
        </div>

    </div>

    <script>
    
        document.getElementById('post').addEventListener('submit', post);
        const content = document.querySelector('.content');
        const btnSubmit = document.querySelector('.btn');
        content.addEventListener('click', contentClicked);
        
        function getPosts() {

            // fetch('https://sampahku-api.herokuapp.com/posts')
            fetch('http://localhost/sampahku-api/posts')
                .then((res) => { return res.json() })
                .then((data) => {
                    let result = `<h2 class="w3-center w3-allerta w3-xxlarge"> Latest Post for You </h2>`;
                    data.forEach((post) => {
                        const {id, title, writer, content, published_date} = post;
                        result +=
                            `<div class="w3-panel w3-leftbar w3-border w3-round-small w3-border-blue w3-margin id="content" data-id=${id}>
                                <h5 class="title"> ${title} </h5>
                                <ul class="w3-ul">
                                    <li class="writer"> Writer : ${writer}</li>
                                    <li> Published : ${published_date} </li>
                                    <li class="content"> ${content} </li>
                                </ul>
                                <input type="button" class="w3-button w3-blue w3-round w3-small w3-padding-small w3-section" style="width:60px" value="Edit" id="edit">
                                <input type="button" class="w3-button w3-blue w3-round w3-small w3-padding-small w3-section" style="width:60px" value="Delete" id ="delete">
                             </div>`;
                        document.getElementById('result').innerHTML = result;
                    });
                })

        }

        function post(event) {
            event.preventDefault();

            let title = document.getElementById('title').value;
            let writer = document.getElementById('writer').value;
            let content = document.getElementById('content').value;

            console.log(title);

            ((title || writer) || content) === "" ?
            alert('Please enter all details') :
            fetch('http://localhost/sampahku-api/posts', {
                method: 'POST',
                headers: new Headers(),
                body: JSON.stringify({ title: title, writer: writer, content: content })
            }).then((res) => res.json())
                .then((data) => {alert('Data Sent')} )
                .then(() => getPosts())
                .catch((err) => console.log(err))

            document.getElementById('title').value = "";
            document.getElementById('writer').value = "";
            document.getElementById('content').value = "";
        }

        function contentClicked(e) {
            e.preventDefault();

            let delClicked = e.target.id == 'delete';
            let editClicked = e.target.id == 'edit';

            let id = e.target.parentElement.dataset.id;
            console.log(e.target.id);
            console.log(id);

            // DELETE waste
            if (delClicked) {
                fetch(`http://localhost/sampahku-api/posts/${id}`, {
                    method: 'DELETE',
                })
                    .then(res => res.json())
                    .then(() => location.reload())
            }

            if (editClicked) {
                const parent = e.target.parentElement;
                let title = parent.querySelector(".title").textContent;
                let writer = parent.querySelector(".writer").textContent;
                let content = parent.querySelector(".content").textContent;
                document.getElementById('title').value = title;
                document.getElementById('writer').value = writer;
                document.getElementById('content').value = content;
            }

            // UPDATE
            btnSubmit.addEventListener('click', (e) => {
                e.preventDefault();

                fetch(`http://localhost/sampahku-api/posts/${id}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        title: document.getElementById('title').value,
                        writer: document.getElementById('writer').value,
                        content: document.getElementById('content').value
                    })
                })
                    .then(res => res.json())
                    .then(res => {alert('Data updated')})
                    .then(() => location.reload())
                    .catch((err) => console.log(err))

                document.getElementById('title').value = "";
                document.getElementById('writer').value = "";
                document.getElementById('content').value = "";
            })
        }

    </script>

</body>

</html>