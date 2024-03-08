function _fcVenues() {
    let venues = window.venues || [];
    let currentVenue = null;
    let $venueDetailContainerMobile;
    const $resultsContainer = $('#results_container');
    const $venueDetailContainer = $('#venue_detail_container');
    const $sidebarSearchContainer = $('.map-sidebar__search-container');

    function _load() {
        $.ajax({
            url: '/map/load',
            async: false,
            dataType: 'json',
            success: function(data) {
                venues = window.venues = data;
            }
        });
    }

    this.initEvents = function()
    {
        $venueDetailContainer.find('.venue-detail__close').on('click', () => this.closeDetail());

        $sidebarSearchContainer.on('scroll', function() {
            if (null === currentVenue) {
                return;
            }

            moveDetailKnob(venues[currentVenue]);
        });
    }

    this.load = function() {
        if (0 === venues.length) { _load(); }

        return venues;
    };

    function scrollToVenue(venue)
    {
        const $sidebar = $resultsContainer.parent();
        const $listItem = $resultsContainer.find('.results-item[data-id="'+ venue.id +'"]');
        const resultsContainerTop = $resultsContainer.offset().top;
        const listItemTop = $listItem.offset().top;
        const listItemHeight = $listItem.height();
        const centerOffset = Math.round($sidebar.height() / 2);

        const scrollTop = listItemTop - resultsContainerTop - centerOffset + listItemHeight;

        if (isMobile()) {
            $('html,body').animate({scrollTop: listItemTop});
        } else {
            $sidebar.animate({scrollTop: scrollTop}, {
                progress: function () {
                    moveDetailKnob(venue);
                }
            });
        }
    }

    function moveDetailKnob(venue)
    {
        const $sidebar = $resultsContainer.parent();
        const $listItem = $resultsContainer.find('.results-item[data-id="'+ venue.id +'"]');
        const $categoryList = $('#category_list');
        const categoryListHeight = $categoryList.height();

        if ($listItem.length) {
            const knobY = $listItem.offset().top - $sidebar.offset().top - ($categoryList.is(':visible') ? categoryListHeight : 0) + Math.round($listItem.height() / 2) + 24;
            $venueDetailContainer.css('--knob-y', `${knobY}px`);
        }
    }

    function createMobileVenueDetail(venue)
    {
        const $listItem = $resultsContainer.find('.results-item[data-id="'+ venue.id +'"]');
        $venueDetailContainerMobile = $('<div class="map-sidebar__detail-container--mobile" id="venue_detail_container__mobile"><button class="venue-detail__close">Zavřít</button><div class="venue-detail__inner"></div></div>');
        $venueDetailContainerMobile.insertAfter($listItem);
        $venueDetailContainerMobile.find('.venue-detail__close').on('click', () => this.closeDetail());
        return $venueDetailContainerMobile;
    }

    this.showDetail = function(venue, scroll = false)
    {
        if (venue.id === currentVenue) {
            return;
        }

        if (isMobile() && $venueDetailContainerMobile !== undefined) {
            $venueDetailContainerMobile.remove();
        }

        const $_detailContainer = isMobile() ? createMobileVenueDetail(venue) : $venueDetailContainer;
        this.deactivateCurrentVenue();
        currentVenue = venue.id;
        $_detailContainer.find('.venue-detail__inner').html(
            template('#venue_detail_template', this.prepareTemplateVariables(venue))
        );

        venue._marker.content.classList.add('active');
        venue._marker.zIndex = 99999999;
        window.fcMap.removeMarkerFromClusterer(venue._marker);

        if (scroll || isMobile()) {
            scrollToVenue(venue);
        } else if (!isMobile()) {
            moveDetailKnob(venue);
        }

        $_detailContainer.addClass('map-sidebar__detail-container--open');
        Fancybox.bind('[data-fancybox="venue-photos"]');
    }

    this.closeDetail = function()
    {
        $venueDetailContainer.removeClass('map-sidebar__detail-container--open');
        $venueDetailContainerMobile.removeClass('map-sidebar__detail-container--open');
        this.deactivateCurrentVenue();
        $venueDetailContainerMobile.remove();
        currentVenue = null;

        setTimeout(() => {
            $venueDetailContainer.find('.venue-detail__inner').html('');
        }, 300);
    }

    this.deactivateCurrentVenue = function()
    {
        if (null === currentVenue) {
            return;
        }

        const venue = venues[currentVenue];
        venue._marker.zIndex = null;
        venue._marker.content.classList.remove('active');
        window.fcMap.addMarkerToClusterer(venue._marker);
    }

    this.prepareTemplateVariables = (venue) => {
        return {
            id: venue.id,
            title: venue.title,
            perex: venue.perex,
            article: venue.article,
            address_link: `https://www.google.com/maps/place/${venue.street},+${venue.zip}+${venue.city}`,
            address: `${venue.street}, ${venue.zip} ${venue.city}`,
            homepage: venue.homepage,
            phone: venue.phone,
            email: venue.email,
            facebook: venue.social_links.facebook,
            instagram: venue.social_links.instagram,
            twitter: venue.social_links.twitter,
            youtube: venue.social_links.youtube,
            linkedin: venue.social_links.linkedin,
            mainPhotoThumbnail: venue.main_photo_thumbnail || 'https://placehold.co/440x248',
            mainPhoto: venue.main_photo || null,
            photos: venue.photos,
            photosCount: venue.photos.length
        };
    }

    return this;
}

const fcVenues = _fcVenues();