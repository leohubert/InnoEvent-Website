@extends('layouts.app')

@section('content')
    <div class="row justify-content-center event-view">
        <div class="col-md-9 svg-wrapper">
            <div class="svg-parent">
                <svg id="svg" class="svg">
                </svg>
            </div>
            <div class="loader">
                <div class="lds-ripple">
                    <div></div>
                    <div></div>
                </div>
            </div>
        </div>
        <div class="col-md-3 nopadding">
            <div class="card bg-dark card-places" id="places-list">
                <nav>
                    <div class="nav justify-content-center nav-fill nav-tabs bg-dark navbar-dark" id="nav-tab"
                         role="tablist">
                        <a class="nav-item nav-link active" id="nav-home-tab" data-toggle="tab" href="#nav-lowest"
                           role="tab" aria-controls="nav-home" aria-selected="true">Lowest Price</a>
                        <!--<a class="nav-item nav-link" id="nav-profile-tab" data-toggle="tab" href="#nav-best"
                           role="tab" aria-controls="nav-profile" aria-selected="false">Best Seats</a>-->
                    </div>
                </nav>
                <div class="tab-content" id="nav-tabContent" style="overflow: auto">
                    <div class="tab-pane fade show active" id="nav-lowest" role="tabpanel"
                         aria-labelledby="nav-home-tab">
                        <ul class="list-group list-group-flush bg-dark" id="lowest-prices">

                        </ul>
                    </div>
                    <!--<div class="tab-pane fade" id="nav-best" role="tabpanel" aria-labelledby="nav-profile-tab">
                        <ul class="list-group list-group-flush" id="best-seats">
                            <li class="list-group-item  bg-dark text-light list-group-item-action">Cras justo odio</li>
                            <li class="list-group-item bg-dark text-light  list-group-item-action">Vestibulum at eros
                            </li>
                        </ul>
                    </div>-->
                </div>
            </div>
            <div class="card d-flex flex-column justify-content-between bg-dark text-light card-places"
                 id="places-buyer" style="display: none !important;">
                <br>
                <h5 class="card-title text-center">Your Tickets</h5>

                <div class="card-body" id="card-places-list" style="overflow: auto;">

                </div>
                <div class="card-footer">
                    <div class="row">
                        <div class="col-md-12 text-light">
                            <div id="dropin-container"></div>
                        </div>
                        <div class="col-md-6">
                            <button id="submit-button" class="btn-success btn">Buy Ticket(s)</button>
                        </div>
                        <div class="col-md-6">
                            <p id="total"> Total: - $</p>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection

@section('style')
    <style>
        .loader {
            position: absolute;
            display: flex;
            align-content: center;
            align-items: center;
            justify-content: center;
            background: black;
            opacity: 0.7;
            width: 100%;
            height: 100%;
        }
    </style>
@endsection


