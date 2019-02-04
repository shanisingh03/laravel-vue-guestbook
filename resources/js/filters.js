// Import Moment JS
import moment from 'moment';

// Capitalize The Text
Vue.filter('upText',function(value){
    if (!value) return ''
    value = value.toString()
    return value.charAt(0).toUpperCase() + value.slice(1)
});

// Readable Date Format
Vue.filter('readableDate',function(date){
   return moment(date).format('MMMM Do YYYY'); // February 2nd 2019, 11:40:23 pm
});
