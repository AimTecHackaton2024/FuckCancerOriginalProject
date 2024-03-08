function _GeolocationControl(map, defaultZoom) {
    var self = this;

    self._init = function(map, defaultZoom) {
        self.map = map;
        self.defaultZoom = defaultZoom;

        // Create the DIV to hold the control and call the constructor passing in this DIV
        self.geolocationDiv = document.createElement('div');
        self.controller = new self.GeolocationController(self.geolocationDiv, self.map);

        self.enabled = false;
        self.marker = null;
        self.circle = null;

    };

    self.GeolocationController = function(controlDiv, map) {


    };

    self.geolocate = function() {
        self.enabled = !self.enabled;
        document.getElementById('geolocationIcon').style.backgroundPosition = self.enabled ? '-18px' : '';

        if (!self.enabled) {
            self.marker.setMap(null);
            self.circle.setMap(null);
            self.enabled = false;
            return;
        }

        if (navigator.geolocation) {

            navigator.geolocation.getCurrentPosition(function(position) {

                var pos = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
                self.map.setCenter(pos);
                if (self.defaultZoom) {
                    self.map.setZoom(self.defaultZoom);
                }
            }, void 0, {
                enableHighAccuracy: true
            });
        }
    };

    self._init(map, defaultZoom);
}

function _fcMap($container, data)
{
    let map;
    let venues;
    let markers;
    let markerCluster;

    function init() {
        map = new google.maps.Map($container[0], {
            mapTypeControl: false,
            streetViewControl: false,
            fullscreenControl: false,
            mapId: "9f72e3cb3bf5e05c",
            gestureHandling: 'greedy',
        });

        let czBounds = new google.maps.LatLngBounds(
            new google.maps.LatLng(48.5553052842, 12.2401111182),
            new google.maps.LatLng(51.1172677679, 18.8531441586)
        );
        map.fitBounds(czBounds);

        if (isMobile()) {
            map.setZoom(17);
        }

        initMapEvents();
        initMarkers();
        initMapGeolocationControl();
        // loadGeolocation();
        fcVenues.initEvents();
    }

    function initMapGeolocationControl()
    {
        if (!(navigator.geolocation)) return;

        // Set CSS for the control btn
        var geolocationBtn = document.createElement('div');
        geolocationBtn.style.backgroundColor = "#fff";
        geolocationBtn.style.border = "2px solid #fff";
        geolocationBtn.style.padding = "9px"; //"3px";
        geolocationBtn.style.borderRadius = "2px";
        geolocationBtn.style.boxShadow = "rgba(0,0,0,0.298039) 0 1px 4px -1px";
        geolocationBtn.style.marginRight = "10px";
        geolocationBtn.style.cursor = "pointer";
        geolocationBtn.id = "geolocationBtn";

        // Set CSS for the control icon
        var geolocationIcon = document.createElement('div');
        geolocationIcon.style.backgroundSize = "18px 18px";
        geolocationIcon.style.width = "18px";
        geolocationIcon.style.height = "18px";
        geolocationIcon.style.opacity = 0.9;
        geolocationIcon.style.backgroundImage = "url(/img/geolocation.png)";
        geolocationIcon.id = "geolocationIcon";

        geolocationBtn.appendChild(geolocationIcon);

        // Setup the click event listeners to geolocate user
        google.maps.event.addListener(geolocationBtn, 'click', loadGeolocation);
        map.controls[google.maps.ControlPosition.RIGHT_BOTTOM].push(geolocationBtn);
    }

    function initMapEvents()
    {
        google.maps.event.addListener(map, 'idle', onChangeViewport );
    }

    function onChangeViewport()
    {
        fcSearch.search(getViewport());
    }

    function getViewport()
    {
        const mapBoundNE = map.getBounds().getNorthEast();
        const mapBoundSW = map.getBounds().getSouthWest();

        return [
            { lat: mapBoundSW.lat(), lng: mapBoundSW.lng() },
            { lat: mapBoundNE.lat(), lng: mapBoundNE.lng() },
        ];
    }

    function initMarkerClusterer()
    {
        markerCluster = new markerClusterer.MarkerClusterer({
            map,
            markers,
            algorithm: new markerClusterer.GridAlgorithm({}),
            renderer: {
                render: function ({ count, position }) {
                    const pinElement = new google.maps.marker.PinElement({
                        glyph: (() => {
                            const inner = document.createElement('div');
                            inner.classList.add('markercluster-number');
                            inner.innerHTML = count;
                            return inner;
                        })(),
                        scale: 1.4,
                        glyphColor: '#ffffff',
                        background: '#c98bdb',
                        borderColor: '#c98bdb',
                    });

                    return new google.maps.marker.AdvancedMarkerElement({
                        map: map,
                        position,
                        content: pinElement.element,
                        zIndex: Number(google.maps.Marker.MAX_ZINDEX) + count
                    });
                }
            }
        });
    }

    function addMarkerToClusterer(marker)
    {
        markerCluster.addMarker(marker);
    }

    function removeMarkerFromClusterer(marker)
    {
        markerCluster.removeMarker(marker);
        marker.setMap(map);
    }

    function initMarkers()
    {
        venues = fcVenues.load();

        markers = Object.values(venues).map(createMarker);
        initMarkerClusterer();
    }

    function filterMarkers(ids)
    {
        Object.values(venues).forEach((venue) => {
            if (null === ids || -1 !== ids.indexOf(venue.id)) {
                venue._marker.setMap(map);
                markerCluster.addMarker(venue._marker);
            } else {
                venue._marker.setMap(null);
                markerCluster.removeMarker(venue._marker);
            }
        });
    }

    function createMarker(venue)
    {
        let marker = new google.maps.marker.AdvancedMarkerElement({
            map: map,
            position: venue.location,
            title: venue.title || "",
            content: getMarkerIconByCategory(venue.category).element,
        });

        venue._marker = marker;
        google.maps.event.addListener(marker, 'click', function() {
            fcVenues.showDetail(venue, true);
        });

        return marker;
    }

    function getMarkerIconByCategory(category)
    {
        const icon = document.createElement('div');
        if (null !== category) {
            icon.innerHTML = `<i class="fa fa-xl fa-${category.icon}"></i>`;
        }

        const pin = new google.maps.marker.PinElement({
            glyph: icon,
            scale: 1.2,
            glyphColor: '#ffffff',
            background: '#c98bdb',
            borderColor: '#c98bdb',
        });

        return pin;
    }

    function loadGeolocation()
    {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function (position) {
                map.setCenter(new google.maps.LatLng(position.coords.latitude, position.coords.longitude));
                map.setZoom(11);
                onChangeViewport();
            }, function () {
                console.log("Your browser not support Geolocation");
                onChangeViewport();
            }, {
                enableHighAccuracy: true
            });
        }
    }

    init();

    this.getViewport = getViewport;
    this.filterMarkers = filterMarkers;
    this.addMarkerToClusterer = addMarkerToClusterer;
    this.removeMarkerFromClusterer = removeMarkerFromClusterer;
    return this;
}

function initMap() {
    let $mapContainer = $('.map-container');
    if ($mapContainer.length) {
        window.fcMap = _fcMap($mapContainer, {});
    }
}

function isMobile()
{
    return $(window).width() < 991;
}