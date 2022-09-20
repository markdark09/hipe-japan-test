<div>
    <div class="container justify-content-center mt-4">
        <div class="row row-cols-auto">
            @foreach ($generalList as $key => $data)
                @if ($data)
                    <input type="checkbox" id="{{'card-' . $key}}" hidden/>
                    <label 
                        class="col-md-4 card-container" 
                        for="{{'card-' . $key}}"
                    >
                        <div class="card-flip">
                            <!-- Card 1 Front -->
                            <div 
                                class="card front text-white bg-primary mb-3"
                                onclick="showFullDetails(this);" 
                                data-coordinates="{{ json_encode($data['geoapify_coordinates']) }}"
                            >
                                <div class="card-body">
                                    <h2 class="card-title">{{ $data['name'] . ', ' . $data['sys']['country'] }}</h2>
                                    <span class="title-description fs-5">{{ Carbon\Carbon::parse($data['dt'])->format('l j F ') }}</span>
                                    <div class="container text-center">
                                        <div class="row">
                                            <div class="col-6">
                                                <img 
                                                        width="120"
                                                        src="{{ 'http://openweathermap.org/img/wn/' . $data['weather'][0]['icon'] . '@2x.png'}}"
                                                >
                                            </div>
                                            <div class="col-6 mt-3">
                                                <span class="fs-2 h2">{{ round($data['main']['temp']) }}&#8451;</span>
                                                <br>
                                                <span class="capitalized-text">{{ $data['weather'][0]['description'] }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="container text-center">
                                        <div class="row">
                                            <div class="col-4">
                                                <div class="fs-4 h4">{{ round($data['main']['temp_max']) }}&#8451;</div>
                                                <div class="title-description">High</div>
                                            </div>
                                            <div class="col-4">
                                                <div class="fs-4 h4">{{ round($data['main']['temp_min']) }}&#8451;</div>
                                                <div class="title-description">Low</div>
                                            </div>
                                            <div class="col-4">
                                                <div class="fs-4 h4">{{ round($data['wind']['speed']) }}mph</div>
                                                <div class="title-description">Wind</div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-6">
                                                <div class="fs-4 h4">{{ Carbon\Carbon::parse($data['sys']['sunrise'])->format('h:i A') }}</div>
                                                <div class="title-description">Sunrise</div>
                                            </div>
        
                                            <div class="col-6">
                                                <div class="fs-4 h4">{{ Carbon\Carbon::parse($data['sys']['sunset'])->format('h:i A') }}</div>
                                                <div class="title-description">Sunset</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Card 1 Front -->

                            <!-- Card 1 Back -->
                            <div class="card back text-white bg-primary mb-3">
                                <div class="card-body">
                                    <div class="fs-5 h5">Next 3 Hours</div>
                                    <div class="row" id="{{ $data['geoapify_coordinates']['geoapify_id'] }}">
                                    </div>
                                </div>
                            </div>
                            <!-- End Card 1 Back -->
                        </div>
                    </label>
                @endif
            @endforeach
        </div>
    </div>
</div>

<script >
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

    document.addEventListener('livewire:load', function () {
        axios.get('/api/weather/forecast/general-list/sort')
            .then(function (response) {
                let data = response.data.data;
                
                @this.generalList = data;
            });
})
</script>

<style>
    .title-description {
        position: relative; 
        bottom: 9px;
    }

    .transparent-black {
        background-color: rgba(37, 34, 34, 0.4); 
        width: 95px;

    }

    .capitalized-text {
        text-transform: capitalize;
    }

    .card-body-custom {
        padding: 5px !important;
    }

    .card-container {
        display: grid;
        perspective: 700px;
    }

    .card-flip {
        display: grid;
        grid-template: 1fr / 1fr;
        grid-template-areas: "frontAndBack";
        transform-style: preserve-3d;
        transition: all 0.7s ease;
        width: 352px;
    }

    .card-flip div {
        backface-visibility: hidden;
        transform-style: preserve-3d;
    }

    .front {
        width: 22rem;
        grid-area: frontAndBack;
    }

    .back {
        width: 22rem;
        grid-area: frontAndBack;
        transform: rotateY(-180deg);
    }

    input[type='checkbox']:checked + .card-container .card-flip {
        transform: rotateY(180deg);
    }
</style>