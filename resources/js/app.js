import $ from 'jquery';
window.$ = window.jQuery = $;

import './bootstrap';
import '../scss/app.scss';

import TomSelect from "tom-select";
import "tom-select/dist/css/tom-select.bootstrap5.css";
import { Html5QrcodeScanner } from "html5-qrcode";

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
    if (document.querySelector('.tom-select')) {
        new TomSelect('.tom-select', {
            plugins: ['remove_button'], // Adds a little 'x' to remove selected items
            create: false, // Set to true if you want to allow creating new authors on the fly
            // For more options, visit the Tom Select documentation
        });
    }

    // You can also initialize single selects for a consistent look
    if (document.querySelector('.tom-select-single')) {
        new TomSelect('.tom-select-single', {
            create: false,
        });
    }

    if (document.getElementById('scanner-region')) {
        let isScanning = false;

        function onScanSuccess(decodedText, decodedResult) {
            if (isScanning) return; // Prevent multiple submissions
            isScanning = true;

            // Stop the scanner
            html5QrcodeScanner.clear();

            // Populate the hidden form and submit it
            document.getElementById('isbn-input').value = decodedText;
            document.getElementById('isbn-form').submit();
        }

        function onScanFailure(error) {
            console.log(error);
        }

        let html5QrcodeScanner = new Html5QrcodeScanner(
            "scanner-region",
            { fps: 1, qrbox: { width: 250, height: 150 } },
            true // verbose = false
        );

        html5QrcodeScanner.render(onScanSuccess, onScanFailure);
    }
});

