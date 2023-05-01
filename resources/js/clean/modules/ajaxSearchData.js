/*
*  Ajax search in entries action
*
* */
export function ajaxSearchData() {

    const config = {
        method: 'GET', // *GET, POST, PUT, DELETE, etc.
        mode: 'same-origin', // no-cors, *cors, same-origin
        cache: 'no-cache', // *default, no-cache, reload, force-cache, only-if-cached
        credentials: 'same-origin', // include, *same-origin, omit
        headers: {
            'Content-Type': 'application/json'
            // 'Content-Type': 'application/x-www-form-urlencoded',
        },
        redirect: 'follow', // manual, *follow, error
        referrerPolicy: 'no-referrer', // no-referrer, *no-referrer-when-downgrade, origin, origin-when-cross-origin, same-origin, strict-origin, strict-origin-when-cross-origin, unsafe-url
        // body: JSON.stringify(params) // body data type must match "Content-Type" header
    };

    // https://css-tricks.com/snippets/javascript/strip-html-tags-in-javascript/
    const stripTagsRegex =  new RegExp('(<([^>]+)>)', 'ig');

    return {
        searchTerm: '',
        panelOpen: false,
        count: 0,
        results: [],

        searchByTitle(apiPath, columnName = 'title') {
            // decrease the number of HTTP calls
            if (this.searchTerm.length >= 3) {
                const key = columnName + ' LIKE';
                let args = {};
                args[key] = '%' + this.searchTerm + '%';

                const queryParams = new URLSearchParams(args);

                // Send request to the api endpoint
                fetch(apiPath + '?' + queryParams.toString(), config)
                    .then((response) => response.json())
                    .then((data) => {
                        if (!data) {
                            return;
                        }
                        let newData = [];
                        let searchTermRegex = new RegExp(`${this.searchTerm}`, "ig"); // search for all instances

                        for (let i = 0; i < data.length; i++) {
                            // remove html tags
                            let title = data[i].title.replaceAll(stripTagsRegex, '');
                            let content = data[i].content.replaceAll(stripTagsRegex, '');

                            // add marks to highlight the search term in the text
                            // https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/String/replace#specifying_a_string_as_the_replacement
                            title = title.replaceAll(searchTermRegex, '<mark>$&</mark>');
                            content = content.replaceAll(searchTermRegex, '<mark>$&</mark>');

                            newData[i] = data[i];
                            newData[i].title = title;
                            newData[i].content = content;
                        }

                        this.panelOpen = true;
                        this.results = newData;
                        this.count = newData.length;

                    });
            } else {
                // this.searchTerm = '';
                this.initializeProperties();
            }
        },

        clearSearch() {
            this.searchTerm = '';
            this.initializeProperties();
        },

        togglePanel() {
            this.panelOpen = !this.panelOpen;
        },

        initializeProperties() {
            this.count = 0;
            this.panelOpen = false;
            this.results = [];
        }

    }
}
