<!doctype html>
<html lang="en">

<head>
    <title>Law</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/highlight.js/11.3.1/styles/default.min.css">
</head>

<body>
    <div class="container mt-4">
        <div class="form-group">
            <input type="text" class="form-control searchText" placeholder="Search" value='chương'>
            <input type="hidden" class="form-control dataFromServe" value='{{ $data }}'>
            <input type="hidden" class="form-control content1" value='{{ $content1 }}'>

        </div>
        <button class="btnSearch btn btn-primary">Search</button>
        <hr>
        <hr>
    </div>
    <div class="container showContent1">


    </div>
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
        integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
        integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous">
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
        integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous">
    </script>


    <script>
        let searchText = $('.searchText')
        let btnSearch = $('.btnSearch')
        let content1 = $('.content1')
        let showContent1 = $('.showContent1')
        let dataFromServe = $('.dataFromServe').val()
        let data = JSON.parse(dataFromServe.toLowerCase())
        let arrSearch = []

        showContent1.html(content1.val());  
        btnSearch.click(function() {
            arrSearch = []
            let searchTextVal = $('.searchText').val()
            for (let key in data) {
                if (key.indexOf(searchTextVal) >= 0) {
                    arrSearch.push(key)
                }
                for (let key_ in data[key]) {
                    if (typeof data[key][key_] === 'object') {
                        data[key][key_].forEach((item) => {
                            if (item.indexOf(searchTextVal) >= 0) {
                                arrSearch.push([key, key_, item])
                            }
                        })
                    } else {
                        if (data[key][key_].indexOf(searchTextVal) >= 0) {
                            arrSearch.push([key, data[key][key_]])
                        }
                    }
                }
            }
            // console.log(arrSearch);
        });

        async function fetchData(url, data) {
            var myHeaders = new Headers();
            myHeaders.append("Cookie", "ASP.NET_SessionId=y05xs0onhiwct1xscyod3p2r; Culture=vi");

            var requestOptions = {
                method: 'GET',
                headers: myHeaders,
                redirect: 'follow'
            };

            return await fetch("https://corsanywhere.herokuapp.com/" + url, requestOptions)
                .then(response => response.text())
                .catch(error => console.log('error', error));
        }
    </script>
</body>

</html>
