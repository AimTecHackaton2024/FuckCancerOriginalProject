function _fcSearch() {
    const $searchBox = $('#search_input');
    const $categoryList = $('#category_list');
    const $resultsContainer = $('#results_container');
    const $clearButton = $('#map_clear_search_box');
    let searchQuery = '';
    let results = {};
    const venues = fcVenues.load();
    let xhr = undefined;
    let blurTimeout = undefined;

    this.initDomEvents = () => {
        $searchBox.on('keyup search change', () => {
            searchQuery = $searchBox.val();

            if (searchQuery.trim().length > 0) {
                $categoryList.hide();
                $clearButton.show();
            } else {
                $categoryList.show();
                $clearButton.hide();
            }
        });

        $searchBox.on('search', () => {
            this.search(fcMap.getViewport());
        });

        $searchBox.on('focus', function() {
            if (undefined !== blurTimeout) {
                clearTimeout(blurTimeout);
            }

            if (searchQuery.trim().length > 0) {
                return;
            }

            $categoryList.show();
        }).on('blur', function(e) {
            blurTimeout = setTimeout(() => { $categoryList.hide(); }, 100);
        }).trigger('focus');


        $('body')
            .on('click', '#category_list ul li', (e) => {
                $searchBox.val($(e.target).data('title') + ' ').trigger('search').trigger('focus');
            })
            .on('click', '.results-item', function(e) {
                if ($(e.target).is('a')) {
                    return;
                }

                fcSearch.fixScrollToResult(venues[$(this).data('id')]);
                fcVenues.showDetail(venues[$(this).data('id')], isMobile());
            })
        ;

        $clearButton.on('click', function() {
            $searchBox.val('').trigger('search');
        });
    }

    this.search = (mapViewport, _searchQuery = null) => {
        const self = this;
        if (null === _searchQuery) {
            _searchQuery = searchQuery;
        }

        if (undefined !== xhr) {
            xhr.abort();
        }

        if (0 === _searchQuery.trim().length) {
            fcMap.filterMarkers(null);
        }

        xhr = $.ajax({
            url: `/map/search`,
            data: {
                q: _searchQuery,
                from_lat: mapViewport[0].lat,
                from_lng: mapViewport[0].lng,
                to_lat: mapViewport[1].lat,
                to_lng: mapViewport[1].lng
            },
            dataType: 'json',
            success: (data) => {
                results = {};

                data.forEach((id) => {
                    if (undefined === venues[id]) {
                        return;
                    }

                    results[id] = venues[id]
                });

                if (_searchQuery.trim().length > 0) {
                    fcMap.filterMarkers(data);
                }
                self.drawResults();
            }
        });
    }

    this.drawResults = () => {
        if (0 === Object.keys(results).length) {
            $resultsContainer.html('<span class="results-message">Nebyly nalezeny žádné výsledky.</span>');
            return;
        }

        $resultsContainer.html('');
        Object.values(results).forEach((result) => {
            $resultsContainer.append(template('#result_item_template', fcVenues.prepareTemplateVariables(result)));
        });
    };

    this.fixScrollToResult = function(venue) {
        const $resultsContainer = $('#results_container');
        const $sidebar = $resultsContainer.parent();
        const $listItem = $resultsContainer.find('.results-item[data-id="'+ venue.id +'"]');

        if ($listItem.position().top < $sidebar.scrollTop()) {
            $sidebar.animate({ scrollTop: $listItem.position().top - $resultsContainer.position().top });
        } else if($listItem.position().top + $listItem.height() > $sidebar.height()) {
            const diff = ($listItem.position().top + $listItem.height()) - $sidebar.height();
            $sidebar.animate({ scrollTop: $sidebar.scrollTop() + diff });
        }
    }

    this.initDomEvents();
    return this;
}

const fcSearch = _fcSearch();