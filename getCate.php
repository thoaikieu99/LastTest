<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

    <title>Nhap thu vien</title>
    <script src="./js/jquery.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
</head>

<body>
    <div class="container mt-5">
        <h1>Lay thu vien</h1>
        <input type="text" id="myText" value="1">
        <button id="22">Go</button>
    </div>
    <script>
        jQuery('#22').click(function() {
            let url = 'controllers.php?waction=cate';

            let id = document.getElementById("myText").value;
            let r = url + "&page=" + id;
            myFunction122(r);
        });


        function myFunction122(url) {
            let oddurl = url;
            jQuery.ajax({
                type: "GET",
                url: url,
                success: function(data) {

                    if (data) {
                        if (data == 'end') {
                            return true;
                        }
                        if (data == 'errors') {
                            console.log("erro");
                        }
                        let url = 'controllers.php?waction=cate&page=' + data;

                        myFunction122(url);
                    }
                },
                error: function() {
                    console.log("erro");
                    myFunction122(oddurl);
                }

            })
            return true;
        }
    </script>
</body>

</html>