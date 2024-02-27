document.addEventListener("DOMContentLoaded", () => {
    let geolocations = document.querySelectorAll('.geolocation');
    geolocations.forEach(geolocation => {
        geolocation.addEventListener('click', function handleClick(event) {
            getLocation();
        });
    })
    let geomaps = document.querySelectorAll('.geomap');
    geomaps.forEach(geomap => {
        geomap.addEventListener('click', function handleClick(event) {
            linkLocation();
        });
    })
    function getLocation() {
        let output = event.target.dataset.target;
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position)
                {
                    if (output === 'jform_longitude')
                        document.getElementById(output).value = position.coords.longitude;
                    else
                        document.getElementById(output).value = position.coords.latitude;
                }
            );
        } else {
            document.getElementById(output).value = "Geolocation is not supported by this browser.";
        }
    }
    function linkLocation() {
        long = document.getElementById('jform_longitude').value;
        lat =  document.getElementById('jform_latitude').value;

        if (long && lat)
            window.open('https://www.google.com/maps/search/?api=1&query=' + lat + ',' + long, 'SichtweitenMap');
        else alert('Bitte Längen- und Breitengrad angeben.')
    }
})
