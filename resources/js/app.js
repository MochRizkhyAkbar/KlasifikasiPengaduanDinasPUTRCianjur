import $ from 'jquery';
window.$ = window.jQuery = $;

import "datatables.net-dt/css/dataTables.dataTables.css";
import DataTable from "datatables.net-dt";

import { Chart, registerables } from 'chart.js';
Chart.register(...registerables);
window.Chart = Chart;

// Pastikan Alpine tetap berjalan
import Alpine from 'alpinejs';
window.Alpine = Alpine;
Alpine.start();

// import Swal from "sweetalert2";
// import "sweetaler2/dist/sweetalert2.min.css";
// window.Swal = Swal;

// window.showToast = function (type,message){
//     Swal.fire({
//         toast: true,
//         position: "top-end",
//         icon: type,
//         tittle: message,
//         showConfirmButton: false,
//         timer: 3000,
//         timerProgressBarr: true
//     });
// };

window.buatChart = function(id, labels, data, labelText, color) {
    const ctx = document.getElementById(id);
    if (ctx) {
        new Chart(ctx.getContext('2d'), {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: labelText,
                    data: data,
                    backgroundColor: color,
                    borderRadius: 5
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false, // Penting untuk mengikuti tinggi div
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0,
                            stepSize: 1 // Memaksa kenaikan angka 1 demi 1
                        }
                    }
                }
            }
        });
    }
};

$(document).ready(function () {
    // Inisialisasi DataTables
    if ($('#tabelPengaduan').length) {
        $('#tabelPengaduan').DataTable({
            responsive: true,
            pageLength: 10,
            lengthChange: true,
            order: [],
            columnDefs: [
                {
                    "targets": 7,
                    "orderable": true
                }
            ],
            language: {
                searchPlaceholder: "Cari...",
                search: "",
            },
        });
    }
});
