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
</head>

<body>
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-3">
                Chọn Địa Bàn
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <select class="form-control stTT" name="slTT">
                        {{-- <option value=''>Chọn Tỉnh Thành</option> --}}
                    </select>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <select class="form-control stQH" name="slQH" id="">
                        <option value='0'>Chọn Quận Huyện...</option>
                    </select>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <select class="form-control slTD" name="slTD" id="">
                        <option value='0'>Chọn Tên Đường</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3">
                Mức giá
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <select class="form-control slMG" name="slMG" id="">
                        <option value='0-99999'>Tất Cả</option>
                    </select>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <button class="btn btn-primary btnSearch">Xem Gia</button>
                </div>
            </div>
        </div>
        <div class="row mb-2">
            Tìm thấy <mark class="amount" style="background: #dbdb4d;">2222</mark> bảng giá đất
        </div>
    </div>
    <div class="container">
        <table class="table">
            <thead>
                <tr>
                    <th>STT</th>
                    <th>Quận/Huyện</th>
                    <th>Tên đường/Làng xã</th>
                    <th>Đoạn: Từ - Đến</th>
                    <th>VT1</th>
                    <th>VT2</th>
                    <th>VT3</th>
                    <th>VT4</th>
                    <th>VT5</th>
                    <th>Loại</th>
                </tr>
            </thead>
            <tbody class="showData">
            </tbody>
        </table>
    </div>
    <div class="container">
        <nav aria-label="Page navigation example">
            <ul class="pagination pagin">
              <li class="page-item"><a class="page-link" href="#">1</a></li>
            </ul>
          </nav>
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
        let slTT = $('.stTT')
        let slQH = $('.stQH')
        let slTD = $('.slTD')
        let slMG = $('.slMG')
        let btnSearch = $('.btnSearch')
        let showData = $('.showData')
        let amount = $('.amount')
        let pagin = $('.pagin')


        btnSearch.click(function() {
            let html = '';
            let htmlAmountPagin = '';
            fetchDataSearch('/search_post', slTT.val(), slQH.val(), slTD.val(), slMG.val()).then((result) => {
                result_ = result.data 
                console.log(result_);
                amount.html(result.amount)
                amountPagin = result.amount/100;
                for (let i = 1; i < amountPagin; i++) {
                    htmlAmountPagin += `<li class="page-item page-pagin"><a onclick="showPageByNumber(${i})" class="page-link" href="#">${i}</a></li>`
                }    
                for (let key in result_) {
                    if (key != 1) {
                        html += `
                                    <tr>
                                        <td>${result_[key][0]}</td>
                                        <td>${result_[key][1]}</td>
                                        <td>${result_[key][2]}</td>
                                        <td>${result_[key][3]}</td>
                                        <td>${result_[key][4]}</td>
                                        <td>${result_[key][5]}</td>
                                        <td>${result_[key][6]}</td>
                                        <td>${result_[key][7]}</td>
                                        <td>${result_[key][8]}</td>
                                        <td>${result_[key][9]}</td>
                                    </tr>           
                        `
                    }
                }
                showData.html(html);
                pagin.html(htmlAmountPagin);
            })
        });

        function showPageByNumber(i){
            let html = '';
            let htmlAmountPagin = '';
            fetchDataSearch('/search_post', slTT.val(), slQH.val(), slTD.val(), slMG.val(), i).then((result) => {
                result_ = result.data 
                amount.html(result.amount)
                amountPagin = result.amount/100;
                for (let i = 1; i < amountPagin; i++) {
                    htmlAmountPagin += `<li class="page-item page-pagin"><a onclick="showPageByNumber(${i})" class="page-link" href="#">${i}</a></li>`
                }    
                for (let key in result_) {
                    if (key != 1) {
                        html += `
                                    <tr>
                                        <td>${result_[key][0]}</td>
                                        <td>${result_[key][1]}</td>
                                        <td>${result_[key][2]}</td>
                                        <td>${result_[key][3]}</td>
                                        <td>${result_[key][4]}</td>
                                        <td>${result_[key][5]}</td>
                                        <td>${result_[key][6]}</td>
                                        <td>${result_[key][7]}</td>
                                        <td>${result_[key][8]}</td>
                                        <td>${result_[key][9]}</td>
                                    </tr>           
                        `
                    }
                }
                showData.html(html);
                pagin.html(htmlAmountPagin);
            })
        }
        slTT.change(function(e) {
            addViewData(slQH, 'Quận Huyện...', 'https://thuvienphapluat.vn/CountryService.asmx/GetDistrictsPrice',
                'Distric', 'City:' + this.value)
        });
        slQH.change(function(e) {
            addViewData(slTD, 'Tên Đường...', 'https://thuvienphapluat.vn/CountryService.asmx/StreetPrice', 'Ward',
                'City:' + slTT.val() + ';' + 'District:' + this.value)
        });

        //slTT
        addViewData(slTT, 'Tỉnh Thành', 'https://thuvienphapluat.vn/CountryService.asmx/GetCitiesPrice', 'City')
        addViewData(slMG, 'Tất Cả', 'https://thuvienphapluat.vn/CountryService.asmx/GetPriceRange', 'PriceRange', '',
            '0-99999')


        function addViewData(tagName, firstEle, url, city, knownCategoryValues, valueSelect = '0') {
            fetchDataSelect(url, city, knownCategoryValues).then((result) => {
                let html = ''
                html = `<option value ="${valueSelect}">Chọn ${firstEle}</option>`
                result.forEach((re) => {
                    // console.log(re);
                    html += `<option value =${re[0]}>${re[1]}</option>`
                })
                tagName.html(html)
            })
        }

        async function fetchDataSelect(url, category, knownCategoryValues) {
            // console.log(url, category, knownCategoryValues);
            var myHeaders = new Headers();
            myHeaders.append("Content-Type", "application/x-www-form-urlencoded");
            myHeaders.append("Cookie", "Culture=vi");

            var urlencoded = new URLSearchParams();
            urlencoded.append("category", category);
            urlencoded.append("knownCategoryValues", knownCategoryValues);

            var requestOptions = {
                method: 'POST',
                headers: myHeaders,
                body: urlencoded,
                redirect: 'follow'
            };
            return await fetch("https://corsanywhere.herokuapp.com/" + url, requestOptions)
                .then(response => response.text())
                .then((result) => {
                    let arr = []
                    const parser = new DOMParser();
                    const doc1 = parser.parseFromString(result, "application/xml");
                    let items = doc1.querySelectorAll('CascadingDropDownNameValue');
                    // console.log(items.children[0]);
                    items.forEach((item) => {
                        arr.push([item.children[1].innerHTML, item.children[0].innerHTML])
                    })
                    // console.log(arr);
                    return arr;
                })
                .catch(error => console.log('error', error));
        }

        async function fetchDataSearch(url, TT = 0, QH = 0, TD = 0, MG = '0-99999', p) {
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
                    body: JSON.stringify({TT, QH, TD, MG, p}),
                })
                .then((result) => result.json())
                .catch(function(error) {
                    console.log(error);
                });
        }
    </script>
</body>

</html>
