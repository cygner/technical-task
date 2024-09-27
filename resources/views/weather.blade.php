<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>Weather</title>

    <link type="text/css" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>

    <div class="container">
        <div class="row">
            <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 col-xxl-12">
                <h1>Weather</h1>
            </div>
        </div>
        <div class="row">
            <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 col-xxl-12">
                <form action="javascript:void(0)" method="post" id="get-weather">
                    @csrf
                    <label for="city" class="form-label fw-bold">Select City</label>
                    <select name="city" id="city" class="form-select form-control">
                        <option value="new york">New York</option>
                        <option value="amsterdam">Amsterdam</option>
                        <option value="london">London</option>
                        <option value="tokyo">Tokyo</option>
                        <option value="boston">Boston</option>
                        <option value="ahmedabad">Ahmedabad</option>
                        <option value="rajkot">Rajkot</option>
                    </select>
                    <button type="submit" class="btn btn-success mt-2">Submit</button>
                </form>
            </div>
        </div>
        <div class="clearfix my-4"></div>
        <div class="row">
            <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 col-xxl-12">
                <div class="alert alert-danger" id="error" style="display: none"></div>

                <div class="card" id="show-weather-data" style="display: none">
                    <div class="card-header bg-dark text-white">
                        <h2 class="mb-0 text-capitalize" id="city-name"></h2>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 col-xxl-12">
                                <h1 class="text-center mb-0">
                                    <img id="weather-icon" src="https://openweathermap.org/img/wn/09d@2x.png" alt="Weather Icon" class="w-auto img-fluid" />
                                    <span id="temperature"></span>
                                    <br/>
                                    <span id="weather"></span>
                                </h1>
                            </div>
                        </div>
                        <hr/>
                        <div class="row">
                            <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-4 col-xxl-4">
                                <p><span class="fw-bold">Feels Like:</span> <span id="feels-like"></span></p>
                            </div>
                            <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-4 col-xxl-4">
                                <p><span class="fw-bold">Minimum Temp:</span> <span id="min-temp"></span></p>
                            </div>
                            <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-4 col-xxl-4">
                                <p><span class="fw-bold">Maximum Temp:</span> <span id="max-temp"></span></p>
                            </div>
                        </div>
                        <hr/>
                        <div class="row">
                            <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-4 col-xxl-4">
                                <p><span class="fw-bold">Humidity:</span> <span id="humidity"></span></p>
                                <p><span class="fw-bold">Wind Speed:</span> <span id="wind-speed"></span></p>
                            </div>
                            <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-4 col-xxl-4">
                                <p><span class="fw-bold">Pressure:</span> <span id="pressure"></span></p>
                                <p><span class="fw-bold">Sea Level:</span> <span id="sea-level"></span></p>
                            </div>
                            <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-4 col-xxl-4">
                                <p><span class="fw-bold">Visibility:</span> <span id="visibility"></span></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript" src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>

    <script type="text/javascript">
        function getWeather(city) {
            $.ajax({
                url: "{{ route('get-weather') }}",
                method: "POST",
                dataType: "json",
                data: {city: city},
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (response) {
                    if (response.status == "success") {
                        $("#error").hide().text("");

                        $("#city-name").text(city + ", " + response.weatherData.sys.country);
                        $("#weather-icon").attr("src", "https://openweathermap.org/img/wn/" + response.weatherData.weather[0].icon + "@2x.png");
                        $("#temperature").text(response.weatherData.main.temp + "°C");
                        $("#weather").text(response.weatherData.weather[0].main + ", " + response.weatherData.weather[0].description);
                        $("#feels-like").text(response.weatherData.main.feels_like + "°C");
                        $("#min-temp").text(response.weatherData.main.temp_min + "°C");
                        $("#max-temp").text(response.weatherData.main.temp_max + "°C");
                        $("#humidity").text(response.weatherData.main.humidity + "%");
                        $("#wind-speed").text(response.weatherData.wind.speed + " m/s");
                        $("#pressure").text(response.weatherData.main.pressure + " hPa");
                        $("#sea-level").text(response.weatherData.main.sea_level + " hPa");
                        $("#visibility").text((response.weatherData.visibility / 1000).toFixed(2) + "km");

                        $("#show-weather-data").show();
                    } else {
                        $("#show-weather-data").hide();
                        $("#error").show().text(response.message);
                    }
                }
            });
        }

        let selectedCity = $("select#city option").filter(":selected").val();
        getWeather(selectedCity);

        $(document).ready(function () {
            $(document).on("submit", "#get-weather", function (evt) {
                evt.preventDefault();

                let city = $("#city").val();
                getWeather(city);
            });
        });
    </script>
</body>
</html>
