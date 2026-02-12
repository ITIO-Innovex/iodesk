<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title><?php echo e($title ?? 'Shared Excel'); ?></title>
  <link rel="stylesheet" href="<?php echo base_url('assets/css/bootstrap.min.css'); ?>">
  <style>
    #excel-table { width: 100%; border-collapse: collapse; }
    #excel-table td, #excel-table th { border: 1px solid #ddd; padding: 6px; min-width: 80px; }
    #excel-table td { background: #fff; }
    #excel-table td[contenteditable="true"] { outline: none; }
    #excel-table thead th { background: #f5f5f5; text-align: center; font-weight: 600; }
    #excel-table .row-header { background: #f5f5f5; text-align: center; font-weight: 600; width: 40px; }
    .excel-toolbar { margin: 15px 0; }
    .excel-toolbar .btn { margin-right: 6px; }
  </style>
</head>
<body>
  <div class="container">
    <div class="excel-toolbar">
      <button type="button" class="btn btn-default btn-sm" id="add-row">Add Row</button>
      <button type="button" class="btn btn-default btn-sm" id="add-col">Add Column</button>
      <button type="button" class="btn btn-default btn-sm" id="bold-cell"><b>B</b></button>
      <button type="button" class="btn btn-default btn-sm" id="italic-cell"><i>I</i></button>
      <button type="button" class="btn btn-default btn-sm" id="underline-cell"><u>U</u></button>
      <input type="color" id="text-color" title="Text Color" style="width:32px;height:32px;vertical-align:middle;">
      <input type="color" id="bg-color" title="BG Color" style="width:32px;height:32px;vertical-align:middle;">
      <button type="button" class="btn btn-primary btn-sm" id="save-excel">Save</button>
      <span id="save-status" class="text-muted mleft10"></span>
    </div>
    <div class="table-responsive">
      <table id="excel-table" class="table"></table>
    </div>
  </div>

  <script src="<?php echo base_url('assets/plugins/jquery/jquery.min.js'); ?>"></script>
  <script>
    function loadXlsxFallback() {
      var s = document.createElement('script');
      s.src = 'https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js';
      document.head.appendChild(s);
    }
  </script>
  <script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js" onerror="loadXlsxFallback()"></script>
  <script>
    $(function() {
      var fileUrl = '<?php echo base_url($document['document_path']); ?>';
      var saveTimer = null;

      function columnName(index) {
        var name = '';
        var n = index;
        while (n >= 0) {
          name = String.fromCharCode((n % 26) + 65) + name;
          n = Math.floor(n / 26) - 1;
        }
        return name;
      }

      function renderTable(data) {
        var $table = $('#excel-table');
        $table.empty();
        var rows = data && data.length ? data.length : 1;
        var cols = 1;
        if (data && data.length) {
          for (var i = 0; i < data.length; i++) {
            cols = Math.max(cols, (data[i] || []).length);
          }
        }
        rows = Math.max(rows, 100);
        cols = Math.max(cols, 26);

        var thead = $('<thead/>');
        var headRow = $('<tr/>');
        headRow.append('<th class="row-header"></th>');
        for (var c = 0; c < cols; c++) {
          headRow.append('<th>' + columnName(c) + '</th>');
        }
        thead.append(headRow);
        $table.append(thead);

        var tbody = $('<tbody/>');
        for (var r = 0; r < rows; r++) {
          var row = data && data.length ? (data[r] || []) : [];
          var tr = $('<tr/>');
          tr.append('<th class="row-header">' + (r + 1) + '</th>');
          for (var c = 0; c < cols; c++) {
            var cell = row[c] != null ? row[c] : '';
            tr.append('<td contenteditable="true">' + $('<div>').text(cell).html() + '</td>');
          }
          tbody.append(tr);
        }
        $table.append(tbody);
      }

      function tableToArray() {
        var data = [];
        $('#excel-table tbody tr').each(function() {
          var row = [];
          $(this).find('td').each(function() {
            row.push($(this).text());
          });
          data.push(row);
        });
        return data;
      }

      function saveExcel() {
        var data = tableToArray();
        var ws = XLSX.utils.aoa_to_sheet(data);
        var wb = XLSX.utils.book_new();
        XLSX.utils.book_append_sheet(wb, ws, 'Sheet1');
        var base64 = XLSX.write(wb, { bookType: 'xlsx', type: 'base64' });
        $('#save-status').text('Saving...');
        $.post('<?php echo site_url('important_document_public/save/' . $document['share_token']); ?>', {
          file_base64: base64
        }, function(resp) {
          if (resp && resp.success) {
            $('#save-status').text('Saved');
          } else {
            $('#save-status').text('Save failed');
          }
        }, 'json');
      }

      function debounceSave() {
        if (saveTimer) { clearTimeout(saveTimer); }
        saveTimer = setTimeout(saveExcel, 1200);
      }

      function loadExcel() {
        if (typeof XLSX === 'undefined') {
          alert('Excel library failed to load. Please refresh the page.');
          return;
        }
        fetch(fileUrl)
          .then(function(res) { return res.arrayBuffer(); })
          .then(function(buf) {
            var wb = XLSX.read(buf, { type: 'array', cellDates: true });
            var sheet = wb.Sheets[wb.SheetNames[0]];
            var data = XLSX.utils.sheet_to_json(sheet, { header: 1, blankrows: true });
            renderTable(data);
          })
          .catch(function() {
            alert('Failed to load file');
          });
      }

      $('#add-row').on('click', function() {
        var colCount = $('#excel-table thead th').length - 1;
        var rowCount = $('#excel-table tbody tr').length + 1;
        var tr = $('<tr/>');
        tr.append('<th class="row-header">' + rowCount + '</th>');
        for (var c = 0; c < colCount; c++) {
          tr.append('<td contenteditable="true"></td>');
        }
        $('#excel-table tbody').append(tr);
        debounceSave();
      });

      $('#add-col').on('click', function() {
        var colCount = $('#excel-table thead th').length - 1;
        $('#excel-table thead tr').append('<th>' + columnName(colCount) + '</th>');
        $('#excel-table tbody tr').each(function() {
          $(this).append('<td contenteditable="true"></td>');
        });
        debounceSave();
      });

      $('#bold-cell').on('click', function() {
        document.execCommand('bold', false, null);
      });
      $('#italic-cell').on('click', function() {
        document.execCommand('italic', false, null);
      });
      $('#underline-cell').on('click', function() {
        document.execCommand('underline', false, null);
      });
      $('#text-color').on('change', function() {
        document.execCommand('foreColor', false, $(this).val());
      });
      $('#bg-color').on('change', function() {
        document.execCommand('hiliteColor', false, $(this).val());
      });

      $('#save-excel').on('click', function() {
        saveExcel();
      });

      $('#excel-table').on('input', 'td', function() {
        debounceSave();
      });

      loadExcel();
    });
  </script>
</body>
</html>
