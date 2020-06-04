$(document).ready(function() {

    function clicker () {
        $("#do_weather").click();
    }
        $(document).on('click', '#do_weather', function () {

            $.ajax({
                type: "GET",
                url: "weather_servis_functions.php",
                success: function (response) {
                    var res = JSON.parse(response);
                    if (res.status == true) {
                        $('.city').text(res.City_name);
                        $('.temperature').text(res.Temperature);
                        $('.wind').text(res.Wind_Speed);
                        $('.condition').text(res.Weather_condition);
                        $('.icon').text(res.Weather_icon_id);
                    } else {
                        $('#err').text('Не удалоссь получить данные с стороннего сервиса');
                    }
                }
            });

        })

    let timerId = setInterval(clicker, 5000);    //    устанавливаем обновление данных каждые 5 сек
    setTimeout(() => { clearInterval(timerId); alert('stop'); }, 60000);       // для избежания блокировки токина от частых запросов ограничиваю продолжительность обновлений до 1 мин.

});