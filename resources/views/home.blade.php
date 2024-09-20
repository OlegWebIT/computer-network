@extends('layouts.app')

@section('content')

<div class="container ">
    @if(Auth::user()->is_super)
        <div class="text-bg-success p-3">Вы зашли под СУПЕР админом</div>
    @else
        <div class="text-bg-success p-3">Вы зашли под менеджером</div>
    @endif

    <div class="row justify-content-center mt-5">
        <div style="text-align: center;width: auto;margin: 0 auto;min-width:600px;: 300px;margin-bottom: 54px;"><canvas style="" id="graphic"></canvas></div><br/>
        <script>

            let myFetch = fetch('?getDataLogs=1');


            myFetch.then(function (response) {
                response.json().then(function (resJson) {
                    let xValues = [];
                    let yValues = [];

                    resJson[0].forEach(function (xKey) {
                        xValues.push(xKey)
                    });

                    resJson[1].forEach(function (yKey) {
                        yValues.push(yKey)
                    });


                    const chart = new Chart(
                        document.getElementById('graphic'), {
                            type: 'line',

                            data: {
                                labels: xValues,
                                datasets: [{
                                    label: 'Активность за день',
                                    borderDashOffset: 1,
                                    data: yValues,
                                    tension: 0.2,
                                    segment: 1,
                                }],
                            }
                        });
                });
            });



        </script>
    </div>
