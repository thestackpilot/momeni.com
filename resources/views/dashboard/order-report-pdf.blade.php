<html>

<head>
    <title>Order Report</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

    <style type="text/css">
        .content {
            padding: 20px;
        }
    </style>

    <script>
        $(document).ready(function() {
            var data = JSON.parse($('#report_details').html());
            var content_body = '';

            function getDetails(section) {
                var modal_body = '';
                if (section.length < 1) {
                    modal_body += '<div class="col-md-12">';
                    modal_body += '<h5>N/A</h5>';
                    modal_body += '</div>';
                } else {
                    modal_body += '<div class="col-md-12">';
                    modal_body += '<table class="table mt-2 text-center details">';
                    modal_body += '<thead>';
                    modal_body += '<tr>';
                    Object.keys(section[0]).forEach(function(key) {
                        modal_body += "<th>" + ((key.replace(/([A-Z|0-9])/g, ' $1').trim()).replace('_', ' ')).replace(/([A-Z])\s(?=[A-Z])/g, '$1') + "</th>";
                    });
                    modal_body += '</tr>';
                    modal_body += '</thead>';
                    modal_body += '<tbody>';
                    section.forEach(row => {
                        modal_body += '<tr>';
                        Object.keys(row).forEach(function(index) {
                            modal_body += "<td>" + (row[index] == null || row[index] == '' ? '-' : row[index]) + "</td>";
                        });
                        modal_body += '</tr>';
                    });
                    modal_body += '</tbody>';
                    modal_body += '</table>';
                    modal_body += '</div>';
                }

                return modal_body;
            }

            data.sections.forEach((section, i) => {
                content_body += '<div class="row ' + (i == 0 ? '' : 'mt-5') + '">';
                content_body += '<div class="col-md-12">';
                content_body += '<h3>' + section.title + '</h3>';
                content_body += '<div class="row">';
                if (Array.isArray(section.content) && typeof section.content.length !== 'undefined') {
                    content_body += getDetails(section.content);
                } else if (typeof section.content.tabs !== 'undefined') {
                    content_body += '<div class="col-md-12">';
                    Object.keys(section.content.tabs).forEach(function(tab, i) {
                        content_body += `<h4 style="text-transform: capitalize">${tab}</h4>`;
                        content_body += `<div id="${tab}" role="tabpanel" aria-labelledby="${tab}-tab">`;
                        content_body += getDetails(section.content.tabs[tab]);
                        content_body += '</div>';
                    });
                    content_body += '</div>';
                } else {
                    Object.keys(section.content).forEach(function(key) {
                        var value = section.content[key] == 0 || section.content[key] == '' ? 'N/A' : section.content[key];
                        content_body += '<div class="col-md-4">' + ((key.replace(/([A-Z|0-9])/g, ' $1').trim()).replace('_', ' ').replace(/(^\w{1})|(\s+\w{1})/g, letter => letter.toUpperCase())).replace(/([A-Z])\s(?=[A-Z])/g, '$1') + ' : ' + value + '</div>';
                    });
                }
                content_body += '</div>';
                content_body += '</div>';
                content_body += '</div>';
            });

            $('#report_content').html(content_body);
            window.print();

        });
    </script>

</head>

<body marginstyle="width:0" marginheight="0" topmargin="0">

    <div class="content">
        <div id="report_content"></div>
        <span id="report_details" style="display: none;">{{ $report_data }}</span>
    </div>

</body>

</html>
