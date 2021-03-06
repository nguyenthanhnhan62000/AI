
<!doctype html>
<html lang="en">
  <head>
    <title>Title</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        #showData{
            color: #46ab1c;
            font-size: 50px;
        }
    </style>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  </head>
  <body>
    
    <div class="container">
        <!-- Nav tabs -->
        <ul class="nav nav-tabs" id="navId">
            <li class="nav-item">
                <a href="#tab1Id" class="nav-link active">Phân Lớp</a>
            </li>
            <li class="nav-item">
                <a href="/data_mining/cau_1/index" class="nav-link">Phân Cụm Văn Bản</a>
            </li>
        </ul>
        
        <!-- Tab panes -->
        <div class="tab-content">
            <div class="tab-pane fade show active" id="tab1Id" role="tabpanel"></div>
            <div class="tab-pane fade" id="tab2Id" role="tabpanel"></div>
            <div class="tab-pane fade" id="tab3Id" role="tabpanel"></div>
            <div class="tab-pane fade" id="tab4Id" role="tabpanel"></div>
            <div class="tab-pane fade" id="tab5Id" role="tabpanel"></div>
        </div>
        
        <script>
            $('#navId a').click(e => {
                e.preventDefault();
                $(this).tab('show');
            });
        </script>

    </div>
    <div class="container mt-4">
        <div class="form-group">
          <textarea style="height:200px"  id="guess_test" class="form-control" placeholder="Enter ..."></textarea>
          <input type="hidden" id="array"  value ="{{ $array }}">
          <input type="hidden" id="space" value ="{{ $space }}">
          <input type="hidden" id="arrayInput" value ="{{ $arrayInput }}">
        </div>
    </div>
    <div class="container text-center">
        <button type="submit" id="btnsubmit" class="btn btn-primary">Submit</button><br>
        <img src="{{ asset('img/gif.gif') }}" width="100px" id="iLoad" style="display:none">

        <p id="showData"></p>
    </div>

 
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <script>
        var guess_test = $('#guess_test')
        var btnsubmit = $('#btnsubmit')
        var showData = $('#showData')
        var array = $('#array')
        var space = $('#space')
        var arrayInput = $('#arrayInput')
        var iLoad = $('#iLoad')
 
        $(btnsubmit).click(function () { 
            iLoad.css('display', 'inline')
            data = { "guess_test" : guess_test.val(),'array' : array.val(),'space' : space.val(),'arrayInput' : arrayInput.val()}
            fetchDataSearch('/data_mining/index/post_test',data).then((result) =>{
                var largest = Math.max.apply(Math, result); 
                iLoad.css('display', 'none')
                console.log(result,largest);
                result.forEach((element,index) => {
                    if (largest == element) {
                        if (index == 0) {
                            showData.html("The Thao")
                        }else if(index == 1) {
                            showData.html("Kinh Doanh")
                        }else if(index == 2) {
                            showData.html("Phap Luat")
                        }else if(index == 3) {
                            showData.html("Giai Tri")
                        }else if(index == 4) {
                            showData.html("Giao Duc")
                        }
                    }
                });
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