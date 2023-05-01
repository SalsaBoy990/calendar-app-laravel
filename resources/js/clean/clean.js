import Alpine from 'alpinejs'

import {data} from "./modules/data";
import {tabsData} from "./modules/tabsData";
import {accordionData} from "./modules/accordionData";
import {lightboxData} from "./modules/lightboxData";
import {sliderData} from "./modules/sliderData";
import {modalData} from "./modules/modalData";
import {alertData} from './modules/alertData';
import {progressBarData} from "./modules/progressBarData";
import {animateData} from "./modules/animateData";
import {filterData} from './modules/filterData';
import {dropdownData} from "./modules/dropdownData";
import {ajaxSearchData} from "./modules/ajaxSearchData";
import {tabbedImagesData} from "./modules/tabbedImagesData";

window.Alpine = Alpine

Alpine.data('data', data);
Alpine.data('dropdownData', dropdownData);
Alpine.data('ajaxSearchData', ajaxSearchData);
Alpine.data('modalData', modalData);
Alpine.data('progressBarData', progressBarData);
Alpine.data('animateData', animateData);
Alpine.data('filterData', filterData);
Alpine.data('alertData', alertData);
Alpine.data('sliderData', sliderData);
Alpine.data('lightboxData', lightboxData);
Alpine.data('accordionData', accordionData);
Alpine.data('tabsData', tabsData);
Alpine.data('tabbedImagesData', tabbedImagesData);

Alpine.start()
