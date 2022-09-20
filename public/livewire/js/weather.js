function showFullDetails (e)
{
    let coordinates = JSON.parse(e.getAttribute('data-coordinates'));

    e.removeAttribute('onclick');

    axios.get('/api/weather/forecast/single/details', {
            params: {
                latitude: coordinates['latitude'],
                longitude: coordinates['longitude']
            }
        })
        .then(function (response) {
            let list = response.data.data.list;


            list.forEach(data => {
                var dateTime = moment(data['dt_txt']).format('HH:mm'),
                    description = data['weather'][0]['description'],
                    temp_min = Math.round(data['main']['temp_min']),
                    temp_max = Math.round(data['main']['temp_max']),
                    imgSrc = `http://openweathermap.org/img/wn/${data['weather'][0]['icon']}@2x.png`;

                $(`#${coordinates['geoapify_id']}`).append(`
                    <div class="col-4" style="padding-bottom: 10px;">
                        <div class="card transparent-black">
                            <div class="card-body card-body-custom text-center">
                                <div class="fs-6">${dateTime}</div>
                                <img 
                                    width="80"
                                    src="${imgSrc}"
                                >
                                <div class="fs-6">${description}</div>
                            </div>
                        </div>
                    </div>
                `)
            });

        });
}