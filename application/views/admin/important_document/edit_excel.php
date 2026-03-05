<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
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
<div id="wrapper">
  <div class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="tw-flex tw-justify-between tw-items-center tw-mb-3">
          <h4 class="tw-mt-0 tw-font-semibold tw-text-lg tw-text-white">Edit Excel</h4>
          <a href="<?php echo admin_url('important_document'); ?>" class="btn btn-default">Back</a>
        </div>
        <div class="panel_s">
          <div class="panel-body">
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
        </div>
      </div>
    </div>
  </div>
</div>

<?php init_tail(); ?>
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
    var saveUrl = '<?php echo admin_url('important_document/save_excel/' . (int)$document['id']); ?>';
    var csrfName = '<?php echo $this->security->get_csrf_token_name(); ?>';
    var csrfHash = '<?php echo $this->security->get_csrf_hash(); ?>';
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

    function allowedFormatTags(html) {
      if (!html || typeof html !== 'string') return '';
      var div = document.createElement('div');
      div.innerHTML = html;
      var allowed = { b: true, i: true, u: true, strong: true, em: true, span: true, font: true };
      function sanitize(node) {
        if (node.nodeType === 3) return node.textContent;
        if (node.nodeType !== 1) return '';
        var tag = (node.tagName || '').toLowerCase();
        if (allowed[tag]) {
          var inner = [];
          for (var i = 0; i < node.childNodes.length; i++) inner.push(sanitize(node.childNodes[i]));
          var open = '<' + tag;
          var fontColor = node.getAttribute && node.getAttribute('color');
          if (tag === 'font' && fontColor) {
            open += ' color="' + String(fontColor).replace(/"/g, '&quot;') + '"';
          }
          if (tag === 'span' && node.style) {
            var styles = [];
            if (node.style.color) {
              styles.push('color:' + String(node.style.color).replace(/"/g, '&quot;'));
            }
            if (node.style.backgroundColor) {
              styles.push('background-color:' + String(node.style.backgroundColor).replace(/"/g, '&quot;'));
            }
            if (styles.length) {
              open += ' style="' + styles.join(';') + '"';
            }
          }
          return open + '>' + inner.join('') + '</' + tag + '>';
        }
        return node.textContent || '';
      }
      var out = '';
      for (var i = 0; i < div.childNodes.length; i++) out += sanitize(div.childNodes[i]);
      return out;
    }

    function renderTable(data, formatMap) {
      formatMap = formatMap || {};
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
          var key = r + ',' + c;
          var content = formatMap[key];
          if (content) {
            content = allowedFormatTags(content);
            if (!content) content = $('<div>').text(cell).html();
          } else {
            content = $('<div>').text(cell).html();
          }
          tr.append($('<td contenteditable="true"></td>').html(content));
        }
        tbody.append(tr);
      }
      $table.append(tbody);
    }

    function tableToArrayAndFormats() {
      var data = [];
      var formatMap = {};
      $('#excel-table tbody tr').each(function(rowIndex) {
        var row = [];
        $(this).find('td').each(function(colIndex) {
          var $td = $(this);
          var text = $td.text();
          var html = $td.html();
          row.push(text);
          if (html && html !== $('<div>').text(text).html()) {
            formatMap[rowIndex + ',' + colIndex] = html;
          }
        });
        data.push(row);
      });
      return { data: data, formatMap: formatMap };
    }

    function saveExcel() {
      if (typeof XLSX === 'undefined') {
        $('#save-status').text('Excel library not loaded');
        return;
      }
      var out = tableToArrayAndFormats();
      var data = out.data;
      var formatMap = out.formatMap;
      var ws = XLSX.utils.aoa_to_sheet(data);
      var wb = XLSX.utils.book_new();
      XLSX.utils.book_append_sheet(wb, ws, 'Sheet1');
      if (Object.keys(formatMap).length > 0) {
        var formatJson = JSON.stringify(formatMap);
        var formatWs = XLSX.utils.aoa_to_sheet([[formatJson]]);
        XLSX.utils.book_append_sheet(wb, formatWs, '_formatting');
      }
      var base64 = XLSX.write(wb, { bookType: 'xlsx', type: 'base64' });
      $('#save-status').text('Saving...').removeClass('text-danger');
      $.ajax({
        url: saveUrl,
        type: 'POST',
        data: {
          file_base64: base64,
          [csrfName]: csrfHash
        },
        dataType: 'json',
        success: function(resp) {
          if (resp && resp.success) {
            $('#save-status').text('Saved').removeClass('text-danger');
            if (typeof alert_float === 'function') {
              alert_float('success', resp.message || 'Saved');
            }
          } else {
            $('#save-status').text(resp && resp.message ? resp.message : 'Save failed').addClass('text-danger');
          }
        },
        error: function(xhr, status, err) {
          var msg = 'Save failed';
          if (xhr.responseJSON && xhr.responseJSON.message) {
            msg = xhr.responseJSON.message;
          } else if (xhr.responseText) {
            try {
              var j = JSON.parse(xhr.responseText);
              if (j.message) msg = j.message;
            } catch (e) {
              if (xhr.status === 403) msg = 'Request blocked. Try refreshing the page.';
              else if (xhr.status === 404) msg = 'Save URL not found.';
              else if (xhr.status >= 500) msg = 'Server error. Try again later.';
            }
          }
          $('#save-status').text(msg).addClass('text-danger');
        }
      });
    }

    function debounceSave() {
      if (saveTimer) clearTimeout(saveTimer);
      saveTimer = setTimeout(saveExcel, 1200);
    }

    function loadExcel() {
      if (typeof XLSX === 'undefined') {
        alert('Excel library failed to load. Please refresh the page.');
        return;
      }
      var url = fileUrl + (fileUrl.indexOf('?') === -1 ? '?' : '&') + 't=' + Date.now();
      fetch(url, { cache: 'no-store' })
        .then(function(res) { return res.arrayBuffer(); })
        .then(function(buf) {
          var wb = XLSX.read(buf, { type: 'array', cellDates: true });
          var sheetName = wb.SheetNames[0];
          var sheet = wb.Sheets[sheetName];
          var data = XLSX.utils.sheet_to_json(sheet, { header: 1, blankrows: true });
          var formatMap = {};
          if (wb.SheetNames.indexOf('_formatting') !== -1) {
            var formatSheet = wb.Sheets['_formatting'];
            var formatCell = formatSheet['A1'];
            if (formatCell && formatCell.v && typeof formatCell.v === 'string') {
              try {
                formatMap = JSON.parse(formatCell.v) || {};
              } catch (e) {}
            }
          }
          renderTable(data, formatMap);
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
</body></html>
