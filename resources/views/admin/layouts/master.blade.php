<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>@yield('title')</title>

    @include('inc.head')

    <!-- Google Fonts -->
    <link href="https://fonts.gstatic.com" rel="preconnect">
    <link
        href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i"
        rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="{{ asset('admin/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('admin/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('admin/vendor/boxicons/css/boxicons.min.css') }}" rel="stylesheet">
    <link href="{{ asset('admin/vendor/quill/quill.snow.css') }}" rel="stylesheet">
    <link href="{{ asset('admin/vendor/quill/quill.bubble.css') }}" rel="stylesheet">
    <link href="{{ asset('admin/vendor/remixicon/remixicon.css') }}" rel="stylesheet">
    <link href="{{ asset('admin/vendor/simple-datatables/style.css') }}" rel="stylesheet">

    <!-- Template Main CSS File -->
    <link href="{{ asset('admin/css/style.css') }}" rel="stylesheet">

    {{--    <script src="https://cdnjs.cloudflare.com/ajax/libs/autonumeric/4.10.9/autoNumeric.min.js"--}}
    {{--            integrity="sha512-cVa6IRDb7tSr/KZqJkq/FgnWMwBaRfi49qe3CVW4DhYMU30vHAXsIgbWu17w/OuVa0jyGly6/kJvcIzr8vFrDQ=="--}}
    {{--            crossorigin="anonymous" referrerpolicy="no-referrer"></script>--}}

    {{--    <script src="https://cdn.jsdelivr.net/npm/cleave.js"></script>--}}

    <!-- Sweet Alert -->
    <script src="https://unpkg.com/sweetalert2@7.18.0/dist/sweetalert2.all.js"></script>
    <!-- Jquery -->
    <!-- jQuery (phải nằm trước) -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" crossorigin="anonymous"></script>

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>
    <!-- Select2 Bootstrap 5 Theme -->
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.2.0/dist/select2-bootstrap-5-theme.min.css"
          rel="stylesheet"/>
    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <!-- DataTables core -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

    <!-- Buttons extension -->
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap5.min.js"></script>

    <!-- Export to Excel/CSV/PDF -->
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>

    <!-- Dependencies -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>


    <style>
        .table-responsive {
            overflow-x: auto !important;
            -webkit-overflow-scrolling: touch;
        }
    </style>
    <script>
        async function confirmDelete(type) {
            let countChecked = $('input[name="check_item[]"]:checked').length;

            if (countChecked === 0) {
                alert('Vui lòng chọn lựa chọn muuốn xóa!');
                return false;
            }

            if (!confirm('Bạn có chắc chắn muốn xóa các lựa chọn không?')) {
                return false;
            }

            await deleteAllItemSelected(type);
        }

        async function deleteAllItemSelected(type) {
            const list_id = [];
            $('input[name="check_item[]"]:checked').each(async function () {
                list_id.push($(this).val());
            })

            const url = `{{ route('api.admin.delete.items') }}`;

            $.ajax({
                url: url,
                method: 'DELETE',
                async: false,
                data: {
                    list_id: list_id,
                    type: type,
                    _token: '{{ csrf_token() }}'
                },
                success: function (response) {
                    console.log(response)
                    alert('Xóa thành công!');
                    window.location.reload();
                },
                error: function (exception) {
                    console.log(exception)
                }
            });
        }
    </script>
    <style>
        input[readonly] {
            cursor: not-allowed;
        }

        input[readonly]:focus {
            outline: none;
            box-shadow: none;
        }
    </style>
</head>

<body>

<!-- ======= Header ======= -->
@include('admin.layouts.header')
<!-- End Header -->

<!-- ======= Sidebar ======= -->
@include('admin.layouts.sidebar')
<!-- End Sidebar-->

@include('sweetalert::alert')

<!-- ======= Main ======= -->
<main id="main" class="main">

    @yield('content')

</main>
<!-- End #main -->

<!-- ======= Footer ======= -->
@include('admin.layouts.footer')
<!-- End Footer -->

<a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
        class="bi bi-arrow-up-short"></i></a>

