import { createApp } from 'vue'
import App from './App.vue'
import router from './router'

import PrimeVue from 'primevue/config'

import 'primevue/resources/themes/saga-blue/theme.css'
import 'primevue/resources/primevue.min.css'
import 'primeicons/primeicons.css'
import 'primeflex/primeflex.css'
import Button from 'primevue/button'
import InputText from 'primevue/inputtext'
import Card from 'primevue/card'
import Divider from 'primevue/divider'
import ProgressSpinner from 'primevue/progressspinner'
import Checkbox from 'primevue/checkbox'
import InputNumber from 'primevue/inputnumber'
import Message from 'primevue/message'
import Tag from 'primevue/tag'
import Dialog from 'primevue/dialog'
import Textarea from 'primevue/textarea'
import RadioButton from 'primevue/radiobutton'
import DataTable from 'primevue/datatable'
import Column from 'primevue/column'
import Dropdown from 'primevue/dropdown'

const app = createApp(App)

app.use(router)
app.use(PrimeVue)

app.component('Button', Button)
app.component('InputText', InputText)
app.component('Checkbox', Checkbox)
app.component('InputNumber', InputNumber)
app.component('Message', Message)
app.component('Button', Button)
app.component('Card', Card)
app.component('Divider', Divider)
app.component('ProgressSpinner', ProgressSpinner)
app.component('Tag', Tag)
app.component('Dialog', Dialog)
app.component('Textarea', Textarea)
app.component('RadioButton', RadioButton)
app.component('DataTable', DataTable)
app.component('Column', Column)
app.component('Dropdown', Dropdown)


app.mount('#app')