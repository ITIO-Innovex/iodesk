<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<style>
  #excel-table { width: 100%; border-collapse: collapse; }
  #excel-table td, #excel-table th { border: 1px solid #ddd; padding: 6px; min-width: 80px; }
  #excel-table td { background: #fff; }
  #excel-table td[contenteditable="true"] { outline: none; }
  .excel-toolbar { margin-bottom: 10px; }
</style>
<div id="wrapper">
  <div class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="tw-flex tw-justify-between tw-items-center tw-mb-3">
          <h4 class="tw-mt-0 tw-font-semibold tw-text-lg">Edit Excel</h4>
          <a href="<?php echo admin_url('important_document'); ?>" class="btn btn-default">Back</a>
        </div>
        <div class="panel_s">
          <div class="panel-body">
            <div class="excel-toolbar">
              <button type="button" class="btn btn-default btn-sm" id="add-row">Add Row</button>
              <button type="button" class="btn btn-primary btn-sm" id="save-excel">Save</button>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<script>
  $(function() {
    var fileUrl = '<?php echo base_url($document['document_path']); ?>';

    function renderTable(data) {
      var $table = $('#excel-table');
      $table.empty();
      if (!data || !data.length) {
        $table.append('<tr><td contenteditable="true"></td></tr>');
        return;
      }
      for (var r = 0; r < data.length; r++) {
        var row = data[r] || [];
        var tr = $('<tr/>');
        for (var c = 0; c < row.length; c++) {
          var cell = row[c] != null ? row[c] : '';
          tr.append('<td contenteditable="true">' + $('<div>').text(cell).html() + '</td>');
        }
        $table.append(tr);
      }
    }

    function tableToArray() {
      var data = [];
      $('#excel-table tr').each(function() {
        var row = [];
        $(this).find('td').each(function() {
          row.push($(this).text());
        });
        data.push(row);
      });
      return data;
    }

    function loadExcel() {
      fetch(fileUrl)
        .then(function(res) { return res.arrayBuffer(); })
        .then(function(buf) {
          var wb = XLSX.read(buf, { type: 'array' });
          var sheet = wb.Sheets[wb.SheetNames[0]];
          var data = XLSX.utils.sheet_to_json(sheet, { header: 1, blankrows: true });
          renderTable(data);
        })
        .catch(function() {
          alert('Failed to load file');
        });
    }

    $('#add-row').on('click', function() {
      $('#excel-table').append('<tr><td contenteditable="true"></td></tr>');
    });

    $('#save-excel').on('click', function() {
      var data = tableToArray();
      var ws = XLSX.utils.aoa_to_sheet(data);
      var wb = XLSX.utils.book_new();
      XLSX.utils.book_append_sheet(wb, ws, 'Sheet1');
      var base64 = XLSX.write(wb, { bookType: 'xlsx', type: 'base64' });

      $.post('<?php echo admin_url('important_document/save_excel/' . $document['id']); ?>', {
        file_base64: base64,
        <?php echo $this->security->get_csrf_token_name(); ?>: '<?php echo $this->security->get_csrf_hash(); ?>'
      }, function(resp) {
        if (resp && resp.success) {
          alert_float('success', resp.message || 'Saved');
        } else {
          alert(resp && resp.message ? resp.message : 'Failed to save');
        }
      }, 'json');
    });

    loadExcel();
  });
</script>
</body></html>