<script>
    $(document).ready(function () {
        $(document).on('click', '.btnShowOrHide', function (e) {
            e.preventDefault();
            const text = $(this).text();
            if (text === 'Mở rộng') {
                $(this).text('Thu gọn');
                $(this).parent().parent().find('form').removeClass('d-none');
            } else {
                $(this).text('Mở rộng');
                $(this).parent().parent().find('form').addClass('d-none');
            }
        });

        init_only_number();

        $('.btnDelete').on('click', function () {
            if (confirm('Bạn có chắc chắn muốn xóa không?')) {
                $(this).closest('form').submit();
            }
        });

        $('#check_all').on('change', function () {
            if (this.checked) {
                $('input[name="check_item[]"]').each(function () {
                    this.checked = true;
                });
            } else {
                $('input[name="check_item[]"]').each(function () {
                    this.checked = false;
                });
            }
        })

        $('.btn_reload').click(function () {
            window.location.href = window.location.pathname;
        })
    })

    // function init_only_number() {
    //     $('.onlyNumber').on('input', function () {
    //         let val = $(this).val();
    //
    //         // Xoá hết ký tự không hợp lệ
    //         val = val.replace(/[^0-9.]/g, '');
    //
    //         // Tách phần nguyên & phần thập phân
    //         let parts = val.split('.');
    //
    //         let intPart = parts[0];
    //         let decPart = parts[1] || '';
    //
    //         // Format phần nguyên với dấu phân tách hàng nghìn
    //         intPart = intPart.replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    //
    //         // Chỉ giữ lại 1 dấu "." và phần thập phân (nếu có)
    //         val = intPart + (decPart ? "." + decPart.replace(/[^0-9]/g, '') : '');
    //
    //         $(this).val(val);
    //     });
    // }

    function init_only_number() {
        $('.onlyNumber').on('keypress', function (e) {
            const char = String.fromCharCode(e.which);
            if (!/[0-9,.]/.test(char)) {
                e.preventDefault(); // Chặn ký tự không hợp lệ
            }
        }).on('input', function () {
            $(this).val(function (i, val) {
                return val.replace(/[^0-9,.]/g, ''); // Xoá ký tự không hợp lệ
            });
        });
    }
</script>
<script>
    $(document).ready(function () {
        $('.selectCustom').select2({
            theme: 'bootstrap-5',
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder') ?? 'Lựa chọn...',
            allowClear: Boolean($(this).data('allow-clear')) || true,
            minimumResultsForSearch: $(this).data('minimum-results-for-search') ? $(this).data('minimum-results-for-search') : 0,
            containerCssClass: $(this).data('container-css-class') ? $(this).data('container-css-class') : '',
            dropdownCssClass: $(this).data('dropdown-css-class') ? $(this).data('dropdown-css-class') : '',
            dropdownAutoWidth: $(this).data('dropdown-auto-width'),
            dropdownParent: $(this).data('dropdown-parent'),
            dropdownPosition: $(this).data('dropdown-position'),
        });
    });

    function init_datatable(page_size = 10) {
        if ($.fn.DataTable.isDataTable('.datatable_wrapper')) {
            $('.datatable_wrapper').DataTable().destroy();
        }

        $('.datatable_wrapper').DataTable({
            paging: true,
            pageLength: page_size,
            searching: true,
            ordering: true,
            info: true,
            responsive: true,
            order: [],
            language: {
                search: "",
                zeroRecords: "Không tìm thấy dữ liệu phù hợp",
                info: "Hiển thị _START_ đến _END_ của _TOTAL_ bản ghi",
                infoEmpty: "Không có dữ liệu để hiển thị",
                infoFiltered: "(lọc từ _MAX_ bản ghi)",
                lengthMenu: "Số lượng _MENU_",
                paginate: {
                    first: "Đầu",
                    last: "Cuối",
                    next: "Tiếp",
                    previous: "Trước"
                }
            },
            columnDefs: [
                {orderable: false, targets: 0},
            ],
            dom: 'Bfrtip',
            buttons: [
                {
                    extend: 'excelHtml5',
                    text: '<i class="bi bi-file-earmark-excel"></i> Xuất Excel',
                    className: 'btn btn-success'
                },
            ],
            initComplete: function () {
                $('.dataTables_filter input').attr('placeholder', 'Tìm kiếm');
            }
        });
    }

    $(document).ready(function () {
        init_datatable();
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        init_number_format_input();
    });

    function init_number_format_input() {
        // document.querySelectorAll('.onlyNumber').forEach(el => {
        //     const anElement = AutoNumeric.getAutoNumericElement(el);
        //     if (anElement) {
        //         anElement.remove();
        //     }
        // });
        //
        // const instances = AutoNumeric.multiple('.onlyNumber', {
        //     digitGroupSeparator: ',',
        //     decimalPlaces: 3
        // });

        const elements = document.querySelectorAll('.onlyNumber');

        document.querySelectorAll('form').forEach(form => {
            form.addEventListener('submit', function () {
                elements.forEach((el, i) => {
                    let num = el.value
                    el.value = num.replaceAll(',', '');
                });
            });
        });
    }
</script>

<!-- Vendor JS Files -->
<script src="{{ asset('admin/vendor/apexcharts/apexcharts.min.js') }}"></script>
<script src="{{ asset('admin/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('admin/vendor/chart.js/chart.umd.js') }}"></script>
<script src="{{ asset('admin/vendor/echarts/echarts.min.js') }}"></script>
<script src="{{ asset('admin/vendor/quill/quill.min.js') }}"></script>
<script src="{{ asset('admin/vendor/simple-datatables/simple-datatables.js') }}"></script>
<script src="{{ asset('admin/vendor/tinymce/tinymce.min.js') }}"></script>
<script src="{{ asset('admin/vendor/php-email-form/validate.js') }}"></script>

<!-- Template Main JS File -->
<script src="{{ asset('admin/js/main.js') }}"></script>
<script src="{{ asset('admin/js/number_formater.js') }}"></script>
</body>

</html>
