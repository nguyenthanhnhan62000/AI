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
            <div class="col-md-6">
                <div class="form-group">
                    <label><b>Nhap URL De Phan Cum Van Ban</b> </label>
                    <textarea height="100px" type="text" id="url" class="form-control"
                        placeholder="...">https://thuvienphapluat.vn/van-ban/Bat-dong-san/Luat-dat-dai-2013-215836.aspx</textarea>
                    {{-- <input type="text" id="data" hidden class="form-control" {{ $data }}> <br> --}} <br>
                    <button type="button" id="btnGetData" class="btn btn-primary">Lay Du Lieu</button>
                    <img src="{{ asset('img/gif.gif') }}" width="100px" id="iLoad_get" style="display:none">
                    <b>
                        <p id="pMsg_get" style="color:rgb(0, 255, 0);font-size:20px;margin-top:2px;display:none;">Lay Du
                            Lieu Thanh Cong</p>
                    </b>

                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="">Nhap So Cum</label>
                    <input type="text" id="cluster" class="form-control" placeholder="" value="2"> <br>
                    <button type="button" id="btnCluster" class="btn btn-primary">Phan Cum</button>
                    <img src="{{ asset('img/gif.gif') }}" width="100px" id="iLoad_cluster" style="display:none">
                    <b>
                        <p id="pMsg_cluster" style="color:rgb(0, 255, 0);font-size:20px;margin-top:2px;display:none;">
                            Phan Cum Thanh Cong</p>
                    </b>

                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="">Tim Kiem</label>
                    <input type="text" class="form-control" id="iSearch" value="đất đai"> <br>
                    <button class="btn btn-info" id="bSearch">Tim Kiem</button>
                </div>
            </div>
        </div>

    </div>
    <div class="container">
        <em style="display:none" id="eResultSearch">số kết quả tìm kiếm được <mark id="mAmountSearch">0</mark></em>

    </div>
    <div class="container mt-4" id="showDataCluster">

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
        var btnCluster = $("#btnCluster");
        var btnGetData = $("#btnGetData");
        var pMsg_get = $("#pMsg_get");
        var pMsg_cluster = $("#pMsg_cluster");
        var iLoad_get = $("#iLoad_get");
        var iLoad_cluster = $("#iLoad_cluster");
        var showDataCluster = $("#showDataCluster");
        var bSearch = $("#bSearch");
        var iSearch = $("#iSearch");
        var mAmountSearch = $("#mAmountSearch");
        var eResultSearch = $("#eResultSearch");
        var _result;
        var _resultCluster;
        var space;
        var array;
        var docCollection;



        bSearch.click(function(e) {
            let searchText = iSearch.val();
            let html = ''
            let i = 0;
            let data = {
                "space": space,
                "guess_test": searchText,
                "array": array,
                "docCollection": docCollection
            };
           
            fetchDataSearch('/data_mining/cau_1/search', data).then((re) => {
                
                for (const key in re) {
                    var index = re[key][0]
                    if (re[key][1] !== 0) {
                        i++;
                        if (_result[index].chuong !== undefined) {
                            html += ` <b><p style="font-size: 24px">${_result[index].chuong}</p></b>`
                        }
                        html += `
                                    <p style="font-size: 18px">${_result[index].dieu}</p>
                                    <p>${_result[index].nd}</p>
                                    <hr>
                        `
                    }
                }
                showDataCluster.html(html);
                mAmountSearch.html(i);
                eResultSearch.css('display', 'block')
            })
        });
        btnCluster.click(function(e) {
            data = {
                "docCollection": _result,
                "cluster": cluster.val()
            }
            iLoad_cluster.css('display', 'block');
            pMsg_cluster.css('display', 'none');
            eResultSearch.css('display', 'none')
            fetchDataSearch('/data_mining/cau_1/cluster_post', data)
                .then((results) => {
                    // console.log(result);
                    _resultCluster = results.result;
                    space = results.space;
                    array = results.array;
                    docCollection = results.docCollection;
                    pMsg_cluster.css('display', 'block');
                    iLoad_cluster.css('display', 'none');
                    return results.result;
                })
                .then((data) => {
                    let html = '';
                    for (var i in data) {
                        let nu = parseInt(i) + 1;
                        html +=
                            `<h4 class="text-center">--------------------------------cluster ${nu}---------------------------------</h4>`
                        data[i].GroupedDocument.forEach(e => {
                            for (let index = 0; index < 100; index++) {
                                if (e[0] == _result[index].nd) {
                                    if (_result[index].chuong !== undefined) {
                                        html +=
                                            ` <b><p style="font-size: 24px">${_result[index].chuong}</p></b>`
                                    }
                                    html += `
                                                <p style="font-size: 18px">${_result[index].dieu}</p>
                                                <p>${e[0]}</p>
                                                <hr>
                                    `
                                }
                            }
                        })
                    }
                    showDataCluster.html(html);
                })

        });

        btnGetData.click(() => {
            data = {
                'url': url.val()
            }
            pMsg_get.css('display', 'none');
            iLoad_get.css('display', 'block');
            eResultSearch.css('display', 'none')
            fetchDataSearch('/data_mining/cau_1/data_post', data)
                .then((result) => {
                    _result = result
                    iLoad_get.css('display', 'none');
                    pMsg_get.css('display', 'block');
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
