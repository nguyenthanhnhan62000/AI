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
</head>

<body>
    <div class="container mt-4">
        <form action="" method="post">

            <div class="row">
                <div class="col-md-3">
                    Chọn Địa Bàn
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <select class="form-control stTT" name="slTT" id="">
                            {{-- <option value=''>Chọn Tỉnh Thành</option> --}}
                    </select>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <select class="form-control stQH" name="slQH" id="">
                        <option value=''>Chọn Quận Huyện...</option>
                    </select>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <select class="form-control slTD" name="slTD" id="">
                        <option value=''>Chọn Tên Đường</option>
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
                        <option value=''>Tất Cả</option>
                    </select>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Xem Gia</button>
                </div>
            </div>
        </div>
    </div>
</form>

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
        addViewData(slMG, 'Tất Cả', 'https://thuvienphapluat.vn/CountryService.asmx/GetPriceRange', 'PriceRange')


        function addViewData(tagName, firstEle, url, city, knownCategoryValues) {
            fetchData(url, city, knownCategoryValues).then((result) => {
                let html = ''
                html = `<option value ="">Chọn ${firstEle}</option>`
                result.forEach((re) => {
                    // console.log(re);
                    html += `<option value =${re[0]}>${re[1]}</option>`
                })
                tagName.html(html)
            })
        }

        async function fetchData(url, category, knownCategoryValues) {
            console.log(url, category, knownCategoryValues);
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
            return await fetch(" https://corsanywhere.herokuapp.com/" + url, requestOptions)
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
    </script>
</body>

</html>
