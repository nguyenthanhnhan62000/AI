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
    <style>
        mark {
            background: #e5e576;
        }

    </style>
</head>

<body>
    <div class="container mt-4">
        <div class="form-group">
            <input type="text" class="form-control searchText" placeholder="Search" value='chương'>
            <input type="hidden" class="form-control dataFromServe" value='{{ $data }}'>
            <input type="hidden" class="form-control content1" value='{{ $content1 }}'>

        </div>
        <button class="btnSearch btn btn-primary">Search</button>
        <div class="mt-4">
            <em class="amount_search">0</em> kết quả tìm kiếm
        </div>
        <hr>
    </div>
    <div class="container showDataSearch" style="overflow:auto;">
     

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
        let showDataSearch = $('.showDataSearch')
        let amount_search = $('.amount_search')
        let dataFromServe = $('.dataFromServe').val()
        let data = JSON.parse(dataFromServe.toLowerCase())
        let arrSearch = []

        showContent1.html(content1.val());
        
        btnSearch.click(function() {
            arrSearch = []
            let htmlResultSearch = ''

            let searchTextVal = ($('.searchText').val()).toLowerCase()

            //begin highlight content
            let regular = new RegExp(searchTextVal, 'gi');
            showContent1_ = (content1.val()).replace(regular, `<mark>${searchTextVal}</mark>`)
            showContent1.html(showContent1_);
            //end highlight content

            //begin add result search to arr
            for (let key in data) {
                // key = key.toLowerCase();
                if ((key.toLowerCase()).indexOf(searchTextVal) >= 0) {
                    arrSearch.push(key)
                }
                for (let key_ in data[key]) {
                    // key_ = key_.toLowerCase();
                    if ((key_.toLowerCase()).indexOf(searchTextVal) >= 0) {
                        arrSearch.push([key.toLowerCase(), key_])
                    }
                    if (typeof data[key][key_] === 'object') {
                        data[key][key_].forEach((item) => {
                            if ((item.toLowerCase()).indexOf(searchTextVal) >= 0) {
                                arrSearch.push([key, key_, item])
                            }
                        })
                    } else {
                        if ((data[key][key_].toLowerCase()).indexOf(searchTextVal) >= 0) {
                            arrSearch.push([key, data[key][key_]])
                        }
                    }
                }
            }
            //end add result search to arr

            //begin show result search to view
            arrSearch.forEach((result,index) => {

                if (typeof result === 'string') {
                    let result_ = (result.toLowerCase()).replace(searchTextVal, `<mark>${searchTextVal}</mark>`);
                    htmlResultSearch += `<div class="row">
                                            <div class="col-md-1">
                                                <p>${index+1}</p>
                                            </div>
                                            <div class="col-md-11">
                                                <h5>${result_}</h5>
                                            </div>
                                        </div>
                                        <hr>
                    `
                } else {
                    let r0 = result[0].toLowerCase()
                    let r1 = result[1].toLowerCase()
                    let r2 = result[2] !== undefined ? result[2].toLowerCase() : ''
                    let regularexp = new RegExp(searchTextVal, 'gi');
                    r0 = r0.replace(regularexp, `<mark>${searchTextVal}</mark>`)
                    r1 = r1 !== undefined ?  r1.replace(regularexp, '<mark>'+searchTextVal+'</mark>') : '' 
                    r2 = r2 !== undefined ?  r2.replace(regularexp, '<mark>'+searchTextVal+'</mark>') : '' 
                    htmlResultSearch += `
                                        <div class="row">
                                            <div class="col-md-1 text-center">
                                                <p>${index+1}</p>
                                            </div>
                                            <div class="col-md-11">
                                                <h5>${r0}</h5>
                                                <h6>${r1}</h6>
                                                <p>${r2}</p>
                                            </div>
                                        </div>
                                        <hr>
                    `
                }
            })
            console.log(data);
            amount_search.html(arrSearch.length);
            showDataSearch.html(htmlResultSearch)
            showDataSearch.css('height','400px')
            //end show result search to view

        });
    </script>
    <script>
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

        function hideddrivetip() {}
        function LS_Tootip_Type_Bookmark_Archive() {}
        function LS_Tootip_Type_Bookmark_DC_Archive(){}
    </script>
</body>

</html>
