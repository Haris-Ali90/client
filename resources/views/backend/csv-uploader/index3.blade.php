@extends('backend.layouts.app')
@section('title', 'CSV uploader')

<head>
    {{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"> --}}
    <script type="text/javascript" src="https://unpkg.com/xlsx@0.17.0/dist/xlsx.full.min.js"></script>
    <style>
        .topBar {
            width: 1000px !important;
            margin: 30px auto 40px !important;
            border: 2px solid #F8BB00 !important;
            justify-content: center !important;
            align-items: center !important;
            border-radius: 10px !important;
            background: #f6f2ef !important;
        }
        td.errorCell {
    text-align: center;
}


        table.table.table-striped.table-bordered.error {
            width: 0%;
           
        }

        div#editForm {
            margin: 4% 18px;
        }

        /* .table-data{
            margin-top:15%;
        } */

        /* Form-like styling */
        .edit-form {
    display: none;
    position: fixed;
    top: -87px;
    right: -19px;
    /* transform: translate(-50%, -50%); */
    border: 1px solid #ccc;
    padding: 20px;
    background-color: #f6f2efd9;
    width: 50%;
    height: 102vh;
    Z-INDEX: 999999999;
    BACKDROP-FILTER: blur(10PX);
    BOTTOM: 0px;
}

        h2 {
            color: #e46d29;
        }

        .edit-form label {
            display: block;
            color: #F8BB00;
            margin-bottom: 6px;
            font-size: 18px;
            font-weight: 500;
            color: #2a53b0;
        }

        .form-control:focus {
            color: var(--bs-body-color);
            background-color: var(--bs-body-bg);
            border-color: #d66b04;
            outline: 0;
            box-shadow: 0 0 0 0.25rem rgb(220 108 28 / 29%);
        }

        .edit-form input[type="text"] {
            width: 100%;
            padding: 10px;
            color: #444;
        }

        .edit-form button {
            margin-top: 10px;
            padding: 5px 10px;
            background-color: #2a53b0;
            color: white;
            border: none;
            cursor: pointer;
        }

        .invalid,
        .error>tr {
            border: 1px solid red;
        }

        .invalid-row {
            background-color: #ffcccc;
            /* or any other styling you prefer for invalid rows */
        }

        input.form-control.custominput.invalid {
            border: 1px solid red !important;
        }

        td.action-cell {
            display: flex;
            gap: 10px;
        }

        table>tbody>tr:first-child td,
        table>tbody>tr:first-child th {
            font-weight: bold;
            color: #000;
        }

        button.edit-button {
            background:#558AFE ;
            color: #ffff !important;
            border: none;
            border-radius: 5px;
            padding: 10px 15px;
        }

        button.delete-button {
            background: #ff0000;
            color: #ffff !important;
            border: none;
            border-radius: 5px;
            padding: 10px 15px;
        }

        .edit-form button {
            margin: 12px 12px;
            max-width: 200px;
            min-height: 44px;
            border-radius: 6px;
            font-size: 18px;
            font-weight: 600;
        }

        .downNup-fileBox {
            width: 800px;
            display: flex;
            margin: 0 auto;
            min-height: 300px;
            justify-content: center;
            align-items: center;
            border: 2px dashed #F8BB00;
            flex-wrap: wrap;
            border-radius: 10px;
        }

        .uploadFile-box {
            width: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            border-right: 2px dashed #F8BB00;
            height: 300px;
        }

        .downloadFile {
            width: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .topBar {
            width: 1000px;
            margin: 30px auto 40px;
        }

        .topBar {
            width: 1000px;
            margin: 30px auto 40px;
            border: 2px solid #F8BB00;
            justify-content: center;
            align-items: center;
            border-radius: 10px;
            background: #f6f2ef;
        }

        .addoneBox {
            min-height: 140px;
            padding: 10px;
            border-right: 2px solid #f2ba00;
        }

        .addoneBox {
            min-height: 140px;
            padding: 10px;
            border-right: 2px solid #f2ba00;
            filter: grayscale(1);
            opacity: 0.3;
            cursor: pointer;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .title-box h5 {
            color: #2a53b0;
            font-size: 20px;
            margin-bottom: 0;
        }

        .addoneBox.active {
            filter: grayscale(0);
            opacity: 1;
        }

        .title-box p {
            margin-bottom: 0;
            color: #444;
            font-size: 14px;
        }

        .icontext {
            height: 45px;
            width: 45px;
            background: #558AFE;
            color: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
            border-radius: 100px;
            margin-bottom: 5px;
            padding: 25px;
            text-align: center;
            line-height: 14px;
        }
        



td.action-cell {
    display: flex;
    gap: 10px;
    border-bottom: none;
    border-left: none;
}

        /* loader styling  */
        #processingMessage .loading {
            position: relative;
            display: flex;
            width: 100%;
            justify-content: center;
        }

        .prccing-btn {
            max-width: 800px;
            min-height: 400px;
            margin: 0 auto;
            padding: 100px;
            border: 2px solid #558AFE;
            border-radius: 10px;
            text-align: center;
            /*display: flex;*/
            margin-top: 15%;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            gap: 20px;
        }
        .table-data {
    border: 1px solid #808080d9 !important;
}

        @keyframes animateLight {

            0%,
            49.99% {
                background: #22e4e3;
                box-shadow: 0 0 5px #22e4e3, 0 0 10px #22e4e3, 0 0 40px #22e4e3;
            }

            50%,
            100% {
                background: #111;
                box-shadow: none;
            }
        }

        #processingMessage .loading .text {
            position: relative;
            width: 80px;
            color: #fff;
            text-align: right;
            letter-spacing: 1px;
        }

        #processingMessage .loading .percent {
            position: relative;
            top: 2px;
            width: calc(100% - 120px);
            height: 20px;
            background: #151515;
            border-radius: 20px;
            margin: 0 10px;
            box-shadow: inset 0 0 10px #000;
            overflow: hidden;
        }

        #processingMessage .loading .percent .progress {
            position: absolute;
            top: 0;
            left: 0;
            width: 0;
            height: 100%;
            border-radius: 20px;
            background: linear-gradient(45deg, #f2ba00, #edb900);
            animation: animate 6s ease-in-out infinite;
        }

        .addoneBox:last-child {
            border-right: 0;
        }

        .iconBoc-success {
            height: 100px;
            width: 100px;
            display: flex;
            justify-content: center;
            align-items: center;
            background: #558AFE !important;
        }

        .csvBox {
            padding: 20px;
            background: #f6f2ef;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 22px;
            min-height: 300px;
            border-radius: 20px;
            gap: 20px;
            cursor: pointer;
            position: relative;
        }

        .downloadFile.csvBox {
            background: transparent;
        }

        .downloadFile.csvBox a {
            color: #3a55b6;
            height: 256px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        input#excelFileInput {
            position: absolute;
            height: 100%;
            width: 100%;
            opacity: 0;
            display: flex;
            border-radius: 20px;
            cursor: pointer;
        }

        .iconBoc-success i.checkmark {
            font-size: 60px;
            color:#ffd954;
        }

        @keyframes animate {
            0% {
                width: 0;
                left: 0;
            }

            50% {
                width: 100%;
                left: 0;
            }

            100% {
                width: 100%;
                left: 100%;
            }
        }
        div#section2 {
    margin: 0;
    padding: 0;
    background: white;
    margin-top: 204px;
    padding: 20px 15px 2px 15px;
    background:#ffffff;
    border: 1px solid #ebe5e2;
    box-shadow: 0 0px 13px 2px #ebe5e2;
}
button#proceedButton {
    margin-bottom: 15px;
}
td.errorCell {
    padding: 5px 0px;
}
    </style>