</div>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Формирование запросов SQL</div>

                <div class="card-body">
                    <form method="GET" class="row g-2">
                        <div class="col-12">
                            <input type="hidden" name="sql[is_active]" value="1">
                            <label for="validationServer01" class="form-label">Имя</label>
                            <input name="sql[sqlRequest]" style="height: 100px;" type="text" class="form-control" placeholder="Введите SQL" id="validationServer01" value="INSERT INTO device (type, brand, model, ip_adress, status) VALUES ('Коммутатор','Cisco','OSX','93.123.12.12','on');" required="">
                        </div>
                        <div class="col-12">
                            <button class="btn btn-primary" type="submit">Отправить форму</button>
                        </div>
                    </form>

                    @if($responseFromFormSql)
                        <div class="card text-bg-success mb-3 mt-2" >
                            <div class="card-header">Результаты запроса (<?=$sqlRequest?>)</div>
                            <div class="card-body">
                                <p class="card-text">
                                    <pre style="text-align: left">
                                        <?php var_dump($responseFromFormSql)?>
                                    </pre>
                                </p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @if(count($device) > 0)
        <div class="row justify-content-center mt-5">
            <div class="col-md-8">
                <h2>Девайсы</h2>
                <div class="card">
                    <table class="table table-striped table-hover ">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Тип</th>
                                <th scope="col">Бренд</th>
                                <th scope="col">Модель</th>
                                <th scope="col">Ip адрес</th>
                                <th scope="col">Статус</th>
                                <th>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash-fill" viewBox="0 0 16 16">
                                        <path fill="#950000" d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1H2.5zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5zM8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5zm3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0z"/>
                                    </svg>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($device->chunk(count($device)) as $chunk)
                                @foreach ($chunk as $product)
                                    <tr>
                                        <th scope="row"><?=$product->id?></th>
                                        <td><?=$product->type?></td>
                                        <td><?=$product->brand?></td>
                                        <td><?=$product->model?></td>
                                        <td><?=$product->ip_adress?></td>
                                        <td><?=$product->status?></td>
                                        <th>
                                            <a onclick="return confirm('Вы действительно хотите удалить эту запись?');" href="/?delete=<?=$product->id?>">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash-fill" viewBox="0 0 16 16">
                                                    <path fill="#950000" d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1H2.5zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5zM8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5zm3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0z"/>
                                                </svg>
                                            </a>
                                        </th>
                                    </tr>
                                @endforeach
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif

    @if(count($address) > 0)
        <div class="row justify-content-center mt-5">
            <div class="col-md-8">
                <h2>Ip адреса</h2>
                <div class="card">
                    <table class="table table-striped table-hover ">
                        <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Тип</th>
                            <th scope="col">Ip</th>
                            <th scope="col">Статус</th>
                            <th>
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash-fill" viewBox="0 0 16 16">
                                    <path fill="#950000" d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1H2.5zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5zM8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5zm3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0z"/>
                                </svg>
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($address->chunk(count($address)) as $chunk)
                            @foreach ($chunk as $ip)
                                <tr>
                                    <th scope="row"><?=$ip->id?></th>
                                    <td><?=$ip->type?></td>
                                    <td><?=$ip->address?></td>
                                    <td><?=$ip->status?></td>
                                    <th>
                                        <a onclick="return confirm('Вы действительно хотите удалить эту запись?');" href="/?delete=<?=$product->id?>">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash-fill" viewBox="0 0 16 16">
                                                <path fill="#950000" d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1H2.5zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5zM8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5zm3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0z"/>
                                            </svg>
                                        </a>
                                    </th>
                                </tr>
                            @endforeach
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif

    @if(count($domains) > 0)
        <div class="row justify-content-center mt-5">
            <div class="col-md-8">
                <h2>Домены</h2>
                <div class="card">
                    <table class="table table-striped table-hover ">
                        <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Тип</th>
                            <th scope="col">Ip</th>
                            <th scope="col">Ресурс</th>
                            <th scope="col">Статус</th>
                            <th>
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash-fill" viewBox="0 0 16 16">
                                    <path fill="#950000" d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1H2.5zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5zM8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5zm3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0z"/>
                                </svg>
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($domains->chunk(count($domains)) as $chunk)
                            @foreach ($chunk as $domain)
                                <tr>
                                    <th scope="row"><?=$domain->id?></th>
                                    <td><?=$domain->type?></td>
                                    <td><?=$domain->address?></td>
                                    <td><?=$domain->resourses_name?></td>
                                    <td><?=$domain->status?></td>
                                    <th>
                                        <a onclick="return confirm('Вы действительно хотите удалить эту запись?');" href="/?delete=<?=$product->id?>">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash-fill" viewBox="0 0 16 16">
                                                <path fill="#950000" d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1H2.5zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5zM8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5zm3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0z"/>
                                            </svg>
                                        </a>
                                    </th>
                                </tr>
                            @endforeach
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif

    @if(count($connections) > 0)
        <div class="row justify-content-center mt-5">
            <div class="col-md-8">
                <h2>Соединения</h2>
                <div class="card">
                    <table class="table table-striped table-hover ">
                        <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Тип</th>
                            <th scope="col">Скороть передачи, бит/с</th>
                            <th scope="col">Длинна кабеля, м</th>
                            <th scope="col">Пропускная способность, Мбит/с</th>
                            <th>
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash-fill" viewBox="0 0 16 16">
                                    <path fill="#950000" d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1H2.5zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5zM8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5zm3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0z"/>
                                </svg>
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($connections->chunk(count($connections)) as $chunk)
                            @foreach ($chunk as $ip)
                                <tr>
                                    <th scope="row"><?=$ip->id?></th>
                                    <td><?=$ip->type?></td>
                                    <td><?=$ip->speed?></td>
                                    <td><?=$ip->cable_length?></td>
                                    <td><?=$ip->bandwidth?></td>
                                    <th>
                                        <a onclick="return confirm('Вы действительно хотите удалить эту запись?');" href="/?delete=<?=$product->id?>">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash-fill" viewBox="0 0 16 16">
                                                <path fill="#950000" d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1H2.5zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5zM8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5zm3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0z"/>
                                            </svg>
                                        </a>
                                    </th>
                                </tr>
                            @endforeach
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif

    @if(count($services) > 0)
        <div class="row justify-content-center mt-5">
            <div class="col-md-8">
                <h2>Сервисы</h2>
                <div class="card">
                    <table class="table table-striped table-hover ">
                        <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Сервис</th>
                            <th scope="col">Описание</th>
                            <th scope="col">Версия</th>
                            <th scope="col">Уровень</th>
                            <th>
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash-fill" viewBox="0 0 16 16">
                                    <path fill="#950000" d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1H2.5zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5zM8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5zm3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0z"/>
                                </svg>
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($services->chunk(count($services)) as $chunk)
                            @foreach ($chunk as $sv)
                                <tr>
                                    <th scope="row"><?=$sv->id?></th>
                                    <td><?=$sv->name?></td>
                                    <td><?=$sv->descr?></td>
                                    <td><?=$sv->version?></td>
                                    <td><?=$sv->level?></td>
                                    <th>
                                        <a onclick="return confirm('Вы действительно хотите удалить эту запись?');" href="/?delete=<?=$product->id?>">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash-fill" viewBox="0 0 16 16">
                                                <path fill="#950000" d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1H2.5zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5zM8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5zm3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0z"/>
                                            </svg>
                                        </a>
                                    </th>
                                </tr>
                            @endforeach
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif


</div>
@endsection

