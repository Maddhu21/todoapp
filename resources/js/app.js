import './bootstrap';
import * as bootstrap from 'bootstrap';
import $ from 'jquery';
import toastr from 'toastr';
import 'toastr/build/toastr.min.css';

// Make jQuery available globally
window.$ = window.jQuery = $;

//Init toastr
window.toastr = toastr;

window.bootstrap = bootstrap;