</head>
@section('content')
<div class="right_col" role="main">
        <div class="">
    <div class="row topBar">
        <div class="col-lg-3 col-md-6 col-12 addoneBox active">
            <div class="title-cox">
                <div class="icontext">1 step</div>
                <div class="title-box">
                    <h5>Upload Orders</h5>
                    <p>Upload and Validate .csv file</p>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-12 addoneBox" id="adon2">
            <div class="title-cox">
                <div class="icontext">2 step</div>
                <div class="title-box">
                    <h5>Validate Orders</h5>
                    <p>Validate data before creating Orders</p>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-12 addoneBox" id="adon3">
            <div class="title-cox">
                <div class="icontext">3 step</div>
                <div class="title-box">
                    <h5>Processing Orders</h5>
                    <p>Creating optimized orders. This may take some time...</p>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-12 addoneBox" id="adon4">
            <div class="title-cox">
                <div class="icontext">4 step</div>
                <div class="title-box">
                    <h5>Confirmation</h5>
                    <p>See Your computed Custom Orders.</p>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid mt-4">
        <!-- Step 2: Display Table -->
        <div class="form-section " id="section2" style="display: none; ">
            <div id="table-container" class="table table-striped  yajrabox  c-table-bord" class="table-responsive" >
                <!-- table upload dynamic -->
            </div>
            <!-- <button id="proceedButton" class="btn btn-primary">Proceed</button> -->
            <button class="btn sub-ad c-color" id="proceedButton">Proceed</button>
        </div>
        <!-- Step 1: Upload Excel File -->
        <div class="form-section  " id="section1">
            <div class="downNup-fileBox">
                <div class="uploadFile-box csvBox">
                    <h3>Please Upload CSV File</h3>
                    <input type="file" id="excelFileInput" class="mb-3">
                </div>
                <div class="downloadFile csvBox">
                    {{-- <a href="{{ route('csv.export') }}"> --}}
                    <a href="https://smrtesting.com/ksa/assets/public/csv/csv-uploader/JoeycoSample-Ecom.csv">
                        Download Sample File
                    </a>
                </div>
            </div>
        </div>
        <!-- Step 3: Process Order -->
        <div class="form-section" id="section3" style="display: none;">
            <div id="processingMessage" style="display: none;">
                <div class="prccing-btn">
                    <h4>Processing...</h4>
                    <div class="loading">
                        <div class="percent">
                            <div class="progress"></div>
                        </div>
                    </div>
                    <p>Your Order Request has been Send Please Wait.</p>
                </div>
            </div>
            <div id="successMessage" style="display: none;">
                <div class="prccing-btn">
                    <div class="card border-0" style="width: 100%;">
                        <div class="iconBoc-success" style="border-radius:200px; margin:0 auto;">
                            <i class="checkmark">✓</i>
                        </div>
                        <h1>Success</h1>
                        <p>Order Processed Successfully!<br /> we'll be in touch shortly!</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Overlay for Processing Animation -->
    <div id="processingOverlay" class="overlay" style="display: none;">
        <div class="overlay-content">
            <div class="processing-animation">Processing...</div>
        </div>
    </div>
    <!-- Edit form -->
    <div id="editForm" class="edit-form">
        <h2>Edit Row</h2>
        <form id="csv_update" class="row" method="post">
            @csrf
            <div class="col-12">
                <button type="submit" class="btn" id="saveEdit">Update</button>
            </div>
        </form>
    </div>
    </div>
    </div>
    </div>
    <script>
        // Your JavaScript code...
        document.addEventListener('DOMContentLoaded', function() {
            var excelData = [];
            var currentRowIndex = -1;
            var currentSection = 1;

            document.getElementById('proceedButton').addEventListener('click', showNextSection);

            function showSection(sectionNumber) {
                var sections = document.querySelectorAll('.form-section');
                sections.forEach(function(section) {
                    section.style.display = 'none';
                    // document.getElementById('adon2').addClass('active');
                    var adon2 = document.getElementById('adon2');
                    adon2.classList.add('active');
                });
                document.getElementById('section' + sectionNumber).style.display = 'block';
                currentSection = sectionNumber;
            }

            function handleExcelFile(event) {
                var file = event.target.files[0];
                if (!file) return;


                const fileName = file.name;
                // Extract the file extension
                const fileExtension = fileName.split('.').pop().toLowerCase();
                // Check the file extension
                if (fileExtension === 'csv' || fileExtension === 'xlsx') {
                } else {
                    alert('Invalid file extension. Please select a CSV file.');
                    return false
                }

                var reader = new FileReader();
                reader.onload = function(e) {
                    var buffer = e.target.result;
                    var workbook = XLSX.read(buffer, {
                        type: 'array'
                    });
                    var sheetName = workbook.SheetNames[0];
                    var sheet = workbook.Sheets[sheetName];
                    excelData = XLSX.utils.sheet_to_json(sheet, {
                        header: 1
                    });
                    console.log('excelData')
                    // console.log(excelData.length)
                    // console.log('excelData')
                    if (excelData.length > 101) {
                        alert('Order not must be greater than 100.');
                        return false
                    }
                    var tableContainer = document.getElementById('table-container');
                    tableContainer.innerHTML = excelToEditableHtmlTable(excelData);

                    setupEditListeners();
                    showSection(2); // Show the table display step
                };
                reader.readAsArrayBuffer(file);
            }



            function showNextSection() {
                if (currentSection === 1) {
                    // If in section 1, move to section 2
                    showSection(2);
                } else if (currentSection === 2) {
                    // If in section 2, move to section 3
                    showSection(3);
                    processOrder(); // Automatically trigger order processing when moving to section 3
                } else {
                    // If in section 3 or beyond, you can define additional behavior
                    // or section transitions as needed.
                }
            }


            document.getElementById('proceedButton').addEventListener('click', processOrder);
            var proceedButton = document.getElementById('proceedButton');
            if (proceedButton) {
                proceedButton.addEventListener('click', processOrder);
                document.getElementById('excelFileInput').addEventListener('change', handleExcelFile);
            }

            function showProcessingAnimation() {
                var processingMessage = document.getElementById('processingMessage');
                processingMessage.style.display = 'block';
            }

            function hideProcessingAnimation() {
                var processingMessage = document.getElementById('processingMessage');
                processingMessage.style.display = 'none'; // Change 'block' to 'none'
            }

            function showSuccessScreen() {
                var successMessage = document.getElementById('successMessage');
                successMessage.style.display = 'block';
            }


            function excelToEditableHtmlTable(data) {
                var tableHtml = '<table border="1" class="table-data" >';
                for (var i = 0; i < data.length; i++) {
                    var rowValid = true; // Flag to track row validity
                    tableHtml += '<tr' + (rowValid ? '' : ' class="invalid-row"') +
                        '>'; // Add 'invalid-row' class if row is not valid
                    for (var j = 0; j < data[i].length; j++) {
                        var cellValue = data[i][j];
                        var cellClass = validateInput(cellValue) ? '' : 'invalid';
                        if (!validateInput(cellValue === undefined)) {
                            rowValid = false;
                        }
                        tableHtml += '<td class="' + cellClass + ' errorCell">' + cellValue + '</td>';
                    }
                    tableHtml += '</tr>';
                }
                tableHtml += '</table>';
                return tableHtml;
            }
            function setupEditListeners() {
                var rows = document.querySelectorAll('tr');
                rows.forEach(function(row, rowIndex) {

                    if (rowIndex === 0) {
                        var th = document.createElement('td');
                        row.appendChild(th);
                        th.textContent = 'Status';
                        th.classList.add(" errorCell");
                        return; // Skip header row
                    } else if (rowIndex += 1) {

                        var actionCell = document.createElement('td');
                        actionCell.className = 'action-cell';
                        actionCell.innerHTML =
                            '<button class="edit-button ">&#9998;</button><button class="delete-button">&#128465;</button>';
                        actionCell.querySelector('.edit-button ').addEventListener('click', function() {

                            openEditForm(rowIndex - 1);
                        });
                        actionCell.querySelector('.delete-button').addEventListener('click', function() {
                            deleteRow(rowIndex - 1);
                        });
                    }

                    row.appendChild(actionCell);
                });
            }
            rowData = null;

            function openEditForm(rowIndex) {
                var modal = document.getElementById('editForm');
                var formContent = document.querySelector('.edit-form form');
                formContent.innerHTML = ''; // Clear previous content

                var rowData = excelData[rowIndex];
                currentRowIndex = rowIndex;
                console.log(rowIndex)
                var tableHeaders = excelData[0]; // Assuming the first row contains column headers
                for (var i = 0; i < rowData.length; i++) {
                    // Create a new div element to wrap the label and input
                    var divWrapper = document.createElement('div');
                    divWrapper.classList.add('col-lg-6', 'col-md-12', 'mt-3'); // Add classes to the wrapping div
                    var label = document.createElement('label');
                    label.textContent = tableHeaders[i] + ':';
                    label.classList.add('label-custom'); // You can add any label-specific class here
                    var input = document.createElement('input');
                    input.classList.add('form-control', 'custominput'); // Customize input classes as needed
                    input.type = 'text';
                    input.value = rowData[i];
                    divWrapper.appendChild(label);
                    divWrapper.appendChild(input);
                    formContent.appendChild(divWrapper);
                }
                var saveButton = document.createElement('button');
                saveButton.type = 'submit';
                saveButton.textContent = 'Update';
                formContent.appendChild(saveButton);
                modal.style.display = 'block';
                var editForm = document.querySelector('.edit-form form');
                editForm.onsubmit = function(event) {
                    event.preventDefault();
                    var editedValues = [];
                    var inputFields = editForm.querySelectorAll('input');
                    var valid = true;
                    inputFields.forEach(function(input, index) {
                        var columnType = getColumnType(tableHeaders[index]);
                        var inputValue = input.value.trim();
                        console.log(inputValue)
                        if (!validateInput(inputValue, columnType)) {
                            valid = false;
                            input.classList.add('invalid');
                        } else {
                            editedValues.push(inputValue);
                            input.classList.remove('invalid');
                        }

                        $('.invalid').css('border', 'red 1px solid !important');
                    });
                   

                    if (valid) {
                        console.log('test')
                        console.log(rowData)

                        excelData[currentRowIndex] = editedValues; // Update data array
                        modal.style.display = 'none';
                        var tableContainer = document.getElementById('table-container');
                        tableContainer.innerHTML = excelToEditableHtmlTable(excelData);
                        setupEditListeners();
                    } else {
                        // alert('Please enter valid values for all fields.');
                    }
                };
            }

            function processOrder() {
                showProcessingAnimation();
                var adon2 = document.getElementById('adon3');
                adon2.classList.add('active');
                setTimeout(function() {
                    hideProcessingAnimation();

                    var adon2 = document.getElementById('adon4');
                    adon2.classList.add('active');
                    showSuccessScreen();
                }, 2000); // Simulate a 2-second processing time
                console.log('abc')
                console.log(rowData)
                console.log('rowData')

                rowData = excelData
                var formData = {
                    rowData
                };
                $.ajax({
                    type: 'POST',
                    url: "{{ route('csv.uploader') }}",
                    data: formData,
                    dataType: 'json',
                    success: function(data) {
                        // var todo = '<tr id="todo' + data.id + '"><td>' + data.id +
                        //     '</td><td>' + data.title + '</td><td>' + data.description +
                        //     '</td>';
                        // if (state == "add") {
                        //     jQuery('#todo-list').append(todo);
                        // } else {
                        //     jQuery("#todo" + todo_id).replaceWith(todo);
                        // }
                        // jQuery('#myForm').trigger("reset");
                        // jQuery('#formModal').modal('hide')
                    },
                    error: function(data) {
                        console.log(data);
                    }
                });

            }

            function getColumnType(header) {
                return 'text';
            }

            function validateInput(value, columnType) {
                return value !== '';
            }

            function deleteRow(rowIndex) {
                excelData.splice(rowIndex, 1); // Remove row from data array
                var tableContainer = document.getElementById('table-container');
                6
                tableContainer.innerHTML = excelToEditableHtmlTable(excelData);

                setupEditListeners();
            }
            // var tableValue = document.querySelector('.error');
            setTimeout(() => {
                // $('.error tr td').text();
                console.log($('.errorCell').text());

                if ($('.errorCell').text() == 'undefined') {
                    // console.log( $('.error tr td').text());
                } else {
                    // console.log('b');
                }
            }, 10000);

        });
        
       
    </script>

@endsection
