@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Event List</div>
                    <div class="card-body">
                        Event :{{ $event->name }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <canvas id="event-canvas"></canvas>



    <style>
        #event-canvas {
            position: absolute;
            top: 0;
            left: 0;
            bottom: 0;
        }

        html, body {
            overflow: hidden;
        }
    </style>


    <script type="module">
        (function () {


            const canvas = document.querySelector('#event-canvas');
            canvas.width = window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth;
            canvas.height = window.innerHeight || document.documentElement.clientHeight || document.body.clientHeight;
            const ctx = canvas.getContext("2d");
            ctx.scale(5, 5);


            function drawRectangle(positions, angle, color) {
                ctx.fillStyle = color;
                ctx.beginPath();
                ctx.save();
                ctx.translate(positions[0], positions[1]);
                ctx.rotate(angle);
                ctx.translate(-positions[0], -positions[1]);
                positions.forEach((position, index) => {
                    if (!(index % 2)) {
                        ctx.lineTo(position, positions[index + 1]);
                    }
                });
                ctx.restore();
                ctx.closePath();
                ctx.fill();
            }

            function drawLine(sectionPosition, fromPositions, toPosition, angle, color) {

                ctx.beginPath();
                ctx.save();
                ctx.translate(sectionPosition[0], sectionPosition[1]);
                ctx.rotate(angle);
                ctx.translate(-sectionPosition[0], -sectionPosition[1]);
                ctx.lineTo(fromPositions[0], fromPositions[1]);
                ctx.lineTo(toPosition[0], toPosition[1]);
                ctx.restore();
                ctx.closePath();
                ctx.lineWidth = 0.3;
                ctx.strokeStyle = color;
                ctx.stroke();
            }

            function drawCircle(sectionPosition, position, angle, color) {
                ctx.beginPath();
                ctx.save();
                ctx.translate(sectionPosition[0], sectionPosition[1]);
                ctx.rotate(angle);
                ctx.translate(-sectionPosition[0], -sectionPosition[1]);
                ctx.arc(position[0], position[1], 0.26, 0, 2 * Math.PI, false);
                ctx.fillStyle = color;
                ctx.fill();
                ctx.lineWidth = 0.7;
                ctx.strokeStyle = color;
                ctx.stroke();
                ctx.restore();
                ctx.closePath();


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
                const scene = room._scene;
                const sittingsSection = room._sittingSections;
                drawRectangle(scene._positions, scene._rotation, "#f00");

                Object.keys(sittingsSection).map(function (objectKey, index) {
                    let section = sittingsSection[objectKey];
                    console.log(section);
                    const rotation = section._rotation + section._userRotation;
                    drawRectangle(section._positions, rotation, "#1aff00")
                    section._rows.forEach((row) => {
                        console.log(row);
                        drawLine(section._positions, row._posStart, row._posEnd, rotation, '#121bff');
                        row._seats.forEach((seat) => {
                            drawCircle(section._positions, seat._pos, rotation, "#ffc901")
                        });
                    });
                });


            });
        })();
    </script>
@endsection
