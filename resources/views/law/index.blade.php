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

    </style>
</head>

<body>
    {{-- <div class="container">
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($laws as $law)
                    
                <tr>
                    <td scope="row">{{ $law->id }}</td>
                    <td>{{ $law->name }}</td>
                    <td>
                        <a href="law/{{ $law->id }}" class="btn btn-primary">View</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div> --}}
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

        btnSearchLaw.click(function() {
            let data = {
                'textSearch': searchLaw.val(),
                'page': 1
            }
            let html = ''
            fetchData('/law/post/index', data).then(function(result) {
                data = result.data;
                console.log(data);
                for (let key in data) {
                    html += `
                            <div class="row">
                                <div class="col-md-1">${key}</div>
                                <div class="col-md-7">
                                    <p><b>${data[key][0]}</b></p>
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
            })
        });
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
