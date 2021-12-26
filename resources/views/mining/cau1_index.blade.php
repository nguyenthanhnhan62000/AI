<!doctype html>
<html lang="en">

<head>
    <title>Title</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body>
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-7">
                <div class="form-group">
                    <label><b>Nhap URL De Phan Cum Van Ban</b> </label>
                    <input type="text" id="url" class="form-control" placeholder="..."  value="https://thuvienphapluat.vn/van-ban/Bat-dong-san/Luat-dat-dai-2013-215836.aspx">
                    <input type="text" id="data" hidden class="form-control" {{ $data }}> <br>
                    <button type="button" id="btnGetData" class="btn btn-primary">Lay Du Lieu</button>
                    <p id="pMsg" style="color:red;font-size:20px;margin-top:2px "></p>
                </div>
            </div>
            <div class="col-md-5">
                <div class="form-group">
                    <label for="">Nhap So Cum</label>
                    <input type="text" id="cluster" class="form-control" placeholder="" value="2"> <br>
                    <button type="button"  id="btnCluster" class="btn btn-primary">Phan Cum</button>
                </div>
            </div>
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

        var url = $("#url");
        var cluster = $("#cluster");
        var _data = $("#data");
        var btnSubmit = $("#btnGetData");
        var pMsg = $("#pMsg");
        var _result;
        
        $(btnGetData).click(()=> {
            data = {'url' : url.val(),'cluster' : cluster.val()}
            fetchDataSearch('/data_mining/cau_1/data_post',data).then((result)=>{

                _result = result
                pMsg.html("Lay Du Lieu Thanh Cong")

            })
        });

        async function fetchDataSearch(url, data) {
            let token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            return await fetch(url, {
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
