import Alpine from 'alpinejs';
import {data} from "./clean/modules/data";
import {tabsData} from "./clean/modules/tabsData";
import {accordionData} from "./clean/modules/accordionData";
import {lightboxData} from "./clean/modules/lightboxData";
import {sliderData} from "./clean/modules/sliderData";
import {modalData} from "./clean/modules/modalData";
import {alertData} from './clean/modules/alertData';
import {progressBarData} from "./clean/modules/progressBarData";
import {animateData} from "./clean/modules/animateData";
import {filterData} from './clean/modules/filterData';
import {dropdownData} from "./clean/modules/dropdownData";
import {ajaxSearchData} from "./clean/modules/ajaxSearchData";
import {tabbedImagesData} from "./clean/modules/tabbedImagesData";

import './custom';

window.Alpine = Alpine;

Alpine.data('data', data);
Alpine.data('dropdownData', dropdownData);
Alpine.data('data', data);
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

Alpine.start();






