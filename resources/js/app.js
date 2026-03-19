import './bootstrap';
import './native';

import Alpine from 'alpinejs';
import Collapse from '@alpinejs/collapse';

Alpine.plugin(Collapse);

window.Alpine = Alpine;

Alpine.start();
