@extends('layouts.app')

@section('content')

    <div class="container">
        <div class="lds-ripple">
            <div></div>
            <div></div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-8 svg-wrapper">
                <div class="svg-parent">
                    <svg id="svg" class="svg">
                    </svg>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Buy ticket</h5>
                        <h6 class="card-subtitle mb-2 text-muted">Event : {{ $event->name }}</h6>
                        <p class="card-text">
                            Section: <span id="sectionId">-</span><br>
                            Row: <span id="rowId">-</span><br>
                            Seat: <span id="seatId">-</span>
                        </p>

                        <p class="card-text">
                            Price: <span id="price">-</span>
                        </p>
                    </div>
                    <div class="card-footer">
                        <div id="dropin-container"></div>
                        <button id="submit-button" class="btn-success btn">Buy Ticket</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://js.braintreegateway.com/web/dropin/1.8.1/js/dropin.min.js"></script>
    <script type="module">


        (function () {
            const button = document.querySelector('#submit-button');
            let selectedPlace = null;


            button.addEventListener('click', showPayment);

            function showPayment() {
                if (!selectedPlace) {
                    return;
                }
                braintree.dropin.create({
                    authorization: "{{ Braintree_ClientToken::generate() }}",
                    container: '#dropin-container'
                }, function (createErr, instance) {
                    button.addEventListener('click', function () {
                        instance.requestPaymentMethod(function (err, payload) {
                            $.get('/payment/process/' + selectedPlace.id, {payload}, function (response) {
                                if (response.success) {
                                    alert('Payment successfull!');
                                } else {
                                    alert('Payment failed');
                                }
                            }, 'json');
                        });
                    });
                });
            }


            const svg = document.querySelector('#svg');
            const svgParent = document.querySelector('.svg-parent');

            let places = null;
            let zoom = 100;

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
                line.setAttribute('id', 'line2');
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
                        selectedPlace = place;

                        const sectionIdEl = document.querySelector('#sectionId');
                        const rowIdEl = document.querySelector('#rowId');
                        const seatIdEl = document.querySelector('#seatId');
                        const priceEl = document.querySelector('#price');

                        sectionIdEl.innerHTML = sectionId;
                        rowIdEl.innerHTML = rowId;
                        seatIdEl.innerHTML = seatId;
                        priceEl.innerHTML = place.price + ' $';

                    });
                    circle.style.cursor = 'pointer';
                } else if (place.buyer_id) {
                    circle.setAttributeNS(null, 'fill', "#f236af");
                    circle.style.cursor = 'no-drop';
                } else {
                    circle.setAttributeNS(null, 'fill', "#f23b3b");
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
                drawRectangle(scene._positions, scene._rotation, "#f00");

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
                        });
                    });
                });


            });
        })();
    </script>
@endsection