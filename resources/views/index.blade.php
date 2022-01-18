<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

    <script src="index.js"></script>
    <title>Index</title>
</head>

<body style="background: #e3f2fd; min-height: 100vh;">
    <div class="container">
        <!-- Nav tabs -->
        <ul class="nav nav-tabs" id="navId">
            <li class="nav-item">
                <a href="#tab1Id" class="nav-link active">Tính lịch trả nợ với dư nợ giảm dần</a>
            </li>
            <li class="nav-item">
                <a href="/law" class="nav-link">Văn Bản</a>
            </li>
            <li class="nav-item">
                <a href="/search" class="nav-link">Tìm kiếm giá đất</a>
            </li>
            <li class="nav-item">
                <a href="/cau_3/search" class="nav-link">Các loại giấy tờ liên quan đến sổ đỏ</a>
            </li>
        </ul>
    </div>
    <div class="mt-4">
        <div class="bg-light container border border-primary rounded-3 p-3">
            <div class="row">
                <div class="col-4">
                    <h2 class="text-center text-Success">Tính lịch trả nợ với dư nợ giảm dần</h2>

                    <label for="basic-url" class="form-label">Số tiền vay / VND</label>
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" id="money" aria-describedby="basic-addon3" required>
                    </div>

                    <label for="basic-url" class="form-label">Thời gian vay / Tháng</label>
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" id="time" aria-describedby="basic-addon3" required>
                    </div>

                    <label for="basic-url" class="percent">Lãi suất % / n</label>
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" id="basic-url" aria-describedby="basic-addon3" required>
                    </div>

                    <button onclick="send()" type="button" class="btn btn-primary w-100">Tính</button>
                </div>

                <div class="col-8" style="height: 80vh; overflow: auto;">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th scope="col">Kỳ</th>
                                <th scope="col">Số gốc còn lại</th>
                                <th scope="col">Gốc</th>
                                <th scope="col">Lãi</th>
                                <th scope="col">Tổng gốc + Lãi</th>
                            </tr>
                        </thead>
                        <tbody id="content">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>









    <!-- Optional JavaScript; choose one of the two! -->

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p"
        crossorigin="anonymous"></script>

    <!-- Option 2: Separate Popper and Bootstrap JS -->
    <!--
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js" integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script>
    -->

    <script>
        function send(){
    var arr = document.getElementsByTagName("input")
    var money = arr[0].value * 1
    var time = arr[1].value * 1
    var percent = arr[2].value * 1/100/12
    console.log(money, time, percent)

    var moneyGoc = money
    var moneyLai = 0
    var moneyTotal = 0

    var goc = (money/time).toFixed(2)
    var content = document.getElementById("content")
    content.innerHTML = ""

    var numVND = new Intl.NumberFormat("it-IT",{
        style: "currency",
        currency: "VND",
    })
    
    for(var i = 1; i <= time; i++){
        var lai = (money * percent)
        var total = (((money/time)+(money * percent))*1).toFixed(2)
        content.innerHTML += `
            <tr>
                <th scope="row">${i}</th>
                <td>${numVND.format(money - goc)}</td>
                <td>${numVND.format(goc)}</td>
                <td>${numVND.format(lai)}</td>
                <td>${numVND.format(total)}</td>
            </tr>
        `

        moneyLai += (money * percent)
        money = money - goc
    }

    moneyTotal = moneyGoc + moneyLai

    content.innerHTML += `
        <tr>
            <td></td>
            <td></td>
            <th scope="row">${numVND.format(moneyGoc)}</th>
            <th scope="row">${numVND.format(moneyLai)}</th>
            <th scope="row">${numVND.format(moneyTotal)}</th>
        </tr>
    `
}
    </script>
</body>

</html>