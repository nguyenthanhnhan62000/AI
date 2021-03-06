<!doctype html>
<html lang="en">

<head>
    <title>Title</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <style>
        em {
            background: yellow;
        }
        span {
            color: red;
        }

    </style>
</head>

<body>

    <hr>
    <div class="container">
        <div class="row">
            <div class="col-md-9">
                <div class="form-group">
                    <input value="Luật đất đai" type="text" placeholder="<Tìm Kiếm Văn Bản Pháp Luật>"
                        class="form-control searchLaw">
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <button type="submit" class="btn btn-success btnSearchLaw">Tìm Kiếm</button>
                </div>
            </div>
        </div>
    </div>
    <div class="container amountData">
        Kết quả <span class="amount1"></span> trong <span class="amount2"></span> văn bản
    </div>
    <div class="container showData">
        <div class="row">

        </div>
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
        let searchLaw = $('.searchLaw');
        let btnSearchLaw = $('.btnSearchLaw');
        let showData = $('.showData');
        let amount1 = $('.amount1');
        let amount2 = $('.amount2');
        test();

        function test() {
            let data = {
            'textSearch': 'Luật đất đai',
            'page': 1
        }
        let html = ''
        fetchData('/law/post/index', data).then(function(result) {
            data = result.data;
            amount = result.amount;
            for (let key in data) {
                html += `
                            <div class="row">
                                <div class="col-md-1">${key}</div>
                                <div class="col-md-7">
                                    <form action="/law/post/show" method="post">
                                        @csrf
                                        <input type="hidden" name="path" value="${data[key][1]}">
                                        <p><button type="submit" class="btn"><b>${data[key][0]}</b></button></p>
                                    </form>
                                    <p>${data[key][2]}</p>
                                </div>
                                <div class="col-md-4">
                                    <div>${data[key][3]}</div>
                                    <div>${data[key][4]}</div>
                                    <div>${data[key][5]}</div>
                                    <div>${data[key][6]}</div>
                                </div>
                            </div>       
                    `
            }
            showData.html(html)
            amount1.html(amount[0])
            amount2.html(amount[1])
        })
        }

        btnSearchLaw.click(function() {
            let data = {
                'textSearch': searchLaw.val(),
                'page': 1
            }
            let html = ''
            fetchData('/law/post/index', data).then(function(result) {
                data = result.data;
                amount = result.amount;
                for (let key in data) {
                    html += `
                            <div class="row">
                                <div class="col-md-1">${key}</div>
                                <div class="col-md-7">
                                    <form action="/law/post/show" method="post">
                                        @csrf
                                        <input type="hidden" name="path" value="${data[key][1]}">
                                        <p><button type="submit" class="btn"><b>${data[key][0]}</b></button></p>
                                    </form>
                                    <p>${data[key][2]}</p>
                                </div>
                                <div class="col-md-4">
                                    <div>${data[key][3]}</div>
                                    <div>${data[key][4]}</div>
                                    <div>${data[key][5]}</div>
                                    <div>${data[key][6]}</div>
                                </div>
                            </div>       
                    `
                }
                showData.html(html)
                amount1.html(amount[0])
                amount2.html(amount[1])
            })
        });


        //redirect To View
        function redirectToView(path) {
            fetchData('/law/post/show', {
                path: path
            }).then((result) => {
                console.log(1);
            });
        }
        //fetch data
        async function fetchData(url, data) {
            let token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            return fetch(url, {
                    headers: {
                        "Content-Type": "application/json",
                        "Accept": "application/json, text-plain, */*",
                        "X-Requested-With": "XMLHttpRequest",
                        "X-CSRF-TOKEN": token
                    },
                    method: 'post',
                    credentials: "same-origin",
                    body: JSON.stringify(data),
                })
                .then((result) => result.json())
                .catch(function(error) {
                    console.log(error);
                });
        }
    </script>
   
</body>

</html>
