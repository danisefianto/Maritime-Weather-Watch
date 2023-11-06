<?= $this->extend('layout'); ?>

<?= $this->section('content');?>
<div class="row">
    <div class="col">
        <div class="card">
            <div class="card-header">
                 <h2 class ="my-auto">Info Cuaca</h2>
            </div>
            <div class="card-body">  
            <div class="card shadow-none border  rounded">
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6 mb-3">
                                <label for="selectType" class="form-label">Pilih Pencarian</label>
                                <select name="" id="selectType" class="form-select" onchange="getType()">
                                    <option value="">- Pilih Type -</option>
                                    <option value="1">Kapal</option>
                                    <option value="2">Pelabuhan</option>
                                    <option value="3">Zona</option>
                                    <option value="4">Lokasi Saya</option>
                                </select>
                            </div>
                            <div class="col-md-6 d-none" id="type">
                                <div class="" id="kapal">
                                    <label for="selectKapal" class="form-label">Kapal</label>
                                    <select id="selectKapal" class="form-select">
                                        <option value="" selected disabled>- Pilih Kapal -</option>
                                    </select>
                                </div>
                                <div class="" id="pelabuhan">
                                    <label for="selectPelabuhan" class="form-label">Pelabuhan</label>
                                    <select id="selectPelabuhan" class="form-select">
                                        <option value="" selected disabled>- Pilih Pelabuhan -</option>
                                    </select>
                                </div>
                                <div class="" id="zona">
                                    <label for="selectZona" class="form-label">Zona</label>
                                    <select id="selectZona" class="form-select">
                                        <option value="" selected disabled>- Pilih Zona -</option>
                                    </select>
                                </div>
                                <div class="" id="lokasi">
                                    <label for="" class="form-label">Lokasi</label>
                                    <button class="btn btn-primary w-100"><i class="ti ti-map-pin"></i> Lokasi Saya</button>
                                    <small class="text-danger d-none">Error</small>
                                </div>
                            </div>
                        </div>
                        <div id="result" class="d-none">
                            <div class="card">
                                <div class="card-body rounded" style="background-color: #ffcda1;">
                                    <div class="d-flex align-items-center gap-3">
                                        <img src="" alt="" id="img-country" style="height: 50px;" class="shadow-sm">
                                        <div>
                                            <h2 id="name" class="m-0"></h2>
                                            <p id="coord" class="m-0"></p>
                                        </div>
                                    </div>
                                    <div id="container-carousel"></div>
                                    <div class="table-responsive">
                                        <table class="table my-card" style="border-radius: 0;">
                                            <caption>Prediksi Cuaca <a href="https://openweathermap.org/" class="text-dark"><u>Open Weather</u></a> <i class="ti ti-sun-wind" style="font-size: 1.3em;"></i></caption>
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Kondisi Cuaca</th>
                                                    <th>Suhu</th>
                                                    <th>Kecepatan Angin</th>
                                                    <th style="white-space: nowrap;">Arah Angin (U<i class="fas fa-long-arrow-alt-up"></i>)</th>
                                                    <th>Waktu</th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                        </table>
                                    </div>
                                </div>
                           </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?= $this->endSection();?>

        <?= $this->section('script'); ?>
        <script>
            var dataKapal = [];

            var getType = () => {
                var select = $("#selectType").val()
                var type = $("#type");
                var kapal = $("#kapal");
                var pelabuhan = $("#pelabuhan");
                var zona = $("#zona");
                var lokasi = $("#lokasi");

                switch (select) {
                    case "1":
                        type.removeClass('d-none')
                        kapal.removeClass('d-none')
                        pelabuhan.addClass('d-none')
                        zona.addClass('d-none')
                        lokasi.addClass('d-none')
                        break;
                    case "2":
                        type.removeClass('d-none')
                        kapal.addClass('d-none')
                        pelabuhan.removeClass('d-none')
                        zona.addClass('d-none')
                        lokasi.addClass('d-none')
                        break;
                    case "3":
                        type.removeClass('d-none')
                        kapal.addClass('d-none')
                        pelabuhan.addClass('d-none')
                        zona.removeClass('d-none')
                        lokasi.addClass('d-none')
                        break;
                    case "4":
                        type.removeClass('d-none')
                        kapal.addClass('d-none')
                        pelabuhan.addClass('d-none')
                        zona.addClass('d-none')
                        lokasi.removeClass('d-none')
                        break;

                    default:
                        type.addClass('d-none')
                        kapal.removeClass('d-none')
                        pelabuhan.removeClass('d-none')
                        zona.removeClass('d-none')
                        lokasi.removeClass('d-none')
                        break;
                }
            }

            var getKapal = () => {
                $.ajax({
                    url: "<YOUR API LINK>/api/get.php",
                    data: {
                        user: "<YOUR USERNAME>",
                        pass: "<YOUR PASSWORD>",
                        type: 1,
                        d: 1,
                        h: 12
                    },
                }).done((kapal) => {
                    if (kapal.data.length > 0) {
                        $.each(kapal.data, (key, val) => {
                            $("#selectKapal").append(`<option value="${key}" data-latitude="${val.lat}" data-longitude="${val.lon}">${val.date} - ${val.name}</option>`)
                        })
                        renderKapal()
                    }
                })
            }

            var getPelabuhanZona = () => {
                $.ajax({
                    url: "<YOUR API LINK>/api/getent.php",
                    data: {
                        user: "<YOUR USERNAME>",
                        pass: "<YOUR PASSWORD>",
                        entity: "zone"
                    },
                }).done((pelabuhanZona) => {
                    $.each(pelabuhanZona.features, (key, val) => {
                        var coordinates = val.geometry.coordinates
                        if (coordinates.length > 2) {
                            $("#selectZona").append(`<optgroup label="${val.properties.zone_name}"></optgroup>`)
                            $.each(coordinates, (keyGroup, valGroup) => {
                                $(`optgroup[label='${val.properties.zone_name}']`).append(`
                                    <option value="${key}" data-latitude="${valGroup[1]}" data-longitude="${valGroup[0]}">${keyGroup + 1}. ${val.properties.zone_name} (${valGroup[1]}, ${valGroup[0]})</option>
                                `)
                            });
                        } else if (coordinates.length == 1) {
                            $("#selectZona").append(`<optgroup label="${val.properties.zone_name}"></optgroup>`)
                            $.each(coordinates[0], (keyGroup2, valGroup2) => {
                                $(`optgroup[label='${val.properties.zone_name}']`).append(`
                                    <option value="${key}" data-latitude="${valGroup2[1]}" data-longitude="${valGroup2[0]}">${keyGroup2 + 1}. ${val.properties.zone_name} (${valGroup2[1]}, ${valGroup2[0]})</option>
                                `)
                            });
                        } else {
                            $("#selectPelabuhan").append(`
                                <option value="${key}" data-latitude="${coordinates[1]}" data-longitude="${coordinates[0]}">${val.properties.zone_name}</option>
                            `)
                        }
                    });
                    renderPelabuhanZona()
                });
            }

            var renderKapal = () => {
                $("#selectKapal").select2({
                    placeholder: "Cari berdasarkan kapal..",
                    allowClear: true,
                    theme: 'bootstrap-5'
                });
                $("#selectKapal").on("select2:select", function(e) {
                    var selectedOption = e.params.data.element.dataset;
                    search(selectedOption)
                });
            }

            var renderPelabuhanZona = () => {
                // Pelabuhan
                $("#selectPelabuhan").select2({
                    placeholder: "Cari berdasarkan pelabuhan..",
                    allowClear: true,
                    theme: 'bootstrap-5'
                });
                $("#selectPelabuhan").on("select2:select", function(e) {
                    var selectedOption = e.params.data.element.dataset;
                    search(selectedOption)
                });

                // Zona
                $("#selectZona").select2({
                    placeholder: "Cari berdasarkan zona..",
                    allowClear: true,
                    theme: 'bootstrap-5'
                });
                $("#selectZona").on("select2:select", function(e) {
                    var selectedOption = e.params.data.element.dataset;
                    search(selectedOption)
                });
            }

            getKapal()
            getPelabuhanZona()


            var search = (dataThrow) => {
                var form_caption = $("#form-caption")
                var table = $('tbody')
                var latitude = dataThrow.latitude
                var longitude = dataThrow.longitude

                if (latitude != "" && longitude != "") {
                    $.ajax({
                        url: 'https://api.openweathermap.org/data/2.5/forecast',
                        data: {
                            lat: latitude,
                            lon: longitude,
                            appid: '<YOUR APP ID>',
                        }
                    }).done(function(data) {
                        var container = "";
                        var tableRow = "";
                        var city = data.city;
                        var weather = data.list;

                        $("#container-carousel").html('<div id="carousel" class="owl-carousel mt-3"></div>')
                        $("#img-country").attr('src', `https://flagcdn.com/w320/${city.country.toLowerCase()}.png`)

                        $.ajax({
                            url: `https://restcountries.com/v3.1/alpha/${city.country.toLowerCase()}`
                        }).done(function(country) {
                            $("#name").text(`${city.name}, ${country[0].name.common}`)
                            $("#coord").text(`Lat: ${city.coord.lat}, Long: ${city.coord.lon}`)
                        }).fail(function(jqXHR, textStatus, errorThrown) {
                            $("#name").text(`${city.name}, ${city.country}`)
                            $("#coord").text(`Lat: ${city.coord.lat}, Long: ${city.coord.lon}`)
                        });

                        $.each(weather, (key, val) => {
                            container += `
                                <div class="card my-card">
                                    <div class="card-body p-1">
                                        <div class="d-flex align-items-center mb-2">
                                            <img src="https://openweathermap.org/img/wn/${val.weather[0].icon}@2x.png" style="width: 40px;">
                                            <p class="m-0 me-2" style="font-size: 1.2em;">${val.weather[0].description}</p>
                                        </div>
                                        <div class="d-flex mb-2 justify-content-between">
                                            <div class="temp ms-2">
                                                <span>Temp</span>
                                                <p style="font-size: 1.3em;" class="m-0">${(val.main.temp - 273.15).toFixed(2)}&deg;C</p>
                                            </div>
                                            <div class="wind ms-2">
                                                <span>Wind</span>
                                                <p style="font-size: 1.3em;" class="m-0">${val.wind.speed}m/s</p>
                                            </div>
                                            <div class="direction ms-2">
                                                <span>Direction</span>
                                                <div class="d-flex" style="font-size: 1.3em; ">
                                                    <p class="m-0">${val.wind.deg}&deg;</p>
                                                    <p class="m-0" style="transform: rotate(${val.wind.deg}deg);">
                                                        <i class="ti ti-arrow-narrow-up"></i>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="time ms-2">
                                            <span>Time</span>
                                            <p class="me-2">${val.dt_txt} WIB</p>
                                        </div>
                                    </div>
                                </div>
                                `;
                            tableRow += `
                                <tr style="font-size: 1.1em;">
                                    <td class="align-middle text-center">${key + 1}</td>
                                    <td class="align-middle">
                                        <img src="https://openweathermap.org/img/wn/${val.weather[0].icon}@2x.png" style="width: 40px;">
                                        ${val.weather[0].description}
                                    </td>
                                    <td class="align-middle">${(val.main.temp - 273.15).toFixed(2)}&deg;C</td>
                                    <td class="align-middle">${val.wind.speed}m/s</td>
                                    <td class="align-middle">
                                        <div class="d-flex justify-content-evenly">
                                            <p class="m-0">${val.wind.deg}&deg;</p>
                                            <p class="m-0" style="transform: rotate(${val.wind.deg}deg);">
                                                <i class="ti ti-arrow-narrow-up"></i>
                                            </p>
                                        </div>
                                    </td>
                                    <td class="align-middle">${val.dt_txt} WIB</td>
                                </tr>
                                `
                        });

                        $("#carousel").empty();
                        $("table tbody").empty();
                        var owlcarousel = $(".owl-carousel")
                        owlcarousel.append(container)
                        $("#carousel").owlCarousel({
                            margin: 10,
                            nav: true,
                            responsiveClass: true,
                            autoWidth: true
                        });

                        $("table tbody").append(tableRow)
                        $("#result").removeClass("d-none")
                    });
                } else {
                    form_caption.addClass('text-danger')
                    form_caption.text('Harap lengkapi form diatas')
                }
            }

            var formatTime = (timestamp) => {
                // Membuat objek Date dari timestamp
                var dateObj = new Date(timestamp);

                // Membuat objek Intl.DateTimeFormat dengan opsi zona waktu berdasarkan lokasi geografis
                var options = {
                    timeZone: 'Asia/Jakarta', // Ganti dengan lokasi geografis yang diinginkan (misal: 'Asia/Makassar', 'Asia/Jayapura', dll.)
                    year: 'numeric',
                    month: '2-digit',
                    day: '2-digit',
                    hour: '2-digit',
                    minute: '2-digit',
                    second: '2-digit'
                };
                var dateTimeFormat = new Intl.DateTimeFormat('id-ID', options);

                // Mengubah objek Date menjadi string dengan informasi zona waktu dinamis
                var formattedDateTime = dateTimeFormat.format(dateObj);

                return formattedDateTime;
            }

            var getApi = 2;
            $(document).ready(function() {
                var button = $("#lokasi button");
                var textError = $("#lokasi small");

                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(function(position) {
                        var latitude = position.coords.latitude;
                        var longitude = position.coords.longitude;

                        button.attr('onclick', `search({latitude: '${latitude}', longitude: '${longitude}'})`)
                    }, function error(error) {
                        textError.text("Error: " + error.message);
                        textError.removeClass("d-none");
                        button.prop("disabled", true);
                    });
                } else {
                    textError.text("Error: Geolocation is not supported by this browser.");
                    textError.removeClass("d-none");
                    button.attr("disabled");
                }
            });
         </script>
        <?= $this->endSection(); ?>