@section('script')
    <script src="https://js.braintreegateway.com/web/dropin/1.8.1/js/dropin.min.js"></script>
    <script type="module">


        (function () {
            const button = document.querySelector('#submit-button');
            const lowestPricesEl = document.querySelector('#lowest-prices');
            const cardPlacesList = document.querySelector('#card-places-list');


            let selectedPlaces = [];

            const svg = document.querySelector('#svg');
            const svgParent = document.querySelector('.svg-parent');

            let places = null;
            let zoom = 100;

            button.addEventListener('click', showPayment);

            function showPayment() {
                if (selectedPlaces.length === 0) {
                    return;
                }

                let placesIds = []

                selectedPlaces.forEach((p, index) => {
                    placesIds.push(p.id);
                });


                braintree.dropin.create({
                    authorization: "{{ Braintree_ClientToken::generate() }}",
                    container: '#dropin-container',
                    vaultManager: true,
                }, function (createErr, instance) {
                    button.addEventListener('click', function () {
                        if (!instance)
                            return;
                        instance.requestPaymentMethod(function (err, payload) {
                            $.get('/payment/process', {payload, placesIds}, function (response) {
                                if (response.success) {
                                    selectedPlaces.forEach((p, index) => {
                                        const placeEl = document.getElementById(p.place_id);
                                        if (placeEl) {
                                            placeEl.removeEventListener('click', null);
                                            placeEl.setAttribute('fill', '#bababa');
                                            placeEl.style.cursor = 'no-drop';
                                        }
                                    });
                                    selectedPlaces = [];
                                    console.log('Payment Success ! ')
                                } else {
                                    console.log('Payment error ! ');
                                }
                            }, 'json');
                        });
                    });
                });
            }

            document.addEventListener('keypress', (event) => {
                const nomTouche = event.key;
                if (svgParent && nomTouche === 'z') {
                    zoom += 40;
                    svgParent.style.width = zoom + "%";
                    svgParent.style.height = zoom + "%";
                }
                if (svgParent && nomTouche === 'a') {
                    if (zoom > 100)
                        zoom -= 40;
                    svgParent.style.width = zoom + "%";
                    svgParent.style.height = zoom + "%";
                }
            });

            function drawRectangle(positions, angle, color) {
                const polygon = document.createElementNS("http://www.w3.org/2000/svg", "polygon");
                svg.appendChild(polygon);

                polygon.setAttributeNS(null, "stroke", color);
                polygon.setAttributeNS(null, "stroke-width", "0.1");
                polygon.setAttributeNS(null, "fill", color);
                polygon.setAttributeNS(null, "fill-opacity", 0.4);

                positions.forEach((position, index) => {
                    if (!(index % 2)) {
                        const point = svg.createSVGPoint();
                        point.x = position;
                        point.y = positions[index + 1];
                        polygon.points.appendItem(point);
                    }
                });
                polygon.setAttributeNS(null, "transform", "rotate(" + angle + ", " + positions[0] + ", " + positions[1] +
                    " )"
                );

            }

            function drawLine(sectionPosition, fromPositions, toPosition, angle, color) {
                const line = document.createElementNS('http://www.w3.org/2000/svg', 'line');
                line.setAttribute('x1', fromPositions[0]);
                line.setAttribute('y1', fromPositions[1]);
                line.setAttribute('x2', toPosition[0]);
                line.setAttribute('y2', toPosition[1]);
                line.setAttribute("stroke", color);
                line.setAttributeNS(null, "stroke-width", "0.3");
                line.setAttributeNS(null, "transform", "rotate(" + angle + ", " + sectionPosition[0] + ", " + sectionPosition[1] +
                    " )"
                );
                svg.appendChild(line);

            }

            function drawCircle(sectionPosition, position, angle, sectionId, rowId, seatId, place) {
                const circle = document.createElementNS('http://www.w3.org/2000/svg', 'circle');
                circle.setAttribute('id', `${sectionId}|${rowId}|${seatId}`);
                circle.setAttributeNS(null, 'cx', position[0]);
                circle.setAttributeNS(null, 'cy', position[1]);
                circle.setAttributeNS(null, 'r', 0.8);
                circle.setAttributeNS(null, "transform", "rotate(" + angle + ", " + sectionPosition[0] + ", " + sectionPosition[1] +
                    " )"
                );
                circle.setAttributeNS(null, "fill-opacity", 0.9);

                if (place.price != -1 && !place.buyer_id) {
                    circle.setAttribute("fill", "#3b7df2");
                    circle.addEventListener('click', () => {

                        const placesBuyer = document.querySelector('#places-buyer');
                        const placesList = document.querySelector('#places-list');

                        let found = false;
                        selectedPlaces.forEach((p, index) => {
                            if (p === place) {
                                found = true;
                                circle.setAttribute("fill", "#3b7df2");
                                selectedPlaces.splice(index, 1);
                            }
                        });

                        if (!found) {
                            selectedPlaces.push(place);
                            circle.setAttributeNS(null, 'fill', "#0F0");
                        }

                        const totalEl = document.querySelector('#total');


                        if (selectedPlaces.length > 0) {
                            placesBuyer.style.display = 'flex';
                            placesList.style.display = 'none';

                            cardPlacesList.innerHTML = '';
                            let total = 0;
                            selectedPlaces.forEach((p) => {
                                let placeIds = p.place_id.split('|');
                                const div = document.createElement('div');
                                div.innerHTML = '<div class="card justify-content-between  text-light" style="background: rgb(33, 34, 38)">\n' +
                                    '                        <div class="card-body">\n' +
                                    '                            <div class="row">\n' +
                                    '                                <div class="col-md-4">\n' +
                                    '                                    <h5 class="card-title">Sec</h5>\n' +
                                    '                                    <h6 class="card-subtitle mb-2 text-muted">' + placeIds[0] + '</h6>\n' +
                                    '                                </div>\n' +
                                    '                                <div class="col-md-4">\n' +
                                    '                                    <h5 class="card-title text-center">Row</h5>\n' +
                                    '                                    <h6 class="card-subtitle mb-2 text-muted text-center">' + placeIds[1] + '</h6>\n' +
                                    '                                </div>\n' +
                                    '                                <div class="col-md-4">\n' +
                                    '                                    <h5 class="card-title text-right">Seat</h5>\n' +
                                    '                                    <h6 class="card-subtitle mb-2 text-muted text-right">' + placeIds[2] + '</h6>\n' +
                                    '                                </div>\n' +
                                    '                            </div>\n' +
                                    '                        </div>\n' +
                                    '                        <div class="card-footer">\n' +
                                    '                            <div class="row">\n' +
                                    '                                <div class="col-md-6">\n' +
                                    '                                    <h7 class="card-title">Standard Admission</h7>\n' +
                                    '                                </div>\n' +
                                    '                                <div class="col-md-6">\n' +
                                    '                                    <h7 class="card-subtitle text-right">' + parseInt(p.price) + '$ + Fees</h7>\n' +
                                    '                                </div>\n' +
                                    '                            </div>\n' +
                                    '                        </div>\n' +
                                    '                    </div><br>'.trim();
                                cardPlacesList.appendChild(div);
                                total += parseInt(p.price);
                            });
                            totalEl.innerText = `Total: ${total} $`;
                        } else {
                            placesBuyer.setAttribute('style', 'display: none !important');
                            placesList.style.display = 'flex';
                        }
                    });
                    circle.style.cursor = 'pointer';
                } else if (place.buyer_id) {
                    circle.setAttributeNS(null, 'fill', "#bababa");
                    //  circle.setAttributeNS(null, 'fill', "#f236af");
                    circle.style.cursor = 'no-drop';
                } else {
                    circle.setAttributeNS(null, 'fill', "#bababa");
                    //circle.setAttributeNS(null, 'fill', "#F00");
                    circle.style.cursor = 'no-drop';
                }
                svg.appendChild(circle);
            }

            function getPlacePrice(sectionId, rowId, seatId) {
                const place = places.filter(place => {
                    return place.place_id === `${sectionId}|${rowId}|${seatId}`
                });
                return place[0];

            }

            function eventFire(el, etype){
                if (el.fireEvent) {
                    el.fireEvent('on' + etype);
                } else {
                    var evObj = document.createEvent('Events');
                    evObj.initEvent(etype, true, false);
                    el.dispatchEvent(evObj);
                }
            }



            let timeout = null;
            fetch(location.pathname, {
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
            }).then((res) => {
                return res.json()
            }).then((res) => {
                const room = res.room;
                svg.setAttribute("viewBox", `0 0 ${room._width} ${room._height}`);
                places = res.places;
                const scene = room._scene;
                const sittingsSection = room._sittingSections;
                const standingsSection = room._standingSections;

                drawRectangle(scene._positions, scene._rotation, "#f00");


                res.lowest_places.forEach((price) => {
                    const li = document.createElement('li');
                    const span = document.createElement('span');
                    const place = price.place_id.split('|');
                    li.classList.add('list-group-item');
                    li.classList.add('bg-dark');
                    li.classList.add('text-light');
                    li.classList.add('list-group-item-action');
                    li.classList.add('d-flex');
                    li.classList.add('justify-content-between');
                    li.classList.add('align-items-center');
                    li.innerText = `Sec ${place[0]}, Row ${place[1]} `;

                    span.classList.add('badge');
                    span.classList.add('badge-primary');
                    span.classList.add('badge-pill');

                    span.innerText = parseInt(price.price) + ' $';
                    li.appendChild(span);

                    li.addEventListener('click', () => {
                        const placeEl = document.getElementById(price.place_id);
                        if (placeEl) {
                            eventFire(placeEl, 'click')
                        }
                    });

                    lowestPricesEl.appendChild(li);
                });

                Object.keys(sittingsSection).map(function (objectKey, index) {
                    let section = sittingsSection[objectKey];
                    const rotation = section._rotation + section._userRotation;
                    drawRectangle(section._positions, rotation, "#0d3ba0");
                    section._rows.forEach((row) => {
                        drawLine(section._positions, row._posStart, row._posEnd, rotation, '#329fff');
                        row._seats.forEach((seat) => {
                            setTimeout(() => {
                                const placePrice = getPlacePrice(section._idSection, row._idRow, seat._idSeat);
                                drawCircle(section._positions, seat._pos, rotation, section._idSection, row._idRow, seat._idSeat, placePrice);
                            }, 1);
                            if (timeout)
                                clearTimeout(timeout);
                            timeout = setTimeout(() => {
                                const loader = document.querySelector('.loader');

                                loader.style.display = 'none';
                            }, 1000);
                        });
                    });
                });

                Object.keys(standingsSection).map(function (objectKey) {
                    let section = standingsSection[objectKey];
                    const rotation = section._rotation + section._userRotation;
                    drawRectangle(section._positions, rotation, "#0d3ba0");
                });
            });
        })();
    </script>
@endsection