import { createApp } from 'vue'
import App from './App.vue'
import router from './router'
import Datepicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css';
import Notification from './components/lib/Notification.vue';

import './assets/main.css'

const app = createApp(App);

app.use(router);
app.component('Notification', Notification);
app.component('Datepicker', Datepicker);

app.mount('#app')
