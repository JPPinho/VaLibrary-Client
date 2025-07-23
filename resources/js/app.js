import $ from 'jquery';
window.$ = window.jQuery = $;

import './bootstrap';
import '../scss/app.scss';

import TomSelect from "tom-select";
import "tom-select/dist/css/tom-select.bootstrap5.css";

$(function() {
    $('.main-nav .dropdown').hover(
        function() {
            $(this).find('.dropdown-menu').addClass('is-active');
        },
        function() {
            $(this).find('.dropdown-menu').removeClass('is-active');
        }
    );
});

document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.tom-select').forEach((el) => {
        new TomSelect(el, {});
    });
});